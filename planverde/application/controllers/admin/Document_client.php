<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$url_base =  $actual_link . "/";

// require '../vendor/autoload.php'; // YA SE ESTÁ UTILIZANDO UN AUTOLOAD EN api_google, POR LO QUE NO ES NECESARIO CARGARLO NUEVAMENTE...

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

class Document_client extends Admin_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('document_client_model');
  }
  public function index(){
    $data['title'] = "Documentos | PLAN VERDE";
    $data['page'] = "Documentos";
    $data['subview'] = $this->load->view('admin/documentsclient/list', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }
  // ------------------- MOSTRAR FORMULARIO DE DOCUMENTO
  public function add_document(){
    $data['title'] = "Agregar Documento";
    $data['all_categories'] = $this->db->get('tbl_categoria')->result_array();
    $data['all_clients'] = $this->db->get('tbl_cliente')->result_array();
    $data['all_anios'] = $this->db->get('tbl_anio')->result_array();
    $data['subview'] = $this->load->view('admin/documentsclient/add_document', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  // ------------------- LISTAR DOCUMENTOS
  public function documentsClientList($type = null){
    if($this->input->is_ajax_request()){
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_documents';
      $this->datatables->join_table = array('tbl_cliente');
      $this->datatables->join_where = array('tbl_cliente.cliente_id = tbl_documents.client_id');
      $this->datatables->column_search = array(
        'tbl_documents.nombre',
        'tbl_cliente.razon_social',
        'tbl_cliente.ruc',
        'tbl_documents.anio',
        'tbl_documents.mes',
      );      
      $this->datatables->column_order = array(' ', 'tbl_documents.nombre');
      $this->datatables->order = array('tbl_documents.document_id' => 'desc');
      $where = (!empty($type)) ? array('tbl_documents.document_id' => $type) : null;
      $fetch_data = make_datatables($where);
      $data = array();
      $edited = can_action('4', 'edited');
      $deleted = can_action('4', 'deleted');
      foreach ($fetch_data as $_key => $document) {
        $action = null;
        $sub_array = array();
        $sub_array[] = $document->document_id;
        if($document->id_contentfolder != ""){
          $sub_array[] = '<a href="https://drive.google.com/open?id='.$document->id_contentfolder.'" target="_blank" class="color-paragraph"><span class="txt-underline">'.$document->nombre.'</span></a>';
        }else{
          $sub_array[] = '<a href="https://drive.google.com/open?id='.$document->id_archivo.'" target="_blank" class="color-paragraph"><span class="txt-underline">'.$document->nombre.'</span></a>';
        }
        $cliente =  $this->db->get_where("tbl_cliente", ['cliente_id' => $document->client_id])->row();
        $sub_array[] = $cliente->ruc . ' - ' . $cliente->razon_social;
        $sub_array[] = $this->db->get_where("tbl_anio", ['anio_id' => $document->anio])->row()->anio;
        $sub_array[] = $document->mes;
        if(!empty($deleted)){
          $action .= '<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Visualizar" target="_blank" href="' . $document->ruta . '"><span class="fa fa-eye"></span></a>' . ' ';
          //$action .='<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Visualizar" target="_blank" href="'.base_url("ver_documents/view-documents.php?key=").base64_encode($document->ruta).'"><span class="fa fa-eye"></span></a>' . ' ';
          $action .= '<span data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs delete-document" title="Click to ' . lang("delete") . ' " data-id="' . $document->document_id . '"><span class="fa fa-trash-o"></span></span>' . ' ';
        }
        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    }else{
      redirect('admin/dashboard');
    }
  }
  // ------------------- GUARDAR DOCUMENTO
  public function save_document($id = null){
    $subcategory_id = $this->input->post('categoria_id');
    $data_subcategory = $this->db->where(['subcategoria_id' => $subcategory_id])->get('tbl_subcategoria')->row();
    $category_id = $data_subcategory->categoria_id;
    $subcategory_name = $data_subcategory->id_carpeta;
    $category_name = $this->db->get_where('tbl_categoria', array('categoria_id' => $category_id))->row()->nombre_categoria;
    $data_anio = $this->db->where(['anio' => $this->input->post('anio'), 'subcategoria_id' => $data_subcategory->subcategoria_id])->get('tbl_detail_anio')->row();    
    if(empty($id) || $id == "" || $id == NULL){      
      $folderparentId = $data_anio->id_carpeta;
      // $folderparentId = '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn'; // LÍNEA MOMENTÁNEA (PRUEBAS)
      $id_archivo = "";
      if(isset($_FILES['files']) && $_FILES['files']['name'] != ""){
        if($_FILES['files']['error'] === UPLOAD_ERR_OK){
          $file = $_FILES['files']['tmp_name'];
          $name = $_FILES['files']['name'];
          $description = "Documento subido a: ".$data_subcategory->nombre_subcategoria;
          $upload = uploadFileToDrive($name,$file,$description,$folderparentId);
          $id_archivo = $upload->id;
        }
      }

      $rutaFolderParent = getParentFolderId($id_archivo); // OBTENER LA CARPETA CONTENEDORA DONDE ESTÁ ALOJADO EL DOCUMENTO...
      $ruta = 'https://drive.google.com/open?id=' . $id_archivo;    
        
      $data = [
      'client_id'    => $this->input->post('cliente_id'),
      'sede_id'      => $this->input->post('sede_id'),
      'nombre'       => $this->input->post('nombre'),
      'categoria_id' => $this->input->post('categoria_id'),
      'anio'         => $this->input->post('anio'),
      'mes'          => $this->input->post('mes'),
      'user_id'      => $_SESSION['user_id'],
      'ruta'         => $ruta,
      'id_archivo'   => $id_archivo,
      'id_contentfolder'   => $rutaFolderParent
      ];

      $this->document_client_model->_table_name  = 'tbl_documents';
      $this->document_client_model->_primary_key = "document_id";
      $return_id = $this->document_client_model->save($data, $id);
      // $consulta = $this->db->query("INSERT INTO tbl_documents VALUES(NULL,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,?)", $data);
      if($return_id){  
        $idClient = $this->input->post('cliente_id');
        $arrDataClient = $this->db->where(['cliente_id' => $idClient])->get('tbl_cliente')->row();
        
        $arr_client = array(
          "d_clienteId" => $arrDataClient->cliente_id,
          "d_razonsocial" => $arrDataClient->razon_social,
          "d_correo" => $arrDataClient->correo
        );
        
        $r = "";
        $mail = new PHPMailer(true);

        $cli_razonsocial = $arr_client['d_razonsocial'];
        // $cli_correo = $arr_client['correo'];
        $cli_correo = 'ranppuntos20@gmail.com';

        // $gmail_username = "plataforma@solugrifos.com"; // USERNAME PARA APLICACIONES DE GOOGLE - 26/11/2023 (plataforma@solugrifos.com)
        // $gmail_password = "nbougqpjipkyvdxb"; // PASSWORD PARA APLICACIONES DE GOOGLE - 26/11/2023 (plataforma@solugrifos.com)
        // --------------------
        $gmail_username = "planverdeplataforma@gmail.com";
        $gmail_password = "xhdz ohmb vtai pduc";
        
        try {
          $mail->CharSet = 'UTF-8';
          //Server settings
          $mail->SMTPDebug = 0;                                                           // Enable verbose debug output
          $mail->isSMTP();                                                    // Set mailer to use SMTP
          $mail->Host       = 'smtp.gmail.com';                                                    // Specify main and backup SMTP servers
          $mail->SMTPAuth   = true;                                                       // Enable SMTP authentication
          $mail->Username   = $gmail_username;                 // SMTP username
          $mail->Password   = $gmail_password;                                                    // SMTP password
          $mail->SMTPSecure = 'tls';                                                      // Enable TLS encryption, `ssl` also accepted
          $mail->Port       = 587; //465, 587;                                          // TCP port to connect to
          
          //Recipients
          $mail->setFrom('notificacionesplanverde@erpsolugrifos.com', 'PLAN VERDE |SOLUGRIFOS');
          //foreach($correo as $val){
          $mail->addAddress($cli_correo);                                        // Add a recipient a quien se le enviara el corre
          //}
          // Content
          $mail->isHTML(true);                                                               // Set email format to HTML
          $mail->Subject = "Hola de nuevo, " . $cli_razonsocial;
          
          $mail->Body    =  '<!DOCTYPE html>
          <html lang="es">
          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style type="text/css">
              body{
                display:flex;align-items:center;justify-content:center;background: rgba(0,0,0,.05);padding: 2.2rem 0 2.2rem 0;
              }
              tr,td{
                border: none !important;
              }
              .cMCont{
                width: 85%;margin: auto;border-radius: 20px;background-position: center;background-repeat: no-repeat;background-size: contain;
              }
              .cMCont__c{
                width: 100%;background: rgba(255,255,255,.7);border-radius: 20px;border: #eee;box-shadow: 0 18px 24px 1px rgba(0,0,0,.1);
              }
              .cMCont__c__cTbl{
                width: 100%;background: rgba(255,255,255,.75);border-radius: 20px;margin: auto;
              }
              .cMCont__c__cTbl__cLogo{
                background-color: #9dc140;
                display:block;align-items:center;justify-content:center;text-align:center;padding: 1rem 2.8rem 0 2.8rem;
              }
              .cMCont__c__cTbl__cLogo img{
                max-width: 260px;min-width: 150px;width: 95%;
              }
              .cMCont__c__cTbl__cTitle{
                color:#3c4858;text-align:center;font-size: 1rem;
              }
              .cMCont__c__cTbl__cC{
                display:block;align-items:center;justify-content:center;text-align:center;padding: .5rem 2.8rem 2.8rem 2.8rem;font-size: .97rem;font-weight: lighter;
              }
              .cMCont__c__cTbl__cC__c{
                margin-bottom:40px;text-align: center;color:#3c4858;
              }
              .cMCont__c__cTbl__cC__c__cTitle-1{
                text-align:left;
              }
              .cMCont__c__cTbl__cC__c__cTitle-h3{
                color:#3c4858;font-weight:bold;
              }
              .cMCont__c__cTbl__cC__c__paragraph{
                text-align:left;
              }
              .cMCont__c__cTbl__cC__c__link{
                text-decoration: none !important;color: #fff;background-color: #FD4259;border-radius: 1.5rem;padding: 1rem 2rem;display: inline-block;
              }
            </style>
          </head>
          <body>
            <div class="cMCont">
              <div class="cMCont__c">
                <table class="cMCont__c__cTbl" rules="all">
                    <thead>
                      <td>
                        <tr>
                          <div class="cMCont__c__cTbl__cLogo">
                            <img src="https://erpsolugrifos.com/planverde/assets/img/logo-interno.png" alt="logo_planverde">
                          </div>
                        </tr>
                        <tr>
                          <div class="cMCont__c__cTbl__cTitle">
                            <span>Se ha creado un nuevo documento.</span>
                          </div>
                        </tr>
                        <div class="cMCont__c__cTbl__cC">
                          <div class="cMCont__c__cTbl__cC__c">
                            <p class="cMCont__c__cTbl__cC__c__paragraph">Hola, .<strong>"'.$cli_razonsocial.'"</strong></p>
                            <p class="cMCont__c__cTbl__cC__c__paragraph">Correo enviado desde PLANVEDE.</p>
                          </div>
                          <h3 class="cMCont__c__cTbl__cC__c__cTitle-h3">El equipo de PLANVERDE</h3>
                        </div>
                      </td>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
              </div>
            </div>
          </body>
          </html>';
          
          $mail->send();
          $r = array(
            'r' => 'true'
          );          
        }catch(Exception $e){
          echo "Ocurrio un error al enviar el correo. Error: {$mail->ErrorInfo}";
          
          $r = array(
            'r' => 'false'
          );
        }
      }else{
        return false;
      }
      if($return_id){
        $returndocument = ['action' => 'created','type' => "success",'message' => "Registo Exitoso", 'document_id' => $return_id];
      }else{
        $returndocument = ['action' => 'created','type' => "error",'message' => "Falló el registro", 'document_id' => ''];
      }
    }else{
      // if($return_id){
      //   $returndocument = ['action' => 'updated','type' => "success",'message' => "Actualización exitosa", 'document_id' => $return_id];
      // }else{
      //   $returndocument = ['action' => 'updated','type' => "error",'message' => "Falló al actualizar", 'document_id' => ''];
      // }
    }
    set_message($returndocument['type'], $returndocument['message']);
    redirect('admin/document_client/');
  }
  // ------------------- ELIMINAR DOCUMENTO
  public function delete_document($id = NULL){
    if(isset($id)){
      $data_document = $this->db->select('document_id, id_archivo')->where('document_id', $id)->order_by('document_id', 'DESC')->limit(1)->get('tbl_documents')->row();
      if(count($data_document) > 0){
        if($data_document->id_archivo != ""){
          $id_archivo = driveDelete($data_document->id_archivo); // ELIMINAR DOCUMENTO SUBIDO EN GOOGLE DRIVE
        }
        if($this->db->where('document_id', $id)->delete('tbl_documents')){
          $data = ['type' => 'success', 'message' => 'Registro Eliminado con Exito!!'];
        }else{
          $data = ['type' => 'error', 'message' => 'Ocurrio un Error al Eliminar el Registro.'];
        }
      }else{
        $data = ['type' => 'error', 'message' => 'Registro no existe'];
      }
    }else{
      $data = ['type' => 'error', 'message' => 'Error al eliminar Registro'];
    }
    echo json_encode($data);
    die();
  }
}

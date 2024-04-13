<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);


$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$url_base =  $actual_link . "/";

require '../vendor/autoload.php';

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
  public function add_document(){
    $data['title'] = "Agregar Documento";
    $data['all_categories'] = $this->db->get('tbl_categoria')->result_array();
    $data['all_clients'] = $this->db->get('tbl_cliente')->result_array();
    $data['all_anios'] = $this->db->get('tbl_anio')->result_array();
    $data['subview'] = $this->load->view('admin/documentsclient/add_document', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  public function save_document($id = null){
    
    $subcategory_id = $this->input->post('categoria_id');
    $data_subcategory = $this->db->where(['subcategoria_id' => $subcategory_id])->get('tbl_subcategoria')->row();
    $category_id = $data_subcategory->categoria_id;
    $subcategory_name = $data_subcategory->id_carpeta;
    $category_name = $this->db->get_where('tbl_categoria', array('categoria_id' => $category_id))->row()->nombre_categoria;
    $data_anio = $this->db->where(['anio' => $this->input->post('anio'), 'subcategoria_id' => $data_subcategory->subcategoria_id])->get('tbl_detail_anio')->row();
    
    try{
      /*
      $nombre = $_FILES['files']['name'];
      $service = driveService() ;
      $file_path = $_FILES['files']['tmp_name'];
      $file = new Google_Service_Drive_DriveFile();
      $file->setName($nombre);
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime_type = finfo_file($finfo, $file_path);
      $file->setParents(array($data_anio->id_carpeta));
      $file->setDescription("Archivo cargado desde el panel de administrador de PLAN VERDE");
      $file->setMimeType($mime_type);
      $resultado = $service->files->create(
        $file,
        array(
          'data' => file_get_contents($file_path),
          'mimeType' => $mime_type,
          'uploadType' => 'media'
        )
      );
      
      $ruta = 'https://drive.google.com/open?id=' . $resultado->id;
      */
      $data = [
      'client_id'    => $this->input->post('cliente_id'),
      'sede_id'      => $this->input->post('sede_id'),
      'nombre'       => $this->input->post('nombre'),
      'categoria_id' => $this->input->post('categoria_id'),
      'anio'         => $this->input->post('anio'),
      'mes'          => $this->input->post('mes'),
      'user_id'      => $_SESSION['user_id'],
      'ruta'         => $ruta,
      'id_archivo'   => $resultado->id
      // 'ruta'         => 'documents/folders/clientes',
      // 'id_archivo'   => 'unsAshAS884kdIGDKOKlskdjs9381fkd'
      ];

      $consulta = $this->db->query("INSERT INTO tbl_documents VALUES(NULL,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,?)", $data);
      if($consulta == true){
          
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
          
          try {
              $mail->CharSet = 'UTF-8';
              //Server settings
              $mail->SMTPDebug = 0;                                                           // Enable verbose debug output
              $mail->isSMTP();                                                    // Set mailer to use SMTP
              $mail->Host       = 'smtp.gmail.com';                                                    // Specify main and backup SMTP servers
              $mail->SMTPAuth   = true;                                                       // Enable SMTP authentication
              $mail->Username   = 'plataforma@solugrifos.com';                 // SMTP username
              $mail->Password   = 'nbougqpjipkyvdxb';                                                    // SMTP password
              $mail->SMTPSecure = 'ssl';                                                      // Enable TLS encryption, `ssl` also accepted
              $mail->Port       = 465; //587;                                          // TCP port to connect to
              
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
                                <p class="cMCont__c__cTbl__cC__c__paragraph">Le enviamos este correo electrónico porque solicitó un restablecimiento de contraseña. Haga clic en este enlace para crear una nueva contraseña.</p>
                                <a class="cMCont__c__cTbl__cC__c__link" href="">Establecer una nueva contraseña</a>
                                <p class="cMCont__c__cTbl__cC__c__paragraph">Si no solicitó un restablecimiento de contraseña, puede ignorar este correo electrónico. Tu contraseña no se cambiará.</p>
                              </div>
                              <h3 class="cMCont__c__cTbl__cC__c__cTitle-h3">El equipo de SrWong.pe</h3>
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
          $type = "success";
          $message = 'Registro Exitoso';
          set_message($type, $message);
          redirect('admin/document_client/');
      }else{
        return false;
      }
    }catch(Google_Service_Exception $gs){
        $mensaje = json_decode($gs->getMessage());
        echo $mensaje->error->message();
    }catch (Exception $e){
        echo $e->getMessage();
    }
  }
  private function guardar_archivo($dir){
    $mi_archivo              = 'files';
    $config['upload_path']   = $dir . "/";
    //$config['file_name']  = "nombre_archivo";
    $config['allowed_types'] = "*";
    $config['max_size']      = "50000000";
    $config['max_width']     = "20000000";
    $config['max_height']    = "20000000";
    $this->load->library('upload', $config);

    if(!$this->upload->do_upload($mi_archivo)){
      $data['uploadError'] = $this->upload->display_errors();
      echo $this->upload->display_errors();
      return;
    }
    return ($dataUpload = $this->upload->data());
  }
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
      if(!empty($type)){
        $where = array('tbl_documents.document_id' => $type);
      }else{
        $where = null;
      }
      $fetch_data = make_datatables($where);
      $data = array();
      $edited = can_action('4', 'edited');
      $deleted = can_action('4', 'deleted');
      foreach ($fetch_data as $_key => $document) {
        $action = null;
        $sub_array = array();
        $sub_array[] = $document->document_id;
        $sub_array[] = '<a href="https://drive.google.com/open?id='.$document->id_archivo.'" target="_blank" class="color-paragraph"><span class="txt-underline">'.$document->nombre.'</span></a>';
        $cliente =  $this->db->get_where("tbl_cliente", ['cliente_id' => $document->client_id])->row();
        $sub_array[] = $cliente->ruc . ' - ' . $cliente->razon_social;
        $sub_array[] = $this->db->get_where("tbl_anio", ['anio_id' => $document->anio])->row()->anio;
        $sub_array[] = $document->mes;
        if(!empty($deleted)){
          $action .= '<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Visualizar" target="_blank" href="' . $document->ruta . '"><span class="fa fa-eye"></span></a>' . ' ';
          //$action .='<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Visualizar" target="_blank" href="'.base_url("ver_documents/view-documents.php?key=").base64_encode($document->ruta).'"><span class="fa fa-eye"></span></a>' . ' ';
          $action .= '<span data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs delete-document" title="Click to ' . lang("delete") . ' " data-id="' . $document->id_archivo . '"><span class="fa fa-trash-o"></span></span>' . ' ';
        }
        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    }else{
      redirect('admin/dashboard');
    }
  }
  public function delete_document($id = NULL){
    if (isset($id)) {
      /* Para subir al Drive */
      include 'api_google/vendor/autoload.php';
      putenv('GOOGLE_APPLICATION_CREDENTIALS=plan-verde-308823-429d0e20a5db.json');
      $client = new Google_Client();

      $client->useApplicationDefaultCredentials();
      $client->SetScopes(['https://www.googleapis.com/auth/drive.file']);
      /* ----- */

      // $data_document = $this->db->where('document_id', $id)->get('tbl_documents')->row();
      $data_document = $this->db->where('id_archivo', $id)->get('tbl_documents')->row();


      if (count($data_document) > 0) {

        if ($this->db->where('id_archivo', $id)->delete('tbl_documents')) {

          /* crear carpetas en drive */
          $service = new Google_Service_Drive($client);
          $file = new Google_Service_Drive_DriveFile();

          /*$optParams = array(
              'fields' => 'files(id)'
          );*/
          //$results = $service->files->listFiles($optParams);
          $service->files->delete($id);

          //$folder = $service->files->delete($file);

          //$data_update['id_carpeta']=$folder->id;

          // BORRANDO EL ARCHIVO DE LA CARPETA

          $data = [
            'type'    => 'success',
            'message' => 'Registro Eliminado con Exito!!'
          ];
        } else {
          $data = [
            'type'    => 'error',
            'message' => 'Ocurrio un Error al Eliminar el Registro.'
          ];
        }
      } else {
        $data = [
          'type'    => 'error',
          'message' => 'Registro no existe'
        ];
      }
    } else {
      $data = [
        'type'    => 'error',
        'message' => 'Error al eliminar Registro'
      ];
    }
    echo json_encode($data);
    die();
  }
}

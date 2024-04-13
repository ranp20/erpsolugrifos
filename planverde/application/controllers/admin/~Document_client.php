<?php


if (!defined('BASEPATH'))
  exit('No direct script access allowed');
ini_set('memory_limit', '-1');
// set max execution time 2 hours / mostly used for exporting PDF
ini_set('max_execution_time', 3600);




class Document_client extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('document_client_model');
  }

  public function index()
  {
    $data['title'] = ('Documentos cliente');
    $data['subview'] = $this->load->view('admin/documentsclient/list', $data, TRUE);
    $this->load->view('admin/_layout_main', $data); //page load
  }

  public function add_document()
  {
    $data['title'] = ('Nuevo Documento');
    $data['all_categories'] = $this->db->get('tbl_categoria')->result_array();
    $data['all_clients'] = $this->db->get('tbl_cliente')->result_array();
    $data['all_anios'] = $this->db->get('tbl_anio')->result_array();
    $data['subview'] = $this->load->view('admin/documentsclient/add_document', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function save_document($id = null)
  {
    /* Para subir al Drive */
    include 'api_google/vendor/autoload.php';
    putenv('GOOGLE_APPLICATION_CREDENTIALS=plan-verde-308823-429d0e20a5db.json');
    $client=new Google_Client();

    $client->useApplicationDefaultCredentials();
    $client->SetScopes(['https://www.googleapis.com/auth/drive.file']);
    /* ----- */

    $target_path = base_url() . "uploads/documents/";

    $subcategory_id = $this->input->post('categoria_id');
    $data_subcategory = $this->db->where(['subcategoria_id' => $subcategory_id])->get('tbl_subcategoria')->row();
    $category_id = $data_subcategory->categoria_id;
    //$subcategory_name = $data_subcategory->nombre_subcategoria;
    $subcategory_name = $data_subcategory->id_carpeta;


   


    // echo "<br>--";
    $category_name = $this->db->get_where('tbl_categoria', array('categoria_id' => $category_id))->row()->nombre_categoria;
    $name_folder = $category_id . '-' . str_replace(" ", "_", $category_name);
    $dir = trim('./uploads/documents/' . $name_folder);

    $name_folder = $subcategory_id . '-' . str_replace(" ", "_", $subcategory_name);
    // mkdir( $folder_new, 0755);
    $dir =  trim($dir . '/' . $name_folder);

    // AÑO
    $data_anio = $this->db->where(['anio' => $this->input->post('anio'),'subcategoria_id'=>$data_subcategory->subcategoria_id])->get('tbl_detail_anio')->row();
    /*
    echo "<pre>";
    print_r($data_anio);
   echo "</pre>" ;
   die();
*/
    //$name_folder = $data_anio->anio;
    $name_folder_sub = $data_anio->id_carpeta_anio;
    $dir =  trim($dir . '/' . $name_folder_sub);
    
    if (!is_dir($dir)) {
      mkdir($dir, 0777);
    }
    // ESTA LINEA ES PARA ENCREAR CATEGORIAS O AL ACTUALIZAR SE CAMBIE EL NOMBRE DE LA CARPETA
    // rename('./uploads/documents/as/', './uploads/documents/asi/');



    // ojo $data_upload = $this->guardar_archivo($dir);
    try{
      $nombre=$_FILES['files']['name'];


      $service=new Google_Service_Drive($client);
      $file_path=$_FILES['files']['tmp_name'];

      $file=new Google_Service_Drive_DriveFile();
      $file->setName($nombre);

      $finfo=finfo_open(FILEINFO_MIME_TYPE);
      $mime_type=finfo_file($finfo,$file_path);

      $file->setParents(array($data_anio->id_carpeta));
      $file->setDescription("archivo de prueva para plan verde");
      $file->setMimeType($mime_type);

      $resultado=$service->files->create(
          $file,
          array(
              'data'=>file_get_contents($file_path),
              'mimeType'=>$mime_type,
              'uploadType'=>'media'
          )
      );

      $ruta='https://drive.google.com/open?id='.$resultado->id;

     
    // guardamos en db
  
    $data = [
      'client_id'    => $this->input->post('cliente_id'),
      'nombre'       => $this->input->post('nombre'),
      'categoria_id' => $this->input->post('categoria_id'),
      'anio'         => $this->input->post('anio'),
      'mes'          => $this->input->post('mes'),
      'user_id'      => $_SESSION['user_id'],
      'ruta'         => $ruta,
      'id_archivo'  =>$resultado->id
    ];
    
    

    //$this->db->set($data);
    //$this->db->insert('tbl_documents',$data);
   // $this->db->insert_id();
    //$consulta=$this->db->query("INSERT INTO tbl_documents VALUES(NULL,".$_POST['cliente_id'].",".(mysql_real_escape_string($_POST['nombre'])).",".$_POST['categoria_id'].",".$_POST['cliente_id'].",".$_POST['cliente_id'].",".$_POST['cliente_id'].",".$_SESSION['user_id'].",".$data_upload['file_name'].");");
    $consulta=$this->db->query("INSERT INTO tbl_documents VALUES(NULL,?,?,?,?,?,?,CURRENT_TIMESTAMP,?,?)",$data);
   // $this->db->query("INSERT INTO board_member (name, position, address) VALUES (?, ?, ?)", array($name, $position, $address));`
            if($consulta==true){
              $type = "success";
    $message = 'Registro Exitoso';
    set_message($type, $message);
    redirect('admin/document_client/');
            }else{
                return false;
            }

          }catch(Google_Service_Exception $gs){
            $mensaje=json_decode($gs->getMessage());
            echo $mensaje->error->message();
      
        }catch(Exception $e){
            echo $e->getMessage();
        }

   

    
   
  }

  private function guardar_archivo($dir)
  {
    $mi_archivo              = 'files';
    $config['upload_path']   = $dir . "/";
    //$config['file_name']  = "nombre_archivo";
    $config['allowed_types'] = "*";
    $config['max_size']      = "50000000";
    $config['max_width']     = "20000000";
    $config['max_height']    = "20000000";
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload($mi_archivo)) {
      //*** ocurrio un error
      $data['uploadError'] = $this->upload->display_errors();
      echo $this->upload->display_errors();
     // die();
      return;
    }

    return ($dataUpload = $this->upload->data());
  }

  public function documentsClientList($type = null)
  {
      
    if ($this->input->is_ajax_request()) {
        
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_documents';

      $this->datatables->column_search = array('tbl_documents.nombre');
      $this->datatables->column_order = array(' ', 'tbl_documents.nombre');
      $this->datatables->order = array('document_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array('tbl_documents.document_id' => $type);
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);
      $data = array();
      $edited = can_action('4', 'edited');
      $deleted = can_action('4', 'deleted');
      foreach ($fetch_data as $_key => $document) {
        $action = null;
        $sub_array = array();
        $sub_array[] = $document->nombre;
        $cliente =  $this->db->get_where("tbl_cliente", ['cliente_id' => $document->client_id])->row();
        $sub_array[] = $cliente->ruc . ' - ' . $cliente->razon_social;
        $sub_array[] = $this->db->get_where("tbl_anio", ['anio_id' => $document->anio])->row()->anio;
        $sub_array[] = $document->mes;
        

        if (!empty($deleted)) {
          
          $action .='<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Visualizar" target="_blank" href="'.$document->ruta.'"><span class="fa fa-eye"></span></a>' . ' ';
          //$action .='<a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Visualizar" target="_blank" href="'.base_url("ver_documents/view-documents.php?key=").base64_encode($document->ruta).'"><span class="fa fa-eye"></span></a>' . ' ';
          $action .='<span data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs delete-document" title="Click to ' . lang("delete") . ' " data-id="' . $document->id_archivo . '"><span class="fa fa-trash-o"></span></span>' . ' ';
        }


        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  public function delete_document($id = NULL)
  {
    if (isset($id)) {
       /* Para subir al Drive */
      include 'api_google/vendor/autoload.php';
      putenv('GOOGLE_APPLICATION_CREDENTIALS=plan-verde-308823-429d0e20a5db.json');
      $client=new Google_Client();

      $client->useApplicationDefaultCredentials();
      $client->SetScopes(['https://www.googleapis.com/auth/drive.file']);
      /* ----- */

     // $data_document = $this->db->where('document_id', $id)->get('tbl_documents')->row();
      $data_document = $this->db->where('id_archivo', $id)->get('tbl_documents')->row();
     
      
      if (count($data_document) > 0) {

        if ($this->db->where('id_archivo', $id)->delete('tbl_documents')) {
          
           /* crear carpetas en drive */
            $service=new Google_Service_Drive($client);
            $file=new Google_Service_Drive_DriveFile();
            
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

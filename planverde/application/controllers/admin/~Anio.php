<?php
// CADA CATEGORIA Y SUB CATEGORIA A L REGISTRAR O ACTUALIZAR SU NOMBRE DEBE CREAR UNA CARPETA Y/O SUBCARPETA 
// AL CREAR EL AÑO  JALAREMOS LAS CATEGORIAS Y SUBCATEGORIAS PARA CREAR DENTRO DE CADA SUBCATEGORIA LA CARPETA CON EL AÑO Q SE ESTA GUARDANDO
// verificaremos las carpetad tanto de categoria como subcategoria previo a la creacion de carpeta año


class Anio extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('anio_model');
    $this->load->model('categoria_model');
    $this->load->model('subcategoria_model');
  }

  public function index($id = NULL)
  {
    //$data['all_anios'] = $this->db->get('tbl_anio')->result();
    $data['all_anios'] = $this->db->get('tbl_anio')->result();
    $data['title'] = "Años";
    $data['page'] = "Años";

    $data['subview'] = $this->load->view('admin/anio/manage_anios', $data, TRUE);
    $this->load->view('admin/_layout_main', $data); //page load
  }

  public function add_anio($id = NULL)
  {
    $data['title'] = ('Registro de Año');
    //$data['all_anios'] = $this->db->get('tbl_anio')->result();
    $data['all_anios'] = $this->db->get('tbl_detail_anio')->result();

    if (!empty($id)) {
      //$data['anio_info'] = (object) $this->db->get_where('tbl_anio', ['anio_id' => $id])->row();
      $data['anio_info'] = (object) $this->db->get_where('tbl_detail_anio', ['detail_anio_id' => $id])->row();
    }
    $data['subview'] = $this->load->view('admin/anio/add_anio', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function save_anio($id = NULL)
  {
    /* Para subir al Drive */
    include 'api_google/vendor/autoload.php';
    putenv('GOOGLE_APPLICATION_CREDENTIALS=plan-verde-308823-429d0e20a5db.json');
    $client=new Google_Client();

    $client->useApplicationDefaultCredentials();
    $client->SetScopes(['https://www.googleapis.com/auth/drive.file']);
    /* ----- */

    $created = true;
    $edited = true;
    if (!empty($created) || !empty($edited) && !empty($id)) {
      /* if (!empty($id)) {
        $old_name_subcategoria = $this->db->get_where('tbl_subcategoria', ['subcategoria_id' => $id])->row()->nombre_subcategoria;
      } */

        // VERIFICANDO SI EXISTE LA CARPETA CATEGORIA
        $data_category = $this->db->get('tbl_categoria')->result();
       
        // aQ =>  guardamos el anio
        
        $data_anio_exit = $this->db->where( ['anio' => $this->input->post('anio') ] )->get( 'tbl_anio')->row();
        if( count($data_anio_exit)>0 ){
          $type = "error";
          $message = 'Año ya Existe';

        set_message($type, $message);
        redirect('admin/anio');
        }
       
        
        $data_anio_add['anio']          = $this->input->post('anio');
        $this->anio_model->_table_name  = 'tbl_anio';
        $this->anio_model->_primary_key = "anio_id";
        $anio_id = $this->anio_model->save($data_anio_add, $id);
        
        
                
          foreach ($data_category as $key => $category) :
          $name_folder = $category->categoria_id . '-' . str_replace(" ", "_", $category->nombre_categoria);
         
         



          $dir_cat = trim('./uploads/documents/' . $name_folder);
          if (!is_dir($dir_cat)) {
            mkdir($dir_cat, 0777);
          }
          // VERIFICAMOS SI EXISTE LA CARPETA DE SUBCATEGORIA CREADA
          $data_subcategoria = $this->db->get_where('tbl_subcategoria', ['categoria_id' => $category->categoria_id,'id_carpeta'=>$category->id_carpeta>0])->result();
         
          

          foreach ($data_subcategoria as $key => $subcat) :
           

            $name_folder = $subcat->subcategoria_id . '-' . str_replace(" ", "_", $subcat->nombre_subcategoria);
            //echo "folderName". $name_folder;
            //$id_carpeta = $this->db->get_where('tbl_subcategoria',array ('categoria_id' => $category['categoria_id']))->row()->id_carpeta;
           
            $data_anio = $this->db->where( ['anio' => $anio_id, 'categoria_id' => $subcat->categoria_id, 'subcategoria_id' => $subcat->subcategoria_id,'id_carpeta'=>$subcat->id_carpeta>0] )->get( 'tbl_detail_anio' )->row();

            /*echo "<pre>";
            echo "data";
            print_r($data);
            echo "subcat";
            print_r($subcat);
            echo "</pre>";*/
           
            
          
            if(count( $data_anio )==0){
            
            $data['id_carpeta'] = '';
            $data['anio']                   = $anio_id;
            $data['categoria_id'] = $subcat->categoria_id;;
            $data['subcategoria_id'] =$subcat->subcategoria_id;//$data_subcategoria;
            $this->anio_model->_table_name  = 'tbl_detail_anio';
            $this->anio_model->_primary_key = "detail_anio_id";
            $return_id                      = $this->anio_model->save($data, $id);

            /*
            echo "<pre>";
            echo "data";
            print_r($data);
            echo "subcat";
            print_r($subcat);
            echo "</pre>";*/
            // die();
            
            /* crear carpetas en drive */
            
            $service=new Google_Service_Drive($client);
            $file=new Google_Service_Drive_DriveFile();
            $file->setName($this->input->post('anio'));
            $file->setParents(array($subcat->id_carpeta));
            $file->setDescription("archivo de prueva para plan verde");
            $file->setMimeType($mime_type);
            $file->setMimeType('application/vnd.google-apps.folder');
            $folder = $service->files->create($file);

            $data_update['id_carpeta']=$folder->id;
            
           


            $id_update = $this->anio_model->save($data_update, $return_id);

         

            
            //$name_folder = $return_id . '-' . str_replace(" ", "_", $this->input->post('nombre_subcategoria'));
            

            /*echo "<pre>";
              print_r($folder);
            echo "</pre>";
            die();*/
            /* -------- */

            //$category_name = $this->db->get_where('tbl_subcategoria', array('subcategoria_id' => $data['subcategoria_id']))->row()->nombre_categoria;
            //$name_anio = $this->db->get_where('tbl_anio', array('anio_id' => $data['anio_id']))->row()->anio;
              
      
            //$name_folder_anio = $data['subcategoria_id'] . '-' . str_replace(" ", "_", $name_anio) . '/';




            






            $dir_subcat = trim($dir_cat . '/' . $name_folder);
            if (!is_dir($dir_subcat)) {
              mkdir($dir_subcat, 0777);
            }

            // se crea la carpeta año dentro de cada subcarpeta( subcategoria )
            $dir_anio = trim($dir_subcat . '/' . $data['anio']);
            if (!is_dir($dir_anio)) {
              mkdir($dir_anio, 0777);
            }
          }
            $dir_subcat = '';
            $dir_anio = '';
          endforeach;
          $dir_cat = '';
        endforeach;
        
        //die();
        

        $type = "success";
        $message = 'Registro Exitoso';
      
      /*} else {
        $type = "error";
        $message = 'Fallo el registro';
      }*/
      set_message($type, $message);
      redirect('admin/anio');
    
    
    }
  }
}

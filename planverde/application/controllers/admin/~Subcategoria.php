<?php
class Subcategoria extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('categoria_model');
    $this->load->model('subcategoria_model');
  }

  public function index($id = NULL)
  {
    $data['all_subcategories'] = $this->db->get('tbl_subcategoria')->result();
    $data['title'] = "Sub categoria";
    $data['page'] = "Subcategoria";



    $data['subview'] = $this->load->view('admin/subcategoria/manage_subcategoria', $data, TRUE);
    $this->load->view('admin/_layout_main', $data); //page load
  }

  public function subcategoriaList($type = null)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_subcategoria';

      $this->datatables->column_search = array('tbl_subcategoria.nombre_subcategoria');
      $this->datatables->column_order  = array('tbl_subcategoria.nombre_subcategoria');
      $this->datatables->order         = array('subcategoria_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array('tbl_subcategoria.subcategoria_id' => $type);
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);

      $data = array();
      /* $edited = can_action('4', 'edited');
            $deleted = can_action('4', 'deleted'); */
      foreach ($fetch_data as $_key => $subcategoria) {
        $action = null;


        $sub_array = array();



        $sub_array[] = $subcategoria->nombre_subcategoria;
        $sub_array[] = $this->db->get_where('tbl_categoria', ['categoria_id' =>  $subcategoria->categoria_id])->row()->nombre_categoria;


        // if (!empty($edited)) {
        // $action .= btn_edit('admin/subcategoria/add_subcategoria/' . $subcategoria->subcategoria_id) . ' ';
        /* }
                if (!empty($deleted)) { */
        $action .= '<a data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="Click para Editar " href="' . base_url() . 'admin/subcategoria/add_subcategoria/' . $subcategoria->subcategoria_id . '"><span class="fa fa-pencil"></span></a>' . ' ';

        $action .= '<a data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs" title="Click para Eliminar " href="' . base_url() . 'admin/subcategoria/delete_subcategoria/' . $subcategoria->subcategoria_id . '"><span class="fa fa-trash-o"></span></a>' . ' ';
        // }

        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  public function add_subcategoria($id = NULL)
  {
    $data['title'] = ('Registro de Subcategoria');
    $data['all_categories'] = $this->db->get('tbl_categoria')->result_array();

    if (!empty($id)) {
      $data['subcategory_info'] =  $this->db->get_where('tbl_subcategoria', ['Subcategoria_id' => $id])->row();
    }
    $data['subview'] = $this->load->view('admin/subcategoria/add_subcategoria', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function save_subcategoria($id = NULL)
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
      if (!empty($id)) {
        $old_name_subcategoria = $this->db->get_where('tbl_subcategoria', ['subcategoria_id' => $id])->row()->nombre_subcategoria;
      }

      

      if (empty($id)) {

        $data['id_carpeta'] = '';
        $data['nombre_subcategoria'] = $this->input->post('nombre_subcategoria');
        $data['upload_client'] = (!empty($this->input->post('upload_client')) ) ? '1' : '0'; 
        $data['categoria_id'] = $this->input->post('categoria_id');
        $this->subcategoria_model->_table_name = 'tbl_subcategoria';
        $this->subcategoria_model->_primary_key = "subcategoria_id";
        $return_id = $this->subcategoria_model->save($data, $id);

        $name_folder = $return_id . '-' . str_replace(" ", "_", $this->input->post('nombre_subcategoria'));
        $id_carpeta = $this->db->get_where('tbl_categoria', array('categoria_id' => $data['categoria_id']))->row()->id_carpeta;

        /* crear carpetas en drive */
        $service=new Google_Service_Drive($client);
        $file=new Google_Service_Drive_DriveFile();
        $file->setName($name_folder);
        $file->setParents(array($id_carpeta));
        $file->setDescription("archivo de prueva para plan verde");
        $file->setMimeType($mime_type);
        $file->setMimeType('application/vnd.google-apps.folder');
        $folder = $service->files->create($file);

        $data_update['id_carpeta']=$folder->id;

        $id_update = $this->subcategoria_model->save($data_update, $return_id);
        $name_folder = $return_id . '-' . str_replace(" ", "_", $this->input->post('nombre_subcategoria'));
        /*echo "<pre>";
          print_r($folder);
        echo "</pre>";
        die();*/
        /* -------- */

        $category_name = $this->db->get_where('tbl_categoria', array('categoria_id' => $data['categoria_id']))->row()->nombre_categoria;
      //$id_carpeta = $this->db->get_where('tbl_categoria', array('categoria_id' => $data['categoria_id']))->row()->id_carpeta;
      
        $name_folder_category = $data['categoria_id'] . '-' . str_replace(" ", "_", $category_name) . '/';

        $dir = trim('./uploads/documents/' . $name_folder_category . $name_folder);

        if (!is_dir($dir)) {
          mkdir($dir, 0777);
        }
      } else {
        $dir_old = trim('./uploads/documents/' . $name_folder_category . $id . '-' . str_replace(" ", "_", $old_name_subcategoria));

        // $name_categoria = $this->db->get_where( 'tbl_categoria', ['categoria_id' => $id] )->row()->nombre_categoria;

        $dir_new = trim('./uploads/documents/' . $name_folder_category . $id . '-' . str_replace(" ", "_", $data['nombre_subcategoria']));

        if (!is_dir($dir_old)) {
          mkdir($dir_new, 0777);
        } else {
          rename($dir_old, $dir_new);
        }
      }

      // messages for user
      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/subcategoria');
    }
  }
}

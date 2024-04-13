<?php
class Categoria extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('categoria_model');
  }

  public function manage_categoria($id = NULL)
  {
    if (!empty($id)) {
      if (is_numeric($id)) {
        $data['active'] = 2;
        // get all categoria info by categoria id
        $this->categoria_model->_table_name = "tbl_categoria"; //table name
        $this->categoria_model->_order_by = "categoria_id";
        $data['categoria_info'] = $this->categoria_model->get_by(array('categoria_id' => $id), TRUE);
        $edited = can_action('4', 'edited');
        if (empty($data['categoria_info']) || empty($edited)) {
          $type = "error";
          $message = "No Record Found";
          set_message($type, $message);
          redirect('admin/categorias/manage_categoria');
        }
      } else {
        $data['active'] = 1;
      }
    } else {
      $data['active'] = 1;
    }
    $data['title'] = lang('manage_categoria'); //Page title
    $data['page'] = lang('categoria');



    $data['subview'] = $this->load->view('admin/categoria/manage_categoria', $data, TRUE);
    $this->load->view('admin/_layout_main', $data); //page load
  }

  public function categoriaList($type = null)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_categoria';

      $this->datatables->column_search = array('tbl_categoria.nombre_categoria');
      $this->datatables->column_order = array(' ', 'tbl_categoria.nombre_categoria');
      $this->datatables->order = array('categoria_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array('tbl_categoria.categoria_id' => $type);
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);

      $data = array();
      $edited = can_action('4', 'edited');
      $deleted = can_action('4', 'deleted');
      foreach ($fetch_data as $_key => $categoria) {
        $action = null;


        $sub_array = array();



        $sub_array[] = $categoria->nombre_categoria;


        if (!empty($edited)) {
          $action .= btn_edit('admin/categoria/manage_categoria/' . $categoria->categoria_id) . ' ';
        }
        if (!empty($deleted)) {
          $action .= '<a data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs" title="Click to ' . lang("delete") . ' " href="' . base_url() . 'admin/categoria/delete_categoria/' . $categoria->categoria_id . '"><span class="fa fa-trash-o"></span></a>' . ' ';
        }

        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  public function save_categoria($id = NULL)
  {
    $created = can_action('4', 'created');
    $edited = can_action('4', 'edited');
    if (!empty($created) || !empty($edited) && !empty($id)) {
      if (!empty($id)) {
        $old_name_categoria = $this->db->get_where('tbl_categoria', ['categoria_id' => $id])->row()->nombre_categoria;
      }

      $data['nombre_categoria'] = $this->input->post('nombre_categoria');
      $this->categoria_model->_table_name = 'tbl_categoria';
      $this->categoria_model->_primary_key = "categoria_id";
      $return_id = $this->categoria_model->save($data, $id);

      if (empty($id)) {
        $category_name = $this->db->get_where('tbl_categoria', array('categoria_id' => $id))->row()->nombre_categoria;
        $name_folder = $return_id . '-' . str_replace(" ", "_", $this->input->post('nombre_categoria'));

        $dir = trim('./uploads/documents/' . $name_folder);

        if (!is_dir($dir)) {
          mkdir($dir, 0777);
        }
      } else {
        $dir_old = trim('./uploads/documents/' . $id . '-' . str_replace(" ", "_", $old_name_categoria));

        $name_categoria = $this->db->get_where('tbl_categoria', ['categoria_id' => $id])->row()->nombre_categoria;

        $dir_new = trim('./uploads/documents/' . $id . '-' . str_replace(" ", "_", $name_categoria));

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
      redirect('admin/categoria/manage_categoria');
    }
    // $save_and_create_contact = $this->input->post('save_and_create_contact', true);
    // if (!empty($save_and_create_contact)) {
    //     redirect('admin/client/client_details/' . $id . '/add_contacts');
    // } else {
    // }
  }
  public function cmb_x_sede($sede_id = NULL)
  {
    $data['title'] = 'Combo Sede por cliente';
    $data['page'] = 'Combo Sede por cliente';
    $data['all_categories'] = $this->db->get('tbl_categoria')->result_object();
    $data['all_subcategories'] = $this->db->get('tbl_subcategoria')->result_object();
    $data['all_permissions'] = json_decode($this->db->where(['sede_id' => $sede_id])->get('tbl_sedes')->row()->permission);
/*     echo "<pre>";
    print_r($data);
    echo "</pre>";
die(); */
    $this->load->view('admin/categoria/cmb_x_sede', $data);
    // $this->load->view('admin/_layout_modal', $data);
  }
}

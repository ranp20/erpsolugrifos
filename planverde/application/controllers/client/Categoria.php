<?php
use Stripe\Error\Permission;
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Categoria extends Client_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('admin_model');
    $this->load->model('document_client_model');
  }
  public function index(){
    redirect("client/dashboard");
  }
  public function list($sede_id = null){
    if(!empty($sede_id)){
        $_SESSION['sede'] = $sede_id;
        $data['title'] = 'CATEGORIAS DE ';
        $data['page'] = 'CATEGORIAS DE ';
        $data_sede = $this->db->where('sede_id', $sede_id)->get('tbl_sedes')->row();
        $data['sede'] = strtoupper($data_sede->direccion);
        $permissions = json_decode($data_sede->permission);
      
        if(!empty($permissions) || $permissions != "" || $permissions != "[]"){
            $categories_ids = $this->db->select('categoria_id')->group_by('categoria_id')->where_in('subcategoria_id', $permissions)->get('tbl_subcategoria')->result();
            $categories = [];
            foreach ($categories_ids as $key => $cat) :
            $categories[] = $cat->categoria_id;
            endforeach;
            $data['all_categories'] = $this->db->where_in('categoria_id', $categories)->get('tbl_categoria')->result();
            $data['subview'] = $this->load->view('client/categorias/list', $data, TRUE);
            $this->load->view('client/_layout_main', $data);
        }else{
            $data['subview'] = $this->load->view('client/categorias/list', $data, TRUE);
            $this->load->view('client/_layout_main', $data);
        }
    }else{
      redirect("client/dashboard");
    }
  }
  public function anio($id_cat = null){
    if (!empty($id_cat)) {
      $data['title'] = 'AÑOS SEGUN LA CATGEORIA';
      $data['page'] = 'AÑOS SEGUN LA CATGEORIA';

      $data['id_categoria'] = $id_cat;
      $data['categoria'] = $this->db->get_where('tbl_categoria', ['categoria_id' => $id_cat])->row()->nombre_categoria;
      $this->db->group_by('anio');
      $data['all_anios'] = $this->db->get_where('tbl_documents', ['categoria_id' => $id_cat])->result_object();

      $data['subview'] = $this->load->view('client/categorias/anios', $data, TRUE);
      $this->load->view('client/_layout_main', $data);
    } else {
      redirect("client/dashboard");
      echo "aass";
    }
  }
  public function list_anios($id_cat = null, $type){

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_documents';
      $this->datatables->column_search = array('tbl_categoria.nombre_categoria');
      $this->datatables->column_order = array(' ', 'tbl_categoria.nombre_categoria');
      $this->datatables->order = array('anio' => 'desc');
      
      if(!empty($type)){
        $where = array('anio' => $type);
      }else{
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
}

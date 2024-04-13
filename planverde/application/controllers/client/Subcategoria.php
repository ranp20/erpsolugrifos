<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subcategoria extends Client_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('admin_model');
        // $this->load->model('invoice_model');
        $this->load->model('categoria_model');
        $this->load->model('subcategoria_model');
    }
    public function index(){
        redirect("client/dashboard");
    }
    public function list( $id_cat = null ){
        if( !empty( $id_cat ) ){
          $data['title'] = 'Subcategorias';
          $data['page'] = 'Subcategorias';
          $permissions = json_decode( $this->db->where('sede_id', $_SESSION['sede'])->get('tbl_sedes')->row()->permission );
          $data['all_subcategories'] = $this->db->where_in('subcategoria_id', $permissions)->where('categoria_id', $id_cat)->get('tbl_subcategoria')->result();
          $data['id_categoria'] = $id_cat;
          $data['categoria'] = $this->db->get_where( 'tbl_categoria', ['categoria_id' => $id_cat] )->row()->nombre_categoria;
          
          // $data['all_subcategories'] = $this->db->get_where( 'tbl_subcategoria', ['categoria_id' => $id_cat] )->result_object();
    
          $data['subview'] = $this->load->view('client/subcategorias/list', $data, TRUE);
          $this->load->view('client/_layout_main', $data);
        }else{
          redirect("client/dashboard");
        }
    }
    public function anio( $id_subcat = null ){
        if( !empty( $id_subcat ) ){
          $data['title'] = 'AÑOS SEGÚN LA CATEGORÍA';
          $data['page'] = 'AÑOS SEGÚN LA CATEGORÍA';
          $data['id_categoria'] = $id_subcat;
          $subcat = $this->db->get_where( 'tbl_subcategoria', ['subcategoria_id' => $id_subcat] )->row();
          $data['subcategoria'] = $subcat->nombre_subcategoria;
          $data['subcategoria_id'] = $subcat->subcategoria_id;
          $data['categoria'] = $this->db->get_where( 'tbl_categoria', ['categoria_id' =>  $subcat->subcategoria_id ] )->row()->nombre_categoria;
          $this->db->group_by( 'anio' );
          $data['all_anios'] = $this->db->order_by('anio', 'DESC')->get( 'tbl_anio')->result_object();
          $data['subview'] = $this->load->view('client/subcategorias/anios', $data, TRUE);
          $this->load->view('client/_layout_main', $data);
        }else{
          redirect("client/dashboard");
        }
    }
    public function list_anios( $id_cat =null, $type ){
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_documents';
            $this->datatables->column_search = array('tbl_categoria.nombre_categoria');
            $this->datatables->column_order = array(' ', 'tbl_categoria.nombre_categoria');
            $this->datatables->order = array('anio' => 'desc');
            
            if (!empty($type)) {
                $where = array('anio' => $type);
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
}
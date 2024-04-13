<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sede extends Client_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('sede_model');
    }
    public function index(){
      $data['title'] = 'Sedes | PLAN VERDE';
      $data['page']  = 'SEDES ';
      $cliente_id = $this->db->where( ['user_id' => $this->session->userdata('user_id')] )->get('tbl_account_details')->row()->company;
      $data['all_sedes'] = $this->db->where(['cliente_id'=> $cliente_id])->get('tbl_sedes')->result_object();
      $data['subview'] = $this->load->view('client/sede/index', $data, TRUE);
      $this->load->view('client/_layout_main', $data);
    }
    public function list_anios($id_cat =null, $type){
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
            foreach($fetch_data as $_key => $categoria){
                $action = null;
                $sub_array = array();
                $sub_array[] = $categoria->nombre_categoria;
                if(!empty($edited)){
                    $action .= btn_edit('admin/categoria/manage_categoria/' . $categoria->categoria_id) . ' ';
                }
                if(!empty($deleted)){
                    $action .= '<a data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs" title="Click to ' . lang("delete") . ' " href="' . base_url() . 'admin/categoria/delete_categoria/' . $categoria->categoria_id . '"><span class="fa fa-trash-o"></span></a>' . ' ';
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data, $where);
        }else{
            redirect('admin/dashboard');
        }
    }
}
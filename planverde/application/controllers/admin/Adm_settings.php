<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Adm_settings extends Admin_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('adm_settings_model');
    }
    public function index($id = NULL){        
        $data['all_settings'] = $this->db->get('tbl_adm_settings')->result();
        $data['title'] = "Ajustes Administrador | PLAN VERDE";
        $data['page'] = "AJUSTES ADMINISTRADOR";
        $data['subview'] = $this->load->view('admin/adm_settings/index', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
        
    }
}
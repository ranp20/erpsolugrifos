<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
/**
 * @author aQMiGuEL <email@email.com>
 */
class Pv_activitie extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('pv_activitie_model');
    $this->load->library('form_validation');

    $this->pv_activitie_model->_table_name  = 'tbl_pv_activities';
    $this->pv_activitie_model->_order_by    = 'pv_activitie_id';
    $this->pv_activitie_model->_primary_key = 'pv_activitie_id';
  }

  public function index()
  {
    $data['title'] = 'Actividades - Plan Verde';
    $data['page'] = 'Actividades - Plan Verde';
    $data['btn_add'] = TRUE;

    $data['subview'] = $this->load->view('admin/pv_activitie/index', $data, TRUE); //TRUE ES CUANDO SE DEVUELVEN COMO DATOS LA VIEW 
    $this->load->view('admin/_layout_main', $data);
  }

  public function add_pv_activitie($id = NULL)
  {
    $data['title'] = 'Nueva Actividad';
    $data['pv_activitie_info'] = ($id) ? $this->pv_activitie_model->get($id, TRUE) : '';
    $data['subview'] = $this->load->view('admin/pv_activitie/add_pv_activitie', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  public function save_pv_activitie($id = NULL)
  {

    $data = [
      'activitie' => strtoupper($this->input->post('activitie')),
      'description' => $this->input->post( 'description' )
    ];
    if ($id == NULL && $this->pv_activitie_model->get_by($data)) {
      $type = 'error';
      $message = 'Actividad ya existe.';
    } else {
      $return_id = $this->pv_activitie_model->save($data, $id);
      if ($return_id) {
        $type = 'success';
        $message = 'Registro Exitoso.';
      } else {
        $type = 'error';
        $message = 'Registro Fallido.';
      }
    }

    set_message($type, $message);
    redirect('admin/pv_activitie');
  }

  public function pvActivitieList($type = NULL)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_pv_activities';
      $this->datatables->column_search = array('tbl_pv_activities.activitie');
      $this->datatables->column_order = array('tbl_pv_activities.activitie');
      $this->datatables->order = array('pv_activitie_id' => 'desc');
      if (!empty($type)) {
        $where = array('tbl_pv_activities.pv_activitie_id' => $type);
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);
      $data = array();
      foreach ($fetch_data as $_key => $pv_activitie) {
        $action = null;
        $sub_array = array();

        $sub_array[] = $_key + 1;
        $popover = ($pv_activitie->description) ? 'style="cursor:pointer" data-toggle="popover" title="Descripción" data-content="'.$pv_activitie->description.'"' : '';
        $sub_array[] = '<span class="text-info" '. $popover .'>' .$pv_activitie->activitie.'</span>';

        $action .= '<span data-placement="top" data-toggle="tooltip" title="EDITAR ACTIVIDAD"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/pv_activitie/add_pv_activitie/' . $pv_activitie->pv_activitie_id . '"><span class="fa fa-pencil"></span></a></span>' . ' ';

        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }
}

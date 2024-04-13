<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
/**
 * @author aQMiGuEL <aquinoproyectos@gmail.com>
 * @phone 981957789
 */
class Service extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('service_model');
    $this->load->library('form_validation');

    $this->service_model->_table_name  = 'tbl_services';
    $this->service_model->_order_by    = 'service_id';
    $this->service_model->_primary_key = 'service_id';
  }

  public function index()
  {
    $data['title'] = 'Servicios';
    $data['page'] = 'Servicios';
    $data['btn_add'] = TRUE;

    $data['subview'] = $this->load->view('admin/service/index', $data, TRUE); //TRUE ES CUANDO SE DEVUELVEN COMO DATOS LA VIEW 
    $this->load->view('admin/_layout_main', $data);
  }

  public function add_service($id = NULL)
  {
    $data['title'] = 'Nuevo Servicio';
    $data['service_info'] = ($id) ? $this->service_model->get($id, TRUE) : '';
    $data['subview'] = $this->load->view('admin/service/add_service', $data, FALSE);
    $this->load->view('admin/_layout_modal_large', $data);
  }
  public function save_service($id = NULL)
  {

    $data = [
      'service' => strtoupper($this->input->post('service'))
    ];
    if ($id == NULL && $this->service_model->get_by($data)) {
      $type = 'error';
      $message = 'Servicio ya existe.';
    } else {
        $data['descripcion'] = $this->input->post('descripcion');
      $return_id = $this->service_model->save($data, $id);
      if ($return_id) {
        $type = 'success';
        $message = 'Registro Exitoso.';
      } else {
        $type = 'error';
        $message = 'Registro Fallido.';
      }
    }

    set_message($type, $message);
    redirect('admin/service');
  }

  public function serviceList($type = NULL)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_services';
      $this->datatables->column_search = array('tbl_services.service');
      $this->datatables->column_order = array('tbl_services.service');
      $this->datatables->order = array('service_id' => 'desc');
      if (!empty($type)) {
        $where = array('tbl_services.service_id' => $type);
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);
      $data = array();
      foreach ($fetch_data as $_key => $service) {
        $action = null;
        $sub_array = array();

        $sub_array[] = $_key + 1;
        $sub_array[] = $service->service;

        $action .= '<span data-placement="top" data-toggle="tooltip" title="EDITAR SERVICIO"><a  data-toggle="modal" data-target="#myModal_large"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/service/add_service/' . $service->service_id . '"><span class="fa fa-pencil"></span></a></span>' . ' ';

        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }
}

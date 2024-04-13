<?php 
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
/**
 * @author Jharol <email@email.com>
 */
class Pv_actividades_realizar extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('pv_activitie_model');
    $this->load->library('form_validation');

    $this->pv_activitie_model->_table_name  = 'tbl_pv_order_activities';
    $this->pv_activitie_model->_order_by    = 'pv_order_activitie_id';
    $this->pv_activitie_model->_primary_key = 'pv_order_activitie_id';
  }

  public function index()
  {
    $data['title'] = 'Actividades Realizar - Plan Verde';
    $data['page'] = 'Actividades Realizar - Plan Verde';
    $data['btn_add'] = TRUE;

    $data['subview'] = $this->load->view('admin/pv_actividades_realizar/index', $data, TRUE); //TRUE ES CUANDO SE DEVUELVEN COMO DATOS LA VIEW 
    $this->load->view('admin/_layout_main', $data);
  }

  public function add_pv_activitie($id = NULL)
  {
    
    $data['title'] = 'Nueva Actividad';
    $data['pv_activitie_info'] = ($id) ? $this->pv_activitie_model->get($id, TRUE) : '';
  //   echo "<pre>";
  // print_r($data['pv_activitie_info']);
  // echo "</pre>";
  // die();
    $data['subview'] = $this->load->view('admin/pv_actividades_realizar/add_pv_activitie', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  private function guardar_archivo($dir)
  {
    $mi_archivo              = 'txtfile';
    $config['upload_path']   = $dir . "/";
    // $config['file_name']  = "nombre_archivo";
    $config['allowed_types'] = "*";
    $config['max_size']      = "5000000000";
    $config['max_width']     = "2000000000";
    $config['max_height']    = "2000000000";
    $this->load->library('upload', $config);

    return $this->upload->do_upload($mi_archivo);
  }

  public function save_pv_activitie($id = NULL)
  {
    
    $dir =  "./uploads/plan_verde";
    if (!is_dir($dir)) {
      mkdir($dir, 0777);
    }
    $dir =  "./uploads/plan_verde/constancia";
    if (!is_dir($dir)) {
      mkdir($dir, 0777);
    }
    $ruta = "";
    // if ($this->input->post('files')) {
    if ($this->guardar_archivo($dir)) {
      $data_upload = $this->upload->data();
      $ruta = $data_upload['file_name'];
    }

    $data = [
      'fecha_constancia' => strtoupper($this->input->post('txtf')),
      'ruta_contancia' => $ruta,
      'status' => 3
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
    redirect('admin/pv_actividades_realizar/');
  }

  public function pvActivitieRealizarList($type = NULL)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_pv_order_activities';
      
      $this->datatables->column_search = array('tbl_pv_order_activities.pv_order_activitie_id');
      $this->datatables->column_order = array('tbl_pv_order_activities.pv_order_activitie_id');
      $this->datatables->order = array('pv_order_activitie_id' => 'desc');
      
      
      if (!empty($type)) {
        $where = array('tbl_pv_order_activities.pv_order_activitie_id' => $type);
      } else {
        $where = array( 'tbl_pv_order_activities.area_asignada' => $this->session->userdata('designations_id')); // FILTRO POR ID DE ÁREA DESIGNADA
        //$where = array( 'tbl_pv_order_activities.user_id' => $this->session->userdata('user_id')); // FILTRO POR ID DE USUARIO
      }

      $fetch_data = make_datatables($where);
      $data = array();
      foreach ($fetch_data as $_key => $pv_activitie) {
        //if( in_array( $pv_activitie->status, [2,3] ) ){
        if( in_array( $pv_activitie->status, [1,2,3] ) ){
          $action = null;
          $sub_array = array();
          $sub_array[] = 'OTA-'.$pv_activitie->pv_order_activitie_id;
          $activities = $this->db->where(['pv_activitie_id' => $pv_activitie->pv_activitie_id ])->get('tbl_pv_activities')->row();
          $order = $this->db->where( ['pv_order_id' => $pv_activitie->pv_order_id] )->get( 'tbl_pv_orders' )->row();
          $cliente = $this->db->where( ['cliente_id' => $order->cliente_id] )->get( 'tbl_cliente')->row();
          $sede = $this->db->where( ['sede_id' => $order->sede_id] )->get( 'tbl_sedes')->row();
          
          $sub_array[] = '<span class="text-info" >' .$activities->activitie.'</span>';
          $sub_array[] = '<span class="text-info" >' .$cliente->razon_social.'</span>';
          $sub_array[] = '<span class="text-info" >' .$sede->direccion.'</span>';
          $sub_array[] = '<span class="text-info" >' . date('d-m-Y', strtotime($pv_activitie->start_date)) .'</span>';
          $sub_array[] = '<span class="text-info" >' . date('d-m-Y', strtotime($pv_activitie->end_date)).'</span>';
          $sub_array[] = $this->status($pv_activitie->status);
          
          $brn_download =  '<span data-placement="top" data-toggle="tooltip" title="EDITAR ACTIVIDAD"><a  target="_blank"  class="btn btn-green btn-xs"  href="' . base_url() . 'uploads/plan_verde/constancia/' . $pv_activitie->ruta_contancia . '"><span class="fa fa-download"></span></a></span>';
          $brn_update =  '<span data-placement="top" data-toggle="tooltip" title="SUBIR CONFORMIDAD"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/pv_actividades_realizar/add_pv_activitie/' . $pv_activitie->pv_order_activitie_id . '"><span class="fa fa-pencil"></span></a></span>';
          
          
          //$action .=(isset($pv_activitie->status) && ($pv_activitie->status)==2) ? $brn_update : '';
          $action .=(isset($pv_activitie->status) && ($pv_activitie->status)==1 || ($pv_activitie->status)==2) ? $brn_update : '';
          $action .=(isset($pv_activitie->status) && ($pv_activitie->status)==3) ? $brn_download : '';

  
          $sub_array[] = $action;
          $data[] = $sub_array;
        }
      }
      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }
  private function status($id = 1)
  {
    switch ($id) {
      case '0':
        $type = "danger";
        $text = "----";
        break;

      case '1':
        $type = "warning";
        $text = "INGRESADO";
        break;

      case '2':
        $type = "info";
        $text = 'POR ATENDER';
        break;


        // COMERCIAL SUBIENDO OT
      case '3':
        $type = "success";
        $text = 'REALIZADO';
        break;

      default:
        $type = "danger";
        $text = "CANCELADO";
        break;
    }
    return '<h5><span class=" label label-xs label-' . $type . '">' . $text . '</span></h5>';
  }

}

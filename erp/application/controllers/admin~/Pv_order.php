<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
/**
 * @author aQMiGuEL <email@email.com>
 */
class Pv_order extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('pv_order_model');
    $this->load->library('form_validation');

    $this->pv_order_model->_table_name  = 'tbl_pv_orders';
    $this->pv_order_model->_order_by    = 'pv_order_id';
    $this->pv_order_model->_primary_key = 'pv_order_id';
  }

  public function index()
  {
    $data['title'] = 'Ordernes para trabajos';
    $data['page'] = 'Ordernes para trabajos';
    // si es comercial para generar la orden
    $data['btn_add'] = (in_array($this->session->userdata('designations_id'), [1])) ? TRUE : FALSE;

    $data['btn_calendar'] = (in_array($this->session->userdata('designations_id'), [5])) ? TRUE : FALSE;

    $data['subview'] = $this->load->view('admin/pv_order/index', $data, TRUE); //TRUE ES CUANDO SE DEVUELVEN COMO DATOS LA VIEW 
    $this->load->view('admin/_layout_main', $data);
  }

  public function add_pv_order($id = NULL)
  {
    $data['title'] = 'Nueva Orden';
    $data['pv_order_info'] = ($id) ? $this->pv_order_model->get($id, TRUE) : '';
    $data['all_clients'] = $this->db->get('tbl_cliente')->result();
    $data['sedes'] = ($cliente_id = $data['pv_order_info']->cliente_id) ? $this->db->where(['cliente_id' => $cliente_id])->get('tbl_sedes')->result() : '';
    $data['subview'] = $this->load->view('admin/pv_order/add_pv_order', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  public function save_pv_order($id = NULL)
  {

    $data = [
      'cliente_id' => strtoupper($this->input->post('cliente_id')),
      'sede_id' => strtoupper($this->input->post('sede_id')),
      'comment' => $this->input->post('comment'),
      'fecha_fin' => $this->input->post('fecha_fin'),

      'user_id' => $this->session->userdata('user_id'),
      'designation_id' => $this->session->userdata('designations_id')
    ];
    /*
    if ($id == NULL && $this->pv_order_model->get_by($data)) {
      $type = 'error';
      $message = 'Orden ya existe.';
    } else {*/
    $return_id = $this->pv_order_model->save($data, $id);
    if ($return_id) {
      $type = 'success';
      $message = 'Registro Exitoso.';
    } else {
      $type = 'error';
      $message = 'Registro Fallido.';
    }
    // }

    set_message($type, $message);
    redirect('admin/pv_order');
  }

  public function pvOrderList($type = NULL)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table         = 'tbl_pv_orders';
      $this->datatables->join_table    = array('tbl_cliente', 'tbl_sedes');
      $this->datatables->join_where    = array('tbl_cliente.cliente_id = tbl_pv_orders.cliente_id', 'tbl_sedes.sede_id = tbl_pv_orders.sede_id');

      $this->datatables->column_search = array('tbl_pv_orders.pv_order_id', 'tbl_cliente.razon_social', 'tbl_cliente.ruc');
      $this->datatables->column_order  = array('tbl_pv_orders.pv_order_id', 'tbl_cliente.razon_social', 'tbl_cliente.ruc');
      $this->datatables->order         = array('tbl_pv_orders.pv_order_id'                               => 'desc');
      if (!empty($type)) {
        $where = array('tbl_pv_orders.status' => $type);
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);
      $data = array();
      foreach ($fetch_data as $_key => $pv_order) {
        $action = null;
        $sub_array = array();

        $sub_array[] = $_key + 1;
        $popover = ($pv_order->comment) ? 'style="cursor:pointer" data-toggle="popover" title="Descripción" data-content="' . $pv_order->comment . '"' : '';
        $sub_array[] = '<span class="text-info" ' . $popover . '>' . $pv_order->ruc . ' ' . $pv_order->razon_social . '</span>';
        $sub_array[] = '<span class="text-info" ' . $popover . '>' . $pv_order->direccion . '</span>';

        $btn_edit = '<span data-placement="top" data-toggle="tooltip" title="EDITAR ORDEN"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/pv_order/add_pv_order/' . $pv_order->pv_order_id . '"><span class="fa fa-pencil"></span></a></span>';

        $btn_activities = '<span data-placement="top" data-toggle="tooltip" title="GENERAR ORDENES DE ACTIVIDADES"><a  class="btn btn-info btn-xs"  href="' . base_url() . 'admin/pv_order/activities/' . $pv_order->pv_order_id . '"><span class="fa fa-arrow-right"></span></a></span>';

        if (in_array($this->session->userdata('designations_id'), [1])) {
          $action .= $btn_edit;
        }
        // ID 5 ES DE PLAN VERDE ADMINISTRATIVO
        if (in_array($this->session->userdata('designations_id'), [5])) {
          $action .= $btn_activities;
        }

        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }
  public function activities($id_order)
  {
    $this->db->select('*');
    $this->db->from('tbl_pv_orders');
    $this->db->join('tbl_cliente', 'tbl_pv_orders.cliente_id = tbl_cliente.cliente_id');
    $this->db->join('tbl_sedes', 'tbl_pv_orders.sede_id = tbl_sedes.sede_id');

    $order = $this->db->where(['tbl_pv_orders.pv_order_id' => $id_order])->get()->row();
    $data['order_id'] = $order->pv_order_id;
    $data['title'] = 'Ordenes de Trabajo ( ACTIVIDADES ) - ' . $order->ruc . ' ' . $order->razon_social . ' - ' . $order->direccion;
    $data['page'] = 'Ordenes de Trabajo ( ACTIVIDADES ) - ' . $order->ruc . ' ' . $order->razon_social . ' - ' . $order->direccion;

    // si es Plan verde para generar la actividades
    $data['btn_add'] = (in_array($this->session->userdata('designations_id'), [5])) ? TRUE : FALSE;

    $data['subview'] = $this->load->view('admin/pv_order/activities', $data, TRUE); //TRUE ES CUANDO SE DEVUELVEN COMO DATOS LA VIEW 
    $this->load->view('admin/_layout_main', $data);
  }
  public function add_pv_activitie_order($order_id, $order_activitie_id = NULL)
  {
    $this->db->select('*');
    $this->db->from('tbl_pv_orders');
    $this->db->join('tbl_cliente', 'tbl_pv_orders.cliente_id = tbl_cliente.cliente_id');
    $this->db->join('tbl_sedes', 'tbl_pv_orders.sede_id = tbl_sedes.sede_id');

    $order = $this->db->where(['tbl_pv_orders.pv_order_id' => $order_id])->get()->row();
    $data['title'] = 'Orden de Trabajo de Actividad : ' . $order->ruc . ' - ' . $order->razon_social;
    $data['pv_order_info'] = $order;
    /*  print_r($order);
    die(); */

    $activitie_order = ($order_activitie_id) ? $this->db->where(['pv_order_activitie_id' => $order_activitie_id])->get('tbl_pv_order_activities')->row() : '';

    $this->db->select('pv_activitie_id');
    $ids_activities = $this->db->where(['pv_order_id' => $order_id])->get('tbl_pv_order_activities')->result();

    foreach ($ids_activities as $key => $id) {
      if (!empty($order_activitie_id)) {
        if ($activitie_order->pv_activitie_id != $id->pv_activitie_id) {
          $ids[] = $id->pv_activitie_id;
        }
      } else {
        $ids[] = $id->pv_activitie_id;
      }
    }

    $data['pv_activitie_order_info'] = $activitie_order;
    $data['pv_activities'] = $this->db->where_not_in('pv_activitie_id', $ids)->get('tbl_pv_activities')->result();

    $data['operativos'] = $this->db->where(['departments_id' => 2])->get('tbl_designations')->result();

    $data['all_clients'] = $this->db->get('tbl_cliente')->result();
    $data['sedes'] = ($cliente_id = $data['pv_order_info']->cliente_id) ? $this->db->where(['cliente_id' => $cliente_id])->get('tbl_sedes')->result() : '';
    $data['subview'] = $this->load->view('admin/pv_order/add_pv_activitie_order', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function pvActivitiesOrderList($order_id, $type = NULL)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table         = 'tbl_pv_order_activities';
      $this->datatables->join_table    = array('tbl_pv_orders', 'tbl_cliente', 'tbl_sedes', 'tbl_pv_activities', 'tbl_designations');
      $this->datatables->join_where    = array('tbl_pv_order_activities.pv_order_id = tbl_pv_orders.pv_order_id', 'tbl_cliente.cliente_id = tbl_pv_orders.cliente_id', 'tbl_sedes.sede_id = tbl_pv_orders.sede_id', 'tbl_pv_order_activities.pv_activitie_id = tbl_pv_activities.pv_activitie_id', 'tbl_pv_order_activities.area_asignada = tbl_designations.designations_id');

      $this->datatables->column_search = array('tbl_pv_orders.pv_order_id', 'tbl_cliente.razon_social', 'tbl_cliente.ruc');
      $this->datatables->column_order  = array('tbl_pv_orders.pv_order_id', 'tbl_cliente.razon_social', 'tbl_cliente.ruc');
      $this->datatables->order         = array('tbl_pv_order_activities.pv_order_activitie_id' => 'desc');
      if (!empty($type)) {
        $where = array('tbl_pv_order_activities.status' => $type);
      } else {
        $where = array('tbl_pv_order_activities.pv_order_id' => $order_id);
        // $where = null;
      }

      $fetch_data = make_datatables($where);
      /* echo "<pre>";
      print_r($fetch_data);
      echo "</pre>";
      die(); */
      $data = array();
      foreach ($fetch_data as $_key => $pv_order) {
        $action = null;
        $sub_array = array();

        $sub_array[] = $_key + 1;
        $sub_array[] = '<span class="text-info" >' . $pv_order->activitie . '</span>';

        $sub_array[] = $pv_order->start_date;
        $sub_array[] = $pv_order->end_date;
        $status_actividad = $this->db->where(['pv_order_activitie_id' => $pv_order->pv_order_activitie_id])->get('tbl_pv_order_activities')->row()->status;
        $sub_array[] = $pv_order->designations;
        $sub_array[] = $this->status($status_actividad);

        $btn_edit = '<span data-placement="top" data-toggle="tooltip" title="Editar OT Actvidad"><a data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/pv_order/add_pv_activitie_order/' . $pv_order->pv_order_id . '/' . $pv_order->pv_order_activitie_id . '"><span class="fa fa-edit"></span></a></span>';

        $brn_download =  '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR CONSTANCIA"><a  target="_blank"  class="btn btn-green btn-xs"  href="' . base_url() . 'uploads/plan_verde/constancia/' . $pv_order->ruta_contancia . '"><span class="fa fa-download"></span></a></span>';
        // ID 5 ES DE PLAN VERDE ADMINISTRATIVO
        if (in_array($this->session->userdata('designations_id'), [5])) {
          $action .= ($status_actividad == 1) ? $btn_edit : '';
          $action .= ($status_actividad == 3) ? $brn_download : '';
        }

        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }
  public function save_pv_activitie_order($id = NULL)
  {

    $data = [
      'pv_activitie_id' => strtoupper($this->input->post('pv_activitie_id')),
      'pv_order_id' => $this->input->post('pv_order_id'),
      'comment' => $this->input->post('comment'),
      'start_date' => $this->input->post('start_date'),
      'end_date' => $this->input->post('end_date'),
      'area_asignada' => $this->input->post('area_asignada'),

      'user_id' => $this->session->userdata('user_id'),
      'designation_id' => $this->session->userdata('designations_id')
    ];
    // print_r($data);
    // die();
    /*
    if ($id == NULL && $this->pv_order_model->get_by($data)) {
      $type = 'error';
      $message = 'Orden ya existe.';
    } else {*/
    $this->pv_order_model->_table_name  = 'tbl_pv_order_activities';
    $this->pv_order_model->_order_by    = 'pv_order_activitie_id';
    $this->pv_order_model->_primary_key = 'pv_order_activitie_id';
    $return_id = $this->pv_order_model->save($data, $id);
    // die();
    if ($return_id) {
      $type = 'success';
      $message = 'Registro Exitoso.';
    } else {
      $type = 'error';
      $message = 'Registro Fallido.';
    }
    // }

    set_message($type, $message);
    redirect('admin/pv_order/activities/' . $this->input->post('pv_order_id'));
  }

  public function activities_calendar($cliente_id = NULL, $area = NULL)
  {
    $data['title'] = 'Calendario de Actividades a Realizar';
    $data['page'] = 'Calendario de Actividades a Realizar';

    $designation_id = $this->session->userdata('designations_id');
    $department_id = $this->db->where(['designations_id' => $designation_id])->get('tbl_designations')->row()->departments_id;

    $where = array();
    if ($department_id == 2) :
      $where['area_asignada'] = $designation_id;
    else : ($cliente_id = $this->input->post('cliente_id')) ? $where['tbl_cliente.cliente_id'] = $cliente_id : '';
      ($area = $this->input->post('designation_id')) ? $where['area_asignada'] = $area : '';

      $this->db->from('tbl_cliente cli');
      $this->db->join('tbl_pv_orders ord', 'cli.cliente_id = ord.cliente_id', 'left');
      $this->db->group_by('cli.cliente_id');
      $data['clientes'] = $this->db->get()->result();

      $this->db->from('tbl_designations d');
      $this->db->join('tbl_pv_order_activities oa', 'd.designations_id = oa.area_asignada');
      $this->db->group_by('d.designations_id');
      $data['designations'] = $this->db->get()->result();

      $data['cliente_id'] = $cliente_id;
      $data['designation_id'] = $area;
    endif;


    $this->db->select('*, tbl_pv_order_activities.status as status_activitie');
    $this->db->from('tbl_pv_order_activities');
    $this->db->join('tbl_pv_orders', 'tbl_pv_order_activities.pv_order_id = tbl_pv_orders.pv_order_id');
    $this->db->join('tbl_cliente', 'tbl_pv_orders.cliente_id = tbl_cliente.cliente_id');
    $this->db->join('tbl_sedes', 'tbl_pv_orders.sede_id = tbl_sedes.sede_id');
    $this->db->join('tbl_pv_activities', 'tbl_pv_order_activities.pv_activitie_id = tbl_pv_activities.pv_activitie_id');

    // if (in_array($area = $designation_id, [6, 7]) ) :


    $this->db->where($where);
    $activities = $this->db->where_in('tbl_pv_order_activities.status', [1, 2])->get()->result();
    /* echo "<pre>";
    print_r( $activities );
    echo "</pre>";
    die(); */
    $data['activities'] = $activities;
    $data['subview'] = $this->load->view('admin/pv_order/activities_calendar', $data, TRUE); //TRUE ES CUANDO SE DEVUELVEN COMO DATOS LA VIEW 
    $this->load->view('admin/_layout_main', $data);
  }

  /* AQMIGUEL */
  public function update_order_activitie($order_activitie_id)
  {
    $this->db->select('*, tbl_pv_order_activities.status as "status_activitie", tbl_pv_order_activities.comment as "comment_activitie"');
    $this->db->from('tbl_pv_order_activities');
    $this->db->join('tbl_pv_orders', 'tbl_pv_order_activities.pv_order_id = tbl_pv_orders.pv_order_id');
    $this->db->join('tbl_cliente', 'tbl_pv_orders.cliente_id = tbl_cliente.cliente_id');
    $this->db->join('tbl_sedes', 'tbl_pv_orders.sede_id = tbl_sedes.sede_id');
    $this->db->join('tbl_pv_activities', 'tbl_pv_order_activities.pv_activitie_id = tbl_pv_activities.pv_activitie_id');
    $this->db->join('tbl_designations', 'tbl_pv_order_activities.area_asignada = tbl_designations.designations_id');

    $order = $this->db->where(['tbl_pv_order_activities.pv_order_activitie_id' => $order_activitie_id])->get()->row();
    $data['title'] = 'Orden de Trabajo de Actividad : ' . $order->ruc . ' - ' . $order->razon_social . ' - ' . $order->direccion;
    $data['pv_order_info'] = $order;
    /*  print_r($order);
    die(); */

    /* $activitie_order = ( $order_activitie_id ) ? $this->db->where( ['pv_order_activitie_id' => $order_activitie_id ] )->get( 'tbl_pv_order_activities' )->row() : '';

    $this->db->select('pv_activitie_id');
    $ids_activities = $this->db->where( ['pv_order_id' => $order_id ] )->get( 'tbl_pv_order_activities' )->result();

    foreach ($ids_activities as $key => $id) {
      if( !empty($order_activitie_id ) ){
        if( $activitie_order->pv_activitie_id != $id->pv_activitie_id ){
          $ids []= $id->pv_activitie_id;
        }
      }else{
        $ids []= $id->pv_activitie_id;
      }
    }

    $data['pv_activitie_order_info'] = $activitie_order;
    $data['pv_activities'] = $this->db->where_not_in('pv_activitie_id', $ids )->get('tbl_pv_activities')->result(); */

    $data['operativos'] = $this->db->where(['departments_id' => 2])->get('tbl_designations')->result();

    /* $data['all_clients'] = $this->db->get('tbl_cliente')->result();
    $data['sedes'] = ($cliente_id = $data['pv_order_info']->cliente_id) ? $this->db->where(['cliente_id' => $cliente_id])->get('tbl_sedes')->result() : ''; */
    $this->load->view('admin/pv_order/update_dates', $data, FALSE);
    // exit();
    // $this->load->view('admin/_layout_modal', $data);
  }
  public function update_pv_activitie_order_dates($id)
  {

    $data = [
      'start_date' => $this->input->post('start_date'),
      'end_date' => $this->input->post('end_date'),
    ];
    $this->pv_order_model->_table_name  = 'tbl_pv_order_activities';
    $this->pv_order_model->_order_by    = 'pv_order_activitie_id';
    $this->pv_order_model->_primary_key = 'pv_order_activitie_id';
    $return_id = $this->pv_order_model->save($data, $id);
    // die();
    if ($return_id) {
      $type = 'success';
      $message = 'Registro Exitoso.';
    } else {
      $type = 'error';
      $message = 'Registro Fallido.';
    }
    // }

    set_message($type, $message);
    redirect('admin/pv_order/activities_calendar/');
  }

  public function send_activities()
  {
    $order_activities = $this->db->where(['status' => 1])->get('tbl_pv_order_activities')->result();

    foreach ($order_activities as $key => $order) {
      $data = [
        'status' => 2
      ];
      $this->pv_order_model->_table_name  = 'tbl_pv_order_activities';
      $this->pv_order_model->_order_by    = 'pv_order_activitie_id';
      $this->pv_order_model->_primary_key = 'pv_order_activitie_id';
      $return_id = $this->pv_order_model->save($data, $order->pv_order_activitie_id);
      $message = 'Nueva OT-A-' . $order->pv_order_activitie_id . ', Realizar Actividad.';
      // CAMBIAMOS A LA RUTAQ JHAROLA PUESTO EN LA LISTADO DE LAS ACTIVIDADES 
      $this->notification($order->area_asignada, 'admin/pv_actividades_realizar', $message);
    }
    // die();
    if ($return_id) {
      $type = 'success';
      $message = 'Registro Exitoso.';
    } else {
      $type = 'error';
      $message = 'Registro Fallido.';
    }
    // }

    set_message($type, $message);
    redirect('admin/pv_order/');
  }

  private function notification($designations_id, $link, $message)
  {
    // $designations_id = 2; // ES ID DE GERENCIA
    $this->db->select('*');
    $this->db->from('tbl_account_details d');
    $this->db->join('tbl_users u', 'd.user_id = u.user_id');
    $to_user = $this->db->where(['designations_id' => $designations_id, 'activated' => 1])->get()->row();

    $data_notif['to_user_id'] = $to_user->user_id;
    $data_notif['from_user_id'] = $this->session->userdata('user_id');
    $data_notif['name'] = $this->session->userdata('user_name');
    $data_notif['link'] = $link;
    $data_notif['description'] = $message;
    $data_notif['value'] = $this->input->post('nombre');
    add_notification($data_notif);
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

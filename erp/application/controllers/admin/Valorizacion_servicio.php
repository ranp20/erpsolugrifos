<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
/**
 * @author aQMiGuEL <email@email.com>
 */
class Valorizacion_Servicio extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('admin_model');
    $this->load->model('valorizacion_model');
    $this->load->helper('admin_helper');
  }
  public function index()
  {
    $data['title'] = 'Valorización de Servicio';
    $data['page'] = 'Valorización de Servicio';
    $data['btn_add'] = ($this->db->where( ['designations_id' => $this->session->userdata('designations_id') ])->get('tbl_designations' )->row()->departments_id == 2 ) ? true : false;

    // $data['dt_buttons'] = (in_array($this->session->userdata('designations_id'), [1,2,3])) ? TRUE : FALSE;
    $data['dt_buttons'] = TRUE;
    $data['subview'] = $this->load->view('admin/valorizacion_servicio/index', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function add_valorizacion()
  {
    $data['title'] = ('Nueva Valorización de Servicio');
    $data['all_clients'] = $this->db->get('tbl_cliente')->result_array();

    $data['services'] = $this->db->get('tbl_services')->result();

    $data['subview'] = $this->load->view('admin/valorizacion_servicio/add_valorizacion', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function save_valorizacion($id = null)
  {

    $dir =  "./uploads/valorizacion_servicio/";
    if (!is_dir($dir)) {
      mkdir($dir, 0777);
    }
    $ruta = "";
    // if ($this->input->post('files')) {
    if ($this->guardar_archivo($dir)) {
      $data_upload = $this->upload->data();
      $ruta = $data_upload['file_name'];
    }

    // guardamos en db
    // AREA ACTUAL => 2 GERENCIA
    $data = [
      'service_id'    => $this->input->post('service_id'),
      'cliente_id'  => $this->input->post('cliente_id'),
      'sede_id'     => $this->input->post('sede_id'),
      'fecha'       => $this->input->post('fecha'),
      'monto'       => $this->input->post('monto'),
      'user_id'     => $_SESSION['user_id'],
      'ruta'        => $ruta,
      'area_inicio' => $this->session->userdata('designations_id'),
      'area_actual' => 2,
      'proceso'     =>  'Por aprobar'
    ];

    $this->db->insert('tbl_valorizacion_servicio', $data);
    $id_valorizacion = $this->db->insert_id();
    if ($id_valorizacion) {

      $data_detail = [
        'user_id'           => $this->session->userdata('user_id'),
        'detail'            => 'EMITIO VALORIZACION DE SERVICIO',
        'valorizacion_servicio_id' => $id_valorizacion,
        'proceso'           => 'emision',
        'proceso_id'        => $id_valorizacion,
        'status' => 1,
        'designations_id' => $this->session->userdata('designations_id')
      ];


      /* DETALLE */
      $this->valorizacion_model->_table_name = 'tbl_valorizacion_servicio_detail'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_servicio_detail_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_servicio_detail_id';
      $id = $this->valorizacion_model->save($data_detail, NULL);


      $designations_id = 2; // ES ID DE GERENCIA
      $designations_id_1 = 3; // ES ID DE ADMINISTRACIÓN
      $designations_id_2 = 1; // ES ID DE USUARIO COMERCIAL (MIGUEL)
      $link = 'admin/valorizacion_servicio/';
      $message = '<span class"n-cont__custom">
                    <span>Nueva <span style="color:#FF00FF;">Valorizacion de Servicio</span> ingresada,</span>
                    <span>Necesita ser Aprobada</span>
                </span>';
      $this->notification($designations_id, $link, $message); // NOTIFICACIÓN (VISITA TÉCNICA) PARA "GERENCIA"
      $this->notification($designations_id_1, $link, $message); // NOTIFICACIÓN (VISITA TÉCNICA) PARA "ADMINISTRACIÓN"
      $this->notification($designations_id_2, $link, $message); // NOTIFICACIÓN (VISITA TÉCNICA) PARA "COMERCIAL"
    }
    $type = "success";
    $message = 'Registro Exitoso';
    set_message($type, $message);
    redirect('admin/valorizacion_servicio/');
  }

  public function valorizacion_servicio_list( $type = NULL)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->select = 'cli.razon_social, se.sede, ser.service, ser.descripcion, vs.*';
      $this->datatables->table = 'tbl_valorizacion_servicio vs';
      $this->datatables->join_table = array('tbl_cliente cli', 'tbl_sedes se', 'tbl_services ser');
      $this->datatables->join_where = array('vs.cliente_id = cli.cliente_id', 'vs.sede_id = se.sede_id', 'ser.service_id = vs.service_id');
      $this->datatables->column_search = array('ser.service', 'vs.status', 'cli.razon_social', 'cli.ruc');
      $this->datatables->column_order = array('ser.service', 'vs.status', 'cli.razon_social', 'cli.ruc');
      $this->datatables->order = array('vs.valorizacion_servicio_id' => 'desc');
      $where = [];
      if( $this->db->where(['designations_id' => $this->session->userdata('designations_id') ])->get('tbl_designations')->row()->departments_id == 2 ):
        
        $where = ['vs.area_inicio'=> $this->session->userdata('designations_id')] ;
      endif;

      if (!empty($type)) {
        // $where = array('vs.status' => $type);
        $where['vs.status'] = $type;
      } else {
        // $where = null;
      }
      /* echo "<pre>";
      print_r($where);
      echo "</pre>";
      exit(); */

      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $valorizacion) {
        $action = null;


        $sub_array = array();



        // $sub_array[] = ($num = $valorizacion->numero) ? $num : '--';
        $sub_array[] = $valorizacion->valorizacion_servicio_id;
        
        $sub_array[] = $valorizacion->service ;

        
        $sub_array[] = $valorizacion->razon_social;

        
        $sub_array[] = $valorizacion->sede;

        $sub_array[] = $valorizacion->monto;
        $sub_array[] = $valorizacion->fecha;

        $data_area = $this->db->where(['designations_id' => $valorizacion->area_actual])->get('tbl_designations')->row();
        $sub_array[] = $data_area->designations;
        $sub_array[] = $this->status($valorizacion->status);
        $sub_array[] = $valorizacion->proceso;

        $valorizacion_documento = (isset($valorizacion->ruta) && !empty($valorizacion->ruta)) ? '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE VALORIZACION DE SERVICIO" >
        <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'uploads/valorizacion_servicio/' . $valorizacion->ruta . '"><span class="fa fa-download"></span></a>
        </span>' . ' ' : '';

        $form_aprobar_valorizacion = '<span data-placement="top" data-toggle="tooltip" title="APROBACION VALORACION SERVICIO">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/valorizacion_servicio/forms/aprobacion/' . $valorizacion->valorizacion_servicio_id . '"><span class="fa fa-check"></span></a>
        </span>' . ' ';
        

        $detail = '<span data-placement="top" data-toggle="tooltip" title="RESUMEN SERVICIO"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-purple btn-xs"  href="' . base_url() . 'admin/valorizacion_servicio/detail_list/' . $valorizacion->valorizacion_servicio_id . '"><span class="fa fa-list-alt"></span></a></span>' . ' ';

        $data_department = $this->db->where(['designations_id' => $this->session->userdata('designations_id')])->get('tbl_designations')->row();
        
        // SI PERTENCEN A OPERATIVOS
        if ($data_department->departments_id == 2) {
          $action .= $detail;
        }
        if ($this->session->userdata('designations_id') == $valorizacion->area_inicio) {
          $action .= $valorizacion_documento;
        }
        
        // GERENCIA
        if ($this->session->userdata('designations_id') == 2) {
          $action .= $valorizacion_documento;
          $action .= ($valorizacion->status == 1) ? $form_aprobar_valorizacion : $detail;
        }
        // ADMINISTRACION -> VIZUALIZA Y DESCARGA EL DOCUMENTO
        if ($this->session->userdata('designations_id') == 3 ) {

          $action .= $detail;
          $action .= ( in_array( $valorizacion->status, [11,12]) ) ? $valorizacion_documento : '';
        }
        // COMERCIAL -> DETALLE Y FORMULARIO PARA EMITIR ORDEN 
        if ($this->session->userdata('designations_id') == 1 ) {

          $action .= $detail;
          $action .= ( in_array( $valorizacion->status, [11,12,2]) ) ? $valorizacion_documento : '';
          
        }


        $sub_array[] = $action;
        $data[] = $sub_array;
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
        $text = "CANCELADO";
        break;

      case '1':
        $type = "warning";
        $text = "INGRESADO";
        break;
        // SI GERENCIA NO APRUEBA LA VALORIZACION DE SERVICIO 
      case '10':
        $type = "danger";
        $text = 'NO APROBADO <i class="fa fa-ban"></i>';
        break;
        // SI ES APROBADA 
      case '11':
        $type = "info";
        $text = 'EN PROCESO - APROBADO <i class="fa fa-check-circle-o sm"></i>';
        break;

        // SUBIO COTIZACION
      case '2':
        $type = "warning";
        $text = 'EN PROCESO - COTIZACION';
        break;
        
        // GERENCIA NO APROBO COTIZACION
      // cuando no se aprueba en el lstado de cotizacion mostrar un boton de generacion de cotizacion bajo el id_valorizacion_servicio
      case '20':
        $type = "danger";
        $text = 'EN PROCESO - NO APROBO COTIZACION';
        break;

      // GERENCIA APROBO COTIZACION
      case '21':
        $type = "primary";
        $text = 'EN PROCESO - C/COTIZACION';
        break;

      // CON ORDEN DE COMPRA 
      // al registrar el 22 orden de compra validadmos si tiene adelanto pasa al 23 si no tiene adelanto pasa al 24
      case '22':
        $type = "primary";
        $text = 'EN PROCESO - C/OC';
        break;

      //SI HAY ADELANTO SUBIRA EL COMPROBANTE DE ADELANTO O GERENCIA APROBARA EL PASE PARA OT 
      // SI SUBIO COMPROBANTE DE ADELANTO SINO PASA A 24 => EMISION DE ORDEN DE TRABAJO 
      case '23':
        $type = "primary";
        $text = 'EN PROCESO - C/COMPROBANTE ADELANTO';
        break;
      
        
      // COMERCIAL SUBIENDO OT
      case '3':
        $type = "primary";
        $text = 'EN PROCESO - OT';
        break;
      
        // GERENCIA NO APROBANDO OT
      case '30':
        $type = "danger";
        $text = 'EN PROCESO - NO APROBO OT';
        break;

        // GERENCIA APROBANDO OT
      case '31':
        $type = "primary";
        $text = 'EN PROCESO - OT APROBADA';
        break;

      
        // AREA USUARIA SUBE SU CONFORMIDAD 
      case '32':
        $type = "primary";
        $text = 'EN PROCESO - C/CONFORMIDAD SERVICIO';
        break;

      // SI YA SE SUBIERON TODOS LOS COMPROBANTES DE PAGO 
      case '33':
        $type = "primary";
        $text = 'EN PROCESO - C/COMPROBANTES';
        break;
      
        // SUBE EL COMPROBANT DE PAGO FINAL Y CIERRA LA ORDEN DE TRABAJO EL AREA COMERCIAL 
      case '100':
        $type = "success";
        $text = "CERRADO - COMPLETO";
        break;

      default:
        $type = "danger";
        $text = "CANCELADO";
        break;
    }
    return '<h5><span class=" label label-xs label-' . $type . '">' . $text . '</span></h5>';
  }

  public function detail_list($id = NULL)
  {
    $data['title'] = ('Detalle del Documento - Seguimiento');
    $data['page']  = 'Detalle del Documento - Seguimiento';
    $data['id']    = $id;

    $data['detail'] = $this->db->where(['valorizacion_servicio_id' => $id])->get('tbl_valorizacion_servicio_detail')->result();

    $data['subview'] = $this->load->view('admin/valorizacion_servicio/detail', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function forms($form = NULL, $id)
  {
    if (isset($form) && $form != NULL) {
      $data['title'] = 'valorizacion Servicio';
      $data['page']  = 'valorizacion Servicio';
      $data['form']  = $form;
      $servicio        = $this->db->where(['valorizacion_servicio_id' => $id])->get('tbl_valorizacion_servicio')->row();
      $data['area_inicio'] = ($form == "orden_visita") ? $servicio->area_inicio : '';
      $data['cliente'] = ($form == "orden_visita" || $form == "constancia_visita") ? $this->db->where(['cliente_id' => $servicio->cliente_id ])->get('tbl_cliente')->row()->razon_social : '';
      $data['sede'] = ($form == "orden_visita" || $form == "constancia_visita") ? $this->db->where(['sede_id' => $servicio->sede_id] )->get('tbl_sedes')->row()->sede  : '';

      $data['all_operativas'] = $this->db->where(['departments_id' => 2])->get('tbl_designations')->result_object();
      $data['id'] = $id;

      $data['subview'] = $this->load->view('admin/valorizacion_servicio/form_' . $form, $data, FALSE);
      $this->load->view('admin/_layout_modal_extra_lg', $data);
    }
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

  private function guardar_archivo($dir)
  {
    $mi_archivo              = 'files';
    $config['upload_path']   = $dir . "/";
    // $config['file_name']  = "nombre_archivo";
    $config['allowed_types'] = "*";
    $config['max_size']      = "5000000000";
    $config['max_width']     = "2000000000";
    $config['max_height']    = "2000000000";
    $this->load->library('upload', $config);

    return $this->upload->do_upload($mi_archivo);
    /* if (!$this->upload->do_upload($mi_archivo)) {
      //*** ocurrio un error
      /* $data['uploadError'] = $this->upload->display_errors();
      echo $this->upload->display_errors();
      die(); 
      return false;
    }

    return ($dataUpload = $this->upload->data()); */
  }

  public function save_forms($form, $id)
  {
    if ($form == 'aprobacion') {

      if ($this->input->post('aprobar')) {

        $data_detail = [
          'user_id'           => $this->session->userdata('user_id'),
          'detail'            => 'SE APROBÓ VALORIZACIÓN DE SERVICIO',
          'valorizacion_servicio_id' => $id,
          'proceso'           => 'Aprobación',
          'proceso_id'        => $id,
          'status'            => 1,
          'designations_id'   => $this->session->userdata('designations_id'),
          'comentario'        => $this->input->post('observaciones')
        ];

        $data_valorizacion = [
          'status'      => 11,
          'area_actual' => 1,
          'proceso'     => 'Subir cotizacion'
        ];
      } else {
        $data_detail = [
          'user_id'           => $this->session->userdata('user_id'),
          'detail'            => 'NO APROBÓ VALORIZACIÓN DE SERVICIO',
          'valorizacion_servicio_id' => $id,
          'proceso'           => 'Aprobación',
          'proceso_id'        => $id,
          'status'            => 0,
          'designations_id'   => $this->session->userdata('designations_id'),
          'comentario'        => $this->input->post('observaciones')
        ];

        $data_valorizacion = [
          'status' => 10,
          'proceso' => '--'
        ];

        
      }
      // ADD DETALLE DE VIISTA
      $this->valorizacion_model->_table_name = 'tbl_valorizacion_servicio_detail'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_servicio_detail_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_servicio_detail_id';
      $id_detalle = $this->valorizacion_model->save($data_detail, NULL);
      
      
      // ACTUALIZAR LA VISITA TECNICA
      $this->valorizacion_model->_table_name = 'tbl_valorizacion_servicio'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_servicio_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_servicio_id';
      $this->valorizacion_model->save($data_valorizacion, $id);


      /* ADD NOTIFICATION */
      if ($this->input->post('aprobar')) {
      $designations_id = 1; // ES ID DE COMERCIAL
      $message = 'Valorizacion de Servicio aprobada, Subir Cotización.';
      $link = 'admin/cotizacion/'; // CAMBIAR PARQ MUESTRE EN LA LISTA DE COTIZACIONES 
      $this->notification($designations_id, $link, $message);
      }
      /**===================================
       * ADD notificacion para el area administracion pueda descaragr el documento 
       * 
       ==================================*/
      $designations_id = 3; // ES ID DE ADMINISTRACION
      $message = 'Valorizacion visita técnica Aprobada';
      $link = 'admin/valorizacion_servicio/';
      $this->notification($designations_id, $link, $message);

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/valorizacion_servicio');
    }
  }

  public function orden_detail( $valorizacion_servicio_id ){
    $data['title'] = 'DETALLE DE ORDEN DE VISITA TÉCNICA';
    $data['orden_detail'] = $this->db->where( ['valorizacion_servicio_id' => $valorizacion_servicio_id ] )->get('tbl_valorizacion_servicio_orden')->row();
    $data_valorizacion = $this->db->where( ['valorizacion_servicio_id' => $valorizacion_servicio_id ] )->get('tbl_valorizacion_servicio')->row();
    $data['valorizacion_servicio_info'] = $data_valorizacion;
    $data['cliente'] = $this->db->where( ['cliente_id' => $data_valorizacion->cliente_id ] )->get('tbl_cliente')->row()->razon_social;
    $data['sede'] = $this->db->where( ['sede_id' => $data_valorizacion->sede_id ] )->get('tbl_sedes')->row()->sede;
    $data['subview'] = $this->load->view('admin/valorizacion_servicio/detail_orden', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function constancia_detail( $valorizacion_servicio_id ){
    $data['title'] = 'DETALLE DE CONSTANCIA DE VISITA TÉCNICA';
    $data['constancia_detail'] = $this->db->where( ['valorizacion_servicio_id' => $valorizacion_servicio_id ] )->get('tbl_valorizacion_servicio_constancia')->row();
    $data_valorizacion = $this->db->where( ['valorizacion_servicio_id' => $valorizacion_servicio_id ] )->get('tbl_valorizacion_servicio')->row();
    $data['valorizacion_servicio_info'] = $data_valorizacion;
    $data['cliente'] = $this->db->where( ['cliente_id' => $data_valorizacion->cliente_id ] )->get('tbl_cliente')->row()->razon_social;
    $data['sede'] = $this->db->where( ['sede_id' => $data_valorizacion->sede_id ] )->get('tbl_sedes')->row()->sede;
    $data['subview'] = $this->load->view('admin/valorizacion_servicio/detail_constancia', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

/**
 * LISTADO DE LAS VALORIZACIONES LISTAS PARA EMISION DE COTIZACION 
 */
  public function valorizacion_servicio_cotizacion_list( $type = 11)
  {

    if ($this->input->is_ajax_request()) {

      $this->load->model('datatables');
      $this->datatables->table = 'cli.razon_social, se.sede, ser.service, ser.descripcion, vs.*';
      $this->datatables->table = 'tbl_valorizacion_servicio vs';
      $this->datatables->join_table = array('tbl_cliente cli', 'tbl_sedes se', 'tbl_services ser');
      $this->datatables->join_where = array('vs.cliente_id = cli.cliente_id', 'vs.sede_id = se.sede_id', 'ser.service_id = vs.service_id');
      $this->datatables->column_search = array('ser.service', 'vs.status', 'cli.razon_social', 'cli.ruc');
      $this->datatables->column_order = array('ser.service', 'vs.status', 'cli.razon_social', 'cli.ruc');
      $this->datatables->order = array('vs.valorizacion_servicio_id' => 'desc');
      if (!empty($type)) {
        $where = array('vs.status' => $type);
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $valorizacion) {
        $action = null;


        $sub_array = array();



        // $sub_array[] = ($num = $valorizacion->numero) ? $num : '--';
        $sub_array[] = $_key +1;
        $sub_array[] = $valorizacion->service;

        
        $sub_array[] = $valorizacion->razon_social;

        
        $sub_array[] = $valorizacion->sede;

        $sub_array[] = $valorizacion->monto;
        $sub_array[] = $valorizacion->fecha;

        $data_area = $this->db->where(['designations_id' => $valorizacion->area_actual])->get('tbl_designations')->row();
        $sub_array[] = $data_area->designations;
        $sub_array[] = $this->status($valorizacion->status);
        $sub_array[] = $valorizacion->proceso;

        $valorizacion_documento = (isset($valorizacion->ruta) && !empty($valorizacion->ruta)) ? '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE VALORIZACION DE SERVICIO" >
        <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'uploads/valorizacion_servicio/' . $valorizacion->ruta . '"><span class="fa fa-download"></span></a>
        </span>' . ' ' : '';

        $form_cotizacion = '<span data-placement="top" data-toggle="tooltip" title="GENERAR COTIZACION">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/cotizacion/add_cotizacion_valorizacion/' . $valorizacion->valorizacion_servicio_id . '"><span class="fa fa-upload"></span></a>
        </span>' . ' ';
        
        $detail = '<span data-placement="top" data-toggle="tooltip" title="RESUMEN SERVICIO"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-purple btn-xs"  href="' . base_url() . 'admin/valorizacion_servicio/detail_list/' . $valorizacion->valorizacion_servicio_id . '"><span class="fa fa-list-alt"></span></a></span>' . ' ';

        $data_department = $this->db->where(['designations_id' => $this->session->userdata('designations_id')])->get('tbl_designations')->row();
        
        $action .= $valorizacion_documento. $form_cotizacion;


        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }
  /* __________________________
 /_________________________*/
}
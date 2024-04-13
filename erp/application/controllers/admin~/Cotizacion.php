<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admistrator
 *
 * @author pc mart ltd
 */
class Cotizacion extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('admin_model');
    $this->load->model('cotizacion_model');
    $this->load->helper('admin_helper');
    $this->load->helper('dompdf_helper');
  }
  public function index()
  {
    $data['title'] = 'Cotizaciones';
    $data['page'] = 'Cotizaciones';
    $data['btn_add'] = true;

    $data['btn_add_cotizacion'] = ($this->session->userdata('designations_id') == 1) ? TRUE : FALSE;
    if ($this->session->userdata('designations_id') == '1') {
      $data['subview'] = $this->load->view('admin/cotizaciones/cotizaciones_list', $data, TRUE);
    } else {
      $data['subview'] = $this->load->view('admin/cotizaciones/index', $data, TRUE);
    }
    $this->load->view('admin/_layout_main', $data);
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


  public function add_cotizacion_valorizacion($id)
  {
    $data['title'] = ('Nueva Cotizacion con Valorizacion');
    $data['valorizacion'] = true; // para saber q ya tiene su valorizacion
    $data_valorizacion = $this->db->where(['valorizacion_servicio_id' => $id])->get('tbl_valorizacion_servicio')->row();
    $data['data_valorizacion'] = $data_valorizacion;



    $data['data_cliente'] = $this->db->where(['cliente_id' => $data_valorizacion->cliente_id])->get('tbl_cliente')->row();
    $data['data_sede'] = $this->db->where(['sede_id' => $data_valorizacion->sede_id])->get('tbl_sedes')->row();

    $data['services'] = $this->db->get('tbl_services')->result();
    $data['subview'] = $this->load->view('admin/cotizaciones/add_cotizacion', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function save_cotizacion($id = null)
  {

    $dir =  "./uploads/cotizaciones/";
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

    $data['adelanto'] = ($this->input->post('adelanto')) ? 1 : 0;

    $data['service_id']      = $this->input->post('service_id');
    $data['cliente_id']  = $this->input->post('cliente_id');
    $data['sede_id']     = $this->input->post('sede_id');
    $data['fecha']       = $this->input->post('fecha');
    $data['fecha_vigencia']       = $this->input->post('fecha-vig');

    $data['monto']       = $this->input->post('monto');
    $data['user_id']     = $_SESSION['user_id'];
    /*$data['ruta']        = $ruta;*/
    $data['status'] = '2';
    $data['area_inicio'] = $this->session->userdata('designations_id');
    $data['area_actual'] = 2;
    $data['accion']      = 'Por aprobar';

    /*
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
    */

    if ($valorizacion_id = $this->input->post('valorizacion_id')) {
      $data['valorizacion_id'] = $valorizacion_id;
    }
    $this->db->insert('cotizaciones', $data);
    $id_cotizacion = $this->db->insert_id();
    if ($id_cotizacion) {
      /*
      $data_detail['accion'] = 1; //INGRESO DE COTIZACION
      $data_detail['valor_accion'] = 'INGRESADO';
      $data_detail['status'] = 1;
      $data_detail['cotizacion_id'] = $id_cotizacion;
      $data_detail['user_id'] = $this->session->userdata('user_id');
      $data_detail['designation_id'] = $this->session->userdata('designations_id');
      $data_detail['comment'] = ($this->input->post('observaciones')) ? $this->input->post('observaciones') : '';
      $data_detail['document'] = $ruta;*/

      $data_detail = [
        'user_id'           => $this->session->userdata('user_id'),
        'detail'            => 'EMITIO COTIZACION',
        'cotizacion_id' => $id_cotizacion,
        'proceso'           => 'emision cotizacion',
        'proceso_id'        => $id_cotizacion,
        'status' => 1,
        'designations_id' => $this->session->userdata('designations_id')
      ];



      $id_detail = $this->add_detail($data_detail);

      /* REGISTRAMOS LOS PAGOS */

      if ($pago_adelanto = $this->input->post('pago_adelanto')) {

        $tipo_pago = 1; // adelanto
        $data_pago['tipo_pago'] = $tipo_pago;
        $data_pago['descripcion'] = 'adelanto';
        $data_pago['porcentaje'] = $pago_adelanto;
        $data_pago['status'] = 1; //ingresado o registrado
        $data_pago['cotizacion_id'] = $id_cotizacion;
        $this->cotizacion_model->_table_name  = 'tbl_cotizacion_pago'; //table name
        $this->cotizacion_model->_order_by    = 'cotizacion_pago_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_pago_id';
        $this->cotizacion_model->save($data_pago, NULL);
      }
      foreach ($_POST['pago'] as $key => $pago) {
        if ($pago > 0) {
          $tipo_pago = 2; // pago 
          $data_pago['tipo_pago'] = $tipo_pago;
          $data_pago['descripcion'] = 'Pago ' . ($key + 1);
          $data_pago['porcentaje'] = $pago;
          $data_pago['status'] = 1; //ingresado o registrado
          $data_pago['cotizacion_id'] = $id_cotizacion;
          $this->cotizacion_model->_table_name  = 'tbl_cotizacion_pago'; //table name
          $this->cotizacion_model->_order_by    = 'cotizacion_pago_id';
          $this->cotizacion_model->_primary_key = 'cotizacion_pago_id';
          $this->cotizacion_model->save($data_pago, NULL);
        }
      }
      if ($valorizacion_id = $this->input->post('valorizacion_id')) {
        $data_valorizacion_servicio = [
          'status' => 2,
          'proceso' => 'cotizacion',
          'area_actual' => 2
        ];
        $this->cotizacion_model->_table_name  = 'tbl_valorizacion_servicio'; //table name
        $this->cotizacion_model->_order_by    = 'valorizacion_servicio_id';
        $this->cotizacion_model->_primary_key = 'valorizacion_servicio_id';
        $this->cotizacion_model->save($data_valorizacion_servicio, $valorizacion_id);
      }
      $designations_id = 2; // ES ID DE GERENCIA
      $message = 'Nueva Cotizacion( ' . $id_cotizacion . ' ) Ingresada, Necesita ser Aprobada';
      $link = 'admin/cotizacion/';
      $this->notification($designations_id, $link, $message);
    }
    $type = "success";
    $message = 'Registro Exitoso';
    set_message($type, $message);
    redirect('admin/cotizacion/');
  }

  private function add_detail($data)
  {
    $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
    $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
    $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
    return $id_detail = $this->cotizacion_model->save($data, NULL);
  }



  /**
   * Undocumented function
   *
   * @return void
   */
  public function add_cotizacion()
  {
    $data['title'] = ('Nueva Cotizacion');
    $data['services'] = $this->db->get('tbl_services')->result();
    $data['all_clients'] = $this->db->get('tbl_cliente')->result_array();
    $data['subview'] = $this->load->view('admin/cotizaciones/add_cotizacion', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
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
  }

  public function CotizacionList($actionURL = NULL, $type = null)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_cotizaciones';

      $this->datatables->column_search = array('tbl_cotizaciones.nombre');
      $this->datatables->column_order = array(' ', 'tbl_cotizaciones.nombre');
      $this->datatables->order = array('cotizacion_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array('tbl_cotizaciones.status' => $type);
      } else {
        $where = null;
      }



      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        $action = null;


        $sub_array = array();


        //ojo
        $sub_array[] = $document->cotizacion_id;
        $service =  $this->db->get_where("tbl_services", ['service_id' => $document->service_id])->row();
        $sub_array[] = $service->service;

        $cliente =  $this->db->get_where("tbl_cliente", ['cliente_id' => $document->cliente_id])->row();
        $sub_array[] = $cliente->ruc . ' - ' . $cliente->razon_social;
        $sede =  $this->db->get_where("tbl_sedes", ['sede_id' => $document->sede_id])->row();
        $sub_array[] = $sede->sede;

        $this->db->select('deptname, designations');
        $this->db->from('tbl_designations ds');
        $this->db->join('tbl_departments dp', 'ds.departments_id = dp.departments_id');

        $data_area = $this->db->where(['ds.designations_id' => $document->area_actual])->get()->row();

        $sub_array[] = ($data_area->departments_id == 1) ? $document->monto : '';
        $sub_array[] = $document->fecha;
        $sub_array[] = $document->fecha_vigencia;


        $sub_array[] = $data_area->designations;

        // JALAMOS LA VALORIZACION NOMBRE SI EXISTE
        if (!empty($document->valorizacion_id)) {
          $service =  $this->db->get_where("tbl_services", ['service_id' => $document->service_id])->row();
          // $sub_array[] = $service->service ;
          //$data_valorizacion = $this->db->where(['valorizacion_servicio_id' => $document->valorizacion_id])->get('tbl_valorizacion_servicio')->row();
          $data_valorizacion = $this->db->get_where("tbl_services", ['service_id' => $document->service_id])->row();
          $sub_array[] = (count($data_valorizacion) > 0) ? $data_valorizacion->service : '';
        } else {
          $sub_array[] = '--';
        }
        $sub_array[] = $this->status($document->status);
        $sub_array[] = $document->accion;



        $cotizacion_documento = '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE COTIZACIÓN" >
        <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'admin/cotizacion/pdf/' . $document->cotizacion_id . '"><span class="fa fa-file-pdf-o"></span></a>
        </span>';

        $detail = '<span data-placement="top" data-toggle="tooltip" title="RESUMEN COTIZACIÓN"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-purple btn-xs"  href="' . base_url() . 'admin/cotizacion/detail_list/' . $document->cotizacion_id . '"><span class="fa fa-list-alt"></span></a></span>' . ' ';

        $form_aprobar_cotizacion = '<span data-placement="top" data-toggle="tooltip" title="APROBACION COTIZACON">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/cotizacion/forms/aprobacion/' . $document->cotizacion_id . '"><span class="fa fa-check"></span></a>
        </span>' . ' ';

        /*$form_culminar_cotizacion = '<span data-placement="top" data-toggle="tooltip" title="CULMINAR COTIZACON">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/cotizacion/forms/culminar/' . $document->cotizacion_id . '"><span class="fa fa-check"></span></a>
        </span>' . ' ';*/

        $designation_id = $this->session->userdata('designations_id');

        // COMERCIAL QUIEN SUBE LA COTIZACION 
        if ($designation_id == 1) {
          $action .= $detail . $cotizacion_documento;
          /*$action .= ($document->status == 33) ? $form_culminar_cotizacion : '';*/
        }

        /**
         * GERENCIA 
         */
        if ($designation_id == 2) {
          $action .= $detail . $cotizacion_documento;
          $action .= (in_array($document->status, [2])) ? $form_aprobar_cotizacion : '';
        }

        /**
         * ADMINISTRACION VISUALIZA EL DOCUMENTO DE COTIZACION
         */
        if ($designation_id == 3) {
          $action .= $cotizacion_documento;
        }
        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
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

  public function forms($form = NULL, $id)
  {
    if (isset($form) && $form != NULL) {
      /* $data['title'] = ('Derivando Cotizacion');
      $data['page'] = ('Derivando Cotizacion');
      $data['form'] = $form;
      $data['id'] = $id;

      $data['subview'] = $this->load->view('admin/cotizaciones/form_' . $form, $data, FALSE);
      $this->load->view('admin/_layout_modal_extra_lg', $data); */

      $data['title'] = 'Cotizacion';
      $data['page'] = 'Cotizacion';
      $data['form'] = $form;
      $cotizacion = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();
      $data['data_cotizacion'] = $cotizacion;

      $valorizacion = $this->db->where(['valorizacion_servicio_id' => $cotizacion->valorizacion_id])->get('tbl_valorizacion_servicio')->row();
      $data['area_inicio'] = $valorizacion->area_inicio;

      ($valorizacion->area_inicio) ? $this->db->where(['designations_id' => $data['area_inicio']]) : '';

      $data['areas'] = $this->db->where(['departments_id' => 2])->get('tbl_designations')->result();

      $data['cliente'] = $this->db->where(['cliente_id' => $cotizacion->cliente_id])->get('tbl_cliente')->row()->razon_social;
      $data['sede'] = $this->db->where(['sede_id' => $cotizacion->sede_id])->get('tbl_sedes')->row()->sede;

      ($form == 'aprobar_ot') ? $data['data_ot'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizacion_ot')->row() : '';

      $data['id'] = $id;

      $data['subview'] = $this->load->view('admin/cotizaciones/form_' . $form, $data, FALSE);
      $this->load->view('admin/_layout_modal_extra_lg', $data);
    }
  }

  public function save_forms($form, $id)
  {
    if ($form == 'aprobacion') {
      if ($this->input->post('aprobar')) {
        /* $this->db->select_max('numero');

        $numero = ($numero = $this->db->get('tbl_visita_tecnica')->row()->numero) ? $numero : 0; */

        $data_detail = [
          'user_id'           => $this->session->userdata('user_id'),
          'detail'            => 'SE APROBÓ COTIZACION',
          'cotizacion_id' => $id,
          'proceso'           => 'Aprobación',
          'proceso_id'        => $id,
          'status' => 1,
          'designations_id' => $this->session->userdata('designations_id'),
          'comentario' => $this->input->post('observaciones')
        ];

        // GENERAR EL NUMERO DE VISITA 
        // $numero = $this->db->select('if(numero>=0, max(numero),0)+1')->get('tbl_visita_tecnica');
        $data_cotizacion = [
          'status' => 21,
          'area_actual' => 1,
          'accion' => 'Subir OC'
        ];
      } else {
        $data_detail = [
          'user_id'           => $this->session->userdata('user_id'),
          'detail'            => 'NO APROBÓ COTIZACION(' . $id . ')',
          'cotizacion_id' => $id,
          'proceso'           => 'Aprobación',
          'proceso_id'        => $id,
          'status' => 0,
          'designations_id' => $this->session->userdata('designations_id'),
          'comentario' => $this->input->post('observaciones')
        ];

        // GENERAR EL NUMERO DE VISITA 
        // $numero = $this->db->select('if(numero>=0, max(numero),0)+1')->get('tbl_visita_tecnica');
        $data_cotizacion = [
          'status' => 20,
          'accion' => '--'
        ];
      }
      // ADD DETALLE DE COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detalle = $this->cotizacion_model->save($data_detail, NULL);


      // ACTUALIZAR LA COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones';
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cotizacion, $id);


      /* ADD NOTIFICATION */
      if ($this->input->post('aprobar')) {
        $designations_id = 1; // ES ID DE COMERCIAL
        $message = 'Cotizacion aprobada ( ' . $id . ' ), Emitir Orden Compra.';
        $link = 'admin/cotizacion/orden_compra';
        $this->notification($designations_id, $link, $message);
      }

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/cotizacion');
    } else if ($form == 'orden_compra') {

      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data_oc = [
        'comment' => $this->input->post('observaciones'),
        'ruta' => $ruta,
        'cotizacion_id' => $id,
        'user_id' => $this->session->userdata('user_id'),
        'designation_id' => $this->session->userdata('designations_id'),
        'status' => 1
      ];
      // ADD OC
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_oc'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_oc_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_oc_id';
      $id_oc = $this->cotizacion_model->save($data_oc, NULL);

      $cotizacion = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();
      if ($cotizacion->adelanto == 1) {
        $proceso = 'comprobante';
        $link_not = 'admin/comprobante_pago';
        // ADMINISTRACION
        $designation_id_not = 3;
        $message_not = 'OC subida ( ' . $id_oc . ' ), Subir comprobante.';
      } else {
        $proceso = 'OT';
        $link_not = 'admin/cotizacion/orden_trabajo';
        // COMERCIAL
        $designation_id_not = 1;
        $message_not = 'OC subida ( ' . $id_oc . ' ), Subir OT.';
      }
      $data_cotizacion = [
        'status' => 22,
        'area_actual' => $this->session->userdata('designations_id'),
        'accion' => $proceso
      ];


      // UPDATE COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cotizacion, $id);

      $data_detail = [
        'user_id'           => $this->session->userdata('user_id'),
        'detail'            => 'Subio Orden Compra',
        'cotizacion_id' => $id,
        'proceso'           => 'Orden Compra',
        'proceso_id'        => $id_oc,
        'status' => 1,
        'designations_id' => $this->session->userdata('designations_id'),
        'comentario' => $this->input->post('observaciones')
      ];

      // ADD DETALLE DE VIISTA
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detalle = $this->cotizacion_model->save($data_detail, NULL);

      /* ADD NOTIFICATION */

      if ($id_detalle) {
        $designations_id = $designation_id_not; // ES ID DE COMERCIAL o AMINISTRACION
        $message = $message_not;
        $link = $link_not;
        $this->notification($designations_id, $link, $message);
      }

      $type = 'success';
      $message = 'Registro Exitoso';

      set_message($type, $message);
      redirect('admin/cotizacion/orden_compra');
    } else if ($form == 'orden_trabajo') {

      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data_ot = [
        'comment' => $this->input->post('observaciones'),
        'ruta' => $ruta,
        'cotizacion_id' => $id,
        'user_id' => $this->session->userdata('user_id'),
        'area_asignada' => $this->input->post('area_asignada'),

        'designation_id' => $this->session->userdata('designations_id'),
        'start_date' => $this->input->post('start_date'),
        'end_date' => $this->input->post('end_date'),
        'status' => 1
      ];
      // ADD OC
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_ot'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_ot_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_ot_id';
      $id_ot = $this->cotizacion_model->save($data_ot, NULL);


      $data_cotizacion = [
        'status' => 3,
        'area_actual' => 2,
        'accion' => 'OT-aprobar'
      ];


      // UPDATE COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cotizacion, $id);

      $data_detail = [
        'user_id'           => $this->session->userdata('user_id'),
        'detail'            => 'Subio Orden Trabajo',
        'cotizacion_id' => $id,
        'proceso'           => 'Orden Trabajo',
        'proceso_id'        => $id_ot,
        'status' => 1,
        'designations_id' => $this->session->userdata('designations_id'),
        'comentario' => $this->input->post('observaciones')
      ];

      // ADD DETALLE DE VIISTA
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detalle = $this->cotizacion_model->save($data_detail, NULL);

      /* ADD NOTIFICATION */

      if ($id_detalle) {
        $designations_id = 2; // GERENCIA
        $message = 'OT Subida ( ' . $id_ot . ' ), Nesecita Aprobacion';
        $link = 'admin/cotizacion/orden_trabajo';
        $this->notification($designations_id, $link, $message);
      }

      $type = 'success';
      $message = 'Registro Exitoso';

      set_message($type, $message);
      redirect('admin/cotizacion/orden_trabajo');
    } else if ($form == 'aprobar_ot') {
      $ot_id = $this->input->post('ot_id');
      if ($this->input->post('aprobar')) {
        /* $this->db->select_max('numero');

        $numero = ($numero = $this->db->get('tbl_visita_tecnica')->row()->numero) ? $numero : 0; */

        $data_detail = [
          'user_id'           => $this->session->userdata('user_id'),
          'detail'            => 'SE APROBÓ ORDEN DE TRABAJO ( ' . $ot_id . ' ) ',
          'cotizacion_id' => $id,
          'proceso'           => 'Aprobación OT',
          'proceso_id'        => $ot_id,
          'status' => 1,
          'designations_id' => $this->session->userdata('designations_id'),
          'comentario' => $this->input->post('observaciones')
        ];

        // GENERAR EL NUMERO DE VISITA 
        // $numero = $this->db->select('if(numero>=0, max(numero),0)+1')->get('tbl_visita_tecnica');
        $data_cotizacion = [
          'status' => 31,
          'area_actual' => $this->input->post('designation_id'),
          'accion' => 'Subir conformidad'
        ];
      } else {

        $data_detail = [
          'user_id'           => $this->session->userdata('user_id'),
          'detail'            => 'NO APROBÓ ORDEN DE TRABAJO ( ' . $ot_id . ' ) ',
          'cotizacion_id' => $id,
          'proceso'           => 'Aprobación OT',
          'proceso_id'        => $ot_id,
          'status' => '0',
          'designations_id' => $this->session->userdata('designations_id'),
          'comentario' => $this->input->post('observaciones')
        ];

        // GENERAR EL NUMERO DE VISITA 
        // $numero = $this->db->select('if(numero>=0, max(numero),0)+1')->get('tbl_visita_tecnica');
        $data_cotizacion = [
          'status' => 30,
          'accion' => '--'
        ];
      }
      // ADD DETALLE DE COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detalle = $this->cotizacion_model->save($data_detail, NULL);


      // ACTUALIZAR LA COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones';
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cotizacion, $id);


      /* ADD NOTIFICATION */
      if ($this->input->post('aprobar')) {
        $designations_id = $this->input->post('designation_id'); // ES ID DE AREA Q INICIO LA VALORIZACION DE SERVICIO O AREA ASGNADA EN LA OT Q SUBIO COMERCIAL
        $message = 'OT aprobada ( ' . $ot_id . ' ), Subir Conformidad.';
        $link = 'admin/cotizacion/conformidad_servicio';
        $this->notification($designations_id, $link, $message);
      }

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/cotizacion/orden_trabajo/');
    } else if ($form == 'conformidad_servicio') {

      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data_conformidad = [
        'comment' => $this->input->post('observaciones'),
        'ruta' => $ruta,
        'cotizacion_id' => $id,
        'user_id' => $this->session->userdata('user_id'),
        'designation_id' => $this->session->userdata('designations_id'),
        'status' => 1
      ];
      // ADD OC
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_conformidad_servicio'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_conformidad_servicio_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_conformidad_servicio_id';
      $id_cs = $this->cotizacion_model->save($data_conformidad, NULL);


      $data_cotizacion = [
        'status' => 32,
        'area_actual' => 3,
        'accion' => 'comprobante pago'
      ];


      // UPDATE COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cotizacion, $id);

      $data_detail = [
        'user_id'           => $this->session->userdata('user_id'),
        'detail'            => 'Subio Conformidad de Servicio',
        'cotizacion_id' => $id,
        'proceso'           => 'Conformidad servicio',
        'proceso_id'        => $id_cs,
        'status' => 1,
        'designations_id' => $this->session->userdata('designations_id'),
        'comentario' => $this->input->post('observaciones')
      ];

      // ADD DETALLE DE COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detalle = $this->cotizacion_model->save($data_detail, NULL);

      /* ADD NOTIFICATION */

      if ($id_detalle) {
        $designations_id = 3; // ES ID DE COMERCIAL o AMINISTRACION
        $message = 'Conformidad de Servicio subida(' . $id_cs . '), Subir comprobantes de pago Restantes. ';
        $link = 'admin/comprobante_pago';
        $this->notification($designations_id, $link, $message);
      }

      $type = 'success';
      $message = 'Registro Exitoso';

      set_message($type, $message);
      redirect('admin/cotizacion/conformidad_servicio');
    } else if ($form == 'culminar') {

      $data_cotizacion = [
        'status' => 100,
        'area_actual' => 1,
        'accion' => 'Proceso Terminado'
      ];


      // UPDATE COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cotizacion, $id);

      $data_detail = [
        'user_id'           => $this->session->userdata('user_id'),
        'detail'            => 'SE CULMINO EL PROCESO CON EXITO ',
        'cotizacion_id' => $id,
        'proceso'           => 'Conformidad servicio',
        'proceso_id'        => $id,
        'status' => 1,
        'designations_id' => $this->session->userdata('designations_id'),
        'comentario' => $this->input->post('observaciones')
      ];

      // ADD DETALLE DE COTIZACION
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detalle = $this->cotizacion_model->save($data_detail, NULL);

      /* ADD NOTIFICATION */

      if ($id_detalle) {


        $type = 'success';
        $message = 'Registro Exitoso';
      } else {
        $type = 'error';
        $message = 'Algo Ocurrio';
      }

      set_message($type, $message);
      redirect('admin/cotizacion');
    }
  }

  /**
   * ORDEN DE COMPRA 
   *
   * @return void
   * @author aQMiGuEL <email@email.com>
   */
  public function orden_compra()
  {
    $data['title'] = 'Orden de compra';
    $data['page'] = 'Orden de compra';

    $data['subview'] = $this->load->view('admin/cotizaciones/orden_compra', $data, TRUE);

    $this->load->view('admin/_layout_main', $data);
  }

  public function cotizacion_orden_compra_list($action = NULL, $type = NULL)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_cotizaciones';

      $this->datatables->column_search = array('tbl_cotizaciones.nombre');
      $this->datatables->column_order = array(' ', 'tbl_cotizaciones.nombre');
      $this->datatables->order = array('cotizacion_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array('tbl_cotizaciones.cotizacion_id' => $type);
      } else {
        // $where = null;
        $where = array('tbl_cotizaciones.status>' => '2');
      }


      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        // if ($document->status >= 21 || $document->status == 3) :
        $action = null;


        $sub_array = array();



        //$sub_array[] = $document->nombre;
        $service =  $this->db->get_where("tbl_services", ['service_id' => $document->service_id])->row();
        $sub_array[] = $service->service;
        $cliente =  $this->db->get_where("tbl_cliente", ['cliente_id' => $document->cliente_id])->row();
        $sub_array[] = $cliente->ruc . ' - ' . $cliente->razon_social;
        $sede =  $this->db->get_where("tbl_sedes", ['sede_id' => $document->sede_id])->row();
        $sub_array[] = $sede->sede;

        $this->db->select('deptname, designations');
        $this->db->from('tbl_designations ds');
        $this->db->join('tbl_departments dp', 'ds.departments_id = dp.departments_id');

        $data_area = $this->db->where(['ds.designations_id' => $document->area_actual])->get()->row();

        $sub_array[] = ($data_area->departments_id == 1) ? $document->monto : '';
        $sub_array[] = $document->fecha;
        $sub_array[] = $data_area->designations;

        // JALAMOS LA VALORIZACION NOMBRE SI EXISTE
        if (!empty($document->valorizacion_id)) {
          $data_valorizacion = $this->db->where(['valorizacion_servicio_id' => $document->valorizacion_id])->get('tbl_valorizacion_servicio')->row();
          $sub_array[] = (count($data_valorizacion) > 0) ? $data_valorizacion->servicio : '';
        } else {
          $sub_array[] = '--';
        }
        $sub_array[] = $this->status($document->status);
        $sub_array[] = $document->accion;



        $cotizacion_documento = '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE COTIZACIÓN" >
        <!--<a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'uploads/cotizaciones/' . $document->ruta . '"><span class="fa fa-download"></span></a>-->
        <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'admin/cotizacion/pdf/' . $document->cotizacion_id . '"><span class="fa fa-file"></span></a>
        </span>';

        $detail = '<span data-placement="top" data-toggle="tooltip" title="RESUMEN COTIZACIÓN"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-purple btn-xs"  href="' . base_url() . 'admin/cotizacion/detail_list/' . $document->cotizacion_id . '"><span class="fa fa-list-alt"></span></a></span>' . ' ';

        $form_upload_OC = '<span data-placement="top" data-toggle="tooltip" title="SUBIR ORDEN DE COMPRA">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/cotizacion/forms/orden_compra/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>
        </span>' . ' ';

        $detail_oc = '<span data-placement="top" data-toggle="tooltip" title="VER OC"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-green btn-xs"  href="' . base_url() . 'admin/cotizacion/detail_oc/' . $document->cotizacion_id . '"><span class="fa fa-eye"></span></a></span>' . ' ';

        $designation_id = $this->session->userdata('designations_id');

        // COMERCIAL QUIEN SUBE LA COTIZACION 
        if ($designation_id == 1) {
          $action .= $detail . $cotizacion_documento;
          $action .= (in_array($document->status, [21])) ? $form_upload_OC : $detail_oc;
        }

        /**
         * ADMINISTRACION VE LAS OC 
         */
        if ($designation_id == 3) {
          $action .= $detail;
          $action .= (($document->status >= 22)) ? $detail_oc : '';
        }

        $sub_array[] = $action;
        $data[] = $sub_array;
        // endif;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  // DETALLE DE ORDE DE COMPRA
  function detail_oc($id)
  {
    //jharol


    $data['title'] = 'ORDEN DE COMPRA';
    $data_cotizacion = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();
    $data['data_cotizacion'] = $data_cotizacion;

    /*$this->db->select('cot.*,cotp.*');
    $this->db->from('tbl_cotizacion_pago cotp');
    $this->db->join('tbl_cotizaciones cot', 'cot.cotizacion_id = cotp.cotizacion_id');
    $data_pago= $this->db->where(['cotp.cotizacion_id' => $id])->get()->row();
    $data['data_pago'] = $data_pago;*/

    $data['data_oc'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizacion_oc')->row();

    $data['cliente'] = $this->db->where(['cliente_id' => $data_cotizacion->cliente_id])->get('tbl_cliente')->row()->razon_social;
    $data['sede'] = $this->db->where(['sede_id' => $data_cotizacion->sede_id])->get('tbl_sedes')->row()->sede;
    $data['descripcion'] = $this->db->where(['cotizacion_id' => $data_cotizacion->cotizacion_id])->get('tbl_cotizacion_pago')->row()->descripcion;





    $data['subview'] = $this->load->view('admin/cotizaciones/detail_oc', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  /**
   * ORDEN DE TRABAJO 
   *
   * @return void
   * @author aQMiGuEL <email@email.com>
   */
  public function orden_trabajo()
  {
    $data['title'] = 'Orden de trabajo';
    $data['page'] = 'Orden de trabajo';

    $data['subview'] = $this->load->view('admin/cotizaciones/orden_trabajo', $data, TRUE);

    $this->load->view('admin/_layout_main', $data);
  }

  public function cotizacion_orden_trabajo_list($action = NULL, $type = NULL)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_cotizaciones';

      $this->datatables->column_search = array('tbl_cotizaciones.nombre');
      $this->datatables->column_order = array(' ', 'tbl_cotizaciones.nombre');
      $this->datatables->order = array('cotizacion_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array('tbl_cotizaciones.cotizacion_id' => $type);
      } else {
        // $where = null;
        // $where = array('tbl_cotizaciones.status>='=>'22', 'tbl_cotizaciones.accion' => 'OT');
        $where = array('tbl_cotizaciones.status>' => '2');
      }


      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        if ($document->status >= 22 || $document->status == 3) :
          $action = null;


          $sub_array = array();

///orden de trabajo  -- Pagos anexados
          $this->db->select('cotot.*,cot.*,cotp.*');
          $this->db->from('tbl_cotizacion_ot cotot');
          $this->db->join('tbl_cotizaciones cot', 'cot.cotizacion_id = cotot.cotizacion_id');
          $this->db->join('tbl_cotizacion_pago cotp', 'cotp.cotizacion_id = cotot.cotizacion_id');

          $data_cotot = $this->db->where(['cotot.cotizacion_id' => $document->cotizacion_id])->get()->row();

          $sub_array[] = $data_cotot->cotizacion_ot_id;
          $service =  $this->db->get_where("tbl_services", ['service_id' => $document->service_id])->row();
          $sub_array[] = $service->service;

          $cliente =  $this->db->get_where("tbl_cliente", ['cliente_id' => $document->cliente_id])->row();
          $sub_array[] = $cliente->ruc . ' - ' . $cliente->razon_social;
          $sede =  $this->db->get_where("tbl_sedes", ['sede_id' => $document->sede_id])->row();
          $sub_array[] = $sede->sede;
          $this->db->select('deptname, designations');
          $this->db->from('tbl_designations ds');
          $this->db->join('tbl_departments dp', 'ds.departments_id = dp.departments_id');

          $data_area = $this->db->where(['ds.designations_id' => $document->area_actual])->get()->row();
          $sub_array[] = $document->fecha;


          

          $sub_array[] = $data_area->designations;

          // JALAMOS LA VALORIZACION NOMBRE SI EXISTE
          if (!empty($document->valorizacion_id)) {
            //$data_valorizacion = $this->db->where(['valorizacion_servicio_id' => $document->valorizacion_id])->get('tbl_valorizacion_servicio')->row();
            $data_valorizacion = $this->db->get_where("tbl_services", ['service_id' => $document->service_id])->row();
            $sub_array[] = (count($data_valorizacion) > 0) ? $data_valorizacion->service : '';
          } else {
            $sub_array[] = '--';
          }
          $sub_array[] = $this->status($document->status);
          $sub_array[] = $document->accion;




          $cotizacion_documento = '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE COTIZACIÓN" >        
        <!--<a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'admin/cotizacion/ot_pdf/' . $data_cotot->cotizacion_ot_id . '"><span class="fa fa-file"></span></a>-->
        <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'admin/cotizacion/pdf/' . $data_cotot->cotizacion_id . '"><span class="fa fa-file"></span></a>
        </span>';

          $detail = '<span data-placement="top" data-toggle="tooltip" title="RESUMEN COTIZACIÓN"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-purple btn-xs"  href="' . base_url() . 'admin/cotizacion/detail_list/' . $document->cotizacion_id . '"><span class="fa fa-list-alt"></span></a></span>' . ' ';

          $form_upload_OT = '<span data-placement="top" data-toggle="tooltip" title="SUBIR ORDEN DE TRABAJO">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/cotizacion/forms/orden_trabajo/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>
        </span>' . ' ';

          $form_aprobar_OT = '<span data-placement="top" data-toggle="tooltip" title="APROBAR ORDEN DE TRABAJO">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/cotizacion/forms/aprobar_ot/' . $document->cotizacion_id . '"><span class="fa fa-check"></span></a>
        </span>' . ' ';


          $form_culminar_cotizacion = '<span data-placement="top" data-toggle="tooltip" title="CULMINAR COTIZACON">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/cotizacion/forms/culminar/' . $document->cotizacion_id . '"><span class="fa fa-check"></span></a>
        </span>' . ' ';


          $detail_ot = '<span data-placement="top" data-toggle="tooltip" title="VER OT"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-green btn-xs"  href="' . base_url() . 'admin/cotizacion/detail_ot/' . $document->cotizacion_id . '"><span class="fa fa-eye"></span></a></span>' . ' ';

          $designation_id = $this->session->userdata('designations_id');

          // COMERCIAL QUIEN SUBE LA COTIZACION 
          if ($designation_id == 1) {
            $action .= $detail . $cotizacion_documento;
            //jharol
            $action .= ($document->status == 33) ? $form_culminar_cotizacion : '';
            $action .= ((in_array($document->status, [22, 23]) && $document->accion == 'OT')) ? $form_upload_OT : '';
            $action .= ((in_array($document->status, [3]) || $document->status >= 31)) ? $detail_ot : '';
          }

          if ($designation_id == 2) {
            $action .= ($document->status == 3) ? $detail_ot . $form_aprobar_OT : '';
            $action .= ($document->status >= 31) ? $detail_ot : '';
          }
          /**
           * ADMINISTRACION VE LAS OC 
           */
          if ($designation_id == 3) {
            // $action .= $detail;
            $action .= (($document->status >= 31)) ? $detail_ot : '';
          }

          $sub_array[] = $action;
          $data[] = $sub_array;
        endif;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  // DETALLE DE ORDE DE COMPRA
  function detail_ot($id)
  {
    $data['title'] = 'ORDEN DE TRABAJO';
    $data_cotizacion = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();
    $data['data_cotizacion'] = $data_cotizacion;

    $data['data_ot'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizacion_ot')->row();

    $data['cliente'] = $this->db->where(['cliente_id' => $data_cotizacion->cliente_id])->get('tbl_cliente')->row()->razon_social;
    $data['sede'] = $this->db->where(['sede_id' => $data_cotizacion->sede_id])->get('tbl_sedes')->row()->sede;

    $data['subview'] = $this->load->view('admin/cotizaciones/detail_ot', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  /**
   * CONFORMIDAD DE SERVICIO
   *
   * @return void
   * @author aQMiGuEL <email@email.com>
   */
  public function conformidad_servicio()
  {
    $data['title'] = 'Conformidad de Servicio';
    $data['page'] = 'Conformidad de Servicio';

    $data['subview'] = $this->load->view('admin/cotizaciones/conformidad_servicio', $data, TRUE);

    $this->load->view('admin/_layout_main', $data);
  }

  public function cotizacion_conformidad_servicio_list($action = NULL, $type = NULL)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->select = '*, cot.status';
      $this->datatables->table = 'tbl_cotizaciones cot';
      $this->datatables->join_table = array('tbl_services ser', 'tbl_cotizacion_ot ot', 'tbl_cliente cli', 'tbl_sedes se');
      $this->datatables->join_where = array('cot.service_id = ser.service_id', 'cot.cotizacion_id = ot.cotizacion_id', 'cot.cliente_id = cli.cliente_id', 'cot.sede_id = se.sede_id');


      $this->datatables->column_search = array('ser.service', 'cli.razon_social');
      $this->datatables->column_order = array('ser.service', 'cli.razon_social');
      $this->datatables->order = array('cot.cotizacion_id' => 'desc');

      // VERIFICAMOS SI ES ADMINISTRATIVO O AREA_USUARIA
      $department = $this->db->where(['designations_id' => $this->session->userdata('designations_id')])->get('tbl_designations')->row()->departments_id;


      // get all invoice
      if (!empty($type)) {
        $where = array('cot.cotizacion_id' => $type);
      } else {
        // $where = null;
        // $where = array('tbl_cotizaciones.status>='=>'22', 'tbl_cotizaciones.accion' => 'OT');
        $where = array('cot.status>=' => '31');
      }
      // if( $department == 2 ) $where['area_asignada'] = $this->session->userdata('designations_id') ;
      /* echo "<pre>";
print_r($where);
echo "</pre>"; */
      // die();

      $fetch_data = make_datatables($where);
      // echo "<pre>";
      // print_r( $fetch_data );
      // echo "</pre>";
      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        if (($department == 2 && $document->area_asignada == $this->session->userdata('designations_id')) || $department == 1) :

          $action = null;


          $sub_array = array();



          $sub_array[] = $document->service;

          $sub_array[] = $document->ruc . ' - ' . $document->razon_social;

          $sub_array[] = $document->sede;
          $this->db->select('deptname, designations');
          $this->db->from('tbl_designations ds');
          $this->db->join('tbl_departments dp', 'ds.departments_id = dp.departments_id');

          $data_area = $this->db->where(['ds.designations_id' => $document->area_actual])->get()->row();

          $sub_array[] = ($data_area->departments_id == 1) ? $document->monto : '';
          $sub_array[] = $document->fecha;

          $sub_array[] = $data_area->designations;

          // JALAMOS LA VALORIZACION NOMBRE SI EXISTE
          if (!empty($document->valorizacion_id)) {
            $data_valorizacion = $this->db->where(['valorizacion_servicio_id' => $document->valorizacion_id])->get('tbl_valorizacion_servicio')->row();
            $sub_array[] = (count($data_valorizacion) > 0) ? $data_valorizacion->servicio : '';
          } else {
            $sub_array[] = '--';
          }
          $sub_array[] = $this->status($document->status);
          $sub_array[] = $document->accion;



          $cotizacion_documento = (isset($document->ruta) && !empty($document->ruta)) ? '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE COTIZACIÓN" >
        <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'uploads/cotizaciones/' . $document->ruta . '"><span class="fa fa-download"></span></a>
        </span>' . ' ' : '';

          $detail = '<span data-placement="top" data-toggle="tooltip" title="RESUMEN COTIZACIÓN"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-purple btn-xs"  href="' . base_url() . 'admin/cotizacion/detail_list/' . $document->cotizacion_id . '"><span class="fa fa-list-alt"></span></a></span>' . ' ';



          $detail_ot = '<span data-placement="top" data-toggle="tooltip" title="VER OT"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-green btn-xs"  href="' . base_url() . 'admin/cotizacion/detail_ot/' . $document->cotizacion_id . '"><span class="fa fa-eye"></span></a></span>' . ' ';

          $form_conformidad_servicio = '<span data-placement="top" data-toggle="tooltip" title="SUBIR CONFORMIDAD DE SERVICIO"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-green btn-xs"  href="' . base_url() . 'admin/cotizacion/forms/conformidad_servicio/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a></span>' . ' ';

          $detail_conformidad = '<span data-placement="top" data-toggle="tooltip" title="VER CONFORMIDAD"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-green btn-xs"  href="' . base_url() . 'admin/cotizacion/detail_conformidad_servicio/' . $document->cotizacion_id . '"><span class="fa fa-eye"></span></a></span>' . ' ';

          $designation_id = $this->session->userdata('designations_id');
          $area_inicio = $data_valorizacion->area_inicio;

          // COMERCIAL Y ADMINISTRACION VE LA CONFORMIDAD
          if (in_array($designation_id, [1, 3])) {
            $action .= ($document->status >= 31) ? $detail_conformidad : '';
          }

          if ($designation_id == $document->area_asignada) {
            $action .= $detail_ot;
            $action .= ($document->status == 31) ? $form_conformidad_servicio : '';
            $action .= ($document->status > 31) ? $detail_conformidad : '';
          }



          $sub_array[] = $action;
          $data[] = $sub_array;
        endif;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  // DETALLE DE ORDE DE COMPRA
  function detail_conformidad_servicio($id)
  {
    $data['title'] = 'CONFORMIDAD DE SERVICIO';
    $data_cotizacion = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();
    $data['data_cotizacion'] = $data_cotizacion;

    $data['data_cs'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizacion_conformidad_servicio')->row();

    $data['cliente'] = $this->db->where(['cliente_id' => $data_cotizacion->cliente_id])->get('tbl_cliente')->row()->razon_social;
    $data['sede'] = $this->db->where(['sede_id' => $data_cotizacion->sede_id])->get('tbl_sedes')->row()->sede;

    $data['subview'] = $this->load->view('admin/cotizaciones/detail_conformidad_servicio', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  /**
   * ___________________________
   */
  public function visita($action = NULL)
  {
    $uri = $this->uri->uri_string(); //la ruta de acceso // VERIFICAR PARA DEJAR PASAR O DENEGAR EL PEMISO 
    $data['title'] = 'Cotizaciones';
    $data['page'] = 'Cotizaciones';
    $data['action'] = $action;
    $data['btn_add'] = false;

    $data['btn_add_cotizacion'] = ($action == 'emision_cotizacion') ? true : false;

    $data['subview'] = $this->load->view('admin/cotizaciones/index', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }






  public function CotizacionList_old($actionURL = NULL, $type = null)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_cotizaciones';

      $this->datatables->column_search = array('tbl_cotizaciones.nombre');
      $this->datatables->column_order = array(' ', 'tbl_cotizaciones.nombre');
      $this->datatables->order = array('cotizacion_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array('tbl_cotizaciones.cotizacion_id' => $type);
      } else {
        $where = null;
      }

      //el 10 es el id de la sub area    AREA USUARIA QUE ES LA QUE INGRESA LA COTIAZCION Y VISUALIZA EL PROCESO 
      // if ($this->session->userdata('designations_id') != 10 || ($this->session->userdata('designations_id') == '10' && !empty($actionURL))) {
      if (!empty($actionURL)) {
        if ($actionURL == 'constancia_visita_tecnica' && ($this->session->userdata('designations_id') == '1' || $this->session->userdata('designations_id') == '3')) {
          $action_id = $this->action_cot($actionURL) + 1; //lista las cotizacones que se encuentran en el proceso de "emision de valoacion en area_usuaria 

        } else {
          $action_id = $this->action_cot($actionURL);
        }
        $where = array(
          'tbl_cotizaciones.accion' => $action_id
        );
      }


      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        $action = null;


        $sub_array = array();



        $sub_array[] = $document->nombre;
        $cliente =  $this->db->get_where("tbl_cliente", ['cliente_id' => $document->cliente_id])->row();
        $sub_array[] = $cliente->ruc . ' - ' . $cliente->razon_social;
        $sede =  $this->db->get_where("tbl_sedes", ['sede_id' => $document->sede_id])->row();
        $sub_array[] = $sede->sede;
        $sub_array[] = $document->monto;
        $sub_array[] = $document->fecha;

        $this->db->select('deptname, designations');
        $this->db->from('tbl_designations ds');
        $this->db->join('tbl_departments dp', 'ds.departments_id = dp.departments_id');

        $data_area = $this->db->where(['ds.designations_id' => $document->area_actual])->get()->row();

        $sub_array[] = $data_area->deptname . ' - ' . $data_area->designations;

        // JALAMOS LA VALORIZACION NOMBRE SI EXISTE
        if (!empty($document->valorizacion_id)) {
          $data_valorizacion = $this->db->where(['valorizacion_servicio_id' => $document->valorizacion_id])->get('tbl_valorizacion_servicio')->row();
          $sub_array[] = (count($data_valorizacion) > 0) ? $data_valorizacion->servicio : '';
        } else {
          $sub_array[] = '--';
        }
        $sub_array[] = $this->status($document->status);

        // STATUS ::: 1 EN PROCESO // 2 APROBADO // 3 =>CANCELADO
        if ($document->status == 1) {
          // $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DERIVAR COTIZACION" href="' . base_url() . 'admin/cotizacion/derivar/' . $document->cotizacion_id . '"><span class="fa fa-share"></span></a>' . ' ';
        }

        if (!empty($deleted)) {
          // $action .= '<span data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs delete-document" title="Click para eliminar " data-id="' . $document->cotizacion_id . '"><span class="fa fa-trash-o"></span></span>' . ' ';
        }
        // 


        $designation_id = $this->session->userdata('designations_id');
        // preguntar si la designatcion o area es la area actual para mostrar sus acciones a realizar
        // comparara tmbn la accion a realizar 

        if ($document->status == 1) {
          /* $action .= $this->action_cot( $actionURL );
          $action .= $this->action_cot( 2 ); */
          if ($actionURL == 'aprobacion') {


            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="APROBACION VALORACION " href="' . base_url() . 'admin/cotizacion/forms/aprobacion_valoracion_visita_tecnica/' . $document->cotizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
          } elseif ($actionURL == 'valoracion') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="VALORACION DE VISITA TECNICA APROBADA " href="' . base_url() . 'admin/cotizacion/forms/valoracion_visita_tecnica_aprobada/' . $document->cotizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
            if (!empty($document->ruta)) {
              $action .= '<a target="_blank"  class="btn btn-success btn-xs" title="DESCARGAR DOCUMENTO " href="' . base_url() . 'uploads/cotizaciones/' . $document->ruta . '"><span class="fa fa-download"></span></a>' . ' ';
            }
          } elseif ($actionURL == 'emision_orden_visita_tecnica') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMISION DE VISITA TECNICA" href="' . base_url() . 'admin/cotizacion/forms/emision_orden_visita_tecnica/' . $document->cotizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
          } elseif ($actionURL == 'constancia_visita_tecnica') {
            if ($this->session->userdata('designations_id') == '10') {
              $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="SUBIR CONTANCIA DE VISITA TECNICA" href="' . base_url() . 'admin/cotizacion/forms/constancia_visita_tecnica/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
            } elseif ($this->session->userdata('designations_id') == '1' || $this->session->userdata('designations_id') == '3') {
              // jalamos el archivo del detalle 
              $constancia_visita = $this->db->where(['cotizacion_id' => $document->cotizacion_id, 'accion' => 5])->get('cotizacion_detail')->row()->document;
              if ($constancia_visita) {
                $action .= '<a  target="_blank" href="' . base_url() . 'uploads/cotizaciones/constancia_visita_tecnica/' . $constancia_visita . '" title="DESCARGAR CONSTANCIA DE VISITA TECNICA"><span class="fa fa-download"></span></a>' . ' ';
              }
            }
          } elseif ($actionURL == 'emision_valoracion_servicio') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMISION VALORACION SERVICIO" href="' . base_url() . 'admin/cotizacion/forms/emision_valoracion_servicio/' . $document->cotizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
          } elseif ($actionURL == 'aprobacion_valoracion_servicio') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMISION VALORACION SERVICIO" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
          } elseif ($actionURL == 'emision_cotizacion') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMISION DE COTIZACION" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          } elseif ($actionURL == 'aprobacion_cotizacion') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="APROBAR COTIZACION" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-check"></span></a>' . ' ';
          } elseif ($actionURL == 'orden_compra') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="SUBIR ORDEN DE COMPRA" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          } elseif ($actionURL == 'factura_comprobante_pago') {
            $action_id = $this->action_cot('orden_compra');
            $orden_compra = $this->db->where(['accion' => $action_id, 'cotizacion_id' => $document->cotizacion_id])->get('tbl_cotizacion_detail')->row()->document;
            $action .= '<a target="_blank"  class="btn btn-info btn-xs" title="DESCARGAR ORDEN DE COMPRA" href="' . base_url() . 'uploads/cotizaciones/orden_compra/' . $orden_compra . '"><span class="fa fa-download"></span></a>' . ' ';

            $action_id = $this->action_cot('emision_cotizacion');
            $cotizacion_doc = $this->db->where(['accion' => $action_id, 'cotizacion_id' => $document->cotizacion_id])->get('tbl_cotizacion_detail')->row()->document;

            $action .= '<a target="_blank"  class="btn btn-info btn-xs" title="DESCARGAR COTIZACION" href="' . base_url() . 'uploads/cotizaciones/emision_cotizacion/' . $cotizacion_doc . '"><span class="fa fa-download"></span></a>' . ' ';

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="SUBIR FACTURA O COMPROBANTE DE PAGO" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          } elseif ($actionURL == 'emision_orden_trabajo') {


            $action_id = $this->action_cot('factura_comprobante_pago');
            $comprobante = $this->db->where(['accion' => $action_id, 'cotizacion_id' => $document->cotizacion_id])->get('tbl_cotizacion_detail')->row()->document;

            $action .= '<a target="_blank"  class="btn btn-info btn-xs" title="DESCARGAR COMPROBANTE DE PAGO" href="' . base_url() . 'uploads/cotizaciones/factura_comprobante_pago/' . $comprobante . '"><span class="fa fa-download"></span></a>' . ' ';

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="SUBIR ORDEN DE COMPRA" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          } elseif ($actionURL == 'aprobacion_orden_trabajo') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="APROBAR ORDEN DE TRABAJO" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          } elseif ($actionURL == 'conformidad_servicio') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="CONFORMIDAD DE SERVICIO" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          } elseif ($actionURL == 'comprobante_pago_administracion') {
            $action_id = $this->action_cot('conformidad_servicio');
            $conformidad = $this->db->where(['accion' => $action_id, 'cotizacion_id' => $document->cotizacion_id])->get('tbl_cotizacion_detail')->row()->document;
            $action .= '<a target="_blank"  class="btn btn-info btn-xs" title="DESCARGAR ORDEN DE COMPRA" href="' . base_url() . 'uploads/cotizaciones/conformidad_servicio/' . $conformidad . '"><span class="fa fa-download"></span></a>' . ' ';


            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="SUBIR FACTURA O COMPROBANTE DE PAGO" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          } elseif ($actionURL == 'cierre_orden') {
            $action_id = $this->action_cot('factura_comprobante_pago_administracion');
            $conformidad = $this->db->where(['accion' => $action_id, 'cotizacion_id' => $document->cotizacion_id])->get('tbl_cotizacion_detail')->row()->document;
            $action .= '<a target="_blank"  class="btn btn-info btn-xs" title="DESCARGAR COMPROBANTE" href="' . base_url() . 'uploads/cotizaciones/factura_comprobante_pago_administracion/' . $conformidad . '"><span class="fa fa-download"></span></a>' . ' ';


            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="CULMINAR PROCESO" href="' . base_url() . 'admin/cotizacion/forms/' . $actionURL . '/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          } elseif (empty($actionURL)) {
            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DETALLE DEL DOCUMENTO" href="' . base_url() . 'admin/cotizacion/detail_list/' . $document->cotizacion_id . '"><span class="fa fa-list"></span></a>' . ' ';
          }
        } elseif ($document->status == 0) {

          if (empty($actionURL)) {
            $action .= 'cancelado';
          }
        } else if ($document->status == 2) {
          $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DETALLE DEL DOCUMENTO" href="' . base_url() . 'admin/cotizacion/detail_list/' . $document->cotizacion_id . '"><span class="fa fa-list"></span></a>' . ' ';
        }

        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  public function ValorizacionesList($actionURL = NULL, $type = null)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_valorizacion';

      $this->datatables->column_search = array('tbl_valorizacion.nombre');
      $this->datatables->column_order = array(' ', 'tbl_valorizacion.nombre');
      $this->datatables->order = array('valorizacion_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array(
          'tbl_valorizacion.valorizacion_id' => $type,
          'tbl_valorizacion.accion' => '8',
          'tbl_valorizacion.status' => '1'
        );
      } else {
        $where = array(
          'tbl_valorizacion.accion' => '8',
          'tbl_valorizacion.status' => '1'
        );
      }

      if (!empty($actionURL)) {
        if ($actionURL == 'constancia_visita_tecnica' && ($this->session->userdata('designations_id') == '1' || $this->session->userdata('designations_id') == '3')) {
          $action_id = $this->action_cot($actionURL) + 1; //lista las cotizacones que se encuentran en el proceso de "emision de valoacion en area_usuaria 

        } else {
          $action_id = $this->action_cot($actionURL);
        }
        $where = array(
          'tbl_cotizaciones.accion' => $action_id
        );
      }


      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        $action = null;


        $sub_array = array();


        $sub_array[] = $document->nombre;
        $cliente =  $this->db->get_where("tbl_cliente", ['cliente_id' => $document->cliente_id])->row();
        $sub_array[] = $cliente->ruc . ' - ' . $cliente->razon_social;
        $sede =  $this->db->get_where("tbl_sedes", ['sede_id' => $document->sede_id])->row();
        $sub_array[] = $sede->sede;
        $sub_array[] = $document->monto;
        $sub_array[] = $document->fecha;

        $this->db->select('deptname, designations');
        $this->db->from('tbl_designations ds');
        $this->db->join('tbl_departments dp', 'ds.departments_id = dp.departments_id');

        $data_area = $this->db->where(['ds.designations_id' => $document->area_actual])->get()->row();

        $sub_array[] = $data_area->deptname . ' - ' . $data_area->designations;
        $sub_array[] = $this->status($document->status);



        $designation_id = $this->session->userdata('designations_id');
        // preguntar si la designatcion o area es la area actual para mostrar sus acciones a realizar
        // comparara tmbn la accion a realizar 


        $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-success btn-xs" title="DETALLE DEL DOCUMENTO" href="' . base_url() . 'admin/valorizacion/detail_list/' . $document->valorizacion_id . '"><span class="fa fa-list"></span></a>' . ' ';

        /* SI YA ESTA EN COTIZACION YA NO DEBE APARECER EN VALORIZACIONES PARA EMITI COTIZACION  */
        $_data_valorizacion = $this->db->where(['valorizacion_id' => $document->valorizacion_id])->get('tbl_cotizaciones')->row();
        if (count($_data_valorizacion) == 0) {
          $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMITIR COTIZACIÓN" href="' . base_url() . 'admin/cotizacion/add_cotizacion_valorizacion/' . $document->valorizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
        }
        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  public function derivar($id = NULL)
  {
    if (isset($id) && $id != NULL) {
      $data['title'] = ('Derivando Cotizacion');
      $data['page'] = ('Derivando Cotizacion');


      $data['subview'] = $this->load->view('admin/cotizaciones/derivar_cotizacion_form', $data, FALSE);
      $this->load->view('admin/_layout_modal', $data);
    }
  }


  private function add_cotizacion_detail($accion, $valor_accion, $status = 1, $cotizacion_id, $comment = '', $document = '')
  {
  }

  public function save_forms_old($form, $id)
  {
    if ($form == 'aprobacion_valoracion_visita_tecnica') {
      $data['accion'] = 2; //aprovacion de visita 
      if ($this->input->post('aprobar')) {
        $data['valor_accion'] = 'Aprobado';
        $data['status'] = 1;
        $data_cot['area_actual'] = 3;
        $data_cot['accion'] = 3;
      } else {
        $data_cot['area_actual'] = 10;
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'Cancelado';
      }
      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/aprobaciones";
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      // if ($this->input->post('files')) {
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data['document'] = $ruta;

      // GUARDANDO EL DETALLE Y ACTUALIZANDO LA COTIZACION CABECERA
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cot, $id);

      /* DETALLE */
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id = $this->cotizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      $designations_id = 3; // ES ID DE DMINISTRACION
      $message = 'Nueva Cotizacion Ingresada, Necesita ser Valorizada';
      $link = 'admin/cotizacion/visita/aprobacion';
      $this->notification($designations_id, $link, $message);

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/cotizacion/visita/aprobacion');
    } else if ($form == 'valoracion_visita_tecnica_aprobada') {
      $data['accion'] = 3; //valoracion de visita tecnoca aprobada 
      if ($this->input->post('aprobar')) {
        $data['valor_accion'] = 'Aceptada el pase ';
        $data['status'] = 1;
        $data_cot['area_actual'] = 1; // 1_> comercial
        $data_cot['accion'] = 4;
      } else {
        $data_cot['area_actual'] = 10; //10 area_usuaria
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'Denegado';
      }
      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/valoraciones";
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";

      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data['document'] = $ruta;

      // GUARDANDO EL DETALLE Y ACTUALIZANDO LA COTIZACION CABECERA
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cot, $id);

      /* DETALLE */
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id = $this->cotizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($this->input->post('aprobar')) {
        $designations_id = 1; // ES ID DE DMINISTRACION
        $message = 'Nueva Cotizacion Ingresada, Necesita emision de orden';
        $link = 'admin/cotizacion/visita/emision_orden_visita';
        $this->notification($designations_id, $link, $message);
      } else {
        $designations_id = 10; // ES ID DE area_usuaria
        $message = 'Cotizacion ha sido enegada la valoracion por e area de Comercio';
        $link = 'admin/cotizacion/';
        $this->notification($designations_id, $link, $message);
      }

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/cotizacion/visita/valoracion');
    } else if ($form == 'emision_orden_visita_tecnica') {
      $data['accion'] = 4; //valoracion de visita tecnoca aprobada 
      if ($this->input->post('aprobar')) {
        $data['valor_accion'] = 'Aceptado el pase ';
        $data['status'] = 1;
        $data_cot['area_actual'] = 10; // 1_> comercial
        $data_cot['accion'] = 5;
      } else {
        $data_cot['area_actual'] = 10; //10 area_usuaria
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'Denegado el pase';
      }
      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/emision_orden_visita_tecnica";
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";

      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data['document'] = $ruta;

      // GUARDANDO EL DETALLE Y ACTUALIZANDO LA COTIZACION CABECERA
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cot, $id);

      /* DETALLE */
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id = $this->cotizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($this->input->post('aprobar')) {
        $designations_id = 10; // ES ID DE area_usuaria
        $message = 'Subir constancia de visita tecnica';
        $link = 'admin/cotizacion/visita/constancia_visita_tecnica';
        $this->notification($designations_id, $link, $message);
      } else {
        $designations_id = 10; // ES ID DE area_usuaria
        $message = 'denego el area Comercial';
        $link = 'admin/cotizacion/';
        $this->notification($designations_id, $link, $message);
      }

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/cotizacion/visita/valoracion');
    } else if ($form == 'constancia_visita_tecnica') {
      $data['accion'] = 5; //valoracion de visita tecnoca aprobada 

      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/constancia_visita_tecnica";
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      /* print_r( $_POST );
      print_r( $_FILES ); */
      // die();
      // if ($this->input->post('files')) {
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();

        $ruta = $data_upload['file_name'];


        $data['valor_accion'] = 'Subio Constancia de visita tecnica';
        $data['status'] = 1;
        $data_cot['area_actual'] = 10; // 1_> comercial // debe poner un array( para poner   varias areas la cual puedan observar el proceso )
        $data_cot['accion'] = 6;




        $data['document'] = $ruta;

        // GUARDANDO EL DETALLE Y ACTUALIZANDO LA COTIZACION CABECERA
        $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_id';
        $this->cotizacion_model->save($data_cot, $id);

        /* DETALLE */
        $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
        $id = $this->cotizacion_model->save($data, NULL);

        $designations_id = 10; // ES ID DE area_usuaria
        $message = 'Emitir valoracion servicio';
        $link = 'admin/cotizacion/visita/emision_valoracion_servicio';
        $this->notification($designations_id, $link, $message);

        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "danger";
        $message = 'Registro Fallido';
      }

      set_message($type, $message);
      redirect('admin/cotizacion/visita/constancia_visita_tecnica');
    } else if ($form == 'emision_valoracion_servicio') {
      $data['accion'] = $this->action_cot($form); //function regresa el id dde la accion


      $data['valor_accion'] = 'Emision de valoracion de servicio ';
      $data['status'] = 1; // correcto
      $data_cot['area_actual'] = 2; // 1_> comercial
      $data_cot['accion'] = 7;

      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');

      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";

      if ($this->input->post('archivo')) {
        if ($this->guardar_archivo($dir)) {
          $data_upload = $this->upload->data();
          $ruta = $data_upload['file_name'];
        }
      }
      $data['document'] = $ruta;

      // GUARDANDO EL DETALLE Y ACTUALIZANDO LA COTIZACION CABECERA
      $this->cotizacion_model->_table_name  = 'tbl_cotizaciones'; //table name
      $this->cotizacion_model->_order_by    = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cot, $id);

      /* DETALLE */
      $this->cotizacion_model->_table_name  = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by    = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id                                   = $this->cotizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($id) {
        $designations_id = 2; // ES ID DE GERENCIA
        $message = 'Se emitio una valoracion de servicio, Necesita ser aprobada';
        $link = 'admin/cotizacion/visita/aprobacion_valoracion_servicio';
        $this->notification($designations_id, $link, $message);

        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "danger";
        $message = 'Registro Fallido';
      }


      set_message($type, $message);
      redirect('admin/cotizacion/visita/valoracion');
    } else if ($form == 'aprobacion_valoracion_servicio') {
      $data['accion'] = $this->action_cot($form);
      if ($this->input->post('aprobar')) {
        $data['valor_accion'] = 'Aprobado';
        $data['status'] = 1;
        $data_cot['area_actual'] = 1; //comercial
        $data_cot['accion'] = $this->action_cot($form) + 1;
      } else {
        $data_cot['area_actual'] = 10;
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'No aprobo la valoracion de servicio';
      }
      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data['document'] = $ruta;

      // GUARDANDO EL DETALLE Y ACTUALIZANDO LA COTIZACION CABECERA
      $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_id';
      $this->cotizacion_model->save($data_cot, $id);

      /* DETALLE */
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id = $this->cotizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($id) {
        $designations_id = 1; // ES ID DE COMERCIAL
        $message = 'Se aprobo Valoracion, Emitir Cotizacion';
        $link = 'admin/cotizacion/visita/emision_cotizacion';
        $this->notification($designations_id, $link, $message);
        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "success";
        $message = 'Registro Fallido';
      }

      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    } else if ($form == 'emision_cotizacion') {
      $data['accion'] = $this->action_cot($form);
      $data['valor_accion'] = 'Cotizacion Emitida';
      $data['status'] = 1;
      $data_cot['area_actual'] = 2; //gerencia
      $data_cot['accion'] = $this->action_cot($form) + 1;



      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];

        $data['document'] = $ruta;




        /* REGISTRANDO DETALLE */
        $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
        $id_detail = $this->cotizacion_model->save($data, NULL);

        /* ADD NOTIFICATION */
        if ($id_detail) {
          // ACTUALIZANDO LA COTIZACION CABECERA
          $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
          $this->cotizacion_model->_order_by = 'cotizacion_id';
          $this->cotizacion_model->_primary_key = 'cotizacion_id';
          $this->cotizacion_model->save($data_cot, $id);

          $designations_id = 2; // ES ID DE GERENCIA
          $message = 'Se emitio Cotizacion, Verificar y aprobar.';
          $link = 'admin/cotizacion/visita/aprobacion_cotizacion';
          $this->notification($designations_id, $link, $message);
          $type = "success";
          $message = 'Registro Exitoso';
        } else {
          $type = "error";
          $message = 'Registro Fallido';
        }
      } else {
        $type = "error";
        $message = 'No se Anexo archivo de cotizacion.  ';
      }




      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    } else if ($form == 'aprobacion_cotizacion') {
      $data['accion'] = $this->action_cot($form);
      if ($this->input->post('aprobar')) {
        $data['valor_accion'] = 'Cotizacion aprobada';
        $data['status'] = 1;
        $data_cot['area_actual'] = 1; //comercial
        $data_cot['accion'] = $this->action_cot($form) + 1;
      } else {
        $data_cot['area_actual'] = 10;
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'No se aprobo la cotizacion';
      }
      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data['document'] = $ruta;



      /* DETALLE */
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detail = $this->cotizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($id_detail) {
        //ACTUALIZANDO LA COTIZACION CABECERA
        $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_id';
        $this->cotizacion_model->save($data_cot, $id);


        $designations_id = 1; // ES ID DE COMERCIAL
        $message = 'Se aprobo Cotizacion, Subir orden de compra';
        $link = 'admin/cotizacion/visita/orden_compra';
        $this->notification($designations_id, $link, $message);
        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "success";
        $message = 'Registro Fallido';
      }

      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    } else if ($form == 'orden_compra') {
      $data['accion'] = $this->action_cot($form);
      $data['valor_accion'] = 'Orden de compra Emitida';
      $data['status'] = 1;
      $data_cot['area_actual'] = 3; //administracion
      $data_cot['accion'] = $this->action_cot($form) + 1;



      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];

        $data['document'] = $ruta;

        /* REGISTRANDO DETALLE */
        $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
        $id_detail = $this->cotizacion_model->save($data, NULL);

        /* ADD NOTIFICATION */
        if ($id_detail) {
          // ACTUALIZANDO LA COTIZACION CABECERA
          $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
          $this->cotizacion_model->_order_by = 'cotizacion_id';
          $this->cotizacion_model->_primary_key = 'cotizacion_id';
          $this->cotizacion_model->save($data_cot, $id);

          $adelanto = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row()->adelanto;
          if ($adelanto == 1) {
            $designations_id = 3; // ES ID DE ADMINISTRACION
            $message = 'Se emitio Orden de compra, subir el comprobante de pago';
            $link = 'admin/cotizacion/visita/factura_comprobante_pago';
          } else {
            $designations_id = 1; // ES ID DE COMERCIAL
            $message = 'Se emitio Orden de compra, Gerenar Orden de Trabajo';
            $link = 'admin/cotizacion/visita/emision_orden_trabajo';
          }
          $this->notification($designations_id, $link, $message);
          $type = "success";
          $message = 'Registro Exitoso';
        } else {
          $type = "error";
          $message = 'Registro Fallido';
        }
      } else {
        $type = "error";
        $message = 'No se Anexo archivo de cotizacion.  ';
      }




      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    } else if ($form == 'factura_comprobante_pago') {
      $data['accion'] = $this->action_cot($form);
      $data['valor_accion'] = 'Comprobante de pago subido';
      $data['status'] = 1;
      $data_cot['area_actual'] = 1; //comercial
      $data_cot['accion'] = $this->action_cot($form) + 1;

      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];

        $data['document'] = $ruta;

        /* REGISTRANDO DETALLE */
        $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
        $id_detail = $this->cotizacion_model->save($data, NULL);

        /* ADD NOTIFICATION */
        if ($id_detail) {
          // ACTUALIZANDO LA COTIZACION CABECERA
          $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
          $this->cotizacion_model->_order_by = 'cotizacion_id';
          $this->cotizacion_model->_primary_key = 'cotizacion_id';
          $this->cotizacion_model->save($data_cot, $id);

          $designations_id = $data_cot['area_actual']; // ES ID DE COMERCIAL
          $message = 'Se subio el comprobante de pago. Emitir orden de trabajo';
          $link = 'admin/cotizacion/visita/emision_orden_trabajo';
          $this->notification($designations_id, $link, $message);
          $type = "success";
          $message = 'Registro Exitoso';
        } else {
          $type = "error";
          $message = 'Registro Fallido';
        }
      } else {
        $type = "error";
        $message = 'No se Anexo archivo.  ';
      }




      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    } else if ($form == 'emision_orden_trabajo') {
      $data['accion'] = $this->action_cot($form);
      $data['valor_accion'] = 'Emitio orden de trabajo';
      $data['status'] = 1;
      $data_cot['area_actual'] = 2; //Gerencia
      $data_cot['accion'] = $this->action_cot($form) + 1;

      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];

        //$data['document'] = $ruta;

        /* REGISTRANDO DETALLE */
        $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
        $id_detail = $this->cotizacion_model->save($data, NULL);

        /* ADD NOTIFICATION */
        if ($id_detail) {
          // ACTUALIZANDO LA COTIZACION CABECERA
          $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
          $this->cotizacion_model->_order_by = 'cotizacion_id';
          $this->cotizacion_model->_primary_key = 'cotizacion_id';
          $this->cotizacion_model->save($data_cot, $id);

          $designations_id = $data_cot['area_actual'];
          $message = 'Se emitio orden de trabajo, necesita ser aprobada.';
          $link = 'admin/cotizacion/visita/aprobacion_orden_trabajo';

          $this->notification($designations_id, $link, $message);
          $type = "success";
          $message = 'Registro Exitoso';
        } else {
          $type = "error";
          $message = 'Registro Fallido';
        }
      } else {
        $type = "error";
        $message = 'No se Anexo archivo.  ';
      }




      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    } else if ($form == 'aprobacion_orden_trabajo') {
      $data['accion'] = $this->action_cot($form);
      if ($this->input->post('aprobar')) {
        $data['valor_accion'] = 'Orden de trabajo aprobada';
        $data['status'] = 1;
        $data_cot['area_actual'] = 10; //AREA_USUARIA
        $data_cot['accion'] = $this->action_cot($form) + 1;
      } else {
        $data_cot['area_actual'] = 10;
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'No se aprobo la Orden de trabajo';
      }
      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data['document'] = $ruta;



      /* DETALLE */
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detail = $this->cotizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($id_detail) {
        //ACTUALIZANDO LA COTIZACION CABECERA
        $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_id';
        $this->cotizacion_model->save($data_cot, $id);


        $designations_id = $data_cot['area_actual'];
        $message = 'Se aprobo Orden de trabajo, generar conformidad';
        $link = 'admin/cotizacion/visita/conformidad_servicio';
        $this->notification($designations_id, $link, $message);
        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "success";
        $message = 'Registro Fallido';
      }

      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    } else if ($form == 'conformidad_servicio') {
      $data['accion'] = $this->action_cot($form);
      if ($this->input->post('aprobar')) {
        $data['valor_accion'] = 'Conformmidad de servicio';
        $data['status'] = 1;
        $data_cot['area_actual'] = 3; //administracion
        $data_cot['accion'] = $this->action_cot($form) + 1;
      } else {
        $data_cot['area_actual'] = 10;
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'No conforme con servicio';
      }
      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data['document'] = $ruta;



      /* DETALLE */
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detail = $this->cotizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($id_detail) {
        //ACTUALIZANDO LA COTIZACION CABECERA
        $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_id';
        $this->cotizacion_model->save($data_cot, $id);


        $designations_id = $data_cot['area_actual'];
        $message = 'Conforme con el servicio, Subir el comprobante de pago';
        $link = 'admin/cotizacion/visita/comprobante_pago_administracion';
        $this->notification($designations_id, $link, $message);
        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "success";
        $message = 'Registro Fallido';
      }

      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    } else if ($form == 'comprobante_pago_administracion') {
      $data['accion'] = $this->action_cot($form);
      $data['valor_accion'] = 'Comprobante de pago subido';
      $data['status'] = 1;
      $data_cot['area_actual'] = 1; //comercial
      $data_cot['accion'] = $this->action_cot($form) + 1;

      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];

        $data['document'] = $ruta;

        /* REGISTRANDO DETALLE */
        $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
        $id_detail = $this->cotizacion_model->save($data, NULL);

        /* ADD NOTIFICATION */
        if ($id_detail) {
          // ACTUALIZANDO LA COTIZACION CABECERA
          $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
          $this->cotizacion_model->_order_by = 'cotizacion_id';
          $this->cotizacion_model->_primary_key = 'cotizacion_id';
          $this->cotizacion_model->save($data_cot, $id);

          $designations_id = $data_cot['area_actual']; // ES ID DE COMERCIAL
          $message = 'Se subio el comprobante de pago. Culminar el proceso';
          $link = 'admin/cotizacion/visita/emision_orden_trabajo';
          $this->notification($designations_id, $link, $message);
          $type = "success";
          $message = 'Registro Exitoso';
        } else {
          $type = "error";
          $message = 'Registro Fallido';
        }
      } else {
        $type = "error";
        $message = 'No se Anexo archivo.  ';
      }




      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    } else if ($form == 'cierre_orden') {
      $data['accion'] = $this->action_cot($form);
      if ($this->input->post('aprobar')) {
        $data['valor_accion'] = 'Cierre de orden';
        $data['status'] = 1;
        $data_cot['status'] = 2; //APROBADA
        $data_cot['area_actual'] = 10; //area_usuaria
        $data_cot['accion'] = $this->action_cot($form) + 1;
      } else {
        $data_cot['area_actual'] = 10;
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'No culmino el proceso de la orden';
      }
      $data['cotizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/cotizaciones/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data['document'] = $ruta;



      /* DETALLE */
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id_detail = $this->cotizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($id_detail) {
        //ACTUALIZANDO LA COTIZACION CABECERA
        $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_id';
        $this->cotizacion_model->save($data_cot, $id);


        $designations_id = $data_cot['area_actual'];
        $message = 'Proceso de la orden culminada con exito';
        $link = 'admin/cotizacion/';
        $this->notification($designations_id, $link, $message);
        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "success";
        $message = 'Registro Fallido';
      }

      set_message($type, $message);
      redirect('admin/cotizacion/visita/' . $form);
    }
  }
  public function detail(int $id = NULL)
  {
  }

  private function status_old($id = 1)
  {
    switch ($id) {
      case '0':
        $type = "danger";
        $text = "CANCELADO";
        break;

      case '1':
        $type = "info";
        $text = "EN PROCESO";
        break;

      case '2':
        $type = "success";
        $text = "APROBADO Y CULMINADO";
        break;

      default:
        $type = "danger";
        $text = "CANCELADO";
        break;
    }
    return '<h4><span class=" label label-' . $type . '">' . $text . '</span></h4>';
  }


  private function action_cot($action = NULL)
  {
    $actions = [
      1    => 'ingresar',
      2    => 'aprobacion',
      3    => 'valoracion',
      4    => 'emision_orden_visita_tecnica',
      5    => 'constancia_visita_tecnica',
      6    => 'emision_valoracion_servicio',
      7    => 'aprobacion_valoracion_servicio',
      71   => 'valoracion_servicio_aprobada',
      8    => 'emision_cotizacion',
      9    => 'aprobacion_cotizacion',
      10   => 'orden_compra',
      11   => 'factura_comprobante_pago',
      12   => 'emision_orden_trabajo',
      /* 13 => 'emision_orden_trabajo', */
      13   => 'aprobacion_orden_trabajo',
      /* 14   => 'orden_trabajo', */
      14   => 'conformidad_servicio',
      15   => 'comprobante_pago_administracion',
      16   => 'cierre_orden',
    ];
    if (is_int($action)) {
      return $actions[$action];
    } else {
      return array_search($action, $actions);
    }
    /*
    switch ($action) {
      case 'ingresar':
        $id =  1;
        break;
      case 'aprobacion':
        $id =  2;
        break;
      case 'valoracion':
        $id =  3;
        break;
      case 'emision_orden_visita_tecnica':
        $id =  4;
        break;
      case 'constancia_visita_tecnica': // lo puede visualizar comercial y administracion (ver y descargar el arvchivo)
        $id =  5;
        break;
      case 'emision_valoracion_servicio':
        $id =  6;
        break;
      case 'aprobacion_valoracion_servicio':
        $id =  7;
        break;
      case 'valoracion_servicio_aprobada':
        $id =  7;
        break;
      default:
        $id = 1;
        break;
    }
    return $id; */
  }

  public function detail_list($id = NULL)
  {
    $data['title'] = ('Detalle del Documento - Seguimiento');
    $data['page']  = 'Detalle del Documento - Seguimiento';
    $data['id']    = $id;
    $data['cotizacion_info'] = $this->db->where('cotizacion_id', $id)->get('tbl_cotizaciones')->row();

    $data['detail'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizacion_detail')->result();

    $data['subview'] = $this->load->view('admin/cotizaciones/detail', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }


  /* FORMA DE PAGO  */
  public function forma_pago($id = NULL, $adelanto = NULL)
  {
    if (!empty($id) && ($id == 1 || $id == 2)) {
      $data['partes'] = 3; // si se desea qingrese lacantidaddepartes mas adelante.
      $data['adelanto'] = $adelanto;
      // $data['subview'] = $this->load->view( 'admin/cotizaciones/forma_pago/forma_'.$id );
      $this->load->view('admin/cotizaciones/forma_pago/index', $data);
    }
  }

  // TABLA => VIGENCIA

  public function pdf($id)
  {

    $data['title'] = 'PDF';
    $this->db->select('ser.*,cotp.*,cl.razon_social,cl.ruc,cl.direccion_legal,cl.correo as cor_cl,cl.celular as cel_cl,cot.*,se.*');
    $this->db->from('tbl_cotizaciones cot');
    $this->db->join('tbl_cliente cl', 'cl.cliente_id = cot.cliente_id');
    $this->db->join('tbl_sedes se', 'se.sede_id = cot.sede_id');
    $this->db->join('tbl_services ser', 'ser.service_id = cot.service_id');
    $this->db->join('tbl_cotizacion_pago cotp', 'cotp.cotizacion_id = cot.cotizacion_id');

    //$data = $this->db->where(['cot.cotizacion_id' => $id])->get()->row();
    //$data['pagos']=$this->db->where(['cotp.cotizacion_id' => $id])->get('tbl_cotizacion_pago')->result();
    $info = $this->db->where(['cot.cotizacion_id' => $id])->get()->row();
    $data['info'] = $info;
    $data['service'] = $this->db->where(['service_id' => $info->service_id])->get('tbl_services')->row();

    $data['pagos'] = $this->db->where(['cotizacion_id' => $data['info']->cotizacion_id])->get('tbl_cotizacion_pago')->result();

    /*
    echo "<pre>";
      print_r($data['pagos']);
      print_r($data['info']);
    echo "</pre>";
    die();
    */

    $html = $this->load->view('admin/cotizaciones/pdf', $data, TRUE);
    aQ_pdf_create($html, 'ejemploPDF', TRUE);
  }
  public function ot_pdf($id)
  {

    //$data['title'] = 'PDF';    
    // $this->db->select('cot.cotizacion_id,cot.service_id,cot.cliente_id,cot.sede_id,cot.fecha,cot.fecha_vigencia,cot.monto as monto_tot_cot,cot.accion,cot.valorizacion_id,cot.adelanto,se.*,cli.razon_social,cli.ruc,cli.direccion_legal,cli.correo as cor_cl,cli.celular as cel_cl,ser.*, cotot.*');
    $this->db->select('cotot.*, se.sede, cli.ruc, cli.razon_social, ser.service, de.designations, ac.fullname ');
    $this->db->from('tbl_cotizacion_ot cotot');
    $this->db->join('tbl_cotizaciones cot', 'cot.cotizacion_id = cotot.cotizacion_id');
    $this->db->join('tbl_sedes se', 'se.sede_id = cot.sede_id');
    $this->db->join('tbl_cliente cli', 'cli.cliente_id = cot.cliente_id');
    $this->db->join('tbl_services ser', 'ser.service_id = cot.service_id');
    $this->db->join('tbl_designations de', 'de.designations_id = cotot.area_asignada');
    $this->db->join('tbl_account_details ac', 'cotot.user_id = ac.user_id');
    /* $this->db->join('tbl_cotizacion_pago cotp', 'cotp.cotizacion_id = cotot.cotizacion_id'); */
    $data['info'] = $this->db->where(['cotot.cotizacion_ot_id' => $id])->get()->row();

    $data['pagos'] = $this->db->where(['cotizacion_id' => $data['info']->cotizacion_id])->get('tbl_cotizacion_pago')->result();


    /*
    echo "<pre>";
      print_r($data['info']);
    echo "</pre>";
    die();
    */


    $html = $this->load->view('admin/cotizaciones/ot_pdf', $data, TRUE);
    aQ_pdf_create($html, 'ejemploPDF', TRUE);
  }
}

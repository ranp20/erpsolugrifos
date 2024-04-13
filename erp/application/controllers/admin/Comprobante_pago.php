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
class Comprobante_pago extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('admin_model');
    $this->load->model('cotizacion_model');
    $this->load->helper('admin_helper');
  }
  public function index()
  {
    $data['title'] = 'Comprobantes de pagos';
    $data['page'] = 'Comprobantes de pagos';

    $data['subview'] = $this->load->view('admin/comprobante_pago/index', $data, TRUE);

    $this->load->view('admin/_layout_main', $data);
  }

  /*public function visita($action = NULL)
  {
    $uri = $this->uri->uri_string();
    $data['title'] = 'Cotizaciones';
    $data['page'] = 'Cotizaciones';
    $data['action'] = $action;
    $data['btn_add'] = false;

    $data['btn_add_cotizacion'] = ($action == 'emision_cotizacion') ? true : false;

    $data['subview'] = $this->load->view('admin/cotizaciones/index', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }*/

  public function add_comprobante_pago()
  {
    $data['title'] = ('Abjuntar comprobantes de pago');
    $data['all_comprobante_pago'] = $this->db->get('tbl_cotizacion_pago')->result_array();
    $data['subview'] = $this->load->view('admin/comprobante_pago/add_comprobante_pago', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  /*public function add_cotizacion_valorizacion($id)
  {
    $data['title'] = ('Nueva Cotizacion con Valorizacion');
    $data['valorizacion'] = true;
    $data_valorizacion = $this->db->where(['valorizacion_servicio_id' => $id])->get('tbl_valorizacion_servicio')->row();
    $data['data_valorizacion'] = $data_valorizacion;

    $data['data_cliente'] = $this->db->where(['cliente_id' => $data_valorizacion->cliente_id])->get('tbl_cliente')->row();
    $data['data_sede'] = $this->db->where(['sede_id' => $data_valorizacion->sede_id])->get('tbl_sedes')->row();

    $data['subview'] = $this->load->view('admin/cotizaciones/add_cotizacion', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }*/

  public function save_comprobante_pago($id = null)
  {

    $dir =  "./uploads/cotizaciones/comprobante_pago_administracion";
    if (!is_dir($dir)) {
      mkdir($dir, 0777);
    }
    $ruta = "";
    // if ($this->input->post('files')) {
    if ($this->guardar_archivo($dir, 'files')) {
      $data_upload = $this->upload->data();
      $ruta = $data_upload['file_name'];
    }

    // guardamos en db

    $data['adelanto'] = ($this->input->post('adelanto')) ? 1 : 0;

    $data['nombre']      = $this->input->post('nombre');
    $data['cliente_id']  = $this->input->post('cliente_id');
    $data['sede_id']     = $this->input->post('sede_id');
    $data['fecha']       = $this->input->post('fecha');
    $data['monto']       = $this->input->post('monto');
    $data['user_id']     = $_SESSION['user_id'];
    $data['ruta']        = $ruta;

    $data['area_inicio'] = $this->session->userdata('designations_id');
    $data['area_actual'] = 2;
    $data['accion']      = 2;

    if ($valorizacion_id = $this->input->post('valorizacion_id')) {
      $data['valorizacion_id'] = $valorizacion_id;
    }
    $this->db->insert('cotizaciones', $data);
    $id_cotizacion = $this->db->insert_id();
    if ($id_cotizacion) {

      $data_detail['accion'] = 1; //INGRESO DE COTIZACION
      $data_detail['valor_accion'] = 'INGRESADO';
      $data_detail['status'] = 1;
      $data_detail['cotizacion_id'] = $id_cotizacion;
      $data_detail['user_id'] = $this->session->userdata('user_id');
      $data_detail['designation_id'] = $this->session->userdata('designations_id');
      $data_detail['comment'] = ($this->input->post('observaciones')) ? $this->input->post('observaciones') : '';
      $data_detail['document'] = $ruta;
      // DETALLE
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id = $this->cotizacion_model->save($data_detail, NULL);

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
      $designations_id = 2; // ES ID DE GERENCIA
      $message = 'Nueva Cotizacion Ingresada, Necesita ser Aprobada';
      $link = 'admin/cotizacion/visita/aprobacion';
      $this->notification($designations_id, $link, $message);
    }
    $type = "success";
    $message = 'Registro Exitoso';
    set_message($type, $message);
    redirect('admin/cotizacion/');
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
  private function guardar_archivo($dir, $name)
  {
    $mi_archivo              = $name;
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


  public function ComprobantePagoList($actionURL = NULL, $type = null)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_cotizaciones';

      $this->datatables->column_search = array('tbl_cotizaciones.nombre');
      $this->datatables->column_order = array(' ', 'tbl_cotizaciones.nombre');
      $this->datatables->order = array('cotizacion_id' => 'desc');
      // get all invoice
      $where = array('tbl_cotizaciones.status >= ' => 22);
      if (!empty($type)) {
        $where = array('tbl_cotizaciones.cotizacion_id' => $type);
      } else {
        $where = null;
      }


      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        if ($document->status > 20) {
          $action = null;

          $sub_array = array();

          $sub_array[] = $document->cotizacion_id;


          $this->db->select('razon_social, ruc, direccion, sede');
          $this->db->from('tbl_cotizaciones cot');
          $this->db->join('tbl_cliente cli', 'cli.cliente_id = cot.cliente_id');
          $this->db->join('tbl_sedes sed', 'sed.sede_id = cot.sede_id');

          $data_area = $this->db->where(['cot.cotizacion_id' => $document->cotizacion_id])->get()->row();
          //if (count($data_area) > 0) {
          $sub_array[] = $data_area->razon_social . ' - ' . $data_area->ruc;
          $sub_array[] = $data_area->sede;


          $cotizacion_documento = '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE COTIZACIÓN" >
                                    <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'admin/cotizacion/pdf/' . $document->cotizacion_id . '">
                                        <span class="fa fa-file-pdf-o"></span>
                                    </a>
                                </span>';
          $sub_array[] = $cotizacion_documento;


          $upload_comprobantes = '<span data-placement="top" data-toggle="tooltip" title="COMPROBANTE DE PAGOS">
                                    <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" href="' . base_url() . 'admin/comprobante_pago/forms/comprobante_pago/' . $document->cotizacion_id . '">
                                        <i class="fa fa-upload"></i> C
                                    </a>
                                </span>';

          $upload_facturas = '<span data-placement="top" data-toggle="tooltip" title="FACTURAS">
                                <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" href="' . base_url() . 'admin/factura/form_facturas/' . $document->cotizacion_id . '">
                                <i class="fa fa-upload"></i> F
                                </a>
                            </span>';

          
          $detail_comprobante = '<span data-placement="top" data-toggle="tooltip" title="DETALLES DE PAGOS">
                                    <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" href="' . base_url() . 'admin/comprobante_pago/detail_comprobante/' . $document->cotizacion_id . '">
                                        <i class="fa fa-list-alt"></i> D
                                    </a>
                                </span>';
          

          if ($this->session->userdata('designations_id') == 3) {

            //$action .=  ((($document->status == 22 && $document->accion == 'comprobante') || $document->status == 32) ? $upload_comprobantes . ' ' . $upload_facturas : $detail_comprobante . $upload_facturas);
            $action .= $detail_comprobante . ' ' . $upload_comprobantes . ' ' . $upload_facturas;
          }

          // COMERCIAL
          if ($this->session->userdata('designations_id') == 1) {
            $action .= $detail_comprobante;
          }

          $sub_array[] = $action;
          $data[] = $sub_array;
        }
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }


  public function detail_comprobante($id)
  {
    $data['title'] = 'DETALLE DE LOS PAGOS ';
    $data['page']  = 'DETALLE DE LOS PAGOS ';
    $data['id']    = $id;
    // $this->db->where('ruta IS NOT NULL');
    $data['comprobantes'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizacion_pago')->result();

    $cotizacion = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();
    $data['data_cotizacion'] = $cotizacion;

    $data['cliente'] = $this->db->where(['cliente_id' => $cotizacion->cliente_id])->get('tbl_cliente')->row()->razon_social;
    $data['sede'] = $this->db->where(['sede_id' => $cotizacion->sede_id])->get('tbl_sedes')->row()->direccion;
    $data['subview'] = $this->load->view('admin/comprobante_pago/detail', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function save_forms($form, $id)
  {
    if ($form == 'comprobante_pago') {

      /*
      echo "<pre>";
      print_r($_POST);
      echo "</pre>";
      print_r($_FILES);
      exit();
      */

      $data['accion'] = $this->action_cot($form);
      $data['valor_accion'] = 'Comprobante de pago subido';
      $data['status'] = 1;
      $data_cot['area_actual'] = 1; //comercial
      $data_cot['accion'] = $this->action_cot($form) + 1;

      $dir = './uploads/cotizaciones/comprobante_pago_administracion';
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $dir = './uploads/cotizaciones/comprobante_pago_administracion';
      foreach ($_POST['tmonto'] as $key => $monto) {
        $ruta = "";

        if ($this->guardar_archivo($dir, 'files_' . $key)) {
          $data_upload = $this->upload->data();

          $ruta = $data_upload['file_name'];
          $data_pagos['ruta'] = $ruta;
        }

        $fech = $_POST['tfecha'][$key];
        // $fech_v = $_POST['tfecha_vencimiento'][$key];
        $data_pagos['monto_upload'] = $monto;
        $data_pagos['fecha_comprobante'] = $fech;
        // $data_pagos['fecha_vencimiento'] = $fech_v;
        $data_pagos['banco'] = $_POST['banco'][$key];
        $data_pagos['numero_operacion'] = $_POST['numero_operacion'][$key];

        $this->cotizacion_model->_table_name = 'tbl_cotizacion_pago'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_pago_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_pago_id';
        $id_pago = $this->cotizacion_model->save($data_pagos, $key);

        $tipo_comprobante = $this->db->where(['cotizacion_pago_id' => $key])->get('tbl_cotizacion_pago')->row()->tipo_pago;

        $cotizacion = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();

        if ($tipo_comprobante == 1) {
          // VERIFICAMOS SI ES SUBIDA DE COMPROBANTE ADELANTO PARA MODIFICAR EL STATUS DE COTIZACION ASI CONTINUAR EL SIGUENTE PROCESO 
          if ($cotizacion->status == 22 && $cotizacion->accion == 'comprobante') {
            $not_link = 'admin/cotizacion/orden_trabajo';
            $not_designations_id = 1;
            $not_message = 'Comprobante Adelanto Subido, Emitir OT';
            $data_cotizacion = [
              'status' => 23,
              'accion' => 'OT',
              'area_actual' => 1
            ];
          }
          $data_coti_detail = [
            'user_id'           => $this->session->userdata('user_id'),
            'designations_id' => $this->session->userdata('designations_id'),
            'detail'            => 'Subio comprobante de adelanto',
            'cotizacion_id' => $id,
            'proceso'           => 'Comprobante adelanto',
            'proceso_id'        => $key,
            'status' => 1
          ];
        } elseif ($tipo_comprobante == 2) {
          $data_coti_detail = [
            'user_id'           => $this->session->userdata('user_id'),
            'designations_id' => $this->session->userdata('designations_id'),
            'detail'            => 'Subio comprobante de Pago',
            'cotizacion_id' => $id,
            'proceso'           => 'Comprobante Pago',
            'proceso_id'        => $key,
            'status' => 1
          ];
        }
        $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
        $id_detalle = $this->cotizacion_model->save($data_coti_detail, null);
        // } //FIN DE SUBIDA DE ARCHIVO
      }
      // VERIFICAMOS SI YA SE SUBIERON TODOS LOS COMPROBANTES ENTONCES PAAS PARA CULMINAR TODO EL PROCESO 
      $pendientes = count($this->db->where(['ruta' => null, 'cotizacion_id' => $id])->get('tbl_cotizacion_pago')->result());
      if ($pendientes == 0) {
        $not_link = 'admin/cotizacion';
        $not_designations_id = 1;
        $not_message = 'Comprobantes Subidos, Culminar Proceso';
        $data_cotizacion = [
          'status' => 33,
          'accion' => 'cierre',
          'area_actual' => 1
        ];
      }

      if (isset($data_cotizacion)) {
        $this->cotizacion_model->_table_name = 'tbl_cotizaciones'; //table name
        $this->cotizacion_model->_order_by = 'cotizacion_id';
        $this->cotizacion_model->_primary_key = 'cotizacion_id';
        $this->cotizacion_model->save($data_cotizacion, $id);

        $designations_id = $not_designations_id;
        $message = $not_message;
        $link = $not_link;
        $this->notification($designations_id, $link, $message);
      }
      if ($id_pago) {
        $type = "success";
        $message = 'Registro Exitoso';
      }
    } else {
      $type = "error";
      $message = 'No se Anexo archivo.  ';
    }


    set_message($type, $message);
    redirect('admin/comprobante_pago');
  }
  public function detail(int $id = NULL)
  {
  }

  private function status($id = 1)
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





  public function forms($form = NULL, $id)
  {
    if (isset($form) && $form != NULL) {
      $data['title'] = 'Comprobante de Pagos';
      $data['page']  = 'Comprobante de Pagos';
      $data['form']  = $form;
      $data['id']  = $id;
      $cotizacion        = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();
      $data['cotizacion'] = $cotizacion;
      $data['area_inicio'] = ($form == "comprobante_pago") ? $cotizacion->area_inicio : '';

      $data['adelanto_pago'] = ($form == 'comprobante_pago') ? $this->db->where(['cotizacion_id' => $id, 'tipo_pago' => 1])->get('tbl_cotizacion_pago')->result() : '';

      $data['comprobantes_pago'] = ($form == 'comprobante_pago') ? $this->db->where(['cotizacion_id' => $id, 'tipo_pago' => 2])->get('tbl_cotizacion_pago')->result() : '';
      $data['bancos'] = [
        'BBVA',
        'SCOTIABANK',
        'BCP',
        'BANCO DE LA NACION'
      ];

      $data['subview'] = $this->load->view('admin/comprobante_pago/' . $form, $data, FALSE);
      $this->load->view('admin/_layout_modal_extra_lg', $data);
    }
  }

  public function export_excel()
  {
    $this->load->library('excel');

    // STATUS 22 CUANDO YA ESTA ARA SUBIR COMPROBANTES
    $this->db->select('*');
    $this->db->from('tbl_cotizaciones cot');
    $this->db->join('tbl_cliente cli', 'cot.cliente_id = cli.cliente_id');
    $this->db->join('tbl_sedes se', 'cot.sede_id = se.sede_id');

    $cotizaciones = $this->db->where(['cot.status >= ' => 22])->get()->result();

    $style = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );

    // $sheet->getStyle("A1:B1")->applyFromArray($style);
    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('REPORTE DE PAGOS - CLIENTES');

    $this->excel->getActiveSheet()->setCellValue('A1', 'REPORTE DE PAGOS DE CLIENTES');
    $this->excel->getActiveSheet()->mergeCells('A1:I1');
    $this->excel->getActiveSheet()->getStyle('A1:I3')->applyFromArray($style);;

    $this->excel->getActiveSheet()->setCellValue('A2', 'ITEM');
    $this->excel->getActiveSheet()->mergeCells('A2:A3');

    $this->excel->getActiveSheet()->setCellValue('B2', 'CLIENTE');
    $this->excel->getActiveSheet()->mergeCells('B2:B3');

    $this->excel->getActiveSheet()->setCellValue('C2', 'SEDE');
    $this->excel->getActiveSheet()->mergeCells('C2:C3');

    $this->excel->getActiveSheet()->setCellValue('D2', 'MONTO');
    $this->excel->getActiveSheet()->mergeCells('D2:D3');

    $this->excel->getActiveSheet()->setCellValue('E2', 'ADELANTO');
    $this->excel->getActiveSheet()->mergeCells('E2:E3');

    $this->excel->getActiveSheet()->setCellValue('F2', 'PAGOS');
    $this->excel->getActiveSheet()->mergeCells('F2:I2');

    $this->excel->getActiveSheet()->setCellValue('F3', 'ADELANTO');
    $this->excel->getActiveSheet()->setCellValue('G3', 'PAGO 1');
    $this->excel->getActiveSheet()->setCellValue('H3', 'PAGO 2');
    $this->excel->getActiveSheet()->setCellValue('I3', 'PAGO 3');

    $this->excel->getActiveSheet()->getStyle('A2:D2')->getFont()->setSize(11);
    $this->excel->getActiveSheet()->getStyle('A1:I3')->getFont()->setBold(true);

    $row = 4;
    foreach ($cotizaciones as $key => $cotizacion) {
      $this->excel->getActiveSheet()->setCellValue('A' . $row, $key + 1);
      $this->excel->getActiveSheet()->setCellValue('B' . $row, $cotizacion->razon_social);
      $this->excel->getActiveSheet()->setCellValue('C' . $row, $cotizacion->sede);
      $this->excel->getActiveSheet()->setCellValue('D' . $row, $cotizacion->monto);
      $adelanto = $cotizacion->adelanto;
      $adelantoText = ($adelanto == 1) ? 'SI' : 'NO';
      $this->excel->getActiveSheet()->setCellValue('E' . $row, $adelantoText);

      $pagos = $this->db->where(['cotizacion_id' => $cotizacion->cotizacion_id])->get('tbl_cotizacion_pago')->result();
      //       print_r( $pagos );
      // die();

      $letras = [6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I'];
      $item = ($adelanto == 1) ? 6 : 7;
      foreach ($pagos as $key => $pago) {
        $this->excel->getActiveSheet()->setCellValue($letras[$item] . $row, ($pago->monto_upload > 0) ? 'PAGO' : 'DEBE');
        $item += 1;
        // echo $letra;
      }
      // die();

      $row += 1;
    }

    foreach (range('A', 'I') as $columnID) {
      $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    // die();
    // $this->excel->getActiveSheet()->mergeCells('A1:D1');

    $nameFile = 'CLIENTES PAGOS' . date('d-m-Y');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $nameFile . '.xls"');
    header('Cache-Control: max-age=0'); //no cache
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

    // Forzamos a la descarga         
    $objWriter->save('php://output');
  }
  public function report()
  {
    $data['title'] = 'Comprobante de Pagos';
    $data['page']  = 'Comprobante de Pagos';

    $data['dt_buttons'] = (in_array($this->session->userdata('designations_id'), [1, 2, 3])) ? TRUE : FALSE;
    $data['subview'] = $this->load->view('admin/comprobante_pago/report', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function reportList()
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->select = 'cp.*, cli.razon_social, cli.ruc, se.sede, fa.num_factura';
      $this->datatables->table = 'tbl_cotizacion_pago cp';
      $this->datatables->join_table = array('tbl_cotizaciones cot', 'tbl_cliente cli', 'tbl_sedes se', 'tbl_facturas fa');/*/*  */
      $this->datatables->join_where = array('cp.cotizacion_id = cot.cotizacion_id', 'cot.cliente_id = cli.cliente_id', 'cot.sede_id = se.sede_id', 'fa.cotizacion_pago_id = cp.cotizacion_pago_id');

      $this->datatables->column_search = array('cot.cotizacion_id', 'fa.num_factura', 'cli.ruc', 'cli.razon_social');
      $this->datatables->column_order = array('cot.cotizacion_id', 'fa.num_factura', 'cli.ruc', 'cli.razon_social');
      $this->datatables->order = array('cp.fecha_comprobante' => 'desc');
      $where = ['cp.ruta != ' => NULL];
      // get all invoice
      // $where = array('tbl_cotizaciones.status >= ' => 22);
      // if (!empty($type)) {
      //   $where = array('tbl_cotizaciones.cotizacion_id' => $type);
      // } else {
      //   $where = null;
      // }



      $fetch_data = make_datatables($where);
      /* echo "<pre>";
      print_r( $fetch_data );
      echo "</pre>";
      die(); */
      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        $action = null;


        $sub_array = array();



        $sub_array[] = $document->ruc . ' - ' . $document->razon_social;
        $sub_array[] = $document->sede;
        $sub_array[] = $document->cotizacion_id;
        $sub_array[] = $document->fecha_comprobante;
        $sub_array[] = display_money($document->monto_upload);
        $sub_array[] = $document->banco;
        $sub_array[] = $document->numero_operacion;
        $sub_array[] = $document->num_factura;

        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }
}

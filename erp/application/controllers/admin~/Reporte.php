<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Reporte extends Admin_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('cliente_model');
    $this->load->helper('dompdf_helper');
  }
  public function index()
  {
    redirect('admin/reporte/cliente/');
  }
  public function cliente(int $id = NULL)
  {
    ($id) ? $this->db->where(['cliente_id' => $id]) : '';

    $info = ($id) ? $this->db->get('tbl_cliente')->row() : $this->db->get('tbl_cliente')->result();
    $data['title'] = ($id) ? 'Cotizaciones de ' . $info->ruc . ' - ' . $info->razon_social : 'Listado de clientes';

    // $data['info'] = ($id) ? $this->db->where(['cliente_id' => $id])->get('tbl_cotizaciones')->result() : $info;
    $data['info'] =  $info;

    if ($id && count($data['info']) <= 0) :
      $type = "error";
      $message = 'No cuenta con Cotizaciones';
      set_message($type, $message);
      redirect('admin/reporte/cliente');
    endif;

    $data['subview'] = $this->load->view('admin/reporte/' . (($id) ? 'cotizaciones' : 'clientes'), $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function clienteList($type = null)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_cliente cli';
      $this->datatables->column_search = array('cli.ruc', 'cli.roazon_social');
      $this->datatables->column_order = array(' ', 'cli.razon_social', 'cli.ruc');
      $this->datatables->order = array('cli.cliente_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = null;
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);

      $data = array();
      foreach ($fetch_data as $_key => $client) {
        $action = null;

        $sub_array = array();

        $sub_array[] = $client->cliente_id;
        $sub_array[] = $client->ruc;
        $sub_array[] = $client->razon_social;
        $sub_array[] = $client->representante_legal;




        $action .= '<a class="btn btn-primary bg-green btn-xs"  title="VER CAOTIZACIONES DE CLIENTE " href="' . base_url() . 'admin/reporte/cliente/' . $client->cliente_id . '"><span class="fa fa-eye"></span></a>' . ' ';



        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  public function CotizacionList($cliente_id)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->select = 'se.sede, ser.service, co.*';
      $this->datatables->table = 'tbl_cotizaciones co';
      $this->datatables->join_table = array('tbl_sedes se', 'tbl_services ser');
      $this->datatables->join_where = array('co.sede_id = se.sede_id', 'co.service_id = ser.service_id');
      $this->datatables->column_search = array('co.cotizacion_id');
      $this->datatables->column_order = array(' ', 'co.cotizacion_id');
      $this->datatables->order = array('co.cotizacion_id' => 'desc');
      // get all invoice
      $where = array('co.cliente_id' => $cliente_id);
      /* if (!empty($type)) {
        $where .= array('co.status' => $type);
      } else {
        $where .= null;
      } */



      $fetch_data = make_datatables($where);
      /* print_r($fetch_data);
die(); */
      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        $action = null;


        $sub_array = array();


        //ojo
        $sub_array[] = $document->cotizacion_id;
        $sub_array[] = $document->service;


        $sub_array[] = $document->sede;

        $this->db->select('deptname, designations');
        $this->db->from('tbl_designations ds');
        $this->db->join('tbl_departments dp', 'ds.departments_id = dp.departments_id');

        $data_area = $this->db->where(['ds.designations_id' => $document->area_actual])->get()->row();

        $sub_array[] = display_money($document->monto);


        // JALAMOS LA VALORIZACION NOMBRE SI EXISTE

        $sub_array[] = $this->status_cot($document->status);



        $cotizacion_documento = '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE COTIZACIÓN" >
        <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'admin/reporte/cotizacion_pdf/' . $document->cotizacion_id . '"><span class="fa fa-file-pdf-o"></span></a>
        </span>';


        $action .= $cotizacion_documento;

        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  private function status_cot($id = 1)
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

  public function cotizacion_pdf(int $id)
  {
    $data['cotizacion'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();
    $data['pagos'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizacion_pago')->result();
    $data['ot'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizacion_ot')->result();
    $data['status'] = $this->status_cot($data['cotizacion']->status);
    $html = $this->load->view('admin/reporte/cotizacion_pdf', $data, TRUE);
   /*  echo "<pre>";
    print_r($data);
    echo "</pre>";
    die(); */
    aQ_pdf_create($html, 'COTIZACION_DETALLE', TRUE);
  }
}

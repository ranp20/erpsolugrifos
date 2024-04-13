<?php

use Twilio\TwiML\Messaging\Redirect;

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
/**
 * Description of admistrator
 *
 * @author aQMiGuEL <aquinproyectos@gmail.com>
 */
class Factura extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('admin_model');
    $this->load->model('factura_model');
    $this->load->helper('admin_helper');
  }
  public function index()
  {
    $data['title'] = 'Facturas Emitidas';
    $data['page'] = 'Facturas Emitidas';
    $data['dt_buttons'] = ( in_array( $this->session->userdata('designations_id'), [1,2,3] ) ) ? TRUE : FALSE;
    $data['subview'] = $this->load->view('admin/factura/index', $data, TRUE);

    $this->load->view('admin/_layout_main', $data);
  }

  public function form_facturas(int $id)
  {
    $data['title'] = 'Factura';
    $data['page']  = 'Factura';
    $data['id']  = $id;
    $cotizacion        = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizaciones')->row();
    $data['cotizacion'] = $cotizacion;

    $data['pagos'] = $this->db->where(['cotizacion_id' => $id])->get('tbl_cotizacion_pago')->result();
    $data['tipo_ingreso'] = $this->db->get('tbl_tipo_ingreso')->result();

    $data['subview'] = $this->load->view('admin/factura/form_facturas', $data, FALSE);
    $this->load->view('admin/_layout_modal_extra_lg', $data);
  }

  public function save_factura(int $id = null)
  {
    /*
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "<br>";
    print_r($_FILES);
    exit();
    */
    
    (!$this->input->post()) ? redirect('admin/comprobante_pago') : '';

    $dir =  "./uploads/cotizaciones/facturas/";
    if (!is_dir($dir)) {
      mkdir($dir, 0777);
    }
    $ruta = "";

    foreach ($_POST['numero_factura'] as $key => $factura) :
      $data = [
        'num_factura' => $_POST['numero_factura'][$key],
        'fecha_emision' => $_POST['fecha_emision'][$key],
        'fecha_vencimiento' => $_POST['fecha_vencimiento'][$key],
        'descripcion' => $_POST['descripcion'][$key],
        'monto' => $_POST['monto'][$key],
        'cotizacion_pago_id' => $key,
        'tipo_ingreso_id' => $_POST['tipo_ingreso_id'][$key],
      ];
      if (!empty($_FILES['files_' . $key]['name'])) {
        $val = $this->factura_model->aQuploadImage('files_' . $key, $dir);
        $val == TRUE || redirect('dmin/comprobante_pago');
        $data['ruta'] = $val['path'];
      }
      if (
        $_POST['numero_factura'][$key] &&
        $_POST['fecha_emision'][$key] &&
        $_POST['monto'][$key]
      ) {
        $this->factura_model->_table_name = 'tbl_facturas';
        $this->factura_model->_order_by = 'factura_id';
        $this->factura_model->_primary_key = 'factura_id';
        $this->factura_model->save($data, ($id = $_POST['id'][$key]) ? $id : NULL);
      }
    endforeach;
    $type = "success";
    $message = 'Registro Exitoso';
    set_message($type, $message);
    redirect("admin/comprobante_pago");
  }


  public function FacturaList($actionURL = NULL, $type = null)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->select = '*, f.monto, f.ruta, f.fecha_emision, f.fecha_vencimiento, f.descripcion';
      $this->datatables->table = 'tbl_facturas f';
      $this->datatables->join_table = array('tbl_cotizacion_pago cp', 'tbl_cotizaciones c', 'tbl_cliente cli', 'tbl_sedes se', 'tbl_services ser', 'tbl_tipo_ingreso ti');
      $this->datatables->join_where = array('f.cotizacion_pago_id = cp.cotizacion_pago_id', 'cp.cotizacion_id = c.cotizacion_id', 'c.cliente_id = cli.cliente_id', 'c.sede_id = se.sede_id', 'c.service_id = ser.service_id', 'f.tipo_ingreso_id = ti.tipo_ingreso_id');

      $this->datatables->column_search = array('f.numero');
      $this->datatables->column_order = array(' ', 'f.numero');
      $this->datatables->order = array('f.factura_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        // $where = array('f.cotizacion_id' => $type);
        $where = '';
      } else {
        $where = null;
      }



      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $factura) {

        $action = '';
        $sub_array = array();
        $sub_array[] = $factura->factura_id;

        $sub_array[] = $factura->num_factura;
        $sub_array[] = $factura->ruc;
        $sub_array[] = $factura->razon_social;
        $sub_array[] = $factura->sede;
        $sub_array[] = display_money($factura->monto);
        $sub_array[] = $factura->descripcion;
        $sub_array[] = $factura->fecha_emision;
        $sub_array[] = $factura->fecha_vencimiento;
        $dias_vencido = '-';
        $data_cotizacion_pago = $this->db->where(['cotizacion_pago_id' => $factura->cotizacion_pago_id])->get('tbl_cotizacion_pago')->row();
        if ($data_cotizacion_pago->ruta) :
          $status = 2;
        else :
          $dias_vencido = '-';
          $fecha_actual = strtotime(date('Y-m-d'), time());
          $fecha_vencimiento = strtotime($factura->fecha_vencimiento);
          if ($fecha_actual > $fecha_vencimiento) :
            // Calculando dias vencido
            $fecha_actual = new DateTime(date('Y-m-d'));
            $fecha_vencimiento = new DateTime($factura->fecha_vencimiento);
            $dif = $fecha_actual->diff($fecha_vencimiento);
            $dias_vencido = $dif->days;

          endif;
          if ($dias_vencido > 0) :
            $status = 0;
          else :
            $status = 1;
          endif;
        endif;

        $sub_array[] = $dias_vencido;

        $sub_array[] = $this->status($status);
        $sub_array[] = $factura->tipo_ingreso_name;
        

        //$upload_comprobante = '<span data-placement="top" data-toggle="tooltip" title="COMPROBANTE DE PAGOS"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" href="' . base_url() . 'admin/factura/add_comprobante/' . $factura->factura_id . '"><i class="fa fa-upload"></i></a></span>';
        
        // $action .= ($this->session->userdata('designations_id') == 3 && $dias_vencido > 0 ) ? $upload_comprobante : '';
        
        $factura_pdforfiledoc = $factura->ruta;
        
        if($factura_pdforfiledoc != "" && $factura_pdforfiledoc != null){
            $factura_documento = '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE FACTURA" >
                            <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . $factura_pdforfiledoc . '">
                                <span class="fa fa-file-pdf-o"></span>
                            </a>
                        </span>';   
        }

        $action = $factura_documento;

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
        $text = "VENCIDA";
        break;

      case '1':
        $type = "info";
        $text = "EMITIDA";
        break;

      case '2':
        $type = "success";
        $text = "CERRADA";
        break;

      default:
        $type = "danger";
        $text = "NULL";
        break;
    }
    return '<h4><span class=" label label-' . $type . '">' . $text . '</span></h4>';
  }



  public function add_comprobante($factura_id)
  {
    $this->factura_model->_table_name = 'tbl_facturas';
    $this->factura_model->_order_by = 'factura_id';
    $this->factura_model->_primary_key = 'factura_id';
    $factura = $this->factura_model->get($factura_id);

    $this->factura_model->_table_name = 'tbl_cotizacion_pago';
    $this->factura_model->_order_by = 'cotizacion_pago_id';
    $this->factura_model->_primary_key = 'cotizacion_pago_id';
    $cotizacion_pago = $this->factura_model->get($factura->cotizacion_pago_id);
    $data['title'] = "Registrar comprobante de Factura N°: " . $factura->num_factura . '<br> Cotizacion N° ' . $cotizacion_pago->cotizacion_id;

    $data['form'] = 'comprobante_pago';
    $data['id'] = $cotizacion_pago->cotizacion_id;

    $data['cotizacion_pago'] = $cotizacion_pago;
    $data['cotizacion'] = $this->db->where( 'cotizacion_id', $cotizacion_pago->cotizacion_id )->get('tbl_cotizaciones')->row();
    $this->load->view('admin/factura/add_comprobante', $data);
  }
}

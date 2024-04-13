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
  }
  public function index()
  {
    $data['title'] = 'Cotizaciones';
    $data['page'] = 'Cotizaciones';
    $data['btn_add'] = true;
    $data['subview'] = $this->load->view('admin/cotizaciones/index', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function visita($action = NULL)
  {
    $uri = $this->uri->uri_string(); //la ruta de acceso // VERIFICAR PARA DEJAR PASAR O DENEGAR EL PEMISO 
    $data['title'] = 'Cotizaciones';
    $data['page'] = 'Cotizaciones';
    $data['action'] = $action;
    $data['btn_add'] = false;

    $data['subview'] = $this->load->view('admin/cotizaciones/index', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function add_cotizacion()
  {
    $data['title'] = ('Nueva Cotizacion');
    $data['all_clients'] = $this->db->get('tbl_cliente')->result_array();
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
    if ($this->input->post('files')) {
      $data_upload = $this->guardar_archivo($dir);
      $ruta = $data_upload['file_name'];
    }

    // guardamos en db
    $data = [
      'nombre'      => $this->input->post('nombre'),
      'cliente_id'  => $this->input->post('cliente_id'),
      'sede_id'     => $this->input->post('sede_id'),
      'fecha'       => $this->input->post('fecha'),
      'monto'       => $this->input->post('monto'),
      'user_id'     => $_SESSION['user_id'],
      'ruta'        => $ruta,

      'area_inicio' => $this->session->userdata('designations_id'),
      'area_actual' => 2,
      'accion'      => 2
    ];

    $this->db->insert('cotizaciones', $data);
    $id = $this->db->insert_id();
    if ($id) {

      $data_detail['accion'] = 1; //INGRESO DE COTIZACION
      $data_detail['valor_accion'] = 'INGRESADO';
      $data_detail['status'] = 1;
      $data_detail['cotizacion_id'] = $id;
      $data_detail['user_id'] = $this->session->userdata('user_id');
      $data_detail['designation_id'] = $this->session->userdata('designations_id');
      $data_detail['comment'] = ($this->input->post('observaciones')) ? $this->input->post('observaciones') : '';
      $data_detail['document'] = $ruta;
      /* DETALLE */
      $this->cotizacion_model->_table_name = 'tbl_cotizacion_detail'; //table name
      $this->cotizacion_model->_order_by = 'cotizacion_detail_id';
      $this->cotizacion_model->_primary_key = 'cotizacion_detail_id';
      $id = $this->cotizacion_model->save($data_detail, NULL);


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

    if (!$this->upload->do_upload($mi_archivo)) {
      //*** ocurrio un error
      $data['uploadError'] = $this->upload->display_errors();
      echo $this->upload->display_errors();
      die();
      return;
    }

    return ($dataUpload = $this->upload->data());
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
        $where = array('tbl_cotizaciones.cotizacion_id' => $type);
      } else {
        $where = null;
      }

      //el 10 es el id de la sub area    AREA USUARIA QUE ES LA QUE INGRESA LA COTIAZCION Y VISUALIZA EL PROCESO 
      if ($this->session->userdata('designations_id') != 10 || ($this->session->userdata('designations_id') == '10' && !empty($actionURL))) {
        $where = array('tbl_cotizaciones.area_actual' => $this->session->userdata('designations_id'));
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
        $sub_array[] = $sede->direccion;
        $sub_array[] = $document->monto;
        $sub_array[] = $document->fecha;

        $this->db->select('deptname, designations');
        $this->db->from('tbl_designations ds');
        $this->db->join('tbl_departments dp', 'ds.departments_id = dp.departments_id');

        $data_area = $this->db->where(['ds.designations_id' => $document->area_actual])->get()->row();

        $sub_array[] = $data_area->deptname . ' - ' . $data_area->designations;
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

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="SUBIR CONTANCIA DE VISITA TECNICA" href="' . base_url() . 'admin/cotizacion/forms/constancia_visita_tecnica/' . $document->cotizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          }else{
            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DETALLE DEL DOCUMENTO" href="' . base_url() . 'admin/cotizacion/detail_list/' . $document->cotizacion_id . '"><span class="fa fa-list"></span></a>' . ' ';
          }
        } elseif ($document->status == 0) {

          if (empty($actionURL)) {
            $action .= 'cancelado';
          }
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

  public function forms($form = NULL, $id)
  {
    if (isset($form) && $form != NULL) {
      $data['title'] = ('Derivando Cotizacion');
      $data['page'] = ('Derivando Cotizacion');
      $data['form'] = $form;
      $data['id'] = $id;

      $data['subview'] = $this->load->view('admin/cotizaciones/form_' . $form, $data, FALSE);
      $this->load->view('admin/_layout_modal', $data);
    }
  }

  private function add_cotizacion_detail($accion, $valor_accion, $status = 1, $cotizacion_id, $comment = '', $document = '')
  {
  }
  public function save_forms($form, $id)
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
      if ($this->input->post('files')) {
        $data_upload = $this->guardar_archivo($dir);
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
      if ($this->input->post('files')) {
        $data_upload = $this->guardar_archivo($dir);
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
      if ($this->input->post('files')) {
        $data_upload = $this->guardar_archivo($dir);
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
    }
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
        $type = "success";
        $text = "EN PROCESO";
        break;

      case '2':
        $type = "success";
        $text = "APROBADO";
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
    return $id;
  }

  public function detail_list($id = NULL)
  {
    $data['title'] = ('Detalle del Documento - Seguimiento');
    $data['page']  = 'Detalle del Documento - Seguimiento';
    $data['id']    = $id;
    
    $data['detail'] = $this->db->where( ['cotizacion_id' => $id ] )->get( 'tbl_cotizacion_detail' )->result();

    $data['subview'] = $this->load->view('admin/cotizaciones/detail', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
}

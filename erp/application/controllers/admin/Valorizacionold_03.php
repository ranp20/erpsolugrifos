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
class Valorizacion extends Admin_Controller
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
    $data['title'] = 'Valorizaciones';
    $data['page'] = 'Valorizaciones';
    $data['btn_add'] = true;

    $data['subview'] = $this->load->view('admin/valorizacion/index', $data, TRUE);
    //$data['subview'] = $this->load->view('admin/valorizacion/list_emision_orden_visita', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function visita($action = NULL)
  {
    $uri = $this->uri->uri_string(); //la ruta de acceso // VERIFICAR PARA DEJAR PASAR O DENEGAR EL PEMISO 
    $data['title'] = 'Valorizaciones';
    $data['page'] = 'Valorizaciones';
    $data['action'] = $action;
    $data['btn_add'] = false;

    $data['subview'] = $this->load->view('admin/valorizacion/index', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function visita_emision($action = NULL)
  {
    $uri = $this->uri->uri_string(); //la ruta de acceso // VERIFICAR PARA DEJAR PASAR O DENEGAR EL PEMISO 
    $data['title'] = 'Valorizaciones';
    $data['page'] = 'Valorizaciones';
    $data['action'] = $action;
    $data['btn_add'] = false;

    //$data['btn_add_cotizacion'] = ($action == 'emision_cotizacion') ? true : false;
    $data['subview'] = $this->load->view('admin/valorizacion/list_emision_orden_visita', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function add_valorizacion()
  {
    $data['title'] = ('Nueva Cotizacion');
    $data['all_clients'] = $this->db->get('tbl_cliente')->result_array();
    $data['subview'] = $this->load->view('admin/valorizacion/add_valorizacion', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  
  

  public function save_valorizacion($id = null)
  {

    $dir =  "./uploads/valorizaciones/";
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

    $this->db->insert('valorizacion', $data);
    $id = $this->db->insert_id();
    if ($id) {

      $data_detail['accion'] = 1; //INGRESO DE COTIZACION
      $data_detail['valor_accion'] = 'INGRESADO';
      $data_detail['status'] = 1;
      $data_detail['valorizacion_id'] = $id;
      $data_detail['user_id'] = $this->session->userdata('user_id');
      $data_detail['designation_id'] = $this->session->userdata('designations_id');
      $data_detail['comment'] = ($this->input->post('observaciones')) ? $this->input->post('observaciones') : '';
      $data_detail['document'] = $ruta;
      /* DETALLE */
      $this->valorizacion_model->_table_name = 'tbl_valorizacion_detail'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_detail_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_detail_id';
      $id = $this->valorizacion_model->save($data_detail, NULL);


      $designations_id = 2; // ES ID DE GERENCIA
      $message = 'Nueva valorizacion Ingresada, Necesita ser Aprobada';
      $link = 'admin/valorizacion/visita/aprobacion';
      $this->notification($designations_id, $link, $message);
    }
    $type = "success";
    $message = 'Registro Exitoso';
    set_message($type, $message);
    redirect('admin/valorizacion/');
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
  public function VisitasProgramadasList($actionURL = NULL, $type = null)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_emison_orden_visita_tecnica';

      $this->datatables->column_search = array('tbl_emison_orden_visita_tecnica.emison_orden_id');
      $this->datatables->column_order = array(' ', 'tbl_emison_orden_visita_tecnica.emison_orden_id');
      $this->datatables->order = array('emison_orden_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array('tbl_emison_orden_visita_tecnica.emison_orden_id' => $type);
      } else {
        $where = null;
      }

      //el 10 es el id de la sub area    AREA USUARIA QUE ES LA QUE INGRESA LA COTIAZCION Y VISUALIZA EL PROCESO 
      // if ($this->session->userdata('designations_id') != 10 || ($this->session->userdata('designations_id') == '10' && !empty($actionURL))) {
      

      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $document) {
        $action = null;


        $sub_array = array();


        $document =  $this->db->get_where("tbl_valorizacion", ['valorizacion_id' => $document->valorizacion_id])->row();
        $sub_array[] = $document->nombre;
    
        $cliente =  $this->db->get_where("tbl_cliente", ['cliente_id' => $document->cliente_id])->row();
        $sub_array[] = $cliente->ruc . ' - ' . $cliente->razon_social;

        $sede =  $this->db->get_where("tbl_sedes", ['sede_id' => $document->sede_id])->row();
        $sub_array[] = $sede->direccion;
        
        $sub_array[] = $document->monto;
       

        /*$fechas =  $this->db->get_where("tbl_emison_orden_visita_tecnica", ['emison_orden_id' => $document->emison_orden_id])->row();
        $sub_array[] = $fechas->fecha_inicio;
        $sub_array[] = $fechas->fecha_final;*/
        $this->db->select('fecha_inicio, fecha_final');
        $this->db->from('tbl_emison_orden_visita_tecnica emor');
        $this->db->join('tbl_valorizacion val', 'val.valorizacion_id = emor.valorizacion_id');
        $data_area = $this->db->where(['emor.valorizacion_id'=> $document->valorizacion_id])->get()->row();
        if( count( $data_area ) > 0  ){
          $sub_array[] = $data_area->fecha_inicio ;
          $sub_array[] = $data_area->fecha_final ;

        }


        
        $sub_array[] = $this->status($document->status);

        // STATUS ::: 1 EN PROCESO // 2 APROBADO // 3 =>CANCELADO
        if ($document->status == 1) {
          // $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DERIVAR COTIZACION" href="' . base_url() . 'admin/valorizacion/derivar/' . $document->valorizacion_id . '"><span class="fa fa-share"></span></a>' . ' ';
        }

        if (!empty($deleted)) {
          // $action .= '<span data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs delete-document" title="Click para eliminar " data-id="' . $document->valorizacion_id . '"><span class="fa fa-trash-o"></span></span>' . ' ';
        }
        // 


        $designation_id = $this->session->userdata('designations_id');
        // preguntar si la designatcion o area es la area actual para mostrar sus acciones a realizar
        // comparara tmbn la accion a realizar 

        if ($document->status == 1) {
          /* $action .= $this->action_cot( $actionURL );
          $action .= $this->action_cot( 2 ); */
          if ($actionURL == 'aprobacion') {


            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="APROBACION VALORACION " href="' . base_url() . 'admin/valorizacion/forms/aprobacion_valoracion_visita_tecnica/' . $document->valorizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
          } elseif ($actionURL == 'valoracion') {

            /*$action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="VALORACION DE VISITA TECNICA APROBADA " href="' . base_url() . 'admin/valorizacion/forms/valoracion_visita_tecnica_aprobada/' . $document->valorizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';*/
            if (!empty($document->ruta)) {
              $action .= '<a target="_blank"  class="btn btn-success btn-xs" title="DESCARGAR DOCUMENTO " href="' . base_url() . 'uploads/valorizaciones/' . $document->ruta . '"><span class="fa fa-download"></span></a>' . ' ';
            }
            
          } elseif ($actionURL == 'emision_orden_visita_tecnica') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMISION DE VISITA TECNICA" href="' . base_url() . 'admin/valorizacion/forms/emision_orden_visita_tecnica/' . $document->valorizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
          } elseif ($actionURL == 'constancia_visita_tecnica') {
//AHI QUEDA ::: ESTO ES PARA QUE EL AREA DE MANTENIMIENTO O LEGAL PUEDAN SUBIR LOS ARCHIVPS DE CONTANCIA  AHORA HAY MAS CREO 
				
				$deparment = $this->db->where(['designations_id' => $this->session->userdata('designations_id')])->get('tbl_designations')->row()->departments_id;
            
          }
        } elseif ($document->status == 0) {

          if (empty($actionURL)) {
            $action .= 'cancelado';
          }
        }else if( $document->status == 2){
          $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DETALLE DEL DOCUMENTO" href="' . base_url() . 'admin/valorizacion/detail_list/' . $document->valorizacion_id . '"><span class="fa fa-list"></span></a>' . ' ';
        }
		else if( $document->status == 3){
          $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DETALLE DEL DOCUMENTO" href="' . base_url() . 'admin/valorizacion/detail_list/' . $document->valorizacion_id . '"><span class="fa fa-list"></span></a>' . ' ';
        }

        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }










  }

  public function ValorizacionList($actionURL = NULL, $type = null)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_valorizacion';

      $this->datatables->column_search = array('tbl_valorizacion.nombre');
      $this->datatables->column_order = array(' ', 'tbl_valorizacion.nombre');
      $this->datatables->order = array('valorizacion_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = array('tbl_valorizacion.valorizacion_id' => $type);
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
          'tbl_valorizacion.accion' => $action_id
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
          // $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DERIVAR COTIZACION" href="' . base_url() . 'admin/valorizacion/derivar/' . $document->valorizacion_id . '"><span class="fa fa-share"></span></a>' . ' ';
        }

        if (!empty($deleted)) {
          // $action .= '<span data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs delete-document" title="Click para eliminar " data-id="' . $document->valorizacion_id . '"><span class="fa fa-trash-o"></span></span>' . ' ';
        }
        // 


        $designation_id = $this->session->userdata('designations_id');
        // preguntar si la designatcion o area es la area actual para mostrar sus acciones a realizar
        // comparara tmbn la accion a realizar 

        if ($document->status == 1) {
          /* $action .= $this->action_cot( $actionURL );
          $action .= $this->action_cot( 2 ); */
          if ($actionURL == 'aprobacion') {
// adiciono elboton  

            $action .= '<a target="_blank"  class="btn btn-success btn-xs" title="DESCARGAR DOCUMENTO " href="' . base_url() . 'uploads/valorizaciones/' . $document->ruta . '"><span class="fa fa-download"></span></a>' . ' ';

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="APROBACION VALORACION " href="' . base_url() . 'admin/valorizacion/forms/aprobacion_valoracion_visita_tecnica/' . $document->valorizacion_id . '"><span class="fa fa-check"></span></a>' . ' ';
          } elseif ($actionURL == 'valoracion') {

            /*$action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="VALORACION DE VISITA TECNICA APROBADA " href="' . base_url() . 'admin/valorizacion/forms/valoracion_visita_tecnica_aprobada/' . $document->valorizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';*/
            if (!empty($document->ruta)) {
              $action .= '<a target="_blank"  class="btn btn-success btn-xs" title="DESCARGAR DOCUMENTO " href="' . base_url() . 'uploads/valorizaciones/' . $document->ruta . '"><span class="fa fa-download"></span></a>' . ' ';
            }
            
          } elseif ($actionURL == 'emision_orden_visita_tecnica') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMISION DE VISITA TECNICA" href="' . base_url() . 'admin/valorizacion/forms/emision_orden_visita_tecnica/' . $document->valorizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
          } elseif ($actionURL == 'constancia_visita_tecnica') {
//AHI QUEDA ::: ESTO ES PARA QUE EL AREA DE MANTENIMIENTO O LEGAL PUEDAN SUBIR LOS ARCHIVPS DE CONTANCIA  AHORA HAY MAS CREO 
				
				$deparment = $this->db->where(['designations_id' => $this->session->userdata('designations_id')])->get('tbl_designations')->row()->departments_id;
            if ($deparment == '2') {
				
              $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="SUBIR CONTANCIA DE VISITA TECNICA" href="' . base_url() . 'admin/valorizacion/forms/constancia_visita_tecnica/' . $document->valorizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
            } elseif ($this->session->userdata('designations_id') == '1' || $this->session->userdata('designations_id') == '3') {
              // jalamos el archivo del detalle 
              $constancia_visita = $this->db->where(['valorizacion_id' => $document->valorizacion_id, 'accion' => 5])->get('valorizacion_detail')->row()->document;
              if ($constancia_visita) {
                $action .= '<a  target="_blank" href="' . base_url() . 'uploads/valorizaciones/constancia_visita_tecnica/' . $constancia_visita . '" title="DESCARGAR CONSTANCIA DE VISITA TECNICA"><span class="fa fa-download"></span></a>' . ' ';
              }
            }
          } elseif ($actionURL == 'emision_valoracion_servicio') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMISION VALORACION SERVICIO" href="' . base_url() . 'admin/valorizacion/forms/emision_valoracion_servicio/' . $document->valorizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
          } elseif ($actionURL == 'aprobacion_valoracion_servicio') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMISION VALORACION SERVICIO" href="' . base_url() . 'admin/valorizacion/forms/' . $actionURL . '/' . $document->valorizacion_id . '"><span class="fa fa-edit"></span></a>' . ' ';
          } elseif ($actionURL == 'emision_valorizacion') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="EMISION DE COTIZACION" href="' . base_url() . 'admin/valorizacion/forms/' . $actionURL . '/' . $document->valorizacion_id . '"><span class="fa fa-upload"></span></a>' . ' ';
          } elseif ($actionURL == 'aprobacion_valorizacion') {

            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="APROBAR COTIZACION" href="' . base_url() . 'admin/valorizacion/forms/' . $actionURL . '/' . $document->valorizacion_id . '"><span class="fa fa-check"></span></a>' . ' ';
          } elseif (empty($actionURL)) {
            $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DETALLE DEL DOCUMENTO" href="' . base_url() . 'admin/valorizacion/detail_list/' . $document->valorizacion_id . '"><span class="fa fa-list"></span></a>' . ' ';
          }
        } elseif ($document->status == 0) {

          if (empty($actionURL)) {
            $action .= 'cancelado';
          }
        }else if( $document->status == 2){
          $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DETALLE DEL DOCUMENTO" href="' . base_url() . 'admin/valorizacion/detail_list/' . $document->valorizacion_id . '"><span class="fa fa-list"></span></a>' . ' ';
        }
		else if( $document->status == 3){
          $action .= '<a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" title="DETALLE DEL DOCUMENTO" href="' . base_url() . 'admin/valorizacion/detail_list/' . $document->valorizacion_id . '"><span class="fa fa-list"></span></a>' . ' ';
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


      $data['subview'] = $this->load->view('admin/valorizacion/derivar_valorizacion_form', $data, FALSE);
      $this->load->view('admin/_layout_modal', $data);
    }
  }

  public function forms($form = NULL, $id)
  {
    if (isset($form) && $form != NULL) {
      $data['title'] = ('Derivando Cotizacion');
      $data['page'] = ('Derivando Cotizacion');
      $data['form'] = $form;
	  
	  $data['all_operativas'] = $this->db->where( ['departments_id' => 2]  )->get('tbl_designations')->result_object();
      $data['id'] = $id;

      $data['subview'] = $this->load->view('admin/valorizacion/form_' . $form, $data, FALSE);
      $this->load->view('admin/_layout_modal_extra_lg', $data);
    }
  }

  private function add_valorizacion_detail($accion, $valor_accion, $status = 1, $valorizacion_id, $comment = '', $document = '')
  {
  }
  public function save_forms($form, $id)
  {
    if ($form == 'aprobacion_valoracion_visita_tecnica') {
      $data['accion'] = 2; //aprovacion de visita 
      if ($this->input->post('apro_desa')==0) {
        $data['valor_accion'] = 'Aprobado';
        $data['status'] = 1;
        $data_cot['area_actual'] = 1;
        $data_cot['accion'] = 4;
      } else {
        $data_cot['area_actual'] = 10;
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'Cancelado';
      }
      $data['valorizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/valorizaciones/aprobaciones";
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
      $this->valorizacion_model->_table_name = 'tbl_valorizacion'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_id';
      $this->valorizacion_model->save($data_cot, $id);

      /* DETALLE */
      $this->valorizacion_model->_table_name = 'tbl_valorizacion_detail'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_detail_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_detail_id';
      $id = $this->valorizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      $designations_id = 1; // ES ID DE DMINISTRACION
      $message = 'Nueva Valorizacion Ingresada';
      $link = 'admin/valorizacion/visita_emision/emision_orden_visita_tecnica';
      $this->notification($designations_id, $link, $message);

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/valorizacion/visita/aprobacion');
    } else if ($form == 'valoracion_visita_tecnica_aprobada') {
      $data['accion'] = 3; //valoracion de visita tecnoca aprobada 
      if ($this->input->post('apro_desa')==0) {
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
      $data['valorizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/valorizaciones/valoraciones";
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
      $this->valorizacion_model->_table_name = 'tbl_valorizacion'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_id';
      $this->valorizacion_model->save($data_cot, $id);

      /* DETALLE */
      $this->valorizacion_model->_table_name = 'tbl_valorizacion_detail'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_detail_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_detail_id';
      $id = $this->valorizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($this->input->post('aprobar')) {
        $designations_id = 1; // ES ID DE DMINISTRACION
        $message = 'Nueva valorizacion Ingresada, Necesita emision de orden';
        $link = 'admin/valorizacion/visita/emision_orden_visita';
        $this->notification($designations_id, $link, $message);
      } else {
        $designations_id = 10; // ES ID DE area_usuaria
        $message = 'Valorizacion ha sido denegada la valoracion por el area de Comercio';
        $link = 'admin/valorizacion/';
        $this->notification($designations_id, $link, $message);
      }

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/valorizacion/visita/valoracion');
    } else if ($form == 'emision_orden_visita_tecnica') {
      $data['accion'] = 4; //valoracion de visita tecnoca aprobada 
      if ($this->input->post('apro_desa')==0) {
        $data['valor_accion'] = 'Aceptado el pase ';
        $data['status'] = 1;
        //$data_cot['area_actual'] = 10; // 1_> comercial
		    $data_cot['area_actual'] =$this->input->post('cliente_id'); // 1_> comercial
        $data_cot['accion'] = 5;
      } else {
        $data_cot['area_actual'] =$this->input->post('cliente_id'); //10 area_usuaria
		//$data_cot['area_actual'] = 10; //10 area_usuaria
        $data_cot['status'] = 3;
        $data['status'] = '0';
        $data['valor_accion'] = 'Denegado el pase';
      }
      $data['valorizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/valorizaciones/emision_orden_visita_tecnica";
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";

      $data_visita['valorizacion_id'] = $id;
      $data_visita['user_id'] = $this->session->userdata('user_id');
      $data_visita['designation_id'] = $this->session->userdata('designations_id');
      $data_visita['fecha_inicio'] = $this->input->post('fech_ini');
      $data_visita['fecha_final'] = $this->input->post('fech_fin');
      


      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data['document'] = $ruta;

      // GUARDANDO EL DETALLE Y ACTUALIZANDO LA COTIZACION CABECERA
      $this->valorizacion_model->_table_name = 'tbl_valorizacion'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_id';
      $this->valorizacion_model->save($data_cot, $id);

      // GUARDAMOS EN LA TABLA tbl_emison_orden_visita_tecnica
      $this->valorizacion_model->_table_name = 'tbl_emison_orden_visita_tecnica'; //table name
      $this->valorizacion_model->_order_by = 'emison_orden_id';
      $this->valorizacion_model->_primary_key = 'emison_orden_id';
      $id = $this->valorizacion_model->save($data_visita, NULL);

      //FIN DE tbl_emison_orden_visita_tecnica

      /* DETALLE */
      $this->valorizacion_model->_table_name = 'tbl_valorizacion_detail'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_detail_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_detail_id';
      $id = $this->valorizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($this->input->post('aprobar')) {
		$designations_id = $this->input->post('cliente_id'); // ES ID DE area_usuaria
        //$designations_id = 10; // ES ID DE area_usuaria
        $message = 'Subir constancia de visita tecnica';
        $link = 'admin/valorizacion/visita/constancia_visita_tecnica';
        $this->notification($designations_id, $link, $message);
      } else {
        $designations_id =$this->input->post('cliente_id'); // ES ID DE area_usuaria
		//$designations_id = 10; // ES ID DE area_usuaria
        $message = 'denego el area Comercial';
        $link = 'admin/valorizacion/';
        $this->notification($designations_id, $link, $message);
      }

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      //redirect('admin/valorizacion/visita/valoracion');
      redirect('admin/valorizacion/visita_emision/emision_orden_visita_tecnica');
    } else if ($form == 'constancia_visita_tecnica') {
      $data['accion'] = 5; //valoracion de visita tecnoca aprobada 

      $data['valorizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/valorizaciones/constancia_visita_tecnica";
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
        $this->valorizacion_model->_table_name = 'tbl_valorizacion'; //table name
        $this->valorizacion_model->_order_by = 'valorizacion_id';
        $this->valorizacion_model->_primary_key = 'valorizacion_id';
        $this->valorizacion_model->save($data_cot, $id);

        /* DETALLE */
        $this->valorizacion_model->_table_name = 'tbl_valorizacion_detail'; //table name
        $this->valorizacion_model->_order_by = 'valorizacion_detail_id';
        $this->valorizacion_model->_primary_key = 'valorizacion_detail_id';
        $id = $this->valorizacion_model->save($data, NULL);

        $designations_id = 10; // ES ID DE area_usuaria
        $message = 'Emitir valoracion servicio';
        $link = 'admin/valorizacion/visita/emision_valoracion_servicio';
        $this->notification($designations_id, $link, $message);

        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "danger";
        $message = 'Registro Fallido';
      }

      set_message($type, $message);
      redirect('admin/valorizacion/visita/constancia_visita_tecnica');
    } else if ($form == 'emision_valoracion_servicio') {
      $data['accion'] = $this->action_cot($form); //function regresa el id dde la accion


      $data['valor_accion'] = 'Emision de valoracion de servicio ';
      $data['status'] = 1; // correcto
      $data_cot['area_actual'] = 2; // 1_> comercial
      $data_cot['accion'] = 7;

      $data['valorizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');

      $dir =  "./uploads/valorizaciones/" . $form;
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
      $this->valorizacion_model->_table_name  = 'tbl_valorizacion'; //table name
      $this->valorizacion_model->_order_by    = 'valorizacion_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_id';
      $this->valorizacion_model->save($data_cot, $id);

      /* DETALLE */
      $this->valorizacion_model->_table_name  = 'tbl_valorizacion_detail'; //table name
      $this->valorizacion_model->_order_by    = 'valorizacion_detail_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_detail_id';
      $id                                   = $this->valorizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($id) {
        $designations_id = 2; // ES ID DE GERENCIA
        $message = 'Se emitio una valoracion de servicio, Necesita ser aprobada';
        $link = 'admin/valorizacion/visita/aprobacion_valoracion_servicio';
        $this->notification($designations_id, $link, $message);

        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "danger";
        $message = 'Registro Fallido';
      }


      set_message($type, $message);
      redirect('admin/valorizacion/visita/valoracion');
    } else if ($form == 'aprobacion_valoracion_servicio') {
      $data['accion'] = $this->action_cot($form);
      if ($this->input->post('apro_desa')==0) {
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
      $data['valorizacion_id'] = $id;
      $data['user_id'] = $this->session->userdata('user_id');
      $data['designation_id'] = $this->session->userdata('designations_id');
      $data['comment'] = $this->input->post('observaciones');
      $dir =  "./uploads/valorizaciones/" . $form;
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
      $this->valorizacion_model->_table_name = 'tbl_valorizacion'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_id';
      $this->valorizacion_model->save($data_cot, $id);

      /* DETALLE */
      $this->valorizacion_model->_table_name = 'tbl_valorizacion_detail'; //table name
      $this->valorizacion_model->_order_by = 'valorizacion_detail_id';
      $this->valorizacion_model->_primary_key = 'valorizacion_detail_id';
      $id = $this->valorizacion_model->save($data, NULL);

      /* ADD NOTIFICATION */
      if ($id) {
        $designations_id = 1; // ES ID DE COMERCIAL
        $message = 'Se aprobo Valoracion, Emitir Cotizacion';
        $link = 'admin/valorizacion/visita/emision_valorizacion';
        $this->notification($designations_id, $link, $message);
        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "success";
        $message = 'Registro Fallido';
      }


      set_message($type, $message);
      redirect('admin/valorizacion/visita/' . $form);
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
      /*9    => 'aprobacion_valorizacion',
      10   => 'orden_compra',
      11   => 'factura_comprobante_pago',
      12   => 'emision_orden_trabajo',
      13   => 'aprobacion_orden_trabajo',
      14   => 'conformidad_servicio',
      15   => 'comprobante_pago_administracion',
      16   => 'cierre_orden',*/
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

    $data['detail'] = $this->db->where(['valorizacion_id' => $id])->get('tbl_valorizacion_detail')->result();

    $data['subview'] = $this->load->view('admin/valorizacion/detail', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
}

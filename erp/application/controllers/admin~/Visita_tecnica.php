<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
/**
 * @author aQMiGuEL <email@email.com>
 */
class Visita_Tecnica extends Admin_Controller
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
    $data['title']   = 'Visita Ténica';
    $data['page']    = 'Visita Ténica';
    $data['btn_add'] = ($this->db->where(['designations_id'                        => $this->session->userdata('designations_id')])->get('tbl_designations')->row()->departments_id == 2) ? true : false;

    $data['subview'] = $this->load->view('admin/visita_tecnica/index', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function add_visita()
  {
    $data['title'] = ('Nueva Valorización de visita técnica');
    $data['all_clients'] = $this->db->get('tbl_cliente')->result_array();

    $data['services'] = $this->db->get('tbl_services')->result();

    $data['subview'] = $this->load->view('admin/visita_tecnica/add_visita', $data, FALSE);


    // $data['subview'] = $this->load->view('admin/visita_tecnica/add_visita', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function save_visita($id = null)
  {

    $dir =  "./uploads/visita_tecnica/";
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

    $this->db->insert('visita_tecnica', $data);
    $id_visita = $this->db->insert_id();
    if ($id_visita) {

      $data_detail = [
        'user_id'           => $this->session->userdata('user_id'),
        'detail'            => 'EMITIO VALORIZACION DE VISITA TECNICA',
        'visita_tecnica_id' => $id_visita,
        'proceso'           => 'emision',
        'proceso_id'        => $id_visita,
        'status' => 1,
        'designations_id' => $this->session->userdata('designations_id')
      ];


      /* DETALLE */
      $this->valorizacion_model->_table_name = 'tbl_visita_tecnica_detail'; //table name
      $this->valorizacion_model->_order_by = 'visita_tecnica_detail_id';
      $this->valorizacion_model->_primary_key = 'visita_tecnica_detail_id';
      $id = $this->valorizacion_model->save($data_detail, NULL);


      $designations_id = 2; // ES ID DE GERENCIA
      $message = 'Nueva valorizacion de visita tecnica Ingresada, Necesita ser Aprobada';
      $link = 'admin/visita_tecnica/';
      $this->notification($designations_id, $link, $message);
    }
    $type = "success";
    $message = 'Registro Exitoso';
    set_message($type, $message);
    redirect('admin/visita_tecnica/');
  }

  public function visita_tecnica_list($type = NULL)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->select = 'ser.service,  se.sede, se.direccion, cli.razon_social, vt.*';
      $this->datatables->table = 'tbl_visita_tecnica vt';
      $this->datatables->join_table = array('tbl_services ser', 'tbl_cliente cli', 'tbl_sedes se');

      $this->datatables->join_where = array('ser.service_id=vt.service_id', 'vt.cliente_id = cli.cliente_id', 'se.sede_id = vt.sede_id');

      $this->datatables->column_search = array('vt.numero', 'ser.service', 'vt.status');
      $this->datatables->column_order = array('vt.numero', 'ser.service', 'vt.status');
      $this->datatables->order = array('vt.numero' => 'desc');
      if (!empty($type)) {
        $where = array('vt.status' => $type);
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);

      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $visita) {
        $action = null;


        $sub_array = array();



        $sub_array[] = ($num = $visita->numero) ? $num : '--';

        $sub_array[] = $visita->service;


        $sub_array[] = $visita->razon_social;


        $sub_array[] = $visita->sede;

        $sub_array[] = $visita->monto;
        $sub_array[] = $visita->fecha;

        $data_area = $this->db->where(['designations_id' => $visita->area_actual])->get('tbl_designations')->row();
        $sub_array[] = $data_area->designations;
        $sub_array[] = $this->status($visita->status);
        $sub_array[] = $visita->proceso;


        $visita_documento = (isset($visita->ruta) && !empty($visita->ruta)) ? '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE VALORIZACION DE VISITA TECNICA" >
        <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'uploads/visita_tecnica/' . $visita->ruta . '"><span class="fa fa-download"></span></a>
        </span>' . ' ' : '';

        $form_aprobar_visita = '<span data-placement="top" data-toggle="tooltip" title="APROBACION VALORACION VISITA TECNICA">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/visita_tecnica/forms/aprobacion/' . $visita->visita_tecnica_id . '"><span class="fa fa-check"></span></a>
        </span>' . ' ';

        $form_orden_visita = '<span data-placement="top" data-toggle="tooltip" title="CREAR ORDEN DE VISITA TÉCNICA"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" href="' . base_url() . 'admin/visita_tecnica/forms/orden_visita/' . $visita->visita_tecnica_id . '"><i class="fa fa-upload"></i></a></span>' . ' ';

        // SI DESEA POR ORDEN AQUI FILTAR EL ID ORDEN 
        $form_constancia = '<span data-placement="top" data-toggle="tooltip" title="SUBIR CONSTANCIA DE VISITA TÉCNICA "><a data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"   href="' . base_url() . 'admin/visita_tecnica/forms/constancia_visita/' . $visita->visita_tecnica_id . '"><i class="fa fa-upload"></i></a></span>' . ' ';

        $orden_detail = '<span data-placement="top" data-toggle="tooltip" title="VER ORDEN DE VISITA TÉCNICA "><a  data-toggle="modal" data-target="#myModal"  class="btn btn-green btn-xs"  href="' . base_url() . 'admin/visita_tecnica/orden_detail/' . $visita->visita_tecnica_id . '"><span class="fa fa-eye"></span></a></span>' . ' ';

        $constancia = '<span data-placement="top" data-toggle="tooltip" title="VER CONSTANCIA DE VISITA TÉCNICA "><a  data-toggle="modal" data-target="#myModal"  class="btn btn-success btn-xs"  href="' . base_url() . 'admin/visita_tecnica/constancia_detail/' . $visita->visita_tecnica_id . '"><span class="fa fa-eye"></span></a></span>' . ' ';

        $detail = '<span data-placement="top" data-toggle="tooltip" title="RESUMEN VISITA TECNICA"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-purple btn-xs"  href="' . base_url() . 'admin/visita_tecnica/detail_list/' . $visita->visita_tecnica_id . '"><span class="fa fa-list-alt"></span></a></span>' . ' ';

        $data_department = $this->db->where(['designations_id' => $this->session->userdata('designations_id')])->get('tbl_designations')->row();

        // SI PERTENCEN A OPERATIVOS
        if ($data_department->departments_id == 2) {
          $action .= $detail;
        }
        if ($this->session->userdata('designations_id') == $visita->area_inicio) {
          $action .= $visita_documento;
          $action .= (in_array($visita->status, [12, 2])) ? $orden_detail : '';
          $action .= (in_array($visita->status, [12])) ? $form_constancia : '';
          $action .= (in_array($visita->status, [2])) ? $constancia : '';
        }

        // GERENCIA
        if ($this->session->userdata('designations_id') == 2) {
          $action .= $visita_documento;
          $action .= ($visita->status == 1) ? $form_aprobar_visita : $detail;
        }
        // ADMINISTRACION -> VIZUALIZA Y DESCARGA EL DOCUMENTO
        if ($this->session->userdata('designations_id') == 3) {

          $action .= $detail;
          $action .= (in_array($visita->status, [11, 12])) ? $visita_documento : '';
          $action .= (in_array($visita->status, [2])) ? $constancia : '';
        }
        // COMERCIAL -> DETALLE Y FORMULARIO PARA EMITIR ORDEN 
        if ($this->session->userdata('designations_id') == 1) {

          $action .= $detail;
          $action .= (in_array($visita->status, [11, 12, 2])) ? $visita_documento : '';
          $action .= ($visita->status == 11) ? $form_orden_visita : '';
          $action .= (in_array($visita->status, [12, 2])) ? $orden_detail : '';
          $action .= (in_array($visita->status, [2])) ? $constancia : '';
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
        // SI GERENCIA NO APRUEBA LA VALORIZACION DE VISITA TECNICA 
      case '10':
        $type = "danger";
        $text = 'NO APROBADO <i class="fa fa-ban"></i>';
        break;
        // SI ES APROBADA 
      case '11':
        $type = "info";
        $text = 'EN PROCESO - APROBADO <i class="fa fa-check-circle-o sm"></i>';
        break;

        // SI ES APROBADA Y YA ESTA SU ORDEN 
      case '12':
        $type = "primary";
        $text = 'EN PROCESO - CON ORDEN <i class="fa fa-check-circle-o"></i>';
        break;

        // CUANDO SUBE LA CONFORMIDAD DE LA VISITA TECNICA
      case '2':
        $type = "success";
        $text = "CERRADO";
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

    $data['detail'] = $this->db->where(['visita_tecnica_id' => $id])->get('tbl_visita_tecnica_detail')->result();

    $data['subview'] = $this->load->view('admin/visita_tecnica/detail', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function forms($form = NULL, $id)
  {
    if (isset($form) && $form != NULL) {
      $data['title'] = 'Visita Tecnica';
      $data['page'] = 'Visita Tecnica';
      $data['form'] = $form;
      $visita = $this->db->where(['visita_tecnica_id' => $id])->get('tbl_visita_tecnica')->row();
      $data['area_inicio'] = ($form == "orden_visita") ? $visita->area_inicio : '';
      $data['cliente'] = ($form == "orden_visita" || $form == "constancia_visita") ? $this->db->where(['cliente_id' => $visita->cliente_id])->get('tbl_cliente')->row()->razon_social : '';
      $data['sede'] = ($form == "orden_visita" || $form == "constancia_visita") ? $this->db->where(['sede_id' => $visita->sede_id])->get('tbl_sedes')->row()->sede  : '';

      // $data['all_operativas'] = $this->db->where(['departments_id' => 2])->get('tbl_designations')->result_object();
      $data['all_operativas'] = $this->db->where(['designations_id' => $visita->area_inicio])->get('tbl_designations')->result_object();
      $data['id'] = $id;

      $data['subview'] = $this->load->view('admin/visita_tecnica/form_' . $form, $data, FALSE);
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
        $this->db->select_max('numero');

        $numero = ($numero = $this->db->get('tbl_visita_tecnica')->row()->numero) ? $numero : 0;

        $data_detail = [
          'user_id'           => $this->session->userdata('user_id'),
          'detail'            => 'SE APROBÓ VALORIZACIÓN DE VISITA TÉCNICA',
          'visita_tecnica_id' => $id,
          'proceso'           => 'Aprobación',
          'proceso_id'        => $id,
          'status' => 1,
          'designations_id' => $this->session->userdata('designations_id'),
          'comentario' => $this->input->post('observaciones')
        ];

        // GENERAR EL NUMERO DE VISITA 
        // $numero = $this->db->select('if(numero>=0, max(numero),0)+1')->get('tbl_visita_tecnica');
        $data_visita = [
          'numero' => $numero + 1,
          'status' => 11,
          'area_actual' => 1,
          'proceso' => 'Subir Orden'
        ];
      } else {
        $data_detail = [
          'user_id'           => $this->session->userdata('user_id'),
          'detail'            => 'NO APROBÓ VALORIZACIÓN DE VISITA TÉCNICA',
          'visita_tecnica_id' => $id,
          'proceso'           => 'Aprobación',
          'proceso_id'        => $id,
          'status' => 0,
          'designations_id' => $this->session->userdata('designations_id'),
          'comentario' => $this->input->post('observaciones')
        ];

        // GENERAR EL NUMERO DE VISITA 
        // $numero = $this->db->select('if(numero>=0, max(numero),0)+1')->get('tbl_visita_tecnica');
        $data_visita = [
          'status' => 10,
          'proceso' => '--'
        ];
      }
      // ADD DETALLE DE VIISTA
      $this->valorizacion_model->_table_name = 'tbl_visita_tecnica_detail'; //table name
      $this->valorizacion_model->_order_by = 'visita_tecnica_detail_id';
      $this->valorizacion_model->_primary_key = 'visita_tecnica_detail_id';
      $id_detalle = $this->valorizacion_model->save($data_detail, NULL);


      // ACTUALIZAR LA VISITA TECNICA
      $this->valorizacion_model->_table_name = 'tbl_visita_tecnica'; //table name
      $this->valorizacion_model->_order_by = 'visita_tecnica_id';
      $this->valorizacion_model->_primary_key = 'visita_tecnica_id';
      $this->valorizacion_model->save($data_visita, $id);


      /* ADD NOTIFICATION */
      if ($this->input->post('aprobar')) {
        $designations_id = 1; // ES ID DE COMERCIAL
        $message = 'Valorizacion de visita técnica aprobada N°' . ($numero + 1) . ', Emitir Orden.';
        $link = 'admin/visita_tecnica/';
        $this->notification($designations_id, $link, $message);
      }
      /**===================================
       * ADD notificacion para el area administracion pueda descaragr el documento 
       * 
       ==================================*/
      $designations_id = 3; // ES ID DE ADMINISTRACION
      $message = 'Valorizacion visita técnica Aprobada';
      $link = 'admin/visita_tecnica/';
      $this->notification($designations_id, $link, $message);

      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      redirect('admin/visita_tecnica');
    } else if ($form == 'orden_visita') {
      $dir =  "./uploads/visita_tecnica/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data_orden = [
        'detail' => $this->input->post('detalle'),
        'fecha_visita' => $this->input->post('start_date'),
        'ruta' => $ruta,
        'visita_tecnica_id' => $id,
        'user_id' => $this->session->userdata('user_id'),
        'designation_id_from' => $this->session->userdata('designations_id'),
        'designation_id_to' => $this->input->post('area_inicio'),
        'status' => 1
      ];

      $data_visita = [
        'status' => 12,
        'area_actual' => $this->input->post('area_inicio'),
        'proceso' => 'Constancia'
      ];

      // ADD DETALLE DE VIISTA
      $this->valorizacion_model->_table_name = 'tbl_visita_tecnica_orden'; //table name
      $this->valorizacion_model->_order_by = 'visita_tecnica_orden_id';
      $this->valorizacion_model->_primary_key = 'visita_tecnica_orden_id';
      $id_orden = $this->valorizacion_model->save($data_orden, NULL);

      $data_detail = [
        'user_id'           => $this->session->userdata('user_id'),
        'detail'            => 'Genero Orden de Visita Técnica',
        'visita_tecnica_id' => $id,
        'proceso'           => 'Orden visita',
        'proceso_id'        => $id_orden,
        'status' => 1,
        'designations_id' => $this->session->userdata('designations_id'),
        'comentario' => $this->input->post('detalle')
      ];

      // ADD DETALLE DE VIISTA
      $this->valorizacion_model->_table_name = 'tbl_visita_tecnica_detail'; //table name
      $this->valorizacion_model->_order_by = 'visita_tecnica_detail_id';
      $this->valorizacion_model->_primary_key = 'visita_tecnica_detail_id';
      $id_detalle = $this->valorizacion_model->save($data_detail, NULL);


      // ACTUALIZAR LA VISITA TECNICA
      $this->valorizacion_model->_table_name = 'tbl_visita_tecnica'; //table name
      $this->valorizacion_model->_order_by = 'visita_tecnica_id';
      $this->valorizacion_model->_primary_key = 'visita_tecnica_id';
      $this->valorizacion_model->save($data_visita, $id);



      $designations_id = $this->input->post('area_inicio'); // ES ID DE area_usuaria

      $message = 'Subir constancia de visita tecnica';
      $link = 'admin/visita_tecnica/report_orden';
      $this->notification($designations_id, $link, $message);


      $type = "success";
      $message = 'Registro Exitoso';
      set_message($type, $message);
      //redirect('admin/valorizacion/visita/valoracion');
      redirect('admin/visita_tecnica');
    } else if ($form == 'constancia_visita') {

      $dir =  "./uploads/visita_tecnica/" . $form;
      if (!is_dir($dir)) {
        mkdir($dir, 0777);
      }
      $ruta = "";
      if ($this->guardar_archivo($dir)) {
        $data_upload = $this->upload->data();
        $ruta = $data_upload['file_name'];
      }
      $data_constancia = [
        'comment' => $this->input->post('observaciones'),
        'fecha_visita' => $this->input->post('start_date'),
        'ruta' => $ruta,
        'visita_tecnica_id' => $id,
        'user_id' => $this->session->userdata('user_id'),
        'designation_id' => $this->session->userdata('designations_id'),
        'status' => 1
      ];

      $data_visita = [
        'status' => 2,
        'area_actual' => $this->session->userdata('designations_id'),
        'proceso' => 'COMPLETO'
      ];

      // ADD DETALLE DE VIISTA
      $this->valorizacion_model->_table_name = 'tbl_visita_tecnica_constancia'; //table name
      $this->valorizacion_model->_order_by = 'visita_tecnica_constancia_id';
      $this->valorizacion_model->_primary_key = 'visita_tecnica_constancia_id';
      $id_constancia = $this->valorizacion_model->save($data_constancia, NULL);

      $data_detail = [
        'user_id'           => $this->session->userdata('user_id'),
        'detail'            => 'Subio Constancia de Visita Técnica',
        'visita_tecnica_id' => $id,
        'proceso'           => 'Constancia visita',
        'proceso_id'        => $id_constancia,
        'status' => 1,
        'designations_id' => $this->session->userdata('designations_id'),
        'comentario' => $this->input->post('observaciones')
      ];

      // ADD DETALLE DE VIISTA
      $this->valorizacion_model->_table_name = 'tbl_visita_tecnica_detail'; //table name
      $this->valorizacion_model->_order_by = 'visita_tecnica_detail_id';
      $this->valorizacion_model->_primary_key = 'visita_tecnica_detail_id';
      $id_detalle = $this->valorizacion_model->save($data_detail, NULL);


      // ACTUALIZAR LA VISITA TECNICA
      $this->valorizacion_model->_table_name = 'tbl_visita_tecnica'; //table name
      $this->valorizacion_model->_order_by = 'visita_tecnica_id';
      $this->valorizacion_model->_primary_key = 'visita_tecnica_id';
      $this->valorizacion_model->save($data_visita, $id);

      $type = 'success';
      $message = 'Registro Exitoso';

      set_message($type, $message);
      redirect('admin/visita_tecnica');
    }
  }

  public function orden_detail($visita_tecnica_id)
  {
    $data['title'] = 'DETALLE DE ORDEN DE VISITA TÉCNICA';
    $data['orden_detail'] = $this->db->where(['visita_tecnica_id' => $visita_tecnica_id])->get('tbl_visita_tecnica_orden')->row();
    $data_visita = $this->db->where(['visita_tecnica_id' => $visita_tecnica_id])->get('tbl_visita_tecnica')->row();
    $data['visita_tecnica_info'] = $data_visita;
    //jharol
    $data['service'] = $this->db->where(['service_id' => $data_visita->service_id])->get('tbl_services')->row()->service;
    //
    $data['cliente'] = $this->db->where(['cliente_id' => $data_visita->cliente_id])->get('tbl_cliente')->row()->razon_social;
    $data['sede'] = $this->db->where(['sede_id' => $data_visita->sede_id])->get('tbl_sedes')->row()->sede;
    $data['subview'] = $this->load->view('admin/visita_tecnica/detail_orden', $data, FALSE);
    /*echo "<pre>";
    print_r($data);
    echo "</pre>";
    die()
    */
    $this->load->view('admin/_layout_modal', $data);
  }

  public function constancia_detail($visita_tecnica_id)
  {
    $data['title'] = 'DETALLE DE CONSTANCIA DE VISITA TÉCNICA';
    $data['constancia_detail'] = $this->db->where(['visita_tecnica_id' => $visita_tecnica_id])->get('tbl_visita_tecnica_constancia')->row();
    $data_visita = $this->db->where(['visita_tecnica_id' => $visita_tecnica_id])->get('tbl_visita_tecnica')->row();
    $data['visita_tecnica_info'] = $data_visita;
    //jharol
    $data['service'] = $this->db->where(['service_id' => $data_visita->service_id])->get('tbl_services')->row()->service;
    //
    $data['cliente'] = $this->db->where(['cliente_id' => $data_visita->cliente_id])->get('tbl_cliente')->row()->razon_social;
    $data['sede'] = $this->db->where(['sede_id' => $data_visita->sede_id])->get('tbl_sedes')->row()->sede;
    $data['subview'] = $this->load->view('admin/visita_tecnica/detail_constancia', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }

  public function report_orden()
  {
    $data['title'] = 'REPORTE DE ORDENES DE VISITA';
    $data['subview'] = $this->load->view('admin/visita_tecnica/report_orden', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }
  public function report_orden_list($type = NULL)
  {

    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->select = 'ser.service,  se.sede, se.direccion, cli.razon_social, vto.*, vt.*';
      $this->datatables->table = 'tbl_visita_tecnica vt';
      $this->datatables->join_table = array('tbl_services ser', 'tbl_cliente cli', 'tbl_sedes se', 'tbl_visita_tecnica_orden vto');

      $this->datatables->join_where = array('ser.service_id=vt.service_id', 'vt.cliente_id = cli.cliente_id', 'se.sede_id = vt.sede_id', 'vt.visita_tecnica_id = vto.visita_tecnica_id');

      $this->datatables->column_search = array('ser.service', 'vt.status');
      $this->datatables->column_order = array('ser.service', 'vt.status');
      $this->datatables->order = array('vto.visita_tecnica_orden_id' => 'desc');
      if (!empty($type)) {
        $where = array('vt.status' => $type);
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);
      /* print_r( $fetch_data );
die(); */
      $data = array();
      $edited = true;
      $deleted = true;
      foreach ($fetch_data as $_key => $visita) {
        $action = null;


        $sub_array = array();



        $sub_array[] = $visita->visita_tecnica_orden_id;

        $sub_array[] = $visita->service;


        $sub_array[] = $visita->razon_social;


        $sub_array[] = $visita->sede;

        $sub_array[] = $visita->monto;
        $sub_array[] = $visita->fecha_visita;

        $data_area = $this->db->where(['designations_id' => $visita->area_actual])->get('tbl_designations')->row();
        $sub_array[] = $data_area->designations;

        $constancia_info = $this->db->where(['visita_tecnica_id' => $visita->visita_tecnica_id])->get('tbl_visita_tecnica_constancia')->row();

        if( $constancia_info ):
          $status = 1;
        else:
          if( $visita->fecha_visita < date('Y-m-d') ){
            $status = 0;
          }
          else{
            $status = 2;
          }
        endif;
        $sub_array[] = $this->status_orden($status);
        // $sub_array[] = $visita->proceso;
        // $sub_array[] = ( $constancia_info ) ? 'Y' : 'N';

        /* $visita_documento = (isset($visita->ruta) && !empty($visita->ruta)) ? '<span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE VALORIZACION DE VISITA TECNICA" >
        <a target="_blank"  class="btn btn-success btn-xs"  href="' . base_url() . 'uploads/visita_tecnica/' . $visita->ruta . '"><span class="fa fa-download"></span></a>
        </span>' . ' ' : ''; */

        /* $form_aprobar_visita = '<span data-placement="top" data-toggle="tooltip" title="APROBACION VALORACION VISITA TECNICA">
        <a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"  href="' . base_url() . 'admin/visita_tecnica/forms/aprobacion/' . $visita->visita_tecnica_id . '"><span class="fa fa-check"></span></a>
        </span>' . ' '; */

        /* $form_orden_visita = '<span data-placement="top" data-toggle="tooltip" title="CREAR ORDEN DE VISITA TÉCNICA"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs" href="' . base_url() . 'admin/visita_tecnica/forms/orden_visita/' . $visita->visita_tecnica_id . '"><i class="fa fa-upload"></i></a></span>' . ' '; */

        // SI DESEA POR ORDEN AQUI FILTAR EL ID ORDEN 
        $form_constancia = '<span data-placement="top" data-toggle="tooltip" title="SUBIR CONSTANCIA DE VISITA TÉCNICA "><a data-toggle="modal" data-target="#myModal"  class="btn btn-primary btn-xs"   href="' . base_url() . 'admin/visita_tecnica/forms/constancia_visita/' . $visita->visita_tecnica_id . '"><i class="fa fa-upload"></i></a></span>' . ' ';

        $orden_detail = '<span data-placement="top" data-toggle="tooltip" title="VER ORDEN DE VISITA TÉCNICA "><a  data-toggle="modal" data-target="#myModal"  class="btn btn-green btn-xs"  href="' . base_url() . 'admin/visita_tecnica/orden_detail/' . $visita->visita_tecnica_id . '"><span class="fa fa-eye"></span></a></span>' . ' ';

        $constancia = '<span data-placement="top" data-toggle="tooltip" title="VER CONSTANCIA DE VISITA TÉCNICA "><a  data-toggle="modal" data-target="#myModal"  class="btn btn-success btn-xs"  href="' . base_url() . 'admin/visita_tecnica/constancia_detail/' . $visita->visita_tecnica_id . '"><span class="fa fa-eye"></span></a></span>' . ' ';

        $detail = '<span data-placement="top" data-toggle="tooltip" title="RESUMEN VISITA TECNICA"><a  data-toggle="modal" data-target="#myModal"  class="btn btn-purple btn-xs"  href="' . base_url() . 'admin/visita_tecnica/detail_list/' . $visita->visita_tecnica_id . '"><span class="fa fa-list-alt"></span></a></span>' . ' ';

        $data_department = $this->db->where(['designations_id' => $this->session->userdata('designations_id')])->get('tbl_designations')->row();

        // SI PERTENCEN A OPERATIVOS
        if ($data_department->departments_id == 2) {
          $action .= $detail;
        }
        if ($this->session->userdata('designations_id') == $visita->area_inicio) {
          // $action .= $visita_documento;
          $action .= (in_array($visita->status, [12, 2])) ? $orden_detail : '';
          $action .= (in_array($visita->status, [12])) ? $form_constancia : '';
          $action .= (in_array($visita->status, [2])) ? $constancia : '';
        }

        // GERENCIA
        if ($this->session->userdata('designations_id') == 2) {
          // $action .= $visita_documento;
          // $action .= ($visita->status == 1) ? $form_aprobar_visita : $detail;
        }
        // ADMINISTRACION -> VIZUALIZA Y DESCARGA EL DOCUMENTO
        if ($this->session->userdata('designations_id') == 3) {

          $action .= $detail;
          // $action .= (in_array($visita->status, [11, 12])) ? $visita_documento : '';
          $action .= (in_array($visita->status, [2])) ? $constancia : '';
        }
        // COMERCIAL -> DETALLE Y FORMULARIO PARA EMITIR ORDEN 
        if ($this->session->userdata('designations_id') == 1) {

          $action .= $detail;
          // $action .= (in_array($visita->status, [11, 12, 2])) ? $visita_documento : '';
          // $action .= ($visita->status == 11) ? $form_orden_visita : '';
          $action .= (in_array($visita->status, [12, 2])) ? $orden_detail : '';
          $action .= (in_array($visita->status, [2])) ? $constancia : '';
        }
        /* $action .= (in_array($visita->status, [12])) ? $form_constancia : '';
        $action .= (in_array($visita->status, [12, 2])) ? $orden_detail : '';
        $action .= (in_array($visita->status, [2])) ? $constancia : ''; */

        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  private function status_orden($id = 1)
  {
    switch ($id) {
      case '0':
        $type = "danger";
        $text = "VENCIDO";
        break;

      case '1':
        $type = "success";
        $text = "REALIZADO";
        break;

      case '2':
        $type = "warning";
        $text = 'POR REALIZAR';
        break;
        
      default:
        $type = "danger";
        $text = "CANCELADO";
        break;
    }
    return '<h5><span class=" label label-xs label-' . $type . '">' . $text . '</span></h5>';
  }
  /* __________________________
 /_________________________*/
}

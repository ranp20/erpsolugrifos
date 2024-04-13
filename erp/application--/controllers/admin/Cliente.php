<?php

/**
 * [Client description]
 * @author MIGUEL AQUINO <miguel_28_9@hotmail.com>
 */
class Cliente extends Admin_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('cliente_model');
  }

  public function index()
  {
    $data['title'] = 'LISTA DE CLIENTES'; //Page title
    $data['page'] = 'cliente';


    $data['subview'] = $this->load->view('admin/cliente/manage_cliente', $data, TRUE);
    $this->load->view('admin/_layout_main', $data); //page load
  }

  public function clienteList($type = null)
  {
    if ($this->input->is_ajax_request()) {
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_cliente';
      $this->datatables->column_search = array('tbl_cliente.ruc', 'tbl_cliente.roazon_social');
      $this->datatables->column_order = array(' ', 'tbl_cliente.razon_social', 'tbl_cliente.ruc');
      $this->datatables->order = array('cliente_id' => 'desc');
      // get all invoice
      if (!empty($type)) {
        $where = null;
      } else {
        $where = null;
      }

      $fetch_data = make_datatables($where);

      $data = array();
      $edited = can_action('4', 'edited');
      $deleted = can_action('4', 'deleted');
      foreach ($fetch_data as $_key => $client) {
        $action = null;

        $sub_array = array();

        $sub_array[] = $client->ruc;
        $sub_array[] = $client->razon_social;
        $sub_array[] = $client->representante_legal;
        $sub_array[] = $client->gerente_legal;




        $action .= '<a class="btn btn-primary bg-green btn-xs" data-toggle="modal" data-target="#myModal" title="Editar Clente" href="' . base_url() . 'admin/cliente/change_pass/' . $client->cliente_id . '"><span class="fa fa-lock"></span></a>' . ' ';
        $action .= '<a class="btn btn-primary btn-xs" title="Editar Clente" href="' . base_url() . 'admin/cliente/add_cliente/' . $client->cliente_id . '"><span class="fa fa-pencil"></span></a>' . ' ';
        $action .= '<a data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs" title="Click to ' . lang("delete") . ' " href="' . base_url() . 'admin/cliente/delete_client/' . $client->cliente_id . '"><span class="fa fa-trash-o"></span></a>' . ' ';


        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }

  public function add_cliente($id = NULL)
  {
    $data['title'] = 'Registro de cliente';
    $data['page'] = 'cliente';
    if (!empty($id)) {
      $data['cliente_info'] =  $this->db->get_where('tbl_cliente', ['cliente_id' => $id])->row();
      $data['sedes'] = json_encode($this->db->get_where('tbl_sedes', ['cliente_id' => $id])->result_array());
    }
    $data['subview'] = $this->load->view('admin/cliente/add_cliente', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }

  public function change_pass($id = NULL)
  {
    $_SESSION['id_cliente_pass'] = $id;
    $data['title'] = 'Nueva Contraseña';
    $data['page'] = 'cliente';
    /* if (!empty($id)) {
      $data['cliente_info'] =  $this->db->get_where('tbl_cliente', ['cliente_id' => $id])->row();
      $data['sedes'] = json_encode($this->db->get_where('tbl_sedes', ['cliente_id' => $id])->result_array());
    } */
    $data['cliente_id'] = $id;
    $data['subview'] = $this->load->view('admin/cliente/form_change_pass', $data);
    $this->load->view('admin/_layout_modal', $data);
  }
  public function hash($string)
  {
    return hash('sha512', $string . config_item('encryption_key'));
  }
  public function set_password()
  {
    $user_id = $this->db->where(['company' => $_SESSION['id_cliente_pass']])->get('tbl_account_details')->row()->user_id;
    // die();
    /* $password = $this->hash($this->input->post('old_password', TRUE));
        $check_old_pass = $this->admin_model->check_by(array('password' => $password), 'tbl_users'); */

    if ($this->input->post('new_password') === $this->input->post('confirm_password')) {
      $data['password'] = $this->hash($this->input->post('new_password'));
      $this->cliente_model->_table_name = 'tbl_users';
      $this->cliente_model->_primary_key = 'user_id';
      $this->cliente_model->save($data, $user_id);
      $type = "success";
      $message = 'Contraseña Cambiada';
    } else {
      $type = "error";
      $message = 'Verifique la contraseña debe coincidir';
    }
    set_message($type, $message);
    redirect('admin/cliente'); //redirect page
  }
  public function save_cliente($id = NULL)
  {
    $created = true;
    $edited = true;
    if (!empty($created)) {
      // print_r($_POST);
      if (empty($id) && $this->db->where(['ruc' => $this->input->post('ruc')])->get('tbl_cliente')->row()) {
        set_message('error', 'Ruc ya esta registrada');
        redirect('admin/cliente');
      }
      $data['razon_social']        = $this->input->post('razon_social');
      $data['ruc']                 = $this->input->post('ruc');
      $data['direccion_legal']     = $this->input->post('direccion_legal');
      $data['distrito']            = $this->input->post('distrito');
      $data['provincia']           = $this->input->post('provincia');
      $data['representante_legal'] = $this->input->post('representante_legal');
      $data['dni_representante']   = $this->input->post('dni_representante');
      $data['gerente_legal']       = $this->input->post('gerente_legal');
      $data['dni_gerente']         = $this->input->post('dni_gerente');
      $data['supervisor']          = $this->input->post('supervisor');
      $data['correo']              = $this->input->post('correo');
      $data['celular']             = $this->input->post('celular');

      $sede = [];
      foreach ($_POST['direccion_sede'] as $key => $value) {
        // actualizara las sedes
        $sede[] = [
          'sede' => $_POST['sede'][$key],
          'direccion' => $_POST['direccion_sede'][$key],
          'distrito' => $_POST['distrito_sede'][$key],
          'provincia' => $_POST['provincia_sede'][$key],
          'correo' => $_POST['correo_sede'][$key],
          'celular' => $_POST['celular_sede'][$key],
          'administrador' => $_POST['administrador_sede'][$key],
          'administrador_sst' => $_POST['administrador_sst_sede'][$key]
        ];
      }

      $data['sede_operativa'] = json_encode($sede);
      
      $this->cliente_model->_table_name = 'tbl_cliente';
      $this->cliente_model->_primary_key = "cliente_id";
      $client_id = $this->cliente_model->save($data, $id);

      // AGREGANDO SEDES

      foreach ($_POST['direccion_sede_new'] as $key => $value) {
        // actualizara las sedes
        $permisos = [];
        foreach ($_POST['permisos_new_' . $key] as $keyp => $permiso) {
          $permisos[] = $permiso;
        }
        $data_sede = [
          'sede'         => strtoupper( $_POST['sede_new'][$key] ),
          'direccion'         => $_POST['direccion_sede_new'][$key],
          'distrito'          => $_POST['distrito_sede_new'][$key],
          'provincia'         => $_POST['provincia_sede_new'][$key],
          'correo'            => $_POST['correo_sede_new'][$key],
          'celular'           => $_POST['celular_sede_new'][$key],
          'administrador'     => $_POST['administrador_sede_new'][$key],
          'administrador_sst' => $_POST['administrador_sst_sede_new'][$key],
          'cliente_id'        => $client_id,
          'permission'        => json_encode($permisos)
        ];
        $this->cliente_model->_table_name = 'tbl_sedes';
        $this->cliente_model->_primary_key = "sede_id";
        $sedeId = NULL;
        $sede_id = $this->cliente_model->save($data_sede, $sedeId);
      }
      // ACTUALIZAMOS SEDES

      foreach ($_POST['direccion_sede'] as $key => $value) {
        // actualizara las sedes
        $permisos = [];
        foreach ($_POST['permisos_' . $key] as $keyp => $permiso) {
          $permisos[] = $permiso;
        }
        $data_sede = [
          'sede'         => strtoupper( $_POST['sede'][$key] ),
          'direccion'         => $_POST['direccion_sede'][$key],
          'distrito'          => $_POST['distrito_sede'][$key],
          'provincia'         => $_POST['provincia_sede'][$key],
          'correo'            => $_POST['correo_sede'][$key],
          'celular'           => $_POST['celular_sede'][$key],
          'administrador'     => $_POST['administrador_sede'][$key],
          'administrador_sst' => $_POST['administrador_sst_sede'][$key],
          'cliente_id'        => $client_id,
          'permission'        => json_encode($permisos)
        ];
        $this->cliente_model->_table_name = 'tbl_sedes';
        $this->cliente_model->_primary_key = "sede_id";
        $sedeId = $key;
        $sede_id = $this->cliente_model->save($data_sede, $sedeId);
      }


      if (empty($id)) :
        // VRIFICAR EL USUARIO DEPENDIENDO DEL tbl_account_details

        $user = $data['ruc'];
        $password = hash('sha512', $data['ruc'] . config_item('encryption_key'));

        $data_user['role_id'] = '2';
        $data_user['activated'] = '0';
        $data_user['username'] = $user;
        $data_user['password'] = $password;

        $this->db->insert('tbl_users', $data_user);

        $user_id = $this->db->insert_id();

        // REGISTRAMOS EL tbl_account_details
        $data_account['user_id'] = $user_id;
        $data_account['fullname'] = $data['razon_social'];
        $data_account['company'] = $client_id;
        $data_account['user_id'] = $user_id;
        $this->db->insert('tbl_account_details', $data_account);

        $account_id = $this->db->insert_id();

        $this->db->insert('tbl_client_role', ['user_id' => $user_id, 'menu_id' => 17]);
      endif;

      // messages for user
      if ($client_id) {
        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "error";
        $message = 'Registro Fallido';
      }
      set_message($type, $message);
      redirect('admin/cliente');
    }
  }
  public function update_cliente($id)
  {
    $created = true;
    $edited = true;
    if (!empty($edited)) {
      // print_r($_POST);
      if ($this->db->where(['ruc' => $this->input->post('ruc')])->get('tbl_cliente')->row()) {
        set_message('error', 'Ruc ya esta registrada');
        redirect('admin/cliente');
      }
      $data['razon_social']        = $this->input->post('razon_social');
      $data['ruc']                 = $this->input->post('ruc');
      $data['direccion_legal']     = $this->input->post('direccion_legal');
      $data['distrito']            = $this->input->post('distrito');
      $data['provincia']           = $this->input->post('provincia');
      $data['representante_legal'] = $this->input->post('representante_legal');
      $data['dni_representante']   = $this->input->post('dni_representante');
      $data['gerente_legal']       = $this->input->post('gerente_legal');
      $data['dni_gerente']         = $this->input->post('dni_gerente');
      $data['supervisor']          = $this->input->post('supervisor');
      $data['correo']              = $this->input->post('correo');
      $data['celular']             = $this->input->post('celular');

      $sede = [];
      foreach ($_POST['direccion_sede'] as $key => $value) {
        $sede[] = [
          'direccion' => $_POST['direccion_sede'][$key],
          'distrito' => $_POST['distrito_sede'][$key],
          'provincia' => $_POST['provincia_sede'][$key],
          'correo' => $_POST['correo_sede'][$key],
          'celular' => $_POST['celular_sede'][$key],
          'administrador' => $_POST['administrador_sede'][$key],
          'administrador_sst' => $_POST['administrador_sst_sede'][$key]
        ];
      }
      $data['sede_operativa'] = json_encode($sede);

      /* echo "<pre>";
      print_r($data);
      echo "</pre>";
      die(); */

      $this->cliente_model->_table_name = 'tbl_cliente';
      $this->cliente_model->_primary_key = "cliente_id";
      $client_id = $this->cliente_model->save($data, $id);

      // VRIFICAR EL USUARIO DEPENDIENDO DEL tbl_account_details

      $user = $data['ruc'];
      $password = hash('sha512', $data['ruc'] . config_item('encryption_key'));

      $data_user['role_id'] = '2';
      $data_user['activated'] = '1';
      $data_user['username'] = $user;
      $data_user['password'] = $password;

      $this->db->insert('tbl_users', $data_user);

      $user_id = $this->db->insert_id();

      // REGISTRAMOS EL tbl_account_details
      $data_account['user_id'] = $user_id;
      $data_account['fullname'] = $data['razon_social'];
      $data_account['company'] = $client_id;
      $data_account['user_id'] = $user_id;
      $this->db->insert('tbl_account_details', $data_account);

      $account_id = $this->db->insert_id();

      $this->db->insert('tbl_client_role', ['user_id' => $user_id, 'menu_id' => 17]);


      // messages for user
      if ($user_id && $client_id && $account_id) {
        $type = "success";
        $message = 'Registro Exitoso';
      } else {
        $type = "error";
        $message = 'Registro Fallido';
      }
      set_message($type, $message);
      redirect('admin/cliente');
    }
  }
}

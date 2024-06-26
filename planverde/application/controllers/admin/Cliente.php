<?php
class Cliente extends Admin_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('cliente_model');
    $this->load->model('user_model');
    $this->load->model('account_model');
    // $this->load->model('clientrole_model');
  }
  public function index(){
    $data['title'] = 'Clientes | PLAN VERDE';
    $data['page'] = 'Clientes';
    $data['subview'] = $this->load->view('admin/cliente/manage_cliente', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }
  // ------------------- MOSTRAR FORMULARIO DE CLIENTE
  public function add_cliente($id = NULL){
    $data['title'] = 'Agregar cliente';
    $data['page'] = 'cliente';
    if (!empty($id)){
      $data['title'] = 'Actualizar cliente';
      $data['cliente_info'] =  $this->db->get_where('tbl_cliente', ['cliente_id' => $id])->row();
      $data['sedes'] = json_encode($this->db->get_where('tbl_sedes', ['cliente_id' => $id])->result_array());
    }
    $data['subview'] = $this->load->view('admin/cliente/add_cliente', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }
  // ------------------- LISTAR CLIENTES
  public function clienteList($type = null){
    if ($this->input->is_ajax_request()){
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_cliente';
      $this->datatables->column_search = array(
        'tbl_cliente.razon_social',
        'tbl_cliente.ruc',
        'tbl_cliente.direccion_legal',
        'tbl_cliente.distrito',
        'tbl_cliente.provincia',
        'tbl_cliente.representante_legal');
      $this->datatables->column_order = array(' ', 'tbl_cliente.razon_social', 'tbl_cliente.ruc');
      $this->datatables->order = array('cliente_id' => 'desc');
      if(!empty($type)){
          $where = null;
      }else{
          $where = null;
      }
      $fetch_data = make_datatables($where);
      $data = array();
      $edited = can_action('4', 'edited');
      $deleted = can_action('4', 'deleted');
      foreach ($fetch_data as $_key => $client){
        $action = null;
        $sub_array = array();
        $sub_array[] = $client->ruc;
        $sub_array[] = $client->razon_social;
        $sub_array[] = ($client->representante_legal != "") ? $client->representante_legal : "No especificado";
        $sub_array[] = ($client->gerente_legal != "") ? $client->gerente_legal : "No especificado";
        $action .= '<a class="btn btn-primary bg-green btn-xs" data-toggle="modal" data-target="#myModal" title="Editar Clente" href="' . base_url() . 'admin/cliente/change_pass/' . $client->cliente_id . '"><span class="fa fa-lock"></span></a>' . ' ';
        $action .= '<a class="btn btn-info btn-xs" title="Editar Cliente" href="' . base_url() . 'admin/cliente/add_cliente/' . $client->cliente_id . '"><span class="fa fa-pencil"></span></a>' . ' ';
        $action .= '<button type="button" data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs" title="Click para Eliminar " onclick="deleteClient('.$client->cliente_id.')"><span class="fa fa-trash-o"></span></button>' . ' ';
        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    }else{
      redirect('admin/dashboard');
    }
  }
  public function change_pass($id = NULL){
    $_SESSION['id_cliente_pass'] = $id;
    $data['title'] = 'Nueva Contraseña';
    $data['page'] = 'cliente';
    if (!empty($id)){
    $data['cliente_info'] =  $this->db->get_where('tbl_cliente', ['cliente_id' => $id])->row();
    $data['sedes'] = json_encode($this->db->get_where('tbl_sedes', ['cliente_id' => $id])->result_array());
    }
    $data['cliente_id'] = $id;
    $data['subview'] = $this->load->view('admin/cliente/form_change_pass', $data);
    $this->load->view('admin/_layout_modal', $data);
  }
  public function hash($string){
    return hash('sha512', $string . config_item('encryption_key'));
  }
  public function set_password(){
    $user_id = $this->db->where(['company' => $_SESSION['id_cliente_pass'] ])->get('tbl_account_details')->row()->user_id;
    if($this->input->post('new_password') === $this->input->post('confirm_password')){
      $data['password'] = $this->hash($this->input->post('new_password'));
      $this->cliente_model->_table_name = 'tbl_users';
      $this->cliente_model->_primary_key = 'user_id';
      $this->cliente_model->save($data, $user_id);
      $type = "success";
      $message = 'Contraseña Cambiada';
    }else{
      $type = "error";
      $message = 'Verifique la contraseña debe coincidir';
    }
    set_message($type, $message);
    redirect('admin/cliente');
  }
  // ------------------- GUARDAR CLIENTE
  public function save_cliente($id = NULL){
    $created = (!empty($id) && $id != "") ? "update" : "insert";
    $edited = true;
    if($created == "insert"){
      // ------------- AGREGAR CLIENTE ------------- 
      if(empty($id) && $this->db->where(['ruc' => $this->input->post('ruc')])->get('tbl_cliente')->row()){
          set_message('error', 'Ruc ya esta registrada');
          redirect('admin/cliente');
      }else{
        // VALIDAR PARA QUE SE AGREGUE AL MENOS UNA SEDE ANTES DE GUARDAR EL CLIENTE...
        $this->load->library('session');
        $dataPost = $this->input->post();
        if(!isset($_POST['direccion_sede_new'])){
          $this->session->flashdata('cliente_info', $dataPost);
          set_message('error', 'Necesita <strong>agregar al menos una sede</strong> para guardar el cliente');
          redirect('admin/cliente/add_cliente');
        }else{
          $razon_social = $this->input->post('razon_social');
          $ruc = $this->input->post('ruc');
          $direccion_legal = $this->input->post('direccion_legal');
          $distrito = $this->input->post('distrito');
          $provincia = $this->input->post('provincia');
          $representante_legal = $this->input->post('representante_legal');
          $dni_representante = $this->input->post('dni_representante');
          $email_representante = $this->input->post('email_representante');
          $gerente_legal = $this->input->post('gerente_legal');
          $dni_gerente = $this->input->post('dni_gerente');
          $email_gerente = $this->input->post('email_gerente');

          $data['razon_social'] = (isset($razon_social) && $this->input->post('razon_social') != "") ? $this->input->post('razon_social') : '';
          $data['ruc'] = (isset($ruc) && $this->input->post('ruc') != "") ? $this->input->post('ruc') : '';
          $data['direccion_legal'] = (isset($direccion_legal) && $this->input->post('direccion_legal') != "") ? $this->input->post('direccion_legal') : '';
          $data['distrito'] = (isset($distrito) && $this->input->post('distrito') != "") ? $this->input->post('distrito') : '';
          $data['provincia'] = (isset($provincia) && $this->input->post('provincia') != "") ? $this->input->post('provincia') : '';
          $data['representante_legal'] = (isset($representante_legal) && $this->input->post('representante_legal') != "") ? $this->input->post('representante_legal') : '';
          $data['dni_representante'] = (isset($dni_representante) && $this->input->post('dni_representante') != "") ? $this->input->post('dni_representante') : '';
          $data['email_representante'] = (isset($email_representante) && $this->input->post('email_representante') != "") ? $this->input->post('email_representante') : '';
          $data['gerente_legal'] = (isset($gerente_legal) && $this->input->post('gerente_legal') != "") ? $this->input->post('gerente_legal') : '';
          $data['dni_gerente'] = (isset($dni_gerente) && $this->input->post('dni_gerente') != "") ? $this->input->post('dni_gerente') : '';
          $data['email_gerente'] = (isset($email_gerente) && $this->input->post('email_gerente') != "") ? $this->input->post('email_gerente') : '';
          $superv_collection = [];
          if(isset($_POST['superv_name']) || count($_POST['superv_name']) > 0){
            foreach($this->input->post('superv_name') as $key => $name){
              $superv_collection['superv_collection']['superv'][$key]['name'] = $name;
            }
          }
          if(isset($_POST['superv_email']) || count($_POST['superv_email']) > 0){
            foreach($this->input->post('superv_email') as $key => $email){
              $superv_collection['superv_collection']['superv'][$key]['email'] = $email;
            }
          }
          if(isset($_POST['superv_phone']) || count($_POST['superv_phone']) > 0){
            foreach($this->input->post('superv_phone') as $key => $phone){
              $superv_collection['superv_collection']['superv'][$key]['phone'] = $phone;
            }
          }
          $data['superv_collection'] = json_encode($superv_collection, TRUE);
          $sede = [];
          $data['sede_operativa'] = json_encode($sede);
          // INSERTAR EN 'tbl_cliente'...
          $this->cliente_model->_table_name = 'tbl_cliente';
          $this->cliente_model->_primary_key = "cliente_id";
          $client_id = $this->cliente_model->save($data, $id);

          // RECORRER LAS SEDES...
          foreach ($_POST['direccion_sede_new'] as $key => $value){
            $permisos = [];
            foreach( $_POST['permisos_new_'.$key] as $keyp => $permiso ){
              $permisos[] = $permiso;
            }
            $data_sede = [
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
            // INSERTAR EN 'tbl_sedes'...
            $this->cliente_model->_table_name = 'tbl_sedes';
            $this->cliente_model->_primary_key = "sede_id";
            $sedeId = NULL;
            $sede_id = $this->cliente_model->save($data_sede, $sedeId);
          }
          // INSERTAR EN tbl_users...
          $user = $data['ruc'];
          $password = hash('sha512', $data['ruc'] . config_item('encryption_key'));
          $data_user['client_id'] = $client_id; // PASAR EN ESTE CASO LA VARIABLE $client_id, YA QUE RECIÉN SE CREÓ EL USUARIO
          $data_user['role_id'] = '2';
          $data_user['activated'] = '1';
          $data_user['username'] = $user;
          $data_user['password'] = $password;
          $this->db->insert('tbl_users', $data_user);
          $user_id = $this->db->insert_id();
          // INSERTAR EN tbl_account_details...
          $data_account['user_id'] = $user_id;
          $data_account['fullname'] = $data['razon_social'];
          $data_account['company'] = $client_id;
          $data_account['user_id'] = $user_id;
          $this->db->insert('tbl_account_details', $data_account);
          $account_id = $this->db->insert_id();
          $this->db->insert('tbl_client_role', ['user_id' => $user_id, 'menu_id' => 17]);

          if($client_id){
            $type = "success";
            $message = 'Registro Exitoso';
          }else{
            $type = "error";
            $message = 'Registro Fallido';
          }
          set_message($type, $message);
          redirect('admin/cliente');
        }
      }
    }else if($created == "update"){
      // ------------- ACTUALIZAR CLIENTE -------------
      $validRucClient = "";
      $ruc_obtained = $this->input->post('ruc');
      $validRucClient = ($this->db->where(['ruc' => $ruc_obtained, 'cliente_id != ' => $id])->get('tbl_cliente')->row()) ?  $validRucClient = "ruc_equals" : $validRucClient = "ruc_not-equals";
      
      if($validRucClient == "ruc_equals"){
        set_message('error', '<strong>El RUC ingresado ya existe</strong>, intente con uno nuevo');
        redirect('admin/cliente/add_cliente/'.$id);
      }else{
        if(!isset($_POST['direccion_sede_new'])){
          set_message('error', 'Necesita <strong>agregar al menos una sede</strong> para actualizar el cliente');
          redirect('admin/cliente/add_cliente/'.$id);
        }else{
          $razon_social = $this->input->post('razon_social');
          $ruc = $this->input->post('ruc');
          $direccion_legal = $this->input->post('direccion_legal');
          $distrito = $this->input->post('distrito');
          $provincia = $this->input->post('provincia');
          $representante_legal = $this->input->post('representante_legal');
          $dni_representante = $this->input->post('dni_representante');
          $email_representante = $this->input->post('email_representante');
          $gerente_legal = $this->input->post('gerente_legal');
          $dni_gerente = $this->input->post('dni_gerente');
          $email_gerente = $this->input->post('email_gerente');

          $data['razon_social'] = (isset($razon_social) && $this->input->post('razon_social') != "") ? $this->input->post('razon_social') : '';
          $data['ruc'] = (isset($ruc) && $this->input->post('ruc') != "") ? $this->input->post('ruc') : '';
          $data['direccion_legal'] = (isset($direccion_legal) && $this->input->post('direccion_legal') != "") ? $this->input->post('direccion_legal') : '';
          $data['distrito'] = (isset($distrito) && $this->input->post('distrito') != "") ? $this->input->post('distrito') : '';
          $data['provincia'] = (isset($provincia) && $this->input->post('provincia') != "") ? $this->input->post('provincia') : '';
          $data['representante_legal'] = (isset($representante_legal) && $this->input->post('representante_legal') != "") ? $this->input->post('representante_legal') : '';
          $data['dni_representante'] = (isset($dni_representante) && $this->input->post('dni_representante') != "") ? $this->input->post('dni_representante') : '';
          $data['email_representante'] = (isset($email_representante) && $this->input->post('email_representante') != "") ? $this->input->post('email_representante') : '';
          $data['gerente_legal'] = (isset($gerente_legal) && $this->input->post('gerente_legal') != "") ? $this->input->post('gerente_legal') : '';
          $data['dni_gerente'] = (isset($dni_gerente) && $this->input->post('dni_gerente') != "") ? $this->input->post('dni_gerente') : '';
          $data['email_gerente'] = (isset($email_gerente) && $this->input->post('email_gerente') != "") ? $this->input->post('email_gerente') : '';
          $superv_collection = [];
          if(isset($_POST['superv_name']) || count($_POST['superv_name']) > 0){
            foreach($this->input->post('superv_name') as $key => $name){
              $superv_collection['superv_collection']['superv'][$key]['name'] = $name;
            }
          }
          if(isset($_POST['superv_email']) || count($_POST['superv_email']) > 0){
            foreach($this->input->post('superv_email') as $key => $email){
              $superv_collection['superv_collection']['superv'][$key]['email'] = $email;
            }
          }
          if(isset($_POST['superv_phone']) || count($_POST['superv_phone']) > 0){
            foreach($this->input->post('superv_phone') as $key => $phone){
              $superv_collection['superv_collection']['superv'][$key]['phone'] = $phone;
            }
          }
          $data['superv_collection'] = json_encode($superv_collection, TRUE);
          $sede = [];
          $data['sede_operativa'] = json_encode($sede);
          
          $this->cliente_model->_table_name = 'tbl_cliente';
          $this->cliente_model->_primary_key = "cliente_id";
          $client_id = $this->cliente_model->save($data, $id);

          // RECORRER NUEVAS SEDES Y AGREGARLAS
          foreach ($_POST['direccion_sede_new'] as $key => $value){
            $permisos = [];
            foreach( $_POST['permisos_new_'.$key] as $keyp => $permiso ){
              $permisos[] = $permiso;
            }
            $data_sede = [
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
          // LAS SIGUIENTES 2 LÍNEAS SON PROVICIONALES PARA PODER OBTENER EL ID DEL USUARIO Y ACTUALIZAR EL ID DEL CLIENTE EN tbl_users...
          $user_RUC = $this->input->post('ruc');
          $idUserByRUCClient = $this->db->query("SELECT user_id FROM tbl_users WHERE username = ".$user_RUC."")->result_array()[0]['user_id'];
          
          // ACTUALIZAR EN LA TABLA tbl_users...
          $user = $data['ruc'];
          $password = hash('sha512', $data['ruc'] . config_item('encryption_key'));
          $data_user['client_id'] = $id;
          $data_user['role_id'] = '2';
          $data_user['activated'] = '1';
          $data_user['username'] = $user;
          $data_user['password'] = $password;
          $this->user_model->_table_name = 'tbl_users';
          $this->user_model->_primary_key = "user_id";
          $user_id = $this->user_model->save($data_user, $idUserByRUCClient);
          // ACTUALIZAR EN LA TABLA tbl_account_details...
          $data_account['user_id'] = $idUserByRUCClient;
          $data_account['fullname'] = $data['razon_social'];
          $data_account['company'] = $client_id;
          $data_account['user_id'] = $idUserByRUCClient;
          $this->account_model->_table_name = 'tbl_account_details';
          $this->account_model->_primary_key = "account_details_id";
          $accountdetails_id = $this->account_model->save($data_account, $idUserByRUCClient);

          if($client_id){
            $type = "success";
            $message = 'Registro Exitoso';
          }else{
            $type = "error";
            $message = 'Registro Fallido';
          }
          set_message($type, $message);
          redirect('admin/cliente');
        }
      }
    }else{
      set_message('error', 'No existe el cliente');
      redirect('admin/cliente');
    }
  }
  /*
  public function update_cliente($id){
    $created = true;
    $edited = true;
    if (!empty($edited)){
      if ($this->db->where(['ruc' => $this->input->post('ruc')])->get('tbl_cliente')->row()){
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
      foreach ($_POST['direccion_sede'] as $key => $value){
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

      if($user_id && $client_id && $account_id){
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
  */
  // ------------------- ELIMINAR CLIENTE
  public function delete_client($id = NULL){
    if(isset($id)){
      $getDataclient = $this->db->where('cliente_id', $id)->get('tbl_cliente')->row();
      $cli_ruc = (isset($getDataclient->ruc) && $getDataclient->ruc != "") ? $getDataclient->ruc : "";
      $getDataUsers = $this->db->where('username', $cli_ruc)->get('tbl_users')->row(); // ELIMINAR DE 'tbl_users'
      $getDataAccountDetails = $this->db->where('user_id', $getDataclient->cliente_id)->get('tbl_account_details')->row(); // ELIMINAR DE 'tbl_account_details'
      $getDataSedes = $this->db->where('cliente_id', $getDataclient->cliente_id)->get('tbl_sedes')->row(); // ELIMINAR DE 'tbl_sedes'
      $getDataClientRole = $this->db->where('user_id', $getDataclient->cliente_id)->get('tbl_client_role')->row(); // ELIMINAR DE 'tbl_client_role'
      /*
      echo "tbl_cliente <br>";
      echo "<pre>";
      print_r($getDataclient);
      echo "</pre>";
      echo "tbl_users <br>";
      echo "<pre>";
      print_r($getDataUsers);
      echo "</pre>";
      echo "tbl_account_details <br>";
      echo "<pre>";
      print_r($getDataAccountDetails);
      echo "</pre>";
      echo "tbl_sedes <br>";
      echo "<pre>";
      print_r($getDataSedes);
      echo "</pre>";
      echo "tbl_client_role <br>";
      echo "<pre>";
      print_r($getDataClientRole);
      echo "</pre>";
      exit();
      */
      if(count($getDataclient) > 0 && count($getDataUsers) > 0 && count($getDataAccountDetails) > 0 && count($getDataSedes) > 0 && count($getDataClientRole) > 0){
        if($this->db->where('cliente_id', $id)->delete('tbl_cliente')){
          if($this->db->where('username', $cli_ruc)->delete('tbl_users')){
            if($this->db->where('user_id', $getDataclient->cliente_id)->delete('tbl_account_details')){
              if($this->db->where('cliente_id', $getDataclient->cliente_id)->delete('tbl_sedes')){
                if($this->db->where('user_id', $getDataclient->cliente_id)->delete('tbl_client_role')){
                  $data = ['type' => 'success','message' => 'Registro Eliminado con Exito!!','state_mssg' => 'Todos los datos del cliente en otras tablas han sido eliminadas'];
                }else{
                  $data = ['type' => 'attention','message' => 'Registro Eliminado con Exito!!','state_mssg' => 'No se encontró o no se pudo eliminar datos del cliente en *tbl_client_role*'];
                }
              }else{
                $data = ['type' => 'attention','message' => 'Registro Eliminado con Exito!!','state_mssg' => 'No se encontró o no se pudo eliminar datos del cliente en *tbl_sedes*'];
              }
            }else{
              $data = ['type' => 'attention','message' => 'Registro Eliminado con Exito!!','state_mssg' => 'No se encontró o no se pudo eliminar datos del cliente en *tbl_account_details*'];
            }
          }else{
            $data = ['type' => 'attention','message' => 'Registro Eliminado con Exito!!','state_mssg' => 'No se encontró o no se pudo eliminar datos del cliente en *tbl_users*'];
          }
        }else{
          $data = ['type' => 'error','message' => 'Ocurrio un Error al Eliminar el Registro.'];
        }
      }else{
        $data = ['type' => 'error','message' => 'Registro no existe'];
      }
    }else{
      $data = ['type' => 'error','message' => 'Error al eliminar Registro'];
    }
    echo json_encode($data);
    die();
  }
  /******************************* NUEVO CONTENIDO (INICIO) *******************************/
  public function change_password($password = NULL){
    if(isset($password)){        
      echo "<pre>";
      print_r($_POST);
      echo "</pre>";
      exit();
      /*
      $_SESSION['id_cliente_pass'] = $id;
      $user_id = $this->db->where(['company' => $_SESSION['id_cliente_pass'] ])->get('tbl_account_details')->row()->user_id;
      */
      
      /* $password = $this->hash($this->input->post('old_password', TRUE));
      $check_old_pass = $this->admin_model->check_by(array('password' => $password), 'tbl_users'); */

      /*
      if($this->input->post('new_password') === $this->input->post('confirm_password')){
          $data['password'] = $this->hash($this->input->post('new_password'));
          $this->cliente_model->_table_name = 'tbl_users';
          $this->cliente_model->_primary_key = 'user_id';
          $this->cliente_model->save($data, $user_id);
          $type = "success";
          $message = 'Contraseña Cambiada';
      }else{
          $type = "error";
          $message = 'Verifique la contraseña debe coincidir';
      }
      set_message($type, $message);
      redirect('admin/cliente'); //redirect page
      */
      $getDataclient = $this->db->where('anio_id', $password)->get('tbl_anio')->row();
      if(count($getDataclient) > 0){
        if($this->db->where('anio_id', $password)->delete('tbl_anio')){
          $data = ['type' => 'success','message' => 'Registro Eliminado con Exito!!'];
        }else{
          $data = ['type'    => 'error','message' => 'Ocurrio un Error al Eliminar el Registro.'];
        }
      }else{
        $data = ['type' => 'error','message' => 'Registro no existe'];
      }
    }else{
      $data = ['type' => 'error','message' => 'Error al eliminar Registro'];
    }
    echo json_encode($data);
    die();
  }
}
<?php
class Anio extends Admin_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('anio_model');
    $this->load->model('categoria_model');
    $this->load->model('subcategoria_model');
  }
  public function index($id = NULL){
    $data['title'] = "Años | PLAN VERDE";
    $data['page'] = "Años";
    $data['subview'] = $this->load->view('admin/anio/manage_anios', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }
  // ------------------- MOSTRAR FORMULARIO DE AÑOS
  public function add_anio($id = NULL){
    $data['title'] = "Agregar año";
    if(!empty($id)){
      $data['title'] = "Actualizar año";
      $data['anio_info'] = (object) $this->db->get_where('tbl_anio', ['anio_id' => $id])->row();
    }
    $data['subview'] = $this->load->view('admin/anio/add_anio', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  // ------------------- LISTAR AÑOS
  public function anioList($type = null){
    if($this->input->is_ajax_request()){
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_anio';
      $this->datatables->column_search = array('tbl_anio.anio');
      $this->datatables->column_order = array(' ', 'tbl_anio.anio');
      $this->datatables->order = array('anio_id' => 'desc');
      $where = (!empty($type)) ? array('tbl_anio.anio_id' => $type) : null;
      $fetch_data = make_datatables($where);
      $data = array();
      foreach($fetch_data as $_key => $anio){
        $action = null;
        $sub_array = array();
        $sub_array[] = $anio->anio;
        $action .= '<a data-toggle="modal" data-target="#myModal"  class="btn btn-info btn-xs" title="Click para Editar " href="' . base_url() . 'admin/anio/add_anio/' . $anio->anio_id . '"><span class="fa fa-pencil"></span></a>' . ' ';
        $action .= '<span data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs delete-anio" title="Click Para Eliminiar " class="" data-id="' . $anio->anio_id . '"><span class="fa fa-trash-o"></span></span>' . ' ';
        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    }else{
      redirect('admin/dashboard');
    }
  }
  // ------------------- GUARDAR AÑO
  public function save_anio($id = NULL){
    if($id == "" || $id == NULL){
      $anioWithoutSpaces = str_replace(" ", "", $_POST['anio']);
      $data_anio_exist = $this->db->where(['anio' => $anioWithoutSpaces])->get('tbl_anio')->row();
      if(count($data_anio_exist) > 0){
        set_message('error', '<strong>El año ingresado ya existe</strong>, intente con uno nuevo');
        redirect('admin/anio');
      }
      $data['anio'] = $this->input->post('anio');
      $this->anio_model->_table_name = 'tbl_anio';
      $this->anio_model->_primary_key = "anio_id";
      $anio_id = $this->anio_model->save($data, $id);

      $data_category = $this->db->get('tbl_categoria')->result(); // LLAMAR A TODAS LAS CATEGORÍAS...
      $validdatadetailanio = $this->db->select('id_carpeta')->where(['anio' => $anio_id])->get('tbl_detail_anio')->result();
      $countSubcategories = 0;
      $anio_name = $this->anio_model->get($anio_id)->anio; // OBTENER EL NOMBRE DEL AÑO POR EL ÚLTIMO ID GENERADO LÍNEAS ARRIBA...
      foreach ($data_category as $key => $category){
        $data_subcategoria = $this->db->get_where('tbl_subcategoria', ['categoria_id' => $category->categoria_id])->result(); // OBTENER TODAS LAS SUBCATEGORÍAS NO NULAS...
        foreach ($data_subcategoria as $key => $subcat){
          $data_detailanio = $this->db->get_where('tbl_detail_anio', [
            'anio' => $anio_id,
            'id_carpeta' => $validdatadetailanio[$countSubcategories]->id_carpeta,
            'categoria_id' => $subcat->categoria_id,
            'subcategoria_id' => $subcat->subcategoria_id
          ])->result();

          if (count($data_detailanio) == 0){
            $data['anio'] = $anio_id;
            $data['id_carpeta'] = '';
            $data['categoria_id'] = $subcat->categoria_id;
            $data['subcategoria_id'] = $subcat->subcategoria_id;
            // INSERTAR EL NUEVO AÑO EN LA TABLA "tbl_detail_anio" PARA TODAS LAS SUBCATEGORÍAS DENTRO DE CADA CATEGORÍA...
            $this->anio_model->_table_name = 'tbl_detail_anio';
            $this->anio_model->_primary_key = "detail_anio_id";
            $return_id = $this->anio_model->save($data, $id);
            $description_folder = 'Año'.$anio_name.' para subcategoría: '.$subcat->nombre_subcategoria;
            // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
            $folder = driveCreate($anio_name, $subcat->id_carpeta, $description_folder);
            // $folder = driveCreate($anio_name, '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn', $description_folder); // LÍNEA MOMENTÁNEA (PRUEBAS)
            $data_update['id_carpeta'] = $folder->id;
            $id_update = $this->anio_model->save($data_update, $return_id);
          }else{
            $description_folder = 'Actualización - Año para subcategoría: '.$subcat->nombre_subcategoria;
            $folder = driveUpdate($data_detailanio[0]->id_carpeta, $anio_name, $description_folder); // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
          }
          $countSubcategories++;
        }
      }
      if($anio_id){
        $returnyear = ['action' => 'created','type' => "success",'message' => "Registro exitoso", 'anio_id' => $anio_id];
      }else{
        $returnyear = ['action' => 'created','type' => "error",'message' => "Registro fallido", 'anio_id' => ''];
      }
    }else{
      $anioWithoutSpaces = str_replace(" ", "", $_POST['anio']);
      $data_anio_exist = $this->db->where(['anio' => $anioWithoutSpaces])->get('tbl_anio')->row();
      if (count($data_anio_exist) > 0){
        set_message('error', '<strong>El año ingresado ya existe</strong>, intente con uno nuevo');
        redirect('admin/anio');
      }
      $data['anio'] = $this->input->post('anio');
      $this->anio_model->_table_name = 'tbl_anio';
      $this->anio_model->_primary_key = "anio_id";
      $anio_id = $this->anio_model->save($data, $id);

      $data_category = $this->db->get('tbl_categoria')->result(); // LLAMAR A TODAS LAS CATEGORÍAS...
      $validdatadetailanio = $this->db->select('id_carpeta')->where(['anio' => $anio_id])->get('tbl_detail_anio')->result();
      $countSubcategories = 0;
      $anio_name = $this->anio_model->get($anio_id)->anio; // OBTENER EL NOMBRE DEL AÑO POR EL ÚLTIMO ID GENERADO LÍNEAS ARRIBA...
      foreach ($data_category as $key => $category){
        $data_subcategoria = $this->db->get_where('tbl_subcategoria', ['categoria_id' => $category->categoria_id])->result(); // OBTENER TODAS LAS SUBCATEGORÍAS NO NULAS...
        foreach ($data_subcategoria as $key => $subcat){
          $data_detailanio = $this->db->get_where('tbl_detail_anio', [
            'anio' => $anio_id,
            'id_carpeta' => $validdatadetailanio[$countSubcategories]->id_carpeta,
            'categoria_id' => $subcat->categoria_id,
            'subcategoria_id' => $subcat->subcategoria_id
          ])->result();

          if (count($data_detailanio) == 0){
            $data['anio'] = $anio_id;
            $data['id_carpeta'] = '';
            $data['categoria_id'] = $subcat->categoria_id;
            $data['subcategoria_id'] = $subcat->subcategoria_id;
            // INSERTAR EL NUEVO AÑO EN LA TABLA "tbl_detail_anio" PARA TODAS LAS SUBCATEGORÍAS DENTRO DE CADA CATEGORÍA...
            $this->anio_model->_table_name = 'tbl_detail_anio';
            $this->anio_model->_primary_key = "detail_anio_id";
            $return_id = $this->anio_model->save($data, $id);
            $description_folder = 'Año'.$anio_name.' para subcategoría: '.$subcat->nombre_subcategoria;
            // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
            $folder = driveCreate($anio_name, $subcat->id_carpeta, 'Año para subcategoría: '.$subcat->nombre_subcategoria);
            // $folder = driveCreate($anio_name, '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn', $description_folder); // LÍNEA MOMENTÁNEA
            $data_update['id_carpeta'] = $folder->id;
            $id_update = $this->anio_model->save($data_update, $return_id);
          }else{
            $description_folder = 'Actualización - Año'.$anio_name.' para subcategoría: '.$subcat->nombre_subcategoria;
            $folder = driveUpdate($data_detailanio[0]->id_carpeta, $anio_name, $description_folder); // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
          }
          $countSubcategories++;
        }
      }
      if($anio_id){
        $returnyear = ['action' => 'updated','type' => "success",'message' => "Actualización exitosa", 'anio_id' => $anio_id];
      }else{
        $returnyear = ['action' => 'updated','type' => "error",'message' => "Actualización fallida", 'anio_id' => ''];
      }
    }
    set_message($returnyear['type'], $returnyear['message']);
    redirect('admin/anio');
  }
  // ------------------- CREAR TODAS LAS SUBCATEGORÍAS PARA UNA NUEVA CUENTA...
  // public function createaniosindrive(){
  //   // $this->anio_model->_table_name = 'tbl_anio';
  //   // $this->anio_model->_primary_key = "anio_id";
  //   // $data_anio = $this->anio_model->get();
  //   // $counanios = count($data_anio);
  //   // $counaniosarr = 0;
  //   // echo "<pre>";
  //   // print_r($data_anio);
  //   // echo "</pre>";
  //   // exit();

  //   // $this->categoria_model->_table_name = 'tbl_categoria';
  //   // $this->categoria_model->_primary_key = "categoria_id";
  //   // $data_categoria = $this->categoria_model->get();
  //   // $this->subcategoria_model->_table_name = 'tbl_subcategoria';
  //   // $this->subcategoria_model->_primary_key = "subcategoria_id";
  //   // $this->anio_model->_table_name = 'tbl_detail_anio';
  //   // $this->anio_model->_primary_key = "detail_anio_id";
  //   // foreach($data_categoria as $key => $cat){
  //   //   $data = $this->subcategoria_model->get_by(['categoria_id' => $cat->categoria_id]);
  //   //   foreach($data as $key => $subcat){
  //   //     // echo $data_anio[$counaniosarr]->anio_id."<br>";
  //   //     // $data_detailanio = $this->db->get_where('tbl_detail_anio', [
  //   //     //   'anio' => $anio_id,
  //   //     //   'id_carpeta' => $validdatadetailanio[$countSubcategories]->id_carpeta,
  //   //     //   'categoria_id' => $subcat->categoria_id,
  //   //     //   'subcategoria_id' => $subcat->subcategoria_id
  //   //     // ])->result();
  //   //     // $description_folder = 'Año para subcategoría: '.$subcat->nombre_subcategoria;
  //   //     // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
  //   //     // $folder = driveCreate($anio_name, $subcat->id_carpeta, 'Año para subcategoría: '.$subcat->nombre_subcategoria);
  //   //     // $folder = driveCreate($anio_name, '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn', $description_folder); // LÍNEA MOMENTÁNEA
  //   //     // $data_update['id_carpeta'] = $folder->id;
  //   //     // $id_update = $this->anio_model->save($data_update, $return_id); // CAMBIAR EL ID_CARPETA DE TODOS LOS AÑOS
  //   //   };
  //   // };
  // }
  // ------------------- ELIMINAR AÑO
  public function delete_anio($id = NULL){
    $mensajeAgregado = false;
    if(isset($id)){
      $data_anio = $this->db->where('anio_id', $id)->get('tbl_anio')->row();
      $data_detailanio = $this->db->select('detail_anio_id, id_carpeta')->where(['anio' => $id])->get('tbl_detail_anio')->result_array();
      if(count($data_anio) > 0){
        if($this->db->where('anio_id', $id)->delete('tbl_anio')){
          $data = []; // REVISAR ESTE BLOQUE...
          foreach($data_detailanio as $key => $deatilanio){
            $folder = driveDelete($deatilanio['id_carpeta']); // ELIMINAR LAS CARPETAS DE LOS AÑOS EN GOOGLE DRIVE
            if($this->db->where('detail_anio_id', $deatilanio['detail_anio_id'])->delete('tbl_detail_anio')){
              $data = ['type'    => 'success','message' => 'Registro Eliminado con Exito!!'];
              $mensajeAgregado = true;
            }
          }
        }else{
          $data = ['type'    => 'error', 'message' => 'Ocurrio un Error al Eliminar el Registro.'];
        }
      }else{
        $data = ['type'    => 'error','message' => 'Registro no existe'];
      }
    }else{
      $data = ['type'    => 'error','message' => 'Error al eliminar Registro'];
    }
    echo json_encode($data);
    die();
  }
}

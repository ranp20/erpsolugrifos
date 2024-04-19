<?php
class Subcategoria extends Admin_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('categoria_model');
    $this->load->model('subcategoria_model');
    $this->load->model('anio_model');
  }
  public function index($id = NULL){
    $data['all_subcategories'] = $this->db->get('tbl_subcategoria')->result();
    $data['title'] = "Subcategorías | PLAN VERDE";
    $data['page'] = "Subcategorias";
    $data['subview'] = $this->load->view('admin/subcategoria/manage_subcategoria', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }
  // ------------------- MOSTRAR FORMULARIO DE SUBCATEGORÍAS
  public function add_subcategoria($id = NULL){
    $data['title'] = "Agregar subcategoría";
    $data['all_categories'] = $this->db->get('tbl_categoria')->result_array();
    if(!empty($id)){
      $data['title'] = "Actualizar subcategoría";
      $data['subcategory_info'] =  $this->db->get_where('tbl_subcategoria', ['Subcategoria_id' => $id])->row();
    }
    $data['subview'] = $this->load->view('admin/subcategoria/add_subcategoria', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  // ------------------- LISTAR SUBCATEGORÍAS
  public function subcategoriaList($type = null){
    if($this->input->is_ajax_request()){
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_subcategoria';
      $this->datatables->column_search = array('tbl_subcategoria.nombre_subcategoria');
      $this->datatables->column_order  = array('tbl_subcategoria.nombre_subcategoria');
      $this->datatables->order         = array('subcategoria_id' => 'desc');
      // get all invoice
      if(!empty($type)){
        $where = array('tbl_subcategoria.subcategoria_id' => $type);
      }else{
        $where = null;
      }
      $fetch_data = make_datatables($where);
      $data = array();
      foreach($fetch_data as $_key => $subcategoria){
        $action = null;
        $sub_array = array();
        $sub_array[] = '<a href="https://drive.google.com/open?id='.$subcategoria->id_carpeta.'" target="_blank" class="color-paragraph"><span class="txt-underline">'.$subcategoria->nombre_subcategoria.'</span></a>';
        $sub_array[] = $this->db->get_where('tbl_categoria', ['categoria_id' =>  $subcategoria->categoria_id])->row()->nombre_categoria;
        $action .= '<a data-toggle="modal" data-target="#myModal"  class="btn btn-info btn-xs" title="Click para Editar " href="' . base_url() . 'admin/subcategoria/add_subcategoria/' . $subcategoria->subcategoria_id . '"><span class="fa fa-pencil"></span></a>' . ' ';
        $action .= '<button type="button" data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs delete-subcategoria" title="Click para Eliminar " data-id="'.$subcategoria->subcategoria_id.'"><span class="fa fa-trash-o"></span></button>' . ' ';
        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    }else{
      redirect('admin/dashboard');
    }
  }
  // ------------------- CREAR SUBCATEGORÍA
  public function save_subcategoria($id = NULL){
    $this->subcategoria_model->_table_name = 'tbl_subcategoria';
    $this->subcategoria_model->_primary_key = "subcategoria_id";
    if($id == "" || $id == NULL){
      $data['nombre_subcategoria'] = $this->input->post('nombre_subcategoria');
      $data['categoria_id'] = $this->input->post('categoria_id');
      $data['id_carpeta'] = '';
      $return_id = $this->subcategoria_model->save($data, $id); // GUARDAR LA SUBCATEGORÍA EN LA BD
      if($return_id){
        $dataByIdCategoria = $this->db->select('nombre_categoria, id_carpeta')->where(['categoria_id' => $data['categoria_id']])->limit(1)->get('tbl_categoria')->result_array(); // OBTENEMOS INFORMACIÓN DE LA CATEGORÍA A PARTIR DEL ID SELECCIONADO
        $folderparentId = $dataByIdCategoria[0]['id_carpeta'];
        $name = $this->input->post('nombre_subcategoria'); // NOMBRE DE LA CARPETA
        $folder_name = $return_id.'-'.str_replace(" ", "_", $this->input->post('nombre_subcategoria')); // NOMBRE DE LA CARPETA
        $folder_description = 'Subcategoría: "'.$this->input->post('nombre_subcategoria').'" dentro de Categoría: '.$dataByIdCategoria[0]['nombre_categoria'];
        // $folder = driveCreate($name, '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn', $folder_description); // LÍNEA MOMENTÁNEA (PRUEBAS)
        $folder = driveCreate($name, $folderparentId, $folder_description); // CREAR LA SUBCATEGORÍA EN GOOGLE DRIVE
        $data_update['id_carpeta'] = $folder->id;
        $id_update = $this->subcategoria_model->save($data_update, $return_id);

        // AGREGAR/ACTUALIZAR TODOS LOS AÑOS A LA NUEVA SUBCATEGORÍA...
        $data_old = $this->db->select('subcategoria_id, nombre_subcategoria, id_carpeta')->where(['subcategoria_id' => $return_id])->limit(1)->get('tbl_subcategoria')->result_array();
        $getAnios = $this->db->select('anio_id, anio')->get('tbl_anio')->result();
        foreach($getAnios as $key => $anio){
          $validdatadetailanio = $this->db->select('id_carpeta')->where(['anio' => $anio->anio_id, 'categoria_id' => $this->input->post('categoria_id'), 'subcategoria_id' => $id_update])->get('tbl_detail_anio')->result();
          if(count($validdatadetailanio) == 0){
            $dataBydetailanio['anio'] = $anio->anio_id;
            $dataBydetailanio['id_carpeta'] = '';
            $dataBydetailanio['categoria_id'] = $this->input->post('categoria_id');
            $dataBydetailanio['subcategoria_id'] = $data_old[0]['subcategoria_id'];
            // INSERTAR EL NUEVO AÑO EN LA TABLA "tbl_detail_anio" PARA TODAS LAS SUBCATEGORÍAS DENTRO DE CADA CATEGORÍA...
            $this->anio_model->_table_name = 'tbl_detail_anio';
            $this->anio_model->_primary_key = "detail_anio_id";
            $returndetailanio_id = $this->anio_model->save($dataBydetailanio, $id);
            $description_folder = 'Año'.$anio->anio.' para subcategoría: '.$this->input->post('nombre_subcategoria');
            // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
            $folder = driveCreate($anio->anio, $data_old[0]['id_carpeta'], $description_folder);
            // $folder = driveCreate($anio->anio, '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn', $description_folder); // LÍNEA MOMENTÁNEA (PRUEBAS)
            $data_update['id_carpeta'] = $folder->id;
            $id_update = $this->anio_model->save($data_update, $returndetailanio_id);
          }else{
            $data_detailanio = $this->db->get_where('tbl_detail_anio', [
              'anio' => $anio->anio_id,
              'id_carpeta' => $validdatadetailanio[0]->id_carpeta,
              'categoria_id' => $this->input->post('categoria_id'),
              'subcategoria_id' => $id_update
            ])->result();

            if(count($data_detailanio) == 0){
              $dataBydetailanio['anio'] = $anio->anio_id;
              $dataBydetailanio['id_carpeta'] = '';
              $dataBydetailanio['categoria_id'] = $this->input->post('categoria_id');
              $dataBydetailanio['subcategoria_id'] = $data_old[0]['subcategoria_id'];
              // INSERTAR EL NUEVO AÑO EN LA TABLA "tbl_detail_anio" PARA TODAS LAS SUBCATEGORÍAS DENTRO DE CADA CATEGORÍA...
              $this->anio_model->_table_name = 'tbl_detail_anio';
              $this->anio_model->_primary_key = "detail_anio_id";
              $returndetailanio_id = $this->anio_model->save($dataBydetailanio, $id);
              $description_folder = 'Año'.$anio->anio.' para subcategoría: '.$this->input->post('nombre_subcategoria');
              // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
              $folder = driveCreate($anio->anio, $data_old[0]['id_carpeta'], $description_folder);
              // $folder = driveCreate($anio->anio, '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn', $description_folder); // LÍNEA MOMENTÁNEA (PRUEBAS)
              $data_update['id_carpeta'] = $folder->id;
              $id_update = $this->anio_model->save($data_update, $returndetailanio_id);
            }else{
              $description_folder = 'Actualización - Año '.$anio->anio.' para subcategoría: '.$this->input->post('nombre_subcategoria');
              $folder = driveUpdate($data_detailanio[0]->id_carpeta, $anio->anio, $description_folder); // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
            }
          }
        }
        $returnsubcategory = ['action' => 'created','type' => "success",'message' => "Registro exitoso", 'subcategoria_id' => $return_id];
      }else{
        $returnsubcategory = ['action' => 'created','type' => "error",'message' => "Registro fallido", 'subcategoria_id' => ''];
      }
    }else{
      $newName = $this->input->post('nombre_subcategoria');
      $data['nombre_subcategoria'] = $newName;
      $data['categoria_id'] = $this->input->post('categoria_id');
      $return_id = $this->subcategoria_model->save($data, $id);
      if($return_id){
        $dataByIdCategoria = $this->db->select('nombre_categoria')->where(['categoria_id' => $data['categoria_id']])->limit(1)->get('tbl_categoria')->result_array(); // OBTENEMOS INFORMACIÓN DE LA CATEGORÍA A PARTIR DEL ID SELECCIONADO
        $data_old = $this->db->select('subcategoria_id, nombre_subcategoria, id_carpeta')->where(['subcategoria_id' => $id])->limit(1)->get('tbl_subcategoria')->result_array();
        $name_subcategoria = $data_old[0]['nombre_subcategoria'];
        $folderId = $data_old[0]['id_carpeta'];
        $folder_name = $id.'-'.str_replace(" ", "_", $name_subcategoria);
        $folder_description = 'Subcategoría: "'.$this->input->post('nombre_subcategoria').'" dentro de Categoría: '.$dataByIdCategoria[0]['nombre_categoria'];
        $folder = driveUpdate($folderId, $name_subcategoria, $folder_description);
        // AGREGAR/ACTUALIZAR TODOS LOS AÑOS A LA NUEVA SUBCATEGORÍA...
        $getAnios = $this->db->select('anio_id, anio')->get('tbl_anio')->result();
        foreach($getAnios as $key => $anio){
          $validdatadetailanio = $this->db->select('id_carpeta')->where(['anio' => $anio->anio_id, 'categoria_id' => $this->input->post('categoria_id'), 'subcategoria_id' => $id])->get('tbl_detail_anio')->result();
          if(count($validdatadetailanio) == 0){
            $dataBydetailanio['anio'] = $anio->anio_id;
            $dataBydetailanio['id_carpeta'] = '';
            $dataBydetailanio['categoria_id'] = $this->input->post('categoria_id');
            $dataBydetailanio['subcategoria_id'] = $data_old[0]['subcategoria_id'];
            // INSERTAR EL NUEVO AÑO EN LA TABLA "tbl_detail_anio" PARA TODAS LAS SUBCATEGORÍAS DENTRO DE CADA CATEGORÍA...
            $this->anio_model->_table_name = 'tbl_detail_anio';
            $this->anio_model->_primary_key = "detail_anio_id";
            $returndetailanio_id = $this->anio_model->save($dataBydetailanio, $id);
            $description_folder = 'Año'.$anio->anio.' para subcategoría: '.$newName;
            // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
            $folder = driveCreate($anio->anio, $data_old[0]['id_carpeta'], $description_folder);
            // $folder = driveCreate($anio->anio, '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn', $description_folder); // LÍNEA MOMENTÁNEA (PRUEBAS)
            $data_update['id_carpeta'] = $folder->id;
            $id_update = $this->anio_model->save($data_update, $returndetailanio_id);
          }else{
            $data_detailanio = $this->db->get_where('tbl_detail_anio', [
              'anio' => $anio->anio_id,
              'id_carpeta' => $validdatadetailanio[0]->id_carpeta,
              'categoria_id' => $this->input->post('categoria_id'),
              'subcategoria_id' => $data_old[0]['subcategoria_id']
            ])->result();
            if(count($data_detailanio) == 0){
              $dataBydetailanio['anio'] = $anio->anio_id;
              $dataBydetailanio['id_carpeta'] = '';
              $dataBydetailanio['categoria_id'] = $this->input->post('categoria_id');
              $dataBydetailanio['subcategoria_id'] = $data_old[0]['subcategoria_id'];
              // INSERTAR EL NUEVO AÑO EN LA TABLA "tbl_detail_anio" PARA TODAS LAS SUBCATEGORÍAS DENTRO DE CADA CATEGORÍA...
              $this->anio_model->_table_name = 'tbl_detail_anio';
              $this->anio_model->_primary_key = "detail_anio_id";
              $returndetailanio_id = $this->anio_model->save($dataBydetailanio, $id);
              $description_folder = 'Año'.$anio->anio.' para subcategoría: '.$newName;
              // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
              $folder = driveCreate($anio->anio, $data_old[0]['id_carpeta'], $description_folder);
              // $folder = driveCreate($anio->anio, '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn', $description_folder); // LÍNEA MOMENTÁNEA (PRUEBAS)
              $data_update['id_carpeta'] = $folder->id;
              $id_update = $this->anio_model->save($data_update, $returndetailanio_id);
            }else{
              $description_folder = 'Actualización - Año '.$anio->anio.' para subcategoría: '.$newName;
              $folder = driveUpdate($data_detailanio[0]->id_carpeta, $anio->anio, $description_folder); // CREAR EL NUEVO AÑO A MODO DE CARPETA DENTRO DE TODAS LAS SUBCATEGORÍAS EXISTENTES DENTRO DE CADA CATEGORÍA...
            }
          }
        }
        $returnsubcategory = ['action' => 'updated','type' => "success",'message' => "Actualización exitosa", 'subcategoria_id' => $return_id];
      }else{
        $returnsubcategory = ['action' => 'updated','type' => "error",'message' => "Actualización fallida", 'subcategoria_id' => ''];
      }
    }
    set_message($returnsubcategory['type'], $returnsubcategory['message']);
    redirect('admin/subcategoria');
  }
  // ------------------- CREAR TODAS LAS SUBCATEGORÍAS PARA UNA NUEVA CUENTA...
  public function createsubcategoriesindrive(){
    $this->categoria_model->_table_name = 'tbl_categoria';
    $this->categoria_model->_primary_key = "categoria_id";
    $data_categoria = $this->categoria_model->get();
    $this->subcategoria_model->_table_name = 'tbl_subcategoria';
    $this->subcategoria_model->_primary_key = "subcategoria_id";
    foreach($data_categoria as $key => $cat){
      $idFolderParent = $cat->id_carpeta;
      $data = $this->subcategoria_model->get_by(['categoria_id' => $cat->categoria_id]);
      foreach($data as $key => $subcat){
        $name = $subcat->nombre_subcategoria;
        $name_folder = $subcat->subcategoria_id . '-' . $subcat->nombre_subcategoria;
        $description_description = 'Subcategoría: "'.$subcat->nombre_subcategoria.'" dentro de Categoría: '.$cat->nombre_categoria;
        $folder_data = driveCreate($name, $idFolderParent, $description_description);
        $data_update['id_carpeta'] = $folder_data->id;
        $this->subcategoria_model->save($data_update, $subcat->subcategoria_id); // CAMBIAR EL ID_CARPETA DE TODAS LAS SUBCATEGORÍAS
      };
    };
  }
  // ------------------- ELIMINAR SUBCATEGORÍA
  public function delete_subcategoria($id = NULL){
    $mensajeAgregado = false;
    if(isset($id)){
      $data_subcategoria = $this->db->select('id_carpeta')->where(['subcategoria_id' => $id])->limit(1)->get('tbl_subcategoria')->result_array();
      if(count($data_subcategoria) > 0){
        $folder = driveDelete($data_subcategoria[0]['id_carpeta']); // ELIMINAR LA CARPETA DE LA SUBCATEGORÍA EN GOOGLE DRIVE
        $data_detailanios = $this->db->where(['subcategoria_id' => $id])->get('tbl_detail_anio')->result();
        if(count($data_detailanios) > 0){
          foreach($data_detailanios as $key => $detanio){
            if($this->db->where('detail_anio_id', $detanio->detail_anio_id)->delete('tbl_detail_anio')){ // ELIMINAR LOS AÑOS DE LA TABLA 'tbl_detail_anio'
              if($this->db->where('subcategoria_id', $id)->delete('tbl_subcategoria')){ // ELIMINAR LA SUBCATEGORÍA
                $data = ['type' => 'success','message' => 'Registro Eliminado con Exito!!'];
              }else{
                $data = ['type' => 'error','message' => 'Ocurrio un Error al Eliminar el Registro.'];
              }
              $mensajeAgregado = true;
            }else{
              $data = ['type' => 'error','message' => 'Ocurrio un Error al Eliminar el Registro.'];
            }
          }
        }else{
          if($this->db->where('subcategoria_id', $id)->delete('tbl_subcategoria')){ // ELIMINAR LA SUBCATEGORÍA
            $data = ['type' => 'success','message' => 'Registro Eliminado con Exito!!'];
          }else{
            $data = ['type' => 'error','message' => 'Ocurrio un Error al Eliminar el Registro.'];
          }
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
<?php
class Categoria extends Admin_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('categoria_model');
  }
  public function manage_categoria($id = NULL){    
    $data['title'] = "Categorías | PLAN VERDE";
    $data['page'] = "Categorías";
    $data['subview'] = $this->load->view('admin/categoria/manage_categoria', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }
  // ------------------- MOSTRAR FORMULARIO DE CATEGORÍAS
  public function add_categoria($id = NULL){
    $data['title'] = 'Agregar Categoría';
    if(!empty($id)){
      $data['title'] = 'Actualizar Categoría';
      $data['categoria_info'] = (object) $this->db->get_where('tbl_categoria', ['categoria_id' => $id])->row();
    }
    $data['subview'] = $this->load->view('admin/categoria/add_categoria', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  // ------------------- LISTAR CATEGORÍAS
  public function categoriaList($type = null){
    if($this->input->is_ajax_request()){
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_categoria';
      $this->datatables->column_search = array('tbl_categoria.nombre_categoria');
      $this->datatables->column_order = array(' ', 'tbl_categoria.nombre_categoria');
      $this->datatables->order = array('categoria_id' => 'desc');
      if(!empty($type)){
        $where = array('tbl_categoria.categoria_id' => $type);
      }else{
        $where = null;
      }
      $fetch_data = make_datatables($where);
      $data = array();
      foreach ($fetch_data as $_key => $categoria){
        $action = null;
        $sub_array = array();
        $sub_array[] = '<a href="https://drive.google.com/open?id='.$categoria->id_carpeta.'" target="_blank" class="color-paragraph"><span class="txt-underline">'.$categoria->nombre_categoria.'</span></a>';
        $action .= '<a data-toggle="modal" data-target="#myModal"  class="btn btn-info btn-xs" title="Click para Editar " href="' . base_url() . 'admin/categoria/add_categoria/' . $categoria->categoria_id . '"><span class="fa fa-pencil"></span></a>' . ' ';
        $action .= '<span data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs delete-category" title="Click Para Eliminiar " class="" data-id="' . $categoria->categoria_id . '"><span class="fa fa-trash-o"></span></span>' . ' ';
        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    }else{
      redirect('admin/dashboard');
    }
  }
  // ------------------- GUARDAR CATEGORÍA
  public function save_categoria($id = NULL){
    if($id == "" || $id == NULL){
      $data['id_carpeta'] = '';
      $data['nombre_categoria'] = $this->input->post('nombre_categoria');
      $this->categoria_model->_table_name = 'tbl_categoria';
      $this->categoria_model->_primary_key = "categoria_id";
      $return_id = $this->categoria_model->save($data, $id); // DEVOLVEMOS EL ID DE LA NUEVA CATEGORÍA
      if($return_id){
        $name = $this->input->post('nombre_categoria'); // NUEVO NOMBRE PARA LA CARPETA EN GOOGLE DRIVE
        $name_folder = $return_id . '_' . $this->input->post('nombre_categoria'); // NUEVO NOMBRE PARA LA CARPETA EN GOOGLE DRIVE
        $folderparentId = getIdMainFolder('documentos'); // OBTENEMOS EL ID DE LA CARPETA RAÍZ EN GOOGLE DRIVE
        $description_folder = 'Categoría con formato de nombre: "ID - NOMBRE" ('.$name_folder.')'; // AGREGAR UNA BREVE DESCRIPCIÓN A LA CARPETA
        $folder = driveCreate($name, $folderparentId, $description_folder); // CREACIÓN DE LA CARPETA EN GOOGLE DRIVE
        $data_update['id_carpeta'] = $folder->id; // OBTENEMOS EL ID DE LA CARPETA SUBIDA EN GOOGLE DRIVE
        $id_update = $this->categoria_model->save($data_update, $return_id); // ACTUALIZAMOS EL REGISTRO CON LA NUEVA "ID_CARPETA"
        $returncategory = ['action' => 'created','type' => "success",'message' => "Registo Exitoso", 'categoria_id' => $id_update]; // AGRUPAMOS DENTRO DE ARRAY PARA VALIDAR EL MENSAJE DE CONFIRMACIÓN
      }else{
        $returncategory = ['action' => 'created','type' => "error",'message' => "Registo fallido", 'categoria_id' => '']; // AGRUPAMOS DENTRO DE ARRAY PARA VALIDAR EL MENSAJE DE CONFIRMACIÓN
      }
    }else{
      $data_old = $this->db->get_where('tbl_categoria', ['categoria_id' => $id])->row();
      $old_folderId = $data_old->id_carpeta;
      $newName = $this->input->post('nombre_categoria');
      $data_update = ['nombre_categoria' => $newName];
      $this->categoria_model->_table_name = 'tbl_categoria';
      $this->categoria_model->_primary_key = "categoria_id";
      $return_id = $this->categoria_model->save($data_update, $id);
      if($return_id){
        $name = $newName;
        $name_folder = $return_id . '_' . $newName;
        $description_folder = 'Categoría con formato de nombre: "ID - NOMBRE" ('.$name_folder.')';
        $folder = driveUpdate($old_folderId, $name, $description_folder);
        $returncategory = ['action' => 'updated','categoria_id' => $old_folderId];
        // NOTA: No es necesario pasar el ID de la carpeta ya creada, ya que este es inmutable, a menos que se elimine y/o se vuelva a crear
        $returncategory = ['action' => 'updated','type' => "success",'message' => "Actualización exitosa", 'categoria_id' => $return_id];
      }else{
        $returncategory = ['action' => 'updated','type' => "error",'message' => "Actualización fallida", 'categoria_id' => ''];
      }
    }
    set_message($returncategory['type'], $returncategory['message']);
    redirect('admin/categoria/manage_categoria');
  }
  // ------------------- CREAR TODAS LAS CATEGORÍAS (PARA UNA NUEVA CUENTA)...
  public function createcategoriesindrive(){
    $this->categoria_model->_table_name = 'tbl_categoria';
    $this->categoria_model->_primary_key = "categoria_id";
    $data = $this->categoria_model->get();
    $idMainFolder = getIdMainFolder('documentos');
    foreach($data as $key => $cat){
      $name = $cat->nombre_categoria;
      $name_folder = $cat->categoria_id . '_' . $cat->nombre_categoria;
      $description_folder = 'Categoría con formato de nombre: "ID - NOMBRE" ('.$name_folder.')';
      $folder_data = driveCreate($name, $idMainFolder, $description_folder);
      $data_update['id_carpeta'] = $folder_data->id;
      $this->categoria_model->save($data_update, $cat->categoria_id); // CAMBIAR EL ID_CARPETA DE TODAS LAS CATEGORÍAS
    }
  }
  public function cmb_x_sede($sede_id = NULL){
    $data['title'] = 'Combo Sede por cliente';
    $data['page'] = 'Combo Sede por cliente';
    $data['all_categories'] = $this->db->get('tbl_categoria')->result_object();
    $data['all_subcategories'] = $this->db->get('tbl_subcategoria')->result_object();
    $data['all_permissions'] = json_decode($this->db->where(['sede_id' => $sede_id])->get('tbl_sedes')->row()->permission);
    $this->load->view('admin/categoria/cmb_x_sede', $data);
    // $this->load->view('admin/_layout_modal', $data);
  }
  // ------------------- ELIMINAR CATEGORÍA
  public function delete_categoria($id = NULL){
    if(isset($id)){
      $data_cat = $this->db->where('categoria_id', $id)->get('tbl_categoria')->result_array();
      if(count($data_cat) > 0){
        $data_subcat = $this->db->where('categoria_id', $id)->get('tbl_subcategoria')->row();
        if(count($data_subcat) > 0){
          $data = ['type' => 'error','message' => 'No se puede eliminar, tiene subcategorias'];
        }else{
          $folder = driveDelete($data_cat[0]['id_carpeta']); // ELIMINAR LA CARPETA DE CATEGORÍA EN GOOGLE DRIVE
          if($this->db->where('categoria_id', $id)->delete('tbl_categoria')){
            $data = ['type' => 'success','message' => 'Categoria Eliminada con Exito!!'];
          }else{
            $data = ['type' => 'error','message' => 'Ocurrio un Error al Eliminar Categoria'];
          }
        }
      }else{
        $data = ['type' => 'error','message' => 'Categoria no existe'];
      }
    }else{
      $data = ['type' => 'error','message' => 'Error al eliminar Categoria'];
    }
    echo json_encode($data);
    die();
  }
}

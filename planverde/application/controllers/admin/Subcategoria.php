<?php
class Subcategoria extends Admin_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('categoria_model');
    $this->load->model('subcategoria_model');
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
      $dataByIdCategoria = $this->db->select('nombre_categoria, id_carpeta')->where(['categoria_id' => $data['categoria_id']])->limit(1)->get('tbl_categoria')->result_array(); // OBTENEMOS INFORMACIÓN DE LA CATEGORÍA A PARTIR DEL ID SELECCIONADO
      $folderparentId = $dataByIdCategoria[0]['id_carpeta'];
      $folder_name = $return_id.'-'.str_replace(" ", "_", $this->input->post('nombre_subcategoria')); // NOMBRE DE LA CARPETA
      $folder_description = 'Subcategoría: "'.$this->input->post('nombre_subcategoria').'" dentro de Categoría: '.$dataByIdCategoria[0]['nombre_categoria'];
      $folder = driveCreate($folder_name, '1d7ApZyElUzpjq7SvnK1p4JJ35ktrgukn', $folder_description);
      // $folder = driveCreate($folder_name, $folderparentId, $folder_description); // CREAR LA SUBCATEGORÍA EN GOOGLE DRIVE
      $data_update['id_carpeta'] = $folder->id;
      $id_update = $this->subcategoria_model->save($data_update, $return_id);
      $returnsubcategory = ['action' => 'created','subcategoria_id' => $id_update];
    }else{
      $newName = $this->input->post('nombre_subcategoria');
      $data['nombre_subcategoria'] = $newName;
      $data['categoria_id'] = $this->input->post('categoria_id');
      $return_id = $this->subcategoria_model->save($data, $id);
      $dataByIdCategoria = $this->db->select('nombre_categoria')->where(['categoria_id' => $data['categoria_id']])->limit(1)->get('tbl_categoria')->result_array(); // OBTENEMOS INFORMACIÓN DE LA CATEGORÍA A PARTIR DEL ID SELECCIONADO
      $data_old = $this->db->select('nombre_subcategoria, id_carpeta')->where(['subcategoria_id' => $id])->limit(1)->get('tbl_subcategoria')->result_array();
      $name_subcategoria = $data_old[0]['nombre_subcategoria'];
      $folderId = $data_old[0]['id_carpeta'];
      $folder_name = $id.'-'.str_replace(" ", "_", $name_subcategoria);
      $folder_description = 'Subcategoría: "'.$this->input->post('nombre_subcategoria').'" dentro de Categoría: '.$dataByIdCategoria[0]['nombre_categoria'];
      $folder = driveUpdate($folderId, $folder_name, $folder_description);
      $returnsubcategory = ['action' => 'updated','subcategoria_id' => $return_id];
    }
    if($returnsubcategory['action'] == "created"){
      set_message('success', 'Registro exitoso');
      redirect('admin/subcategoria');
    }else{
      set_message('success', 'Actualización exitosa');
      redirect('admin/subcategoria');
    }
  }
  // ------------------- CREAR TODAS LAS SUBCATEGORÍAS PARA UNA NUEVA CUENTA...
  public function create_folders_drive(){
    $this->categoria_model->_table_name = 'tbl_categoria';
    $this->categoria_model->_primary_key = "categoria_id";
    $data_categoria = $this->categoria_model->get();
    $this->subcategoria_model->_table_name = 'tbl_subcategoria';
    $this->subcategoria_model->_primary_key = "subcategoria_id";
    foreach ($data_categoria as $key => $cat){
      $idFolderParent = $cat->id_carpeta;
      $data = $this->subcategoria_model->get_by(['categoria_id' => $cat->categoria_id]);
      foreach ($data as $key => $subcat){
        $name = $subcat->subcategoria_id . '-' . $subcat->nombre_subcategoria;
        $folder_data = driveCreate($name, $idFolderParent);
        $data_update['id_carpeta'] = $folder_data->id;
        $this->subcategoria_model->save($data_update, $subcat->subcategoria_id);
      };
    };
    exit();
  }
  // ------------------- ELIMINAR SUBCATEGORÍA
  public function delete_subcategoria($id = NULL){
    if(isset($id)){
      $data_subcategoria = $this->db->select('id_carpeta')->where(['subcategoria_id' => $id])->limit(1)->get('tbl_subcategoria')->result_array();
      if(count($data_subcategoria) > 0){
        $folder = driveDelete($data_subcategoria[0]['id_carpeta']); // ELIMINAR LA CARPETA DE LA SUBCATEGORÍA EN GOOGLE DRIVE
        if($this->db->where('subcategoria_id', $id)->delete('tbl_subcategoria')){
          $data = ['type' => 'success','message' => 'Registro Eliminado con Exito!!'];
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
}
<?php
class Announcements_section extends Admin_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('announcements_section_model');
  }
  public function index($id = NULL){
    $data['title'] = "Secciones de Anuncios | PLAN VERDE";
    $data['page'] = "Secciones de anuncios";
    $data['subview'] = $this->load->view('admin/announcements_section/index', $data, TRUE);
    $this->load->view('admin/_layout_main', $data);
  }
  // ------------------- MOSTRAR FORMULARIO DE SECCIÓN DE ANUNCIOS
  public function add_announcements_section($id = NULL){
    $data['title'] = 'Agregar Sección';
    if(!empty($id)){
      $data['title'] = 'Actualizar Sección';
      $data['announcements_sec_info'] = (object) $this->db->get_where('tbl_announcements_section', ['id' => $id])->row();
    }
    $data['subview'] = $this->load->view('admin/announcements_section/add_announcements_section', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  // ------------------- GUARDAR SECCIONES DE ANUNCIOS
  public function save_announcements_section($id = NULL){
    if($id == "" || $id == NULL){
      $data['name'] = $this->input->post('titulo');
      $this->announcements_section_model->_table_name  = 'tbl_announcements_section';
      $this->announcements_section_model->_primary_key = "id";
      $return_id = $this->announcements_section_model->save($data, $id);
      $name_folder = $this->input->post('titulo'); // NUEVO NOMBRE PARA LA CARPETA EN GOOGLE DRIVE
      $folderparentId = getIdMainFolder("anuncios"); // OBTENEMOS EL ID DE LA CARPETA RAÍZ EN GOOGLE DRIVE
      $description_folder = 'Sección de anuncio: "ID_NOMBRE" - ('.$return_id.'_'.$name_folder.')'; // AGREGAR UNA BREVE DESCRIPCIÓN A LA CARPETA
      $folder = driveCreate($name_folder, $folderparentId, $description_folder); // CREACIÓN DE LA CARPETA EN GOOGLE DRIVE
      $data_update['id_carpeta'] = $folder->id; // OBTENEMOS EL ID DE LA CARPETA SUBIDA EN GOOGLE DRIVE
      $id_update = $this->announcements_section_model->save($data_update, $return_id); // ACTUALIZAMOS EL REGISTRO CON LA NUEVA "ID_CARPETA"
      if($id_update){
        $returnannouncement_section = ['action' => 'created','type' => 'success','message' => 'Sección creada','announcement_section_id' => $return_id];
      }else{
        $returnannouncement_section = ['action' => 'created','type' => 'error','message' => 'Fallo al registrar','announcement_section_id' => ''];
      }
    }else{
      $data_old = $this->db->get_where('tbl_announcements_section', ['id' => $id])->row();
      $old_folderId = $data_old->id_carpeta;
      $data['name'] = $this->input->post('titulo');
      $this->announcements_section_model->_table_name  = 'tbl_announcements_section';
      $this->announcements_section_model->_primary_key = "id";
      $return_id = $this->announcements_section_model->save($data, $id);
      if($old_folderId == "" || $old_folderId == NULL){
        $name_folder = $this->input->post('titulo'); // NUEVO NOMBRE PARA LA CARPETA EN GOOGLE DRIVE
        $folderparentId = getIdMainFolder("anuncios"); // OBTENEMOS EL ID DE LA CARPETA RAÍZ EN GOOGLE DRIVE
        $description_folder = 'Sección de anuncio: "ID_NOMBRE" - ('.$return_id.'_'.$name_folder.')'; // AGREGAR UNA BREVE DESCRIPCIÓN A LA CARPETA
        $folder = driveCreate($name_folder, $folderparentId, $description_folder); // CREACIÓN DE LA CARPETA EN GOOGLE DRIVE
        $data_update['id_carpeta'] = $folder->id; // OBTENEMOS EL ID DE LA CARPETA SUBIDA EN GOOGLE DRIVE
        $id_update = $this->announcements_section_model->save($data_update, $return_id); // ACTUALIZAMOS EL REGISTRO CON LA NUEVA "ID_CARPETA"
      }else{
        $name_folder = $this->input->post('titulo'); // NUEVO NOMBRE PARA LA CARPETA EN GOOGLE DRIVE
        $description_folder = 'Sección de anuncio: "ID_NOMBRE" - ('.$return_id.'_'.$name_folder.')'; // AGREGAR UNA BREVE DESCRIPCIÓN A LA CARPETA
        $folder = driveUpdate($old_folderId, $name_folder,$description_folder); // CREACIÓN DE LA CARPETA EN GOOGLE DRIVE
      }
      if($return_id){
        $returnannouncement_section = ['action' => 'updated','type' => 'success','message' => 'Actualización exitosa','announcement_section_id' => $old_folderId];
      }else{
        $returnannouncement_section = ['action' => 'updated','type' => 'error','message' => 'Falló al actualizar','announcement_section_id' => ''];
      }
      
    }
    set_message($returnannouncement_section['type'], $returnannouncement_section['message']);
    redirect('admin/announcements_section');
  }
  // -------------------LISTAR SECCIÓN DE ANUNCIOS
  public function announcements_sectionList($type = null){
    if($this->input->is_ajax_request()){
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_announcements_section';
      $this->datatables->column_search = array('tbl_announcements_section.name');
      $this->datatables->column_order = array(' ', 'tbl_announcements_section.name');
      $this->datatables->order = array('id' => 'desc');
      $where = (!empty($type)) ? array('tbl_announcements_section.id' => $type) : null;
      $fetch_data = make_datatables($where);
      $data = array();
      foreach ($fetch_data as $_key => $announcements_section){
        $action = null;
        $sub_array = array();
        $sub_array[] = $announcements_section->name;
        $checked = ($announcements_section->status == 1) ? "checked" : "";
        $sub_array[] = '<div class="chk__ToggleSwitch">
                            <div class="checkbox">
                                <input type="checkbox" class="status-anuncio" ' . $checked . ' data-id="' . $announcements_section->id . '" data-status="'.$announcements_section->status.'" data-toggle="toggle" data-size="mini" data-on=" Visible " data-off=" No Visible " data-onstyle="success" data-offstyle="danger">
                                <label></label>
                            </div>
                        </div>';        
        $action .= '<a data-toggle="modal" data-target="#myModal"  class="btn btn-info btn-xs" title="Click para Editar " href="' . base_url() . 'admin/announcements_section/add_announcements_section/' . $announcements_section->id . '"><span class="fa fa-pencil"></span></a>' . ' ';
        $action .= '<button type="button" data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs" title="Click para Eliminar " onclick="deleteAnnouncements_section('.$announcements_section->id.')"><span class="fa fa-trash-o"></span></button>' . ' ';
        $sub_array[] = $action;
        $data[] = $sub_array;
      }
      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }
  // ------------------- ACTIVAR/DESACTIVAR SECCIÓN DE ANUNCIO
  function active($id, $status){
    if(!empty($id) && $id > 0 && !empty($status)){
      $st_chck = ($status == "on") ? 1 : 0;
      $this->db->where('id', $id);
      if($this->db->update('tbl_announcements_section', ['status' => $st_chck])){
        $data = ['type' => 'success', 'message' => 'Sección de anuncio Actualizado'];
      }else{
        $data = ['type' => 'error', 'message' => 'Sección de anuncio no Actualizado'];
      }
    }else{
      $data = ['type' => 'error', 'message' => 'Sección de anuncio no Actualizado'];
    }
    echo json_encode($data);
    die();
  }
  // ------------------- ELIMINAR SECCIÓN DE ANUNCIO
  public function delete_announcements_section($id = NULL){
    if(isset($id)){
      $data_announcements_section = $this->db->where('id', $id)->get('tbl_announcements_section')->row();
      if(count($data_announcements_section) > 0){
        if($this->db->where('id', $id)->delete('tbl_announcements_section')){
          $data = ['type' => 'success', 'message' => 'Registro Eliminado con Exito!!'];
        }else{
          $data = ['type' => 'error', 'message' => 'Ocurrio un Error al Eliminar el Registro.'];
        }
      }else{
        $data = ['type' => 'error', 'message' => 'Registro no existe'];
      }
    }else{
      $data = ['type' => 'error', 'message' => 'Error al eliminar Registro'];
    }
  echo json_encode($data);
  die();
  }
}

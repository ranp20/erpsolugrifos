<?php
class Anuncio extends Admin_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('anuncio_model');
    // $this->load->model('announcements_section_model');
  }
  public function index($id = NULL){
    $data['all_anios'] = $this->db->get('tbl_anuncios')->result();
    $data['title'] = "Anuncios | PLAN VERDE";
    $data['page'] = "Anuncios";
    $data['subview'] = $this->load->view('admin/anuncios/index', $data, TRUE);
    $this->load->view('admin/_layout_main', $data); //page load
  }
  // ------------------- MOSTRAR FORMULARIO DE ANUNCIO
  public function add_anuncio($id = NULL){
    $data['title'] = 'Agregar Anuncio';
    $data['all_sections'] = $this->db->get('tbl_announcements_section')->result_array();
    if (!empty($id)){
      $data['title'] = 'Actualizar Anuncio';
      $data['anuncio_info'] = (object) $this->db->get_where('tbl_anuncios', ['anuncio_id' => $id])->row();
    }
    $data['subview'] = $this->load->view('admin/anuncios/add_anuncio', $data, FALSE);
    $this->load->view('admin/_layout_modal', $data);
  }
  // ------------------- LISTAR ANUNCIOS
  public function anuncioList($type = null){
    if ($this->input->is_ajax_request()){
      $this->load->model('datatables');
      $this->datatables->table = 'tbl_anuncios tblads';
      // $this->datatables->select = array('tblads.*, tblsecads.id, tblsecads.name AS name_section, tblsecads.status AS status_section');
      $this->datatables->select = 'tblads.anuncio_id, tblads.title, tblads.descripcion, tblads.foto, tblads.adjunto, tblads.status AS status_ads, tblsecads.id, tblsecads.name AS name_section, tblsecads.status AS status_section';
      $this->datatables->join_table = array('tbl_announcements_section tblsecads');
      $this->datatables->join_where = array('tblsecads.id = tblads.section_id');
      $this->datatables->column_search = array('tblads.titulo', 'name_section', 'tblads.foto', 'status_ads','status_section');
      $this->datatables->column_order = array(' ', 'tblads.titulo', 'name_section', 'tblads.foto', 'status_ads','status_section');
      $this->datatables->order = array('tblads.anuncio_id' => 'desc');
      $where = (!empty($type)) ? array('tblads.anuncio_id' => $type) : null;
      $fetch_data = make_datatables($where);
      $data = array();
      // echo "<pre>";
      // print_r($fetch_data);
      // echo "</pre>";
      // exit();
      foreach ($fetch_data as $_key => $anuncio){
        $action = null;
        $sub_array = array();
        $sub_array[] = $anuncio->titulo;
        $name_section =  $this->db->get_where("tbl_announcements_section", ['id' => $anuncio->section_id])->row();
        $sub_array[] = $name_section->name;
        // $sub_array[] = $anuncio->name_section;
        $foto = (!empty( $anuncio->foto )) ? '<a href="'.base_url().'uploads/anuncios/fotos/'. $anuncio->foto .'" target="_blank" class="c-prevAdd__link"><img width="40px" alt="'.$anuncio->foto.'" src="'.base_url().'uploads/anuncios/fotos/'. $anuncio->foto .'"></a>' : '';
        $sub_array[] = $foto;
        $checked = ($anuncio->status == 1) ? "checked" : "";
        $sub_array[] = '<div class="chk__ToggleSwitch" '.$checked."-".$anuncio->status.'>
                          <div class="checkbox">
                            <input type="checkbox" class="status-anuncio" ' . $checked . ' data-id="' . $anuncio->anuncio_id . '" data-status="'.$anuncio->status.'" data-toggle="toggle" data-size="mini" data-on="Visible" data-off="No Visible" data-onstyle="success" data-offstyle="danger">
                            <label></label>
                          </div>
                        </div>';
        $action = '';
        if(!empty($anuncio->adjunto)){
          $action .= '<a target="_blank" data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Descargar" href="' . base_url() . 'uploads/anuncios/fotos/' . $anuncio->adjunto . '"><span class="fa fa-download"></span></a>' . ' ';
        }
        $action .= '<a data-toggle="modal" data-target="#myModal"  class="btn btn-info btn-xs" title="Click para Editar " href="' . base_url() . 'admin/anuncio/add_anuncio/' . $anuncio->anuncio_id . '"><span class="fa fa-pencil"></span></a>' . ' ';
        $action .= '<button type="button" data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs" title="Click para Eliminar " onclick="deleteAnuncio('.$anuncio->anuncio_id.')"><span class="fa fa-trash-o"></span></button>' . ' ';
        $sub_array[] = $action;
        $data[] = $sub_array;
      }

      render_table($data, $where);
    } else {
      redirect('admin/dashboard');
    }
  }
  private function guardar_archivo($dir, $name){
    $mi_archivo              = $name;
    $config['upload_path']   = $dir . "/";
    // $config['file_name']  = "nombre_archivo";
    $config['allowed_types'] = "*";
    $config['max_size']      = "50000000";
    $config['max_width']     = "20000000";
    $config['max_height']    = "20000000";
    $this->load->library('upload', $config);
    if (!$this->upload->do_upload($mi_archivo)){
      //*** ocurrio un error
      $data['uploadError'] = $this->upload->display_errors();
      echo $this->upload->display_errors();
      die();
      return;
    }
    return ($dataUpload = $this->upload->data());
  }
  public function save_anuncio($id = NULL){
    $created = true;
    $edited = true;
    if (!empty($created) || !empty($edited) && !empty($id)){
      $dir = "./uploads/anuncios/";
      if (!is_dir($dir)){
        mkdir($dir, 0777);
      }

      $dir_foto = trim($dir.'fotos/');
      $dir_adjunto = trim($dir.'adjuntos/');
      if (!is_dir($dir_foto)){
        mkdir($dir_foto, 0777);
      }
      if (!is_dir($dir_adjunto)){
        mkdir($dir_adjunto, 0777);
      }

      $data['titulo']      = $this->input->post('titulo');
      $data['descripcion'] = $this->input->post('descripcion');
      $data_upload_foto    = $this->guardar_archivo($dir_foto, 'foto');
      $data['foto']        = $data_upload_foto['file_name'];
      
      $adjunto = '';
      if(isset($_FILES['adjunto']) && $_FILES['adjunto']['name'] != ""){
        $data_upload_adjunto = $this->guardar_archivo($dir_adjunto, 'adjunto');
        $adjunto     = $data_upload_adjunto['file_name'];
      }
      $data['adjunto'] = $adjunto;
      
      $this->anuncio_model->_table_name  = 'tbl_anuncios';
      $this->anuncio_model->_primary_key = "anuncio_id";
      $return_id = $this->anuncio_model->save($data, $id);
      if($return_id){
        $type = "success";
        $message = 'Registro Exitoso';
      }else{
        $type = "error";
        $message = 'Fallo el registro';
      }
      set_message($type, $message);
      redirect('admin/anuncio');
    }
  }
  // ------------------- ACTIVAR/DESACTIVAR ANUNCIO
  function active($id, $status){
    if(isset($id) && $id > 0 && isset($status)){
      $st_chck = ($status == "on") ? 1 : 0;
      $data_anuncio = $this->db->where('anuncio_id', $id)->get('tbl_anuncios')->row();
      if(count($data_anuncio) > 0){
        $this->db->where('anuncio_id', $id);
        if($this->db->update('tbl_anuncios', ['status' => $st_chck])){
          $data = ['type' => 'success','message' => 'Anuncio Actualizado'];
        }else{
          $data = ['type' => 'error','message' => 'Error al actualizar(A)'];
        }
      }else{
        $data = ['type' => 'error','message' => 'Error al actualizar(B)'];
      }            
    }else{
      $data = ['type' => 'error','message' => 'Error al actualizar(C)'];
    }
    echo json_encode($data);
    die();
  }
  // ------------------- ELIMINAR ANUNCIO
  public function delete_anuncio($id = NULL){
    if(isset($id)){
      $data_anuncio = $this->db->where('anuncio_id', $id)->get('tbl_anuncios')->row();
      if(count($data_anuncio) > 0){
        if($this->db->where('anuncio_id', $id)->delete('tbl_anuncios')){
          $data = ['type' => 'success','message'=>'Registro Eliminado con Exito!!'];
        }else{
          $data = ['type' => 'error','message' =>'Ocurrio un Error al Eliminar el Registro.'];
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

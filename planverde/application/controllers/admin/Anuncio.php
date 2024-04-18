<?php
class Anuncio extends Admin_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('anuncio_model');
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
        // $foto = (!empty( $anuncio->foto )) ? '<img width="40px" height="40px" alt="'.$anuncio->foto.'" src="https://drive.google.com/uc?export=view&id='. $anuncio->id_image .'">' : '';
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
  // ------------------- GUARDAR ARCHIVOS MULTIMEDIA
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
  // ------------------- GUARDAR ANUNCIO
  public function save_anuncio($id = NULL){
    // ------------------ DEFINIR LA RUTA PARA AMBOS CASOS, FOTOS Y ADJUNTOS...
    $dir = "./uploads/anuncios/";
    if(!is_dir($dir)){
      mkdir($dir, 0777);
    }
    $dir_foto = trim($dir.'fotos/');
    $dir_adjunto = trim($dir.'adjuntos/');
    if(!is_dir($dir_foto)){
      mkdir($dir_foto, 0777);
    }
    if(!is_dir($dir_adjunto)){
      mkdir($dir_adjunto, 0777);
    }

    if(empty($id) || $id == "" || $id == NULL){
      $data['section_id'] = $this->input->post('section_id');
      $sectioninfo = $this->db->select('name, id_carpeta')->where('id', $this->input->post('section_id'))->order_by('id', 'DESC')->limit(1)->get('tbl_announcements_section')->row();
      $data['titulo'] = $this->input->post('titulo');
      $data['descripcion'] = $this->input->post('descripcion');
      $folderparentId = $sectioninfo->id_carpeta;
      if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ""){
        $data_upload_foto    = $this->guardar_archivo($dir_foto, 'foto');
        $data['foto']        = $data_upload_foto['file_name'];
        if($_FILES['foto']['error'] === UPLOAD_ERR_OK){
          $file = $_FILES['foto']['tmp_name'];
          $name = $_FILES['foto']['name'];
          $description = "Imagen subida a: ".$sectioninfo->name;
          $upload = uploadFileToDrive($name,$file,$description,$folderparentId);
          $data['id_image'] = $upload->id;
        }
      }

      if(isset($_FILES['adjunto']) && $_FILES['adjunto']['name'] != ""){
        $data_upload_adjunto = $this->guardar_archivo($dir_adjunto, 'adjunto');
        $data['adjunto']     = $data_upload_adjunto['file_name'];
        if($_FILES['adjunto']['error'] === UPLOAD_ERR_OK){
          $file = $_FILES['adjunto']['tmp_name'];
          $name = $_FILES['adjunto']['name'];
          $description = "Archivo subido a: ".$sectioninfo->name;
          $upload = uploadFileToDrive($name,$file,$description,$folderparentId);
          $data['id_document'] = $upload->id;
        }
      }
      
      $this->anuncio_model->_table_name  = 'tbl_anuncios';
      $this->anuncio_model->_primary_key = "anuncio_id";
      $return_id = $this->anuncio_model->save($data, $id);
      if($return_id){
        $returnanuncio = ['action' => 'created','type' => "success",'message' => "Registo Exitoso", 'anuncio_id' => $return_id];
      }else{
        $returnanuncio = ['action' => 'created','type' => "error",'message' => "Falló el registro", 'anuncio_id' => ''];
      }
    }else{
      $anuncioinfo = $this->db->select('foto, adjunto, id_image, id_document')->where('anuncio_id', $id)->order_by('anuncio_id', 'DESC')->limit(1)->get('tbl_anuncios')->row();
      $data['section_id'] = $this->input->post('section_id');
      $sectioninfo = $this->db->select('name, id_carpeta')->where('id', $this->input->post('section_id'))->order_by('id', 'DESC')->limit(1)->get('tbl_announcements_section')->row();
      $data['titulo'] = $this->input->post('titulo');
      $data['descripcion'] = $this->input->post('descripcion');
      $folderparentId = $sectioninfo->id_carpeta;
      if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ""){
        // --------------- ELIMINAR LA FOTO ANTERIOR
        if($anuncioinfo->foto != ""){
          $filePath = $dir_foto . $anuncioinfo->foto;
          if(file_exists($filePath)){
            if(unlink($filePath)){
              // echo "El archivo ha sido eliminado con éxito.";
            }else{
              // echo "Hubo un problema al eliminar el archivo.";
            }
          }else{
            // echo "El archivo no existe en el directorio especificado.";
          }
        }
        $data_upload_foto    = $this->guardar_archivo($dir_foto, 'foto');
        $data['foto']        = $data_upload_foto['file_name'];
        if($anuncioinfo->id_image != ""){
          $id_image = driveDelete($anuncioinfo->id_image); // ELIMINAR LA FOTO ANTERIOR SUBIDA EN GOOGLE DRIVE
        }
        if($_FILES['foto']['error'] === UPLOAD_ERR_OK){
          $file = $_FILES['foto']['tmp_name'];
          $name = $_FILES['foto']['name'];
          $description = "Imagen subida a: ".$sectioninfo->name;
          $upload = uploadFileToDrive($name,$file,$description,$folderparentId);
          $data['id_image'] = $upload->id;
        }
      }

      if(isset($_FILES['adjunto']) && $_FILES['adjunto']['name'] != ""){
        // --------------- ELIMINAR EL ADJUNTO ANTERIOR
        if($anuncioinfo->adjunto != ""){
          $filePath = $dir_adjunto . $anuncioinfo->adjunto;
          $filePathAlternative = $dir_foto . $anuncioinfo->adjunto;
          if(file_exists($filePath)){
            if(unlink($filePath)){
              // echo "El archivo ha sido eliminado con éxito.";
            }else{
              // echo "Hubo un problema al eliminar el archivo.";
            }
          }else if(file_exists($filePathAlternative)){
            if(unlink($filePathAlternative)){
              // echo "El archivo ha sido eliminado con éxito.";
            }else{
              // echo "Hubo un problema al eliminar el archivo.";
            }
          }else{
            // echo "El archivo no existe en el directorio especificado.";
          }
        }
        $data_upload_adjunto = $this->guardar_archivo($dir_adjunto, 'adjunto');
        $data['adjunto']     = $data_upload_adjunto['file_name'];
        if($anuncioinfo->id_document != ""){
          $id_document = driveDelete($anuncioinfo->id_document); // ELIMINAR EL ADJUNTO ANTERIOR SUBIDO EN GOOGLE DRIVE
        }
        if($_FILES['adjunto']['error'] === UPLOAD_ERR_OK){
          $file = $_FILES['adjunto']['tmp_name'];
          $name = $_FILES['adjunto']['name'];
          $description = "Archivo subido a: ".$sectioninfo->name;
          $upload = uploadFileToDrive($name,$file,$description,$folderparentId);
          $data['id_document'] = $upload->id;
        }
      }
      
      $this->anuncio_model->_table_name  = 'tbl_anuncios';
      $this->anuncio_model->_primary_key = "anuncio_id";
      $return_id = $this->anuncio_model->save($data, $id);
      if($return_id){
        $returnanuncio = ['action' => 'updated','type' => "success",'message' => "Actualización Exitosa", 'anuncio_id' => $return_id];
      }else{
        $returnanuncio = ['action' => 'updated','type' => "error",'message' => "Falló la actualización", 'anuncio_id' => ''];
      }
    }
    set_message($returnanuncio['type'], $returnanuncio['message']);
    redirect('admin/anuncio');
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
      $data_anuncio = $this->db->select('foto, adjunto, id_image, id_document')->where('anuncio_id', $id)->order_by('anuncio_id', 'DESC')->limit(1)->get('tbl_anuncios')->row();
      if(count($data_anuncio) > 0){
        // ------------------ DEFINIR LA RUTA PARA AMBOS CASOS, FOTOS Y ADJUNTOS...
        $dir = "./uploads/anuncios/";
        $dir_foto = trim($dir.'fotos/');
        $dir_adjunto = trim($dir.'adjuntos/');
        // --------------- ELIMINAR LA FOTO ANTERIOR
        if($data_anuncio->foto != ""){
          $filePath = $dir_foto . $data_anuncio->foto;
          if(file_exists($filePath)){
            if(unlink($filePath)){
              // echo "El archivo ha sido eliminado con éxito.";
            }else{
              // echo "Hubo un problema al eliminar el archivo.";
            }
          }else{
            // echo "El archivo no existe en el directorio especificado.";
          }
        }
        
        if($data_anuncio->id_image != ""){
          $id_image = driveDelete($data_anuncio->id_image); // ELIMINAR LA FOTO ANTERIOR SUBIDA EN GOOGLE DRIVE
        }

        // --------------- ELIMINAR EL ADJUNTO ANTERIOR
        if($data_anuncio->adjunto != ""){
          $filePath = $dir_adjunto . $data_anuncio->adjunto;
          $filePathAlternative = $dir_foto . $data_anuncio->adjunto;
          if(file_exists($filePath)){
            if(unlink($filePath)){
              // echo "El archivo ha sido eliminado con éxito.";
            }else{
              // echo "Hubo un problema al eliminar el archivo.";
            }
          }else if(file_exists($filePathAlternative)){
            if(unlink($filePathAlternative)){
              // echo "El archivo ha sido eliminado con éxito.";
            }else{
              // echo "Hubo un problema al eliminar el archivo.";
            }
          }else{
            // echo "El archivo no existe en el directorio especificado.";
          }
        }

        if($data_anuncio->id_document != ""){
          $id_document = driveDelete($data_anuncio->id_document); // ELIMINAR EL ADJUNTO ANTERIOR SUBIDO EN GOOGLE DRIVE
        }

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

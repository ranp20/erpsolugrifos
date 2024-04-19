<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Document extends Client_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model('admin_model');
    $this->load->model('document_client_model');
  }
  public function add_document($id_cat = NULL){
    if(!empty($id_cat)){
      $data['title']          = ('Nuevo Documento');
      $data['client_id']      = $_SESSION['client_id'];
      $data['id_subcategoria'] = $id_cat;
      $data['categoria'] = $this->db->get_where('tbl_subcategoria', ['subcategoria_id' => $id_cat])->row()->nombre_subcategoria;
      $data['all_categories'] = $this->db->get('tbl_categoria')->result_array();
      $data['all_clients']    = $this->db->get('tbl_cliente')->result_array();
      $data['all_anios']    = $this->db->get('tbl_anio')->result_object();
      $data['all_sedes']    = $this->db->where('cliente_id', $data['client_id'])->get('tbl_sedes')->result_object();
      $data['subview']        = $this->load->view('client/documents/add_document', $data, FALSE);
      $this->load->view('client/_layout_modal', $data);
    }else{
      redirect("client/dashboard");
    }
  }
  public function list($anio = NULL, $id_subcat = NULL){
    if(!empty($id_subcat) && !empty($anio)){
        $sede_id = "";
        $data['title'] = 'Documentos - ';
        $data['page'] = 'Documentos - ';
        $data['id_subcategoria'] = $id_subcat;
        $documents = [];
        $info_client = [];
        $cliente_id = $this->db->where(['user_id' => $this->session->userdata('user_id')])->get('tbl_account_details')->row()->company;
        $info_client[] = [
          'client_id' => $cliente_id
        ];
        // LISTAR DOCUMENTOS - POR (ID_CLIENTE, ID_CATEGORÍA E DEL ID_AÑO)
        /*
        echo "<pre>";
        echo $cliente_id."<br>";
        echo $id_subcat."<br>";
        echo $anio."<br>";
        echo "</pre>";
        exit();
        */
        //$data_documents = [];
        $sede_id = $_SESSION['sede'];
        // $data_documents = $this->db->order_by('anio','DESC')->get_where('tbl_documents', ['client_id' => $cliente_id, 'sede_id' => $sede_id,'categoria_id' => $id_subcat, 'anio' => $anio])->result_object();
        /*
        $data_documents = $this->db->query("SELECT 
        document_id, 
        client_id, 
        GROUP_CONCAT(sede_id != 0) as 'sede_id',
        nombre,
        categoria_id,
        anio,
        mes,
        user_id,
        created_at,
        ruta,
        id_archivo
        FROM tbl_documents
        WHERE client_id = ".$cliente_id." AND 
        sede_id = ".$sede_id." AND 
        categoria_id = ".$id_subcat." AND 
        anio = ".$anio." GROUP BY document_id ORDER BY anio DESC")->result_object();
        */
        // $data_documents = $this->db->prepare('CALL sp_provicionalconcat_list_documents('.$cliente_id.','.$sede_id.','.$id_subcat.','.$anio.')')->result_object();
        // $data_documents = $this->db->prepare('CALL sp_provicionalconcat_list_documents(?,?,?,?)', ['_client_id' => $cliente_id, '_sedeid' => $sede_id,'_subcatid' => $id_subcat, '_anio' => $anio])->result_object();
        // $data_documents = $this->db->prepare('CALL sp_provicionalconcat_list_documents(?,?,?,?)', array($cliente_id, $sede_id, $id_subcat, $anio));
        // $data_documents = $this->db->prepare('EXEC sp_provicionalconcat_list_documents('.$cliente_id.','.$sede_id.','.$id_subcat.','.$anio.')')->result_object();
        // $data_documents = $this->db->prepare('EXEC sp_provicionalconcat_list_documents(?,?,?,?)', ['_client_id' => $cliente_id, '_sedeid' => $sede_id,'_subcatid' => $id_subcat, '_anio' => $anio])->result_object();
        /*
        $data_documents = $this->db->prepare('EXEC sp_provicionalconcat_list_documents(?,?,?,?)');
        $data_documents->bindParam(1, $cliente_id); 
        $data_documents->bindParam(2, $sede_id);
        $data_documents->bindParam(3, $id_subcat); 
        $data_documents->bindParam(4, $anio);
        $data_documents->result_object();
        */
        // $data_documents->execute();
        /*
        echo "<pre>";
        print_r($data_documents);
        echo "</pre>";
        exit();
        */
        
        // LISTAR DOCUMENTOS SIN SEDE O sede_id = 0;
        $dataArray_1 = $this->db->order_by('anio','DESC')->get_where('tbl_documents', ['client_id' => $cliente_id, 'categoria_id' => $id_subcat, 'anio' => $anio])->result_object();
        // LISTAR DOCUMENTOS CON SEDE O sede_id != 0;
        $dataArray_2 = $this->db->order_by('anio','DESC')->get_where('tbl_documents', ['client_id' => $cliente_id, 'sede_id' => $sede_id,'categoria_id' => $id_subcat, 'anio' => $anio])->result_object();
        
        $data_documents = array_unique(array_merge($dataArray_1,$dataArray_2), SORT_REGULAR);
    
        foreach($data_documents as $key => $doc){
          $anio = $this->db->where(['anio_id' => $doc->anio])->get('tbl_anio')->row()->anio;
          $data_subcategory = $this->db->where(['subcategoria_id' => $id_subcat])->get('tbl_subcategoria')->row();
          $category_id = $data_subcategory->categoria_id;
          $subcategory_name = $data_subcategory->nombre_subcategoria;
          $category_name = $this->db->get_where('tbl_categoria', array('categoria_id' => $category_id))->row()->nombre_categoria;
          $name_folder = $category_id . '-' . str_replace(" ", "_", $category_name);
          $dir = trim('./uploads/documents/' . $name_folder);
          $name_folder = $id_subcat . '-' . str_replace(" ", "_", $subcategory_name);
          $dir =  trim($doc->ruta);
          $created_at =  $doc->created_at;
          $documents[] = [
            'id_categoria' => $category_id,
            'anio' => $anio,
            'mes' => $doc->mes,
            'nombre' => $doc->nombre,
            'ruta' => $dir,
            'id_archivo' => 'https://drive.google.com/uc?export=download&id='.$doc->id_archivo,
            'created_at' => $created_at
          ];
        }
        $data['all_documents'] = (object)$documents;
        $data['info_client'] = $info_client;
        $data['subview'] = $this->load->view('client/documents/list', $data, TRUE);
        $this->load->view('client/_layout_main', $data);
    }else{
      redirect("client/dashboard");
    }
  }
  // public function save_document($id = null){
  //   $target_path = base_url() . "uploads/documents/";
  //   $subcategory_id = $id;
  //   $data_subcategory = $this->db->where(['subcategoria_id' => $subcategory_id])->get('tbl_subcategoria')->row();
  //   $category_id = $data_subcategory->categoria_id;
  //   $subcategory_name = $data_subcategory->nombre_subcategoria;
  //   $category_name = $this->db->get_where('tbl_categoria', array('categoria_id' => $category_id))->row()->nombre_categoria;
  //   $name_folder = $category_id . '-' . str_replace(" ", "_", $category_name);
  //   $dir = trim('./uploads/documents/' . $name_folder);
  //   $name_folder = $subcategory_id . '-' . str_replace(" ", "_", $subcategory_name);
  //   $dir =  trim($dir . '/' . $name_folder);
  //   $data_anio = $this->db->where(['anio_id' => $this->input->post('anio')])->get('tbl_anio')->row();
  //   $name_folder = $data_anio->anio;
  //   $dir =  trim($dir . '/' . $name_folder);

  //   if(!is_dir($dir)){
  //     mkdir($dir, 0777);
  //   }

  //   $data_upload = $this->guardar_archivo($dir);
  //   $cliente_id = $this->db->where(['user_id' => $this->session->userdata('user_id')])->get('tbl_account_details')->row()->company;

  //   $data = [
  //     'client_id'    => $cliente_id,
  //     'nombre'       => $this->input->post('nombre'),
  //     'categoria_id' => $subcategory_id,
  //     'anio'         => $this->input->post('anio'),
  //     'mes'          => $this->input->post('mes'),
  //     'user_id'      => 0,
  //     'ruta'         => $data_upload['file_name']
  //   ];

  //   $this->db->insert('tbl_documents', $data);
  //   $id = $this->db->insert_id();
  //   $type = "success";
  //   $message = 'Registro Exitoso';
  //   set_message($type, $message);
  //   redirect('client/document/list/' . $data_anio->anio_id . '/' . $subcategory_id);
  // }
  // private function guardar_archivo($dir){
  //   $mi_archivo              = 'files';
  //   $config['upload_path']   = $dir . "/";
  //   // $config['file_name']  = "nombre_archivo";
  //   $config['allowed_types'] = "*";
  //   $config['max_size']      = "50000000";
  //   $config['max_width']     = "20000000";
  //   $config['max_height']    = "20000000";
  //   $this->load->library('upload', $config);

  //   if(!$this->upload->do_upload($mi_archivo)){
  //     $data['uploadError'] = $this->upload->display_errors();
  //     echo $this->upload->display_errors();
  //     return;
  //   }
  //   return ($dataUpload = $this->upload->data());
  // }
}

<?php
// CADA CATEGORIA Y SUB CATEGORIA A L REGISTRAR O ACTUALIZAR SU NOMBRE DEBE CREAR UNA CARPETA Y/O SUBCARPETA 
// AL CREAR EL AÑO  JALAREMOS LAS CATEGORIAS Y SUBCATEGORIAS PARA CREAR DENTRO DE CADA SUBCATEGORIA LA CARPETA CON EL AÑO Q SE ESTA GUARDANDO
// verificaremos las carpetad tanto de categoria como subcategoria previo a la creacion de carpeta año


class Sede extends Admin_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index($id = NULL)
  {
    redirect("admin/dashboard");
  }

  public function form()
  {
    $data['title'] = 'Nueva Sede Operativa';
    $data['page'] = 'Nueva Sede Operativa';
    $this->load->view('admin/sede/form_sede', $data);
    // $this->load->view('admin/_layout_modal', $data);
  }

  public function cmb_x_cliente( $cliente_id = NULL, $sede_id = NULL)
  {
    //$data['all_sedes'] = $this->db->query("call sp_listSedesByCliente('".$cliente_id."')")->result_object();
    $data['title'] = 'Combo Sede por cliente';
    $data['page'] = 'Combo Sede por cliente';
    $data['sede_id'] = $sede_id;
    $data['cliente_id'] = $cliente_id;
    $data['sedeid_tblsedes'] = $this->db->where(['cliente_id' => $cliente_id])->get('tbl_sedes')->result_object();
    $data['sedeid_tblpvorders'] = $this->db->where(['cliente_id' => $cliente_id])->get('tbl_pv_orders')->result_object();
    /*
    $tmp = "<select name='sede_id'  id='sede_id' class='form-control select_box' style='width: 100%' required>";
    if (!empty($data['all_sedes'])) {
        $tmp.= "<option value=''>Selecciona</option>";
        foreach ($data['all_sedes'] as $sede) {
            $sedeId = ( $sede_id == $sede->sede_id ) ? 'selected' : '';
            $tmp.= "<option value='".$sede->sede_id."' ".$sedeId." >".$sede->sede."</option>";
        }
    }else{
        $tmp.= "<option value=''>Seleccionar</option>";
    }
    $tmp.= "</select>";
    echo $tmp;
    */
    $this->load->view('admin/sede/cmb_sede', $data);
    // $this->load->view('admin/_layout_modal', $data);
  }
}
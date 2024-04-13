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

  public function cmb_x_cliente( $cliente_id )
  {
    $data['title'] = 'Combo Sede por cliente';
    $data['page'] = 'Combo Sede por cliente';
    $data['all_sedes'] = $this->db->where(['cliente_id' => $cliente_id])->get('tbl_sedes')->result_object();
    $this->load->view('admin/sede/cmb_sede', $data);
    // $this->load->view('admin/_layout_modal', $data);
  }
}
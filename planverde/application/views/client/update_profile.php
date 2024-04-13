<!-- <?php include_once 'assets/admin-ajax.php'; ?> -->
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$user_id = $this->session->userdata('user_id');
$profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();

$user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
// echo $profile_info->company;
$cliente_info = $this->db->where(['cliente_id' => $profile_info->company])->get('tbl_cliente')->row();
// print_r($cliente_info);

// aQMiGuEL
$sedes_info = $this->db->where(['cliente_id' => $profile_info->company])->get('tbl_sedes')->result();
?>
<div class="row">
  <div class="col-sm-1"></div>
  <div class="cDsh__secCont">
    <div class="cDsh__secCont--cFrmCard">
      <div class="panel panel-custom cDsh__secCont--cFrmCard__cnt">
        <header class="panel-heading cDsh__secCont--cFrmCard__cnt__cHeading">
          <h3><?= $title;?></h3>
        </header>
        <?php echo form_open(base_url() . 'client/settings/updated_aQ/', array('id' => 'cliente', 'class' => 'form-horizontal')); ?>
        <div class="cCtrlsGroup__Row">
          <div class="ctFrm__cCtrlsGroup fx_not-upgradeable">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Datos Principales</span>
            </div>
            <div class="form-group">
              <div class="col-sm-8">
                <label class=" control-label"><?= ('Razon social') ?></label>
                <p class="mb-0"><?php echo (isset($cliente_info->razon_social)) ? $cliente_info->razon_social : ''; ?></p>
              </div>
              <div class="col-sm-4">
                <label class=" control-label"><?= ('RUC') ?></label>
                <p class="mb-0"><?php echo (isset($cliente_info->ruc)) ? $cliente_info->ruc : ''; ?></p>
              </div>
            </div>
          </div>
          <div class="ctFrm__cCtrlsGroup fx_not-upgradeable">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Dirección</span>
            </div>
            <div class="form-group grpcol-2 mb-0">
              <div class="col-sm-6">
                <label class=" control-label">Distrito</label>
                <p class="m-0"><?php echo (isset($cliente_info->distrito)) ? $cliente_info->distrito : ''; ?></p>
              </div>
              <div class="col-sm-6">
                <label class=" control-label">Provincia</label>
                <p class="mb-0"><?php echo (isset($cliente_info->provincia)) ? $cliente_info->provincia : ''; ?></p>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <label class=" control-label">Direccion Legal</label>
                <p class="mb-0"><?php echo (isset($cliente_info->direccion_legal)) ? $cliente_info->direccion_legal : ''; ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="cCtrlsGroup__Row">
          <div class="ctFrm__cCtrlsGroup fx_not-upgradeable">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Representante Legal</span>
            </div>
            <div class="form-group">
              <div class="col-sm-8">
                <label class=" control-label">Nombre</label>
                <p class="mb-0"><?php echo (isset($cliente_info->representante_legal)) ? $cliente_info->representante_legal : ''; ?></p>
              </div>
              <div class="col-sm-4">
                <label class=" control-label">DNI</label>
                <p class="mb-0"><?php echo (isset($cliente_info->dni_representante) && $cliente_info->dni_representante != 0) ? $cliente_info->dni_representante : 'No especificado'; ?></p>
              </div>
            </div>
          </div>
          <div class="ctFrm__cCtrlsGroup fx_not-upgradeable">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Gerente Legal</span>
            </div>
            <div class="form-group">
              <div class="col-sm-8">
                <label class=" control-label">Nombre</label>
                <p class="mb-0"><?php echo (isset($cliente_info->gerente_legal)) ? $cliente_info->gerente_legal : ''; ?></p>
              </div>
              <div class="col-sm-4">
                <label class=" control-label">DNI</label>
                <p class="mb-0"><?php echo (isset($cliente_info->dni_gerente) && $cliente_info->dni_gerente != 0) ? $cliente_info->dni_gerente : 'No especificado'; ?></p>
              </div>
            </div>
          </div>
          <div class="ctFrm__cCtrlsGroup fx_not-upgradeable">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Supervisor</span>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <label class=" control-label">Nombre</label>
                <p class="mb-0"><?php echo (isset($cliente_info->supervisor) && $cliente_info->supervisor != "-") ? $cliente_info->supervisor : 'No especificado'; ?></p>
              </div>
              <div class="col-sm-6">
                <label class=" control-label">Correo Electrónico</label>
                <p class="mb-0"><?php echo (isset($cliente_info->correo)) ? $cliente_info->correo : ''; ?></p>
              </div>
              <div class="col-sm-4">
                <label class=" control-label">Celular</label>
                <p class="mb-0"><?php echo (isset($cliente_info->celular) && $cliente_info->celular != 0) ? $cliente_info->celular : 'No especificado'; ?></p>
              </div>
            </div>
          </div>
        </div>
        <?php
          if(count($sedes_info) != 0 && count($sedes_info) != ""){
        ?>
        <div class="ctFrm__cCtrlsGroup">
          <div class="ctFrm__cCtrlsGroup__cFlagTitle">
            <span>Lista de sedes</span>
          </div>
          <div id="sedes">
          <?php
            foreach ($sedes_info as $key => $data) :
              echo $this->load->view('client/sede/form_sede', $data, TRUE);
            endforeach;
          ?>
          </div>
        </div>
        <?php
          }else{
        ?>
        <div class="ctFrm__cCtrlsGroup">
          <div class="ctFrm__cCtrlsGroup__cFlagTitle">
            <span>Lista de sedes</span>
          </div>
          <div id="sedes">
            <div class="col-12" id="cScreenAny_sedes">
              <h3>No existe ninguna Sede</h3>
            </div>
          </div>
        </div>
        <?php
          }
        ?>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
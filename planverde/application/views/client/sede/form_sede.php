<div class="panel panel-custom sede-panel-new">
  <header class="panel-heading ">
    <span>Sede Operativa</span>
  </header>
  <div class="panel-body">
    <div class="cCtrlsGroup__Row">
      <div class="ctFrm__cCtrlsGroup fx_not-upgradeable">
        <div class="ctFrm__cCtrlsGroup__cFlagTitle">
          <span>Datos Principales</span>
        </div>
        <div class="form-group">
          <div class="col-sm-6">
            <label class=" control-label">Administrador</label>
            <p class="mb-0"><?php echo (isset($administrador)) ? $administrador : ''; ?></p>
          </div>
          <div class="col-sm-6">
            <label class=" control-label">Administrador SST</label>
            <p class="mb-0"><?php echo (isset($administrador_sst) && $administrador_sst != "-") ? $administrador_sst : 'No especificado'; ?></p>
          </div>
          <div class="col-sm-4">
            <label class=" control-label">Correo</label>
            <p class="mb-0"><?php echo (isset($correo)) ? $correo : ''; ?></p>
          </div>
          <div class="col-sm-4">
            <label class=" control-label">Celular</label>
            <p class="mb-0"><?php echo (isset($celular) && $celular != 0) ? $celular : 'No especificado'; ?></p>
          </div>
        </div>
      </div>
      <div class="ctFrm__cCtrlsGroup fx_not-upgradeable">
        <div class="ctFrm__cCtrlsGroup__cFlagTitle">
          <span>Dirección</span>
        </div>
        <div class="form-group">
          <div class="col-sm-8">
            <label class=" control-label">Direccion</label>
            <p class="mb-0"><?php echo (isset($direccion)) ? $direccion : ''; ?></p>
          </div>
          <div class="col-sm-4">
            <label class=" control-label">Distrito</label>
            <p class="mb-0"><?php echo (isset($distrito)) ? $distrito : ''; ?></p>
          </div>
          <div class="col-sm-4">
            <label class=" control-label">Provincia</label>
            <p class="mb-0"><?php echo (isset($provincia)) ? $provincia : ''; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
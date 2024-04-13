<div class="panel panel-custom">
  <div class="panel-heading panelCtm__header">
    <header>
      <h3 class="mt-0"><?= $title;?></h3>
    </header>
    <button type="button" class="close" data-dismiss="modal">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Close</span>
    </button>
  </div>
  <?php echo form_open(base_url('admin/anio/save_anio/'. (isset($anio_info) ? $anio_info->anio_id : '')), array('id' => 'anio', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?= ('Año') ?></label>
    <div class="col-sm-5">
      <input type="text" min="2000" max="2100" maxlength="4" name="anio" class="form-control" placeholder="Ingrese el año" value="<?php echo (isset($anio_info->anio)) ? $anio_info->anio : ''; ?>" required>
    </div>
  </div>
  <div class="form-group mt">
    <label class="col-lg-3"></label>
    <div class="col-lg-6">
      <?php if(isset($anio_info)): ?>
        <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
      <?php else: ?>
        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
      <?php endif; ?>
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>
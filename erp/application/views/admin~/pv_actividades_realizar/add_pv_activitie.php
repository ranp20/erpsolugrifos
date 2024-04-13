<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= $title ?>
  </header>
  <?php echo form_open(base_url('admin/pv_actividades_realizar/save_pv_activitie/'.(( $pv_activitie_info ) ? $pv_activitie_info->pv_order_activitie_id : '') ), array('id' => 'pv_activitie', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>


  <div class="form-group">
    <label class="col-sm-3 control-label">Fecha:</label>
    <div class="col-sm-5">
      <input type="date" name="txtf" autofocus id="txtf" class="form-control" required>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Archivo:</label>
    <div class="col-sm-5">
    <input type="file" name="txtfile" autofocus id="txtfile" class="form-control" required>
    </div>
  </div>

  <div class="form-group mt">
    <label class="col-xs-3"></label>
    <div class="col-xs-6">
      <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>

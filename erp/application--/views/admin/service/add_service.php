<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= $title ?>
  </header>
  <?php echo form_open(base_url('admin/service/save_service/'.(( $service_info ) ? $service_info->service_id : '') ), array('id' => 'service_form', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>


  <div class="form-group">
    <label class="col-sm-2 control-label">Servicio:</label>
    <div class="col-sm-8">
      <input type="text" name="service" id="service" class="form-control" placeholder="Nombre del Servicio" required value="<?php echo ( $service_info ) ? $service_info->service : ''; ?>">
    </div>
  </div>
  
  <div class="form-group">
    <label class="col-sm-2 control-label">Descripcion:</label>
    <div class="col-sm-8">
      <textarea name="descripcion" id="descripcion" class="description" placeholder="Descripción del Servicio" cols="50" rows="50" required ><?php echo (isset( $service_info->descripcion )) ? $service_info->descripcion : ''; ?></textarea>
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
<script>
  $(document).ajaxComplete(function(){
    $("#descripcion").summernote({
      height: 150,
  
    });

  })
/*$('#myModal_large').on('loaded.bs.modal', function() {
  })*/
    
</script>
<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    CULMINAR COTIZACION
  </header>
  <?php echo form_open(base_url('admin/cotizacion/save_forms/' . $form . '/' . $id), array('id' => 'Cotizacion', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>




  <div class="form-group">
    <label class="col-sm-3 control-label">Observaciones</label>
    <div class="col-sm-5">
      <textarea name="observaciones" id="observaciones" cols="30" rows="5" class="form-control"></textarea>
    </div>
  </div>


  <div class="form-group mt">
    <label class="col-lg-3"></label>
    <div class="col-lg-9">
      <button type="submit" class="btn btn-sm btn-primary">Culminar Proceso</button>
      <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>

<script type="text/javascript">


  $('#myModal').on('loaded.bs.modal', function() {
    console.log('ok')

    // $("#active-achivo").bootstrapToggle();
    $("input[type=checkbox]").bootstrapToggle();


  })
</script>
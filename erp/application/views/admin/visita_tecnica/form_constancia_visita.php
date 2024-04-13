<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= ('Subir constancia') ?>
  </header>
  <?php echo form_open(base_url('admin/visita_tecnica/save_forms/'.$form.'/'.$id), array('id' => 'visita_tecnica', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>


  <div class="form-group">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
    <label class="control-label">Cliente: </label>

      <span class="text-primary"><?php echo $cliente; ?></span>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
    <label class="control-label">Sede: </label>

      <span class="text-primary"><?php echo $sede; ?></span>
    </div>
  </div>


  <div class="form-group">
    <label class="col-sm-3 control-label">Observaciones</label>
    <div class="col-sm-5">
      <textarea name="observaciones" id="observaciones" cols="30" rows="5" class="form-control"></textarea>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label ">Fecha Visita</label>
    <div class="col-sm-5">
      <input type="text" name="start_date" class="form-control start_date" placeholder="YYYY-mm-dd" required>
    </div>
  </div>

  <div class="document" >
    <div class="form-group">
      <label class="col-sm-3 control-label">Archivo</label>
      <div class="col-sm-5">
        <input type="file" name="files" class="form-control" placeholder="Archivo">
      </div>
    </div>
  </div>

  <div class="form-group mt">
    <label class="col-lg-3"></label>
    <div class="col-lg-3">
      <button type="submit" class="btn btn-sm btn-primary"><?= lang('save') ?></button>
      <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>

<script type="text/javascript">

  $(document).on('change', '#active-achivo', function() {
    if ($(this).prop('checked') == true) {
      $(".document").css({
        'visibility': 'visible'
      })
    } else {
      $(".document").css({
        'visibility': 'hidden'
      })
    }
  })

</script>
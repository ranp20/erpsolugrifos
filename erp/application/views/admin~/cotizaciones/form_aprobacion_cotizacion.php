<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    APROBACION DE COTIZACIÓN
  </header>
  <?php echo form_open(base_url('admin/cotizacion/save_forms/'.$form.'/'.$id), array('id' => 'cotizacion', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>



  <div class="form-group">
    <label class="col-lg-3 control-label" for="aprobar">Aprobar Cotizacion</label>
    <div class="col-lg-5 checkbox">
      <input data-id="" data-toggle="toggle" name="aprobar" id="aprobar" value="1"  data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Observaciones</label>
    <div class="col-sm-5">
      <textarea name="observaciones" id="observaciones" cols="30" rows="5" class="form-control"></textarea>
    </div>
  </div>
<?php /* ?>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="active-achivo">Archivo</label>
    <div class="col-lg-5 checkbox">
      <input data-id="" data-toggle="toggle" name="aprobar" id="active-achivo" value="1"  data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
    </div>
  </div>

  <?php */ ?>

  <div class="document" style="visibility: hidden;">
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
  $(document).ready(function(){
    $('input[type=checkbox]').checkbox($('input[type=checkbox]').data());
    $('input[type=checkbox]').iCheck();
    /* $('input[type=checkbox]').each(function () {
          var $this = $(this);
          if ($this.data('checkbox')) return;
          $this.checkbox($this.data());
        }); */

  })
</script>
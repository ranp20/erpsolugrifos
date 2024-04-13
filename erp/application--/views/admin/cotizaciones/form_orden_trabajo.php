
<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    EMISION DE ORDEN DE TRABAJO
  </header>
  <?php echo form_open(base_url('admin/cotizacion/save_forms/' . $form . '/' . $id), array('id' => 'cotizacion', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>

  <!-- <input type="checkbox" id="check_prueba" data-toggle="toggle" data-on="Ready" data-off="Not Ready" data-onstyle="success" data-offstyle="danger" class="checkboxT"> -->

  <?php /* ?>
  <!-- <div class="form-group">
    <label class="col-lg-3 control-label" for="aprobar">Aceptar Valoracion</label>
    <div class="col-lg-5 checkbox">
      <input data-id="" data-toggle="toggle" name="aprobar" id="aprobar" value="1"  data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
    </div>
  </div>-->
  <div class="form-group">
    <label class="col-lg-3 control-label" for="aprobar">Aprobar valorizacion</label>
    <div class="col-lg-5">
      <select name="apro_desa" id="apro_desa" class="form-control select_box" style="width: 100%" required>
        <option value="">Seleccione</option>
        <option value="0">Aprobar</option>
        <option value="1">Desaprobar</option>
      </select>
    </div>
  </div>
  <?php */ ?>


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
    <label class="col-sm-3 control-label">Area Asignada</label>
    <div class="col-sm-5">
      <div class="input-group">
        <select name="area_asignada" id="area_asignada" class=" form-control select_box " style="width: 100%" required>
          <option value="">Seleccionar</option>
          <?php foreach ($areas as $key => $area) : ?>
            <option value="<?php echo $area->designations_id; ?>" <?php echo ($area->designations_id == $area_inicio ) ? 'selected' : ''; ?>><?php echo $area->designations; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Detalle</label>
    <div class="col-sm-5">
      <textarea name="observaciones" id="observaciones" cols="30" rows="5" class="form-control"></textarea>
    </div>
  </div>


  <div class="form-group">
    <label class="col-sm-3 control-label">Fecha inicio</label>
    <div class="col-sm-5">
      <input type="text" name="start_date" class="form-control start_date" id="start_date" required>
    </div>
  </div>


  <div class="form-group">
    <label class="col-sm-3 control-label">Fecha Fin</label>
    <div class="col-sm-5">
      <input type="text" name="end_date" class="form-control end_date" id="end_date" required>
    </div>
  </div>

  
  <!--
  <div class="document" style="visibility: visible;">
    <div class="form-group">
      <label class="col-sm-3 control-label">Archivo
      
      </label>
      <div class="col-sm-5">
      
        <input type="file" name="files" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
      </div>
    </div>
  </div>
  -->
  

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
  $('#myModal').on('loaded.bs.modal', function () {
  
    // $("#active-achivo").bootstrapToggle();
    $("input[type=checkbox]").bootstrapToggle();
    

  })
</script>
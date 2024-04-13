
<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    EMISION DE ORDEN VISITA TECNICA
  </header>
  <?php echo form_open(base_url('admin/valorizacion/save_forms/' . $form . '/' . $id), array('id' => 'valorizacion', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>

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
    <label class="col-lg-3 control-label"><?= ('Area Operativa') ?> </label>
    <div class="col-lg-5">
      <div class="input-group">
        <select name="cliente_id" id="cliente_id" class="form-control select_box" style="width: 100%" required>
          <option value=""><?= lang('none') ?></option>
          <?php
          if (!empty($all_operativas)) {
            foreach ($all_operativas as $operativa) {

          ?>
              <option <?php echo ( isset( $area_inicio ) && $area_inicio == $operativa->designations_id ) ? 'selected' : ''; ?> value="<?= $operativa->designations_id ?>"><?= $operativa->designations ?></option>
          <?php
            }
          }
          ?>
        </select>

      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Observaciones</label>
    <div class="col-sm-5">
      <textarea name="observaciones" id="observaciones" cols="30" rows="5" class="form-control"></textarea>
    </div>
  </div>

  <?php  ?>
  <div class="form-group">
    <label class="col-lg-3 control-label" for="active-achivo">Archivo</label>
    <div class="col-lg-5 checkbox">
      <input data-id="" data-toggle="toggle" name="aprobar" id="active-achivo" value="1" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox" class="checkboxT">
    </div>
  </div>
  <?php  ?>

  <div class="document" style="visibility: hidden;">
    <div class="form-group">
      <label class="col-sm-3 control-label">Archivo
      
      </label>
      <div class="col-sm-5">
      
        <input type="file" name="files" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
      </div>
    </div>
  </div>
  <legend class="col-sm-12 text-center"> Fecha al realizar la Visita</legend>
  <div class="form-group">
    <label class="col-sm-3 control-label">Fecha Inicio</label>
    <div class="col-sm-5">
      <input type="date" name="fech_ini" class="form-control" required>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Fecha Final</label>
    <div class="col-sm-5">
      <input type="date" name="fech_fin" class="form-control" required>
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
  $('#myModal').on('loaded.bs.modal', function () {
  
    // $("#active-achivo").bootstrapToggle();
    $(".checkboxT").bootstrapToggle();
    

  })
</script>
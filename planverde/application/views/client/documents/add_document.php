<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= ('Nuevo Documento - ' . $categoria) ?>
  </header>
  <?php echo form_open(base_url('client/document/save_document/' . $id_subcategoria), array('id' => 'document', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>


  <div class="form-group">
    <label class="col-sm-3 control-label"><?= ('Sede') ?></label>
    <div class="col-sm-5">
      <select name="sede_id" id="sede_id" class="form-control select_box" style="width: 100%" required>
        <option value="">Selecciona</option>
        <?php
        if (!empty($all_sedes)) {
          foreach ($all_sedes as $sede) {
        ?>
            <option value="<?= $sede->sede_id ?>"
            <?php echo ( isset( $_SESSION['sede'] ) && $sede->sede_id == $_SESSION['sede']  ) ? 'selected' : ''; ?>
            ><?= $sede->direccion ?></option>
        <?php
          }
        }
        ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label"><?= ('nombre') ?></label>
    <div class="col-sm-5">
      <input type="text" name="nombre" class="form-control" placeholder="<?= ('nombre') ?>" required>
      <input type="hidden" name="subcategoria_id" value="<?php echo $id_subcategoria; ?>">
    </div>
  </div>



  <div class="form-group">
    <label class="col-sm-3 control-label"><?= ('Año') ?></label>
    <div class="col-sm-5">

      <select name="anio" class="form-control select_box" style="width: 100%" required>
        <option value="">Seleccione</option>
        <?php
        if ($all_anios) {
          foreach ($all_anios as $key => $anio) {
            echo '<option value="' . $anio->anio_id . '">' . $anio->anio . '</option>';
          }
        }
        ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 control-label"><?= ('Mes') ?> </label>
    <div class="col-lg-5">
      <div class="input-group">
        <select name="mes" class="form-control select_box" style="width: 100%" required>
          <option value="">Seleccione</option>
          <?php
          /* if (!empty($all_categories)) {
            foreach ($all_categories as $cate) {
              $cate = (object) $cate; */
          ?>
          <option value="ENERO">ENERO</option>
          <option value="FEBRERO">FEBRERO</option>
          <option value="MARZO">MARZO</option>
          <option value="ABRIL">ABRIL</option>
          <option value="MAYO">MAYO</option>
          <option value="JUNIO">JUNIO</option>
          <option value="JULIO">JULIO</option>
          <option value="AGOSTO">AGOSTO</option>
          <option value="SETIEMBRE">SETIEMBRE</option>
          <option value="OCTUBRE">OCTUBRE</option>
          <option value="NOVIEMBRE">NOVIEMBRE</option>
          <option value="DICIEMBRE">DICIEMBRE</option>
          <?php
          /*  }
          } */
          ?>
        </select>

      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label"><?= ('Archivo') ?></label>
    <div class="col-sm-5">
      <input type="file" name="files" class="form-control" placeholder="<?= ('Archivo') ?>" required>
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
  $(document).on("submit", "form", function(event) {
    var form = $(event.target);
    if (form.attr('action') == '<?= base_url('admin/items/update_group') ?>') {
      event.preventDefault();
      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize()
      }).done(function(response) {
        response = JSON.parse(response);
        if (response.status == 'success') {
          if (typeof(response.id) != 'undefined') {
            var groups = $('select[name="customer_group_id"]');
            groups.prepend('<option selected value="' + response.id + '">' + response.group + '</option>');
            var select2Instance = groups.data('select2');
            var resetOptions = select2Instance.options.options;
            groups.select2('destroy').select2(resetOptions)
          }
          toastr[response.status](response.message);
        }
        $('#myModal').modal('hide');
      });
    }
  });
</script>
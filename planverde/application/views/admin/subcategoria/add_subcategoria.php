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
  <?php echo form_open(base_url('admin/subcategoria/save_subcategoria/' . (isset($subcategory_info) ? $subcategory_info->subcategoria_id : '')), array('id' => 'subcategoria', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>
  <div class="form-group">
    <label class="col-sm-3 control-label">Nombre</label>
    <div class="col-sm-8">
      <input type="text" name="nombre_subcategoria" class="form-control" placeholder="Nombre de subcategoría" value="<?php echo (isset($subcategory_info->nombre_subcategoria)) ? $subcategory_info->nombre_subcategoria : ''; ?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label"><?= ('Categoria') ?> </label>
    <div class="col-sm-8">
      <div class="input-group">
        <select name="categoria_id" class="form-control select_box" style="width: 100%" required>
          <option value="">Seleccione una categoría</option>
          <?php
          if (!empty($all_categories)) {
            foreach ($all_categories as $cate) {
              $cate = (object) $cate;
          ?>
              <option value="<?= $cate->categoria_id ?>" <?php echo (isset($subcategory_info->categoria_id) && $subcategory_info->categoria_id == $cate->categoria_id) ? 'selected' : ''; ?>><?= $cate->nombre_categoria ?></option>
          <?php
            }
          }
          ?>
        </select>
      </div>
    </div>
  </div>
  <div class="form-group mt">
    <label class="col-lg-3"></label>
    <div class="col-lg-6">
      <?php if (isset($subcategory_info)) : ?>
        <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
      <?php else : ?>
        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
      <?php endif; ?>
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>
<!-- <script type="text/javascript">
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
</script> -->
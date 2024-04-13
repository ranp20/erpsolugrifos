<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-custom" style="margin: 10px;">
      <header class="panel-heading ">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <?= $title ?>
      </header>
      <?php echo form_open(base_url('admin/cliente/save_cliente/' . (isset($cliente_info) ? $cliente_info->cliente_id : '')), array('id' => 'cliente', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>


      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label"><?= ('Razon social') ?></label>
          <input type="text" name="razon_social" class="form-control" placeholder="RAZON SOCIAL DEL CLIENTE" value="<?php echo (isset($cliente_info->razon_social)) ? $cliente_info->razon_social : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label"><?= ('Ruc') ?></label>
          <input type="text" name="ruc" class="form-control" placeholder="NUMERO DE RUC" value="<?php echo (isset($cliente_info->RUC)) ? $cliente_info->RUC : ''; ?>" required>
        </div>
      </div>


      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label">Direccion Legal</label>
          <input type="text" name="direccion_legal" class="form-control" placeholder="DIRECCION LEGAL" value="<?php echo (isset($cliente_info->direccion_legal)) ? $cliente_info->direccion_legal : ''; ?>" required>
        </div>
      </div>


      <div class="form-group">
        <div class="col-sm-6">
          <label class=" control-label">Distrito</label>
          <input type="text" name="distrito" class="form-control" placeholder="DISTRITO" value="<?php echo (isset($cliente_info->distrito)) ? $cliente_info->distrito : ''; ?>" required>
        </div>
        <div class="col-sm-6">
          <label class=" control-label">Provincia</label>
          <input type="text" name="provincia" class="form-control" placeholder="PROVINCIA" value="<?php echo (isset($cliente_info->provincia)) ? $cliente_info->provincia : ''; ?>" required>
        </div>
      </div>


      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label">Representante Legal</label>
          <input type="text" name="representante_legal" class="form-control" placeholder="REPRESENTANTE LEGAl" value="<?php echo (isset($cliente_info->representante_legal)) ? $cliente_info->representante_legal : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">DNI</label>
          <input type="text" name="dni_representante" class="form-control" placeholder="DNI REPRESENTANTE" value="<?php echo (isset($cliente_info->dni_representante)) ? $cliente_info->dni_representante : ''; ?>" required>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label">Gerente Legal</label>
          <input type="text" name="gerente_legal" class="form-control" placeholder="GERENTE LEGAl" value="<?php echo (isset($cliente_info->gerente_legal)) ? $cliente_info->gerente_legal : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">DNI</label>
          <input type="text" name="dni_gerente" class="form-control" placeholder="DNI GERENTE" value="<?php echo (isset($cliente_info->dni_gerente)) ? $cliente_info->dni_gerente : ''; ?>" required>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label">Supervisor</label>
          <input type="text" name="supervisor" class="form-control" placeholder="SUPERVISOR" value="<?php echo (isset($cliente_info->supervisor)) ? $cliente_info->supervisor : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">Correo</label>
          <input type="text" name="correo" class="form-control" placeholder="CORREO SUPERVISOR" value="<?php echo (isset($cliente_info->correo)) ? $cliente_info->correo : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">Celular</label>
          <input type="text" name="celular" class="form-control" placeholder="Celular Supervisor" value="<?php echo (isset($cliente_info->celular)) ? $cliente_info->celular : ''; ?>" required>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label">Direccion</label>
          <input type="text" name="direccion_operativa[]" class="form-control" placeholder="Dirección" value="<?php echo (isset($cliente_info->supervisor)) ? $cliente_info->supervisor : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">Correo</label>
          <input type="text" name="correo" class="form-control" placeholder="CORREO SUPERVISOR" value="<?php echo (isset($cliente_info->correo)) ? $cliente_info->correo : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">Celular</label>
          <input type="text" name="celular" class="form-control" placeholder="Celular Supervisor" value="<?php echo (isset($cliente_info->celular)) ? $cliente_info->celular : ''; ?>" required>
        </div>
      </div>



      <div class="form-group mt">
        <label class="col-lg-3"></label>
        <div class="col-lg-3">
          <?php if (isset($cliente_info)) : ?>
            <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
          <?php else : ?>
            <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
          <?php endif; ?>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
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
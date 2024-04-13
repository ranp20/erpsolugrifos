<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= $title ?>
  </header>
  <?php echo form_open(base_url('admin/pv_order/save_pv_order/' . (($pv_order_info) ? $pv_order_info->pv_order_id : '')), array('id' => 'pv_order', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Cliente:</label>
    <div class="col-sm-5 col-xs-12">
        <?php if(!empty($all_clientes)): ?>
        <select name="cliente_id" id="cliente_id" class="select_box form-control" required>
          <option value="">Seleccionar</option>
          <?php foreach ( $all_clientes as $key  => $cliente): ?>
          <option value="<?php echo $cliente->cliente_id; ?>"><?php echo $cliente->razon_social; ?></option>
          <?php endforeach; ?>
        </select>
        <?php else: ?>
        <select name="" id="" class="select_box form-control">
          <option value="">Seleccionar</option>
        </select>
      <?php endif; ?>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Sede:</label>
    <div class="col-sm-5 col-xs-12">
      <div class="sede">
        <select name="" id="" class="select_box form-control" required>
          <option value="">Seleccionar</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Descripción:</label>
    <div class="col-sm-5 col-xs-12">
        <textarea name="comment" id="comment" cols="30" rows="5" placeholder="Ingresar una pequeña descripción" class="form-control" required></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Fecha Termino:</label>
    <div class="col-sm-5 col-xs-12">
        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" placeholder="Fecha" required>
    </div>
  </div>
  <div class="form-group mt">
    <label class="col-xs-3 col-xs-12"></label>
    <div class="col-xs-6 col-xs-12">
      <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>
<script type="text/javascript">
$('#myModal').on('loaded.bs.modal', function() {
    $("input[type=checkbox]").bootstrapToggle();
});
// CARGA DE SEDES DEPENDIENDO DE CUANTAS TENGA EL CLIENTE SELECCIONADO
function sede_x_cliente(client_id = '', sede_id = '') {
    //console.log(client_id);
    $.ajax({
      type: "POST",
      url: base_url + 'admin/sede/cmb_x_cliente/' + client_id + '/' + sede_id,
      dataType: "html",
      success: function(response) {
        //console.log(response);
        $(".sede").html(response);
      }
    });
}
//ESCUCHAR EL CAMBIO DE CLIENTE
$(document).on('change', '#cliente_id', function() {
    let client_id = $(this).val();
    sede_x_cliente(client_id, '');
});
</script>
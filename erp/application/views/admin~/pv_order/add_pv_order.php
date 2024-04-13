<?php
/* echo "<pre>";
print_r($pv_order_info);
echo "</pre>"; */
?>
<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= $title ?>
  </header>
  <?php echo form_open(base_url('admin/pv_order/save_pv_order/' . (($pv_order_info) ? $pv_order_info->pv_order_id : '')), array('id' => 'pv_order', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>


  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Cliente:</label>
    <div class="col-sm-5 col-xs-12">
      <select name="cliente_id" id="cliente_id" class="form-control select_box required" style="width: 100%" required>
        <option value="">selecccionar</option>
        <?php
        if (!empty($all_clients)) {
          foreach ($all_clients as $client) {
            $client = (object) $client;
        ?>
            <option value="<?= $client->cliente_id ?>" <?php echo ($pv_order_info && $pv_order_info->cliente_id == $client->cliente_id) ? 'selected' : ''; ?>><?= $client->ruc . ' - ' . $client->razon_social ?></option>
        <?php
          }
        }
        ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Sede:</label>
    <div class="col-sm-5 col-xs-12">
      <div class="sede">
      <?php if( !empty( $sedes ) ): ?>
        <select name="sede_id" id="sede_id" class="select_box form-control">
          <option value="">Seleccionar</option>
          <?php foreach ( $sedes as $key  => $sede): ?>
          <option value="<?php echo $sede->sede_id; ?>" <?php echo ($pv_order_info && $pv_order_info->sede_id ==  $sede->sede_id ) ? 'selected' : ''; ?> ><?php echo $sede->direccion; ?></option>
          
          <?php endforeach; ?>
        </select>
        
      <?php else: ?>

        <select name="" id="" class="select_box form-control">
          <option value="">Seleccionar</option>
        </select>
        
      <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Fecha Fin:</label>
    <div class="col-sm-5 col-xs-12">
      <input type="text" class="start_date form-control" name="fecha_fin" value="<?php echo ($pv_order_info) ? $pv_order_info->fecha_fin : ''; ?>">
    </div>
  </div>

  <div class="clear-fix"></div>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Comentario:</label>
    <div class="col-sm-5 col-xs-12">
      <textarea name="comment" id="comment" cols="30" rows="5" placeholder="INGRESAR  UNA PEQUEÑA DESCRIPCION ( OPCIONAL )" class="form-control"><?php echo ($pv_order_info) ? $pv_order_info->comment : ''; ?></textarea>
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

<script>
  $(document).ready(function() {

  })
  function sede_x_cliente(client_id = '', sede_id = '') {
    console.info(client_id + '-' + sede_id)
    $.ajax({
      type: "POST",
      url: base_url + 'admin/sede/cmb_x_cliente/' + client_id + '/' + sede_id,
      dataType: "html",
      success: function(response) {
        $(".sede").html(response);

      },
      complete: function() {

      }
    });
  }

  $('#myModal').on('loaded.bs.modal', function() {
    $("input[type=checkbox]").bootstrapToggle();
    
  })
  $(document).on('change', '#cliente_id', function() {
    console.info('cambio cmb_ON')
  let client_id = $(this).val()
  sede_x_cliente(client_id, '')
})



  
</script>
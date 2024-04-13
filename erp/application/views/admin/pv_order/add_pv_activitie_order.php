<div class="panel panel-custom">
    <?php
        /*
        echo "<pre>";
        echo "<span>designations_id: </span>";
        print_r($_SESSION['designations_id']);
        echo "</pre>";
        */
    ?>
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= $title ?>
  </header>
  <?php echo form_open(base_url('admin/pv_order/save_pv_activitie_order/' . (($pv_activitie_order_info) ? $pv_activitie_order_info->pv_order_activitie_id : '')), array('id' => 'pv_activitie_order', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Actividad:</label>
    <div class="col-sm-5 col-xs-12">
      <input type="hidden" name="pv_order_id" id="pv_order_id" value="<?php echo (($pv_order_info) ? $pv_order_info->pv_order_id : '') ?>">
      <select name="pv_activitie_id" id="pv_activitie_id" class="form-control select_box required" style="width: 100%" required>
        <option value="">selecccionar</option>
        <?php
        if (!empty($pv_activities)) {
          foreach ($pv_activities as $activitie) {
        ?>
            <option value="<?= $activitie->pv_activitie_id ?>" <?php echo ($pv_activitie_order_info && $pv_activitie_order_info->pv_activitie_id == $activitie->pv_activitie_id) ? 'selected' : ''; ?>><?= $activitie->activitie ?></option>
        <?php
          }
        }
        ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Area Operativa  :</label>
    <div class="col-sm-5 col-xs-12">
      <select name="area_asignada" id="area_asignada" class="form-control select_box required" style="width: 100%" required>
        <option value="">selecccionar</option>
        <?php
        if (!empty($operativos)) {
          foreach ($operativos as $operativo) {

        ?>
            <option value="<?= $operativo->designations_id ?>" <?php echo ($pv_activitie_order_info && $pv_activitie_order_info->area_asignada == $operativo->designations_id) ? 'selected' : ''; ?>><?= $operativo->designations ?></option>
        <?php
          }
        }
        ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Inicio:</label>
    <div class="col-sm-5 col-xs-12">
      <input type="text" name="start_date" class="start_date form-control" id="start_date" value="<?php echo ($pv_activitie_order_info) ? $pv_activitie_order_info->start_date : ''; ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Fin:</label>
    <div class="col-sm-5 col-xs-12">
      <input type="text" name="end_date" class="end_date form-control" id="end_date" value="<?php echo ($pv_activitie_order_info) ? $pv_activitie_order_info->end_date : ''; ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Comentario:</label>
    <div class="col-sm-5 col-xs-12">
      <textarea name="comment" id="comment" cols="30" rows="5" placeholder="INGRESAR  UNA PEQUEÑA DESCRIPCION ( OPCIONAL )" class="form-control"><?php echo ($pv_activitie_order_info) ? $pv_activitie_order_info->comment : ''; ?></textarea>
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
    //console.info(client_id + '-' + sede_id)
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
//ESCUCHAR EL CAMBIO DE CLIENTE
$(document).on('change', '#cliente_id', function() {
    //console.info('cambio cmb_ON');
    let client_id = $(this).val()
    sede_x_cliente(client_id, '')
});
</script>
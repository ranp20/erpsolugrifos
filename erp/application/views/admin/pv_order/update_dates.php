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
  <?php echo form_open(base_url('admin/pv_order/update_pv_activitie_order_dates/' . (($pv_order_info) ? $pv_order_info->pv_order_activitie_id : '')), array('id' => 'pv_activitie_order', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>


  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Actividad:</label>
    <div class="col-sm-5 col-xs-12">
      <?php echo ($pv_order_info) ? $pv_order_info->activitie : ''; ?>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Area Operativa :</label>
    <div class="col-sm-5 col-xs-12">
      <?php echo ($pv_order_info) ? $pv_order_info->designations : ''; ?>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Inicio:</label>
    <div class="col-sm-5 col-xs-12">
      <input type="text" name="start_date" class="start_date form-control" id="start_date" value="<?php echo ($pv_order_info) ? $pv_order_info->start_date : ''; ?>">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 col-xs-12 control-label">Fin:</label>
    <div class="col-sm-5 col-xs-12">
      <input type="text" name="end_date" class="end_date form-control" id="end_date" value="<?php echo ($pv_order_info) ? $pv_order_info->end_date : ''; ?>">
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

  $('#myModal_calendar').on('loaded.bs.modal', function() {
    $("input[type=checkbox]").bootstrapToggle();
    $('.start_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd',
      todayBtn: "linked"
      // update "toDate" defaults whenever "fromDate" changes
    }).on('changeDate', function() {
      // set the "toDate" start to not be later than "fromDate" ends:
      $('.end_date').datepicker('setStartDate', new Date($(this).val()));
    });

    $('.end_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd',
      todayBtn: "linked"
      // update "fromDate" defaults whenever "toDate" changes
    }).on('changeDate', function() {
      // set the "fromDate" end to not be later than "toDate" starts:
      $('.start_date').datepicker('setEndDate', new Date($(this).val()));
    });
  })
</script>
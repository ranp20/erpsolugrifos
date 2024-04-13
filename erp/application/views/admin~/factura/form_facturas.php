<style>
  .container-comprobante {
    border: 1px solid blue;
    border-radius: 5px;
    margin: 5px;
  }
</style>
<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    FACTURAS DE LA COTIZACION <?php echo (isset($cotizacion)) ? $cotizacion->cotizacion_id : ''; ?>
  </header>
  <?php echo form_open(base_url('admin/factura/save_factura/' . $id), array('id' => 'facturas', 'class' => 'form-horizontal', "enctype" => "multipart/form-data", "onSubmit" => "return validarForm()" )); ?>

  <?php

  // if (($cotizacion->status == 22 && $cotizacion->accion == 'comprobante') || $cotizacion->status == 32) {

    if ($pagos) {
      foreach ($pagos as $_key => $pago) {
        $factura = $this->db->where( [ 'cotizacion_pago_id' => $pago->cotizacion_pago_id ] )->get( 'tbl_facturas' )->row();
        // print_r($factura);
  ?>
        <div class="container-comprobante">
          <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10 text-center">
              <!-- <label class="control-label">Descripcion: </label> -->
              <span class="text-primary h3"><?php echo strtoupper($pago->descripcion); ?></span>
            </div>
          </div>
          <!-- Fecha Upload -->

          <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-5 col-xs-12">
            <input type="hidden" name="id[<?php echo $pago->cotizacion_pago_id; ?>]" value="<?php echo ($factura) ? $factura->factura_id : '' ;?>">
              <label class="control-label">Numero Factura <i class="text-danger">*</i> : </label>
              <input type="text" name="numero_factura[<?php echo $pago->cotizacion_pago_id; ?>]" class="form-control" placeholder="F001-0005" value="<?php echo ($factura->num_factura) ? $factura->num_factura : '' ;?>">
            </div>
            <div class="col-sm-5 col-xs-12">
              <label class="control-label">Monto: </label>
              <input type="text" name="monto[<?php echo $pago->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" value="<?php echo ($factura->monto > 0) ? $factura->monto : '' ;?>">
            </div>
          </div>
          <!-- fin fecha upload -->
          <!-- Fecha Upload -->

          <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-5 col-xs-12">
              <label class="control-label">Fecha Emision: </label>
              <input type="text" name="fecha_emision[<?php echo $pago->cotizacion_pago_id; ?>]" class="form-control start_date" placeholder="AAAA-MM-DD" value="<?php echo ($factura->fecha_emision > 0) ? $factura->fecha_emision : '' ;?>">
            </div>
            <div class="col-sm-5 col-xs-12">
              <label class="control-label">Fecha Vencimiento: </label>
              <input type="text" name="fecha_vencimiento[<?php echo $pago->cotizacion_pago_id; ?>]" class="form-control end_date" placeholder="AAAA-MM-DD" value="<?php echo ($factura->fecha_vencimiento > 0) ? $factura->fecha_vencimiento : '' ;?>">
            </div>
          </div>
          <!-- fin fecha upload -->
          <!-- Fecha Upload -->

          <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-5 col-xs-12">
              <label class="control-label">Descripcion: </label>
              <textarea name="descripcion[<?php echo $pago->cotizacion_pago_id; ?>]" id="descripcion" class="form-control"><?php echo ($factura->descripcion) ? $factura->descripcion : '' ;?></textarea>
            </div>
            <div class="col-sm-5 col-xs-12">
              <label class="control-label">Factura: </label><?php echo ($factura->ruta) ? ' <a href="'. base_url().$factura->ruta.'" target="_blank" class="h4" title="VER FACTURA"><i class="fa fa-eye"></i></a>' : 'Adjuntar' ;?>
              <input type="file" name="files_<?php echo $pago->cotizacion_pago_id; ?>" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
            </div>
          </div>
          <!-- fin fecha upload -->

        </div>

  <?php

      }
    }
  // }

  ?>

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
  $('#myModal').on('loaded.bs.modal', function() {

    // $("#active-achivo").bootstrapToggle();
    $("input[type=checkbox]").bootstrapToggle();


  })
function validarForm() {
  setTimeout(() => {
  }, 3000);
  // return false
  return true
}
</script>
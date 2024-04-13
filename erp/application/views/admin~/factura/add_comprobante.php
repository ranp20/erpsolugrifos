<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?php echo $title; ?>
  </header>
  <?php

  ?>
  <?php echo form_open(base_url('admin/comprobante_pago/save_forms/' . $form . '/' . $id), array('id' => 'cotizacion_pago_id', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>

  <div style="border: 1px solid blue; border-radius: 5px; margin:5px;">
    <div class="form-group">
      <div class="col-sm-1"></div>
      <div class="col-sm-10">
        <label class="control-label">Descripcion: </label>
        <span class="text-primary"><?php echo $cotizacion_pago->descripcion; ?></span>
      </div>
    </div>
    <!-- Fecha Upload -->

    <div class="form-group">
      <div class="col-sm-1"></div>
      <div class="col-sm-5 col-xs-12">
        <label class="control-label">Fecha: </label>
        <?php
        if (($cotizacion_pago->fecha_comprobante) == 0000 - 00 - 00) {
        ?>
          <input type="date" name="tfecha[<?php echo $cotizacion_pago->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
        <?php
        } else {
        ?>
          <span class="text-primary"><?php echo date('d-m-Y', strtotime($cotizacion_pago->fecha_comprobante)); ?></span>
        <?php
        }
        ?>
      </div>
      <div class="col-sm-5 col-xs-12">
        <label class="control-label">Fecha Vencimiento: </label>
        <?php
        if (($cotizacion_pago->fecha_vencimiento) == 0000 - 00 - 00) {
        ?>
          <input type="date" name="tfecha_vencimiento[<?php echo $cotizacion_pago->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
        <?php
        } else {
        ?>
          <span class="text-primary"><?php echo date('d-m-Y', strtotime($cotizacion_pago->fecha_vencimiento)); ?></span>
        <?php
        }
        ?>
      </div>
    </div>
    <!-- fin fecha upload -->

    <div class="form-group">
      <div class="col-sm-1"></div>
      <div class="col-sm-10">
        <label class="control-label">Monto ( <?php echo $cotizacion_pago->porcentaje . '% de ' . $cotizacion->monto; ?> ): </label>
        <?php
        if (($cotizacion_pago->monto_upload) == 0.00) {
        ?>
          <input type="text" name="tmonto[<?php echo $cotizacion_pago->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">


        <?php
        } else {
        ?>
          <span class="text-primary"><?php echo $cotizacion_pago->monto_upload; ?></span>
        <?php
        }
        ?>
      </div>
    </div>


    <?php
    if (empty($cotizacion_pago->ruta)) {
    ?>
      <div class="document" style="visibility: visible;">
        <div class="form-group">
          <label class="col-sm-3 control-label">Archivo

          </label>
          <div class="col-sm-5">

            <input type="file" name="files_<?php echo $cotizacion_pago->cotizacion_pago_id; ?>" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
          </div>
        </div>
      </div>
    <?php
    } else {
    ?>
      <div class="form-group">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
          <label class="control-label">Comprobante: </label>
          <span class="text-primary"><a target="_blank" class="btn btn-info btn-xs" title="DESCARGAR COMPROBANTE" href="<?php echo base_url() . "uploads/cotizaciones/comprobante_pago_administracion/" . $cotizacion_pago->ruta; ?>"> <span class="fa fa-download"></span></a></span>
        </div>
      </div>
    <?php
    }
    ?>
  </div>

  
  <div class="form-group mt">
    <!-- <label class="col-lg-3"></label> -->
    <div class="text-center">
      <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>

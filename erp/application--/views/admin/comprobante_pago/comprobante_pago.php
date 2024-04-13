<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    COMPROBANTES DE PAGOS
  </header>
  <?php echo form_open(base_url('admin/comprobante_pago/save_forms/' . $form . '/' . $id), array('id' => 'cotizacion_pago_id', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>

  <?php

  if (($cotizacion->status == 22 && $cotizacion->accion == 'comprobante') || $cotizacion->status == 32) {

    if ($adelanto_pago) {
      echo '<h4 class="text-center panel-heading">ADELANTO</h4>';
      foreach ($adelanto_pago as $_key => $adelanto) {
  ?>
        <div style="border: 1px solid blue; border-radius: 5px; margin:5px;">
          <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
              <label class="control-label">Descripcion: </label>
              <span class="text-primary"><?php echo $adelanto->descripcion; ?></span>
            </div>
          </div>
          <!-- Fecha Upload -->

          <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-5 col-xs-12">
              <label class="control-label">Fecha: </label>
              <?php
              if (($adelanto->fecha_comprobante) == 0000 - 00 - 00) {
              ?>
                <input type="date" name="tfecha[<?php echo $adelanto->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
              <?php
              } else {
              ?>
                <span class="text-primary"><?php echo date('d-m-Y', strtotime($adelanto->fecha_comprobante)); ?></span>
              <?php
              }
              ?>
            </div>
            <div class="col-sm-5 col-xs-12">
              <label class="control-label">Fecha Vencimiento: </label>
              <?php
              if (($adelanto->fecha_vencimiento) == 0000 - 00 - 00) {
              ?>
                <input type="date" name="tfecha_vencimiento[<?php echo $adelanto->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
              <?php
              } else {
              ?>
                <span class="text-primary"><?php echo date('d-m-Y', strtotime($adelanto->fecha_vencimiento)); ?></span>
              <?php
              }
              ?>
            </div>
          </div>
          <!-- fin fecha upload -->

          <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
              <label class="control-label">Monto ( <?php echo $adelanto->porcentaje . '% de ' . $cotizacion->monto; ?> ): </label>
              <?php
              if (($adelanto->monto_upload) == 0.00) {
              ?>
                <input type="text" name="tmonto[<?php echo $adelanto->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">


              <?php
              } else {
              ?>
                <span class="text-primary"><?php echo $adelanto->monto_upload; ?></span>
              <?php
              }
              ?>
            </div>
          </div>


          <?php
          if (empty($adelanto->ruta)) {
          ?>
            <div class="document" style="visibility: visible;">
              <div class="form-group">
                <label class="col-sm-3 control-label">Archivo

                </label>
                <div class="col-sm-5">

                  <input type="file" name="files_<?php echo $adelanto->cotizacion_pago_id; ?>" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
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
                <span class="text-primary"><a target="_blank" class="btn btn-info btn-xs" title="DESCARGAR COMPROBANTE" href="<?php echo base_url() . "uploads/cotizaciones/comprobante_pago_administracion/" . $adelanto->ruta; ?>"> <span class="fa fa-download"></span></a></span>
              </div>
            </div>
          <?php
          }
          ?>
        </div>
      <?php

      }
    }
  }
  if ($cotizacion->status == 32) {
    echo '<h4 class="text-center panel-heading">PAGOS</h4>';
    foreach ($comprobantes_pago as $_key => $document) {


      $sub_array = array();


      ?>
      <div style="border: 1px solid blue; border-radius: 5px; margin:5px;">
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <label class="control-label">Descripcion: </label>
            <span class="text-primary"><?php echo $document->descripcion; ?></span>
          </div>
        </div>
        <!-- Fecha Upload -->

        <div class="form-group">
          <div class="col-sm-1"></div>

          <div class="col-sm-5 col-xs-12">
            <label class="control-label">Fecha: </label>
            <?php
            if (($document->fecha_comprobante) == 0000 - 00 - 00) {
            ?>
              <input type="date" name="tfecha[<?php echo $document->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
            <?php
            } else {
            ?>
              <span class="text-primary"><?php echo date('d-m-Y', strtotime($document->fecha_comprobante)); ?></span>
            <?php
            }
            ?>
          </div>
          <div class="col-sm-5 col-xs-12">
            <label class="control-label">Fecha Vencimiento: </label>
            <?php
            if (($document->fecha_vencimiento) == 0000 - 00 - 00) {
            ?>
              <input type="date" name="tfecha_vencimiento[<?php echo $document->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
            <?php
            } else {
            ?>
              <span class="text-primary"><?php echo date('d-m-Y', strtotime($document->fecha_vencimiento)); ?></span>
            <?php
            }
            ?>
          </div>

        </div>


        <!-- fin fecha upload -->

        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <label class="control-label">Monto( <?php echo $document->porcentaje . '% de ' . $cotizacion->monto; ?> ): </label>
            <?php
            if (($document->monto_upload <> 0.00)) {
              //echo $document->ruta;
            ?>
              <span class="text-primary"><?php echo $document->monto_upload; ?></span>
            <?php
            } else {
            ?>
              <input type="text" name="tmonto[<?php echo $document->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
            <?php
            }
            ?>
          </div>
        </div>



        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <label class="control-label">Comprobante: </label>
            <?php
            if (isset($document->ruta)) {
              //echo $document->ruta;
            ?>
              <span class="text-primary"><a target="_blank" class="btn btn-info btn-xs" title="DESCARGAR COMPROBANTE" href="<?php echo base_url() . "uploads/cotizaciones/comprobante_pago_administracion/" . $document->ruta; ?>"> <span class="fa fa-download"></span></a></span>
            <?php
            } else {
            ?>
              <input type="file" name="files_<?php echo $document->cotizacion_pago_id; ?>" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">
            <?php } ?>
          </div>
        </div>


      </div>
  <?php
    }
  }
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
</script>
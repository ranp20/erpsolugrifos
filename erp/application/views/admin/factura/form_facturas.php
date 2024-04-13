<style type="text/css">
  .container-comprobante {border: 1px solid blue;border-radius: 5px;margin: 5px;}
  /* NUEVO CONTENIDO (INICIO) */
  .alert-warning{color: #856404;background-color: #fff3cd;border-color: #ffeeba;}
  .d-inline{display: inline !important;}
  .d-block{display: block !important;}
  .d-none{display: none !important;}
  .cIcon_link{margin-left: auto;width: calc(100% * 1/1.40);display: inline-block;text-align: right;vertical-align: middle;}
  .cIcon_link a{font-size: 2rem;}
  .cIcon_link.update a{padding: 0 1rem;background-color: red;font-weight: 900;font-family: monospace;}
  .link_IconviewDoc.active{visibility: hidden;opacity: 0;pointer-events: none;cursor: not-allowed;}
  .ipt_fileordocimport{display: block !important;visibility: visible;opacity: 1;pointer-events: auto;border: 1px solid #dde6e9;}
  .ipt_fileordocimport.d-none{display: none !important;visibility: hidden;opacity: 0;pointer-events: none;border: none;}
  .ipt_fileordocimport.d-block{display: block !important;visibility: visible;opacity: 1;pointer-events: auto;border: 1px solid #dde6e9;}
  .ipt_fileordocimport.active{display: block !important;visibility: visible;opacity: 1;pointer-events: auto;border: 1px solid #dde6e9;}
  @media (min-width: 767px){
    .cIcon_link{width: calc(100% * 1/1.55);}  
  }
  /* NUEVO CONTENIDO (FIN) */
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
        
        /*
        echo "<pre>";
        print_r($pagos);
        echo "</pre>";
        exit();
        */
        
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
          <!-- ALERTA DE DATOS COMPLETOS -->
          <div style="padding: 0 1.2rem;">
            <div class="alert alert-warning alert-dismissible show" role="alert">
              <strong>AVISO!</strong> Para actualizar el siguiente (<?php echo strtoupper($pago->descripcion); ?>) debe completar toda su información.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
          <!-- -->
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
              <label class="control-label">Tipo Ingreso: </label>
              <select name="tipo_ingreso_id[<?php echo $pago->cotizacion_pago_id; ?>]" class="select_box form-control">
                <option value="none">Seleccionar</option>
                <?php foreach ( $tipo_ingreso as $_key => $ti): ?>
                <option value="<?= $ti->tipo_ingreso_id; ?>" <?php echo ($factura->tipo_ingreso_id == $ti->tipo_ingreso_id) ? 'selected' : ''  ;?> ><?= $ti->tipo_ingreso_name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group" style="background-color: transparent;">
            <div class="col-sm-1"></div>
            <div class="col-sm-5 col-xs-12">
              <label class="control-label">Factura: </label>
              <?php
                $pdf_factura = $factura->ruta;
                //echo "la ruta es: ".$pdf_factura;
                if($pdf_factura != "" && $pdf_factura != null){
                    echo '
                        <div class="d-inline" data-validupload="SI">
                            <a href="'. base_url().$factura->ruta.'" target="_blank" class="h4 link_IconviewDoc" title="VER FACTURA">
                                <i class="fa fa-eye"></i>
                            </a>
                            <span class="cIcon_link" data-filepdf="upload" data-cotepagoid="'.$pago->cotizacion_pago_id.'" data-facturaid="'.$factura->factura_id.'">
                                <a class="btn btn-primary btn-xs" title="EDITAR DOCUMENTO" href="javascript:void(0);">
                                    <span class="fa fa-pencil"></span>
                                </a>
                            </span>
                        </div>
                        <div class="ipt_fileordocimport d-none">
                            <input type="file" name="'. "files_" . $pago->cotizacion_pago_id . '" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs">
                        </div>
                    ';
                }else{
                    echo '
                        <!--<div class="d-inline" data-validupload="NO">
                            <a href="'. base_url().$factura->ruta.'" target="_blank" class="h4 link_IconviewDoc" title="VER FACTURA">
                                <i class="fa fa-eye"></i>
                            </a>
                            <span class="cIcon_link" data-filepdf="upload" data-cotepagoid="'.$pago->cotizacion_pago_id.'" data-facturaid="'.$factura->factura_id.'">
                                <a class="btn btn-primary btn-xs" title="EDITAR DOCUMENTO" href="javascript:void(0);">
                                    <span class="fa fa-pencil"></span>
                                </a>
                            </span>
                        </div>-->
                        <div class="ipt_fileordocimport d-block">
                            <input type="file" name="'. "files_" . $pago->cotizacion_pago_id . '" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs">
                        </div>
                    ';
                }
              ?>
              <?php //echo ($factura->ruta) ? ' <a href="'. base_url().$factura->ruta.'" target="_blank" class="h4" title="VER FACTURA"><i class="fa fa-eye"></i></a>' : 'Adjuntar' ;?>
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

/* NUEVO CONTENIDO (INICIO) */
$(document).on("click", ".cIcon_link[data-filepdf='upload']", function(){
    $(this).each(function(){
       //console.log($(this).attr('data-facturaid'));
       let uploadvalid = $(this).parent().attr('data-validupload');
       if(uploadvalid != "NO"){
           $(this).parent().find('a.link_IconviewDoc').addClass('active');
           $(this).addClass('update');
           $(this).find('a').html(`X`);
           $(this).parent().parent().find('div.ipt_fileordocimport').addClass('d-block');
           $(this).parent().parent().find('div.ipt_fileordocimport').removeClass('d-none');
           $(this).parent().parent().find('div.ipt_fileordocimport').addClass('active');
           $(this).parent().attr('data-validupload', 'NO');
       }else{
           $(this).parent().find('a.link_IconviewDoc').removeClass('active');
           $(this).removeClass('update');
           $(this).find('a').html(`<span class="fa fa-pencil"></span>`);
           $(this).parent().parent().find('div.ipt_fileordocimport').addClass('d-none');
           $(this).parent().parent().find('div.ipt_fileordocimport').removeClass('d-block');
           $(this).parent().parent().find('div.ipt_fileordocimport').removeClass('active');
           $(this).parent().attr('data-validupload', 'SI');
       }
    });
});
/* NUEVO CONTENIDO (FIN) */
</script>
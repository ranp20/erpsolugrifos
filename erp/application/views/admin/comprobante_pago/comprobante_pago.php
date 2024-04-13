<style type="text/css">
  /* NUEVO CONTENIDO (INICIO) */
  .alert-warning{color: #856404;background-color: #fff3cd;border-color: #ffeeba;}
  .d-inline{display: inline !important;}
  .d-block{display: block !important;}
  .d-none{display: none !important;}
  .cIcon_linkcomprob{margin-left: auto;width: calc(100% * 1/1.80);display: inline-block;text-align: right;vertical-align: middle;}
  .cIcon_linkcomprob a{font-size: 2rem;}
  .cIcon_linkcomprob.update a{padding: 0 1rem;background-color: red;font-weight: 900;font-family: monospace;}
  .cIcon_linkcompequal22{margin-left: auto;width: calc(100% * 1/1.80);display: inline-block;text-align: right;vertical-align: middle;}
  .cIcon_linkcompequal22 a{font-size: 2rem;}
  .cIcon_linkcompequal22.update a{padding: 0 1rem;background-color: red;font-weight: 900;font-family: monospace;}
  .link_IconviewDoc.active{visibility: hidden;opacity: 0;pointer-events: none;cursor: not-allowed;}
  .ipt_fileordocimport{display: block !important;visibility: visible;opacity: 1;pointer-events: auto;border: 1px solid #dde6e9;}
  .ipt_fileordocimport.d-none{display: none !important;visibility: hidden;opacity: 0;pointer-events: none;border: none;}
  .ipt_fileordocimport.d-block{display: block !important;visibility: visible;opacity: 1;pointer-events: auto;border: 1px solid #dde6e9;}
  .ipt_fileordocimport.active{display: block !important;visibility: visible;opacity: 1;pointer-events: auto;border: 1px solid #dde6e9;}
  @media (min-width: 767px){
    .cIcon_linkcomprob{width: calc(100% * 1/1.35);}
    .cIcon_linkcompequal22{width: calc(100% * 1/1.35);}
  }
  /* NUEVO CONTENIDO (FIN) */
</style>
<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    COMPROBANTES DE PAGOS
  </header>
  <?php echo form_open(base_url('admin/comprobante_pago/save_forms/' . $form . '/' . $id), array('id' => 'cotizacion_pago_id', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>
  <?php
  if (($cotizacion->status == 22 && $cotizacion->accion == 'comprobante') || $cotizacion->status >= 32) {
    /*
    echo "<pre>";
    print_r($adelanto_pago);
    echo "</pre>";
    */
    if ($adelanto_pago) {
      echo '<h4 class="text-center panel-heading">ADELANTO</h4>';
      foreach ($adelanto_pago as $_key => $document) {
  ?>
        <div style="border: 1px solid blue; border-radius: 5px; margin:5px;">
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <label class="control-label">Descripcion: </label>
            <span class="text-primary"><?php echo ucwords($document->descripcion); ?></span>
          </div>
        </div>
        <!-- ALERTA DE DATOS COMPLETOS -->
        <div style="padding: 0 1.2rem;">
            <div class="alert alert-warning alert-dismissible show" role="alert">
                <strong>AVISO!</strong> Para actualizar el siguiente (<?php echo ucwords($document->descripcion); ?>) debe completar toda su informaci贸n.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <!-- -->
        
        <!--
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-5 col-xs-12">
            <input type="text" name="id[<?php //echo $document->cotizacion_pago_id; ?>]" value="<?php //echo $document->cotizacion_pago_id;?>">
          </div>  
        </div>
        -->
        
        <!-- Fecha Upload -->
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-5 col-xs-12">
            <label class="control-label">Fecha: </label>
            <?php
            // if (($document->fecha_comprobante) == 0000 - 00 - 00) {
            ?>
              <input type="" name="tfecha[<?php echo $document->cotizacion_pago_id; ?>]" class="form-control start_date" placeholder="dd/mm/aaaa" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="" value="<?php echo ($document->fecha_comprobante > 0) ? $document->fecha_comprobante : '' ;?>">
            <?php
            /* } else {
            ?>
              <span class="text-primary"><?php echo date('d-m-Y', strtotime($document->fecha_comprobante)); ?></span>
            <?php
            } */
            ?>
          </div>
          <div class="col-sm-5 col-xs-12">
            <label class="control-label">Banco: </label>
            <?php
            // if (empty($document->banco)) {
            ?>
              <select name="banco[<?php echo $document->cotizacion_pago_id; ?>]" id="" class="form-control select_box">
                <option value="none">Seleccionar</option>
                <?php foreach ($bancos as $key => $banco) : ?>
                  <option value="<?= $banco; ?>" <?php echo ($document->banco == $banco) ? 'selected' : ''; ?>><?= $banco; ?></option>
                <?php endforeach; ?>
              </select>
            <?php
            /* } else {
            ?>
              <span class="text-primary"><?php echo $document->banco; ?></span>
            <?php
            } */
            ?>
          </div>
        </div>
        <!-- fin fecha upload -->
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-5 col-xs-12">
            <label class="control-label">Monto( <?php echo $document->porcentaje . '% de ' . display_money($cotizacion->monto); ?> ): </label>
            <?php
            /* if (($document->monto_upload <> 0.00)) {
              //echo $document->ruta;
            ?>
              <span class="text-primary"><?php echo $document->monto_upload; ?></span>
            <?php
            } else { */
            ?>
              <input type="text" name="tmonto[<?php echo $document->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="" value="<?php echo ($document->monto_upload > 0)? $document->monto_upload : ''; ?>">
            <?php
            // }
            ?>
          </div>
          <div class="col-sm-5 col-xs-12">
            <label class="control-label">N掳 Operaci贸n: </label>
            <?php
            // if (empty($document->numero_operacion)) {
            ?>
              <input type="text" name="numero_operacion[<?php echo $document->cotizacion_pago_id; ?>]" class="form-control" value="<?php echo ($document->numero_operacion > 0)? $document->numero_operacion : ''; ?>">
            <?php
            /* } else {
            ?>
              <span class="text-primary"><?php echo $document->numero_operacion; ?></span>
            <?php
            } */
            ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-10" style="background-color: transparent;">
            <!--<label class="control-label">Comprobante: </label>-->
            <?php
                /*
                echo "La ruta es: ".$document->ruta;
            if (isset($document->ruta)) {
              //echo $document->ruta;
              */
            ?>
              <!--<span class="text-primary"><a target="_blank" class="btn btn-info btn-xs" title="DESCARGAR COMPROBANTE" href="<?php echo base_url() . "uploads/cotizaciones/comprobante_pago_administracion/" . $document->ruta; ?>"> <span class="fa fa-download"></span></a></span>-->
            <?php
             //} //else {
            ?>
              <!--<input type="file" name="files_<?php //echo $document->cotizacion_pago_id; ?>" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="">-->
            <?php //} ?>
            
            
            <label class="control-label">Comprobante: </label>
            <?php
                $doc_comprobante = $document->ruta;
                //echo "La ruta es: " . $doc_comprobante;
                if($doc_comprobante != "" && $doc_comprobante != null){
                    echo '
                        <div class="d-inline" data-validupload="SI">
                            <a href="'. base_url().$document->ruta.'" target="_blank" class="h4 link_IconviewDoc" title="VER COMPROBANTE">
                                <i class="fa fa-eye"></i>
                            </a>
                            <span class="cIcon_linkcompequal22" data-docfilecomprob="upload" data-cotepagoid="'.$document->cotizacion_pago_id.'">
                                <a class="btn btn-primary btn-xs" title="EDITAR DOCUMENTO" href="javascript:void(0);">
                                    <span class="fa fa-pencil"></span>
                                </a>
                            </span>
                        </div>
                        <div class="ipt_fileordocimport d-none">
                            <input type="file" name="'. "files_" . $document->cotizacion_pago_id . '" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs">
                        </div>
                    ';
                }else{
                    echo '
                        <!--<div class="d-inline" data-validupload="NO">
                            <a href="'. base_url().$document->ruta.'" target="_blank" class="h4 link_IconviewDoc" title="VER COMPROBANTE">
                                <i class="fa fa-eye"></i>
                            </a>
                            <span class="cIcon_linkcompequal22" data-docfilecomprob="upload" data-cotepagoid="'.$document->cotizacion_pago_id.'">
                                <a class="btn btn-primary btn-xs" title="EDITAR DOCUMENTO" href="javascript:void(0);">
                                    <span class="fa fa-pencil"></span>
                                </a>
                            </span>
                        </div>-->
                        <div class="ipt_fileordocimport d-block">
                            <input type="file" name="'. "files_" . $document->cotizacion_pago_id . '" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs">
                        </div>
                    ';
                }
              ?>
          </div>
        </div>
      </div>
      <?php
      }
    }
  }
  if ($cotizacion->status >= 32) {
    echo '<h4 class="text-center panel-heading">PAGOS</h4>';
    foreach ($comprobantes_pago as $_key => $document) {
      ?>
      <div style="border: 1px solid blue; border-radius: 5px; margin:5px;">
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <label class="control-label">Descripcion: </label>
            <span class="text-primary"><?php echo ucwords($document->descripcion); ?></span>
          </div>
        </div>
        <!-- ALERTA DE DATOS COMPLETOS -->
        <div style="padding: 0 1.2rem;">
            <div class="alert alert-warning alert-dismissible show" role="alert">
              <strong>AVISO!</strong> Para actualizar el siguiente (<?php echo ucwords($document->descripcion); ?>) debe completar toda su informacion.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        </div>
        <!-- -->
        
        <!--
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-5 col-xs-12">
            <input type="text" name="id[<?php //echo $document->cotizacion_pago_id; ?>]" value="<?php //echo $document->cotizacion_pago_id;?>">
          </div>  
        </div>
        -->
        
        <!-- Fecha Upload -->
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-5 col-xs-12">
            <label class="control-label">Fecha: </label>
            <?php
            // if (($document->fecha_comprobante) == 0000 - 00 - 00) {
            ?>
              <input type="" name="tfecha[<?php echo $document->cotizacion_pago_id; ?>]" class="form-control start_date" placeholder="dd/mm/aaaa" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="" value="<?php echo ($document->fecha_comprobante > 0) ? $document->fecha_comprobante : '' ;?>">
            <?php
            /* } else {
            ?>
              <span class="text-primary"><?php echo date('d-m-Y', strtotime($document->fecha_comprobante)); ?></span>
            <?php
            } */
            ?>
          </div>
          <div class="col-sm-5 col-xs-12">
            <label class="control-label">Banco: </label>
            <?php
            // if (empty($document->banco)) {
            ?>
              <select name="banco[<?php echo $document->cotizacion_pago_id; ?>]" id="" class="form-control select_box">
                <option value="none">Seleccionar</option>
                <?php foreach ($bancos as $key => $banco) : ?>
                  <option value="<?= $banco; ?>" <?php echo ($document->banco == $banco) ? 'selected' : ''; ?>><?= $banco; ?></option>
                <?php endforeach; ?>
              </select>
            <?php
            /* } else {
            ?>
              <span class="text-primary"><?php echo $document->banco; ?></span>
            <?php
            } */
            ?>
          </div>
        </div>
        <!-- fin fecha upload -->
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-5 col-xs-12">
            <label class="control-label">Monto( <?php echo $document->porcentaje . '% de ' . display_money($cotizacion->monto); ?> ): </label>
            <?php
            /* if (($document->monto_upload <> 0.00)) {
              //echo $document->ruta;
            ?>
              <span class="text-primary"><?php echo $document->monto_upload; ?></span>
            <?php
            } else { */
            ?>
              <input type="text" name="tmonto[<?php echo $document->cotizacion_pago_id; ?>]" class="form-control" placeholder="0.00" data-toggle="toggle" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" class="" value="<?php echo ($document->monto_upload > 0)? $document->monto_upload : ''; ?>">
            <?php
            // }
            ?>
          </div>
          <div class="col-sm-5 col-xs-12">
            <label class="control-label">N掳 Operaci贸n: </label>
            <?php
            // if (empty($document->numero_operacion)) {
            ?>
              <input type="text" name="numero_operacion[<?php echo $document->cotizacion_pago_id; ?>]" class="form-control" value="<?php echo ($document->numero_operacion > 0)? $document->numero_operacion : ''; ?>">
            <?php
            /* } else {
            ?>
              <span class="text-primary"><?php echo $document->numero_operacion; ?></span>
            <?php
            } */
            ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-1"></div>
          <div class="col-sm-10" style="background-color: transparent;">
            <label class="control-label">Comprobante: </label>
            <?php
                $doc_comprobante = $document->ruta;
                //echo "La ruta es: " . $doc_comprobante;
                if($doc_comprobante != "" && $doc_comprobante != null){
                    echo '
                        <div class="d-inline" data-validupload="SI">
                            <a href="'. base_url().$document->ruta.'" target="_blank" class="h4 link_IconviewDoc" title="VER COMPROBANTE">
                                <i class="fa fa-eye"></i>
                            </a>
                            <span class="cIcon_linkcomprob" data-docfilecomprob="upload" data-cotepagoid="'.$document->cotizacion_pago_id.'">
                                <a class="btn btn-primary btn-xs" title="EDITAR DOCUMENTO" href="javascript:void(0);">
                                    <span class="fa fa-pencil"></span>
                                </a>
                            </span>
                        </div>
                        <div class="ipt_fileordocimport d-none">
                            <input type="file" name="'. "files_" . $document->cotizacion_pago_id . '" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs">
                        </div>
                    ';
                }else{
                    echo '
                        <!--<div class="d-inline" data-validupload="NO">
                            <a href="'. base_url().$document->ruta.'" target="_blank" class="h4 link_IconviewDoc" title="VER COMPROBANTE">
                                <i class="fa fa-eye"></i>
                            </a>
                            <span class="cIcon_linkcomprob" data-docfilecomprob="upload" data-cotepagoid="'.$document->cotizacion_pago_id.'">
                                <a class="btn btn-primary btn-xs" title="EDITAR DOCUMENTO" href="javascript:void(0);">
                                    <span class="fa fa-pencil"></span>
                                </a>
                            </span>
                        </div>-->
                        <div class="ipt_fileordocimport d-block">
                            <input type="file" name="'. "files_" . $document->cotizacion_pago_id . '" class="form-control" placeholder="Archivo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs">
                        </div>
                    ';
                }
              ?>
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
      <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
  });
  $('#myModal').on('loaded.bs.modal', function() {

    // $("#active-achivo").bootstrapToggle();
    $("input[type=checkbox]").bootstrapToggle();


  });
  
  
/* NUEVO CONTENIDO (INICIO) */
// 1. FUNCIONES CUANDO EL ESTADO DE LA COTIZACIÓN SEA MAYOR O IGUAL A == 32...
$(document).on("click", ".cIcon_linkcomprob[data-docfilecomprob='upload']", function(){
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
// 2. FUNCIONES CUAND EL ESTADO DE LA COTIZACIÓN SEA IGUAL = 22 Y ACCIÓN = 'comprobante'...
$(document).on("click", ".cIcon_linkcompequal22[data-docfilecomprob='upload']", function(){
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
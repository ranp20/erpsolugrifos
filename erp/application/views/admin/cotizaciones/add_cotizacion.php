<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?php echo $title; ?>
  </header>
  <?php echo form_open(base_url('admin/cotizacion/save_cotizacion'), array('id' => 'cotizacion', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>

  <div class="form-group">
    <label class="col-lg-3 control-label"><?= ('Cliente') ?> </label>
    <div class="col-lg-5">
      <div class="input-group">
        <?php

        if (isset($data_cliente)) : ?>
          <input type="hidden" name="cliente_id" id="" value="<?php echo $data_cliente->cliente_id ?>">
          <label for=""><?php echo $data_cliente->razon_social ?></label>
        <?php else : ?>

          <select name="cliente_id" id="cliente_id" class="form-control select_box required" style="width: 100%" required>
            <option value=""><?= lang('none') ?></option>
            <?php
            if (!empty($all_clients)) {
              foreach ($all_clients as $client) {
                $client = (object) $client;
            ?>
                <option value="<?= $client->cliente_id ?>"><?= $client->ruc . ' - ' . $client->razon_social ?></option>
            <?php
              }
            }
            ?>
          </select>

        <?php endif; ?>

      </div>
    </div>
  </div>


  <div class="form-group">
    <label class="col-lg-3 control-label">Sede </label>
    <div class="col-lg-5">
      <div class=" sede">
        <?php if (isset($data_sede)) : ?>
          <input type="hidden" name="valorizacion_id" value="<?php echo (isset($data_valorizacion)) ? $data_valorizacion->valorizacion_servicio_id : ''; ?>">
          <input type="hidden" name="sede_id" value="<?php echo $data_sede->sede_id; ?>">
          <label for=""><?php echo $data_sede->direccion; ?></label>
        <?php else : ?>
          <select name="" id="" class="select-box form-control">
            <option value="">Seleccionar</option>
          </select>

        <?php endif; ?>

      </div>
    </div>
  </div>

  <?php
  if ($data_valorizacion->monto) :
  ?>
    <div class="form-group">
      <label class="col-sm-3 control-label">Monto valorizacion:</label>
      <div class="col-sm-5">
        <input type="text" name="monto_valorixacion" class="form-control" placeholder="Monto ( 00.00)" value="<?php echo $data_valorizacion->monto; ?>" disabled readonly>
      </div>
    </div>

  <?php endif; ?>

  <div class="form-group">
    <label class="col-sm-3 control-label">Servicio</label>
    <div class="col-sm-5">
      <div class="input-group">
        <select name="service_id" id="service_id" class=" form-control select_box " style="width: 100%">
          <option value="">Seleccionar</option>
          <?php foreach ($services as $key => $service) : ?>
            <option value="<?php echo $service->service_id; ?>"><?php echo $service->service; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <!--
  <div class="form-group">
    <label class="col-sm-3 control-label"><?= ('nombre') ?></label>
    <div class="col-sm-5">
      <input type="text" name="nombre" class="form-control require required" placeholder="<?= ('nombre') ?>" required>
    </div>
  </div>
  -->

  <div class="form-group">
    <label class="col-sm-3 control-label">Fecha cotizacion:</label>
    <div class="col-sm-5">
      <input type="date" name="fecha" class="form-control" placeholder="Fecha" required>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Fecha vigencia cotizacion:</label>
    <div class="col-sm-5">
      <input type="date" name="fecha-vig" class="form-control" placeholder="Fecha" required>
    </div>
  </div>



  <div class="form-group">
    <label class="col-sm-3 control-label">Monto:</label>
    <div class="col-sm-5">
      <input type="text" name="monto" class="form-control" placeholder="Monto ( 00.00)" required>
    </div>
  </div>
  
  
  <!-- NUEVO COTENIDO (INICIO) -->
  <div class="form-group">
    <label class="col-sm-3 control-label">ADUNTAR PDF:</label>
    <div class="col-sm-5">
      <input type="file" name="files" class="form-control" placeholder="">
    </div>
  </div>
  <!-- NUEVO COTENIDO (FIN) -->

  <!--
  <div class="form-group">
    <label class="col-sm-3 control-label">Archivo</label>
    <div class="col-sm-5">
      <input type="file" name="files" class="form-control" placeholder="Archivo" require>
    </div>
  </div>
  -->

  <div class="form-group">
    <legend class="col-sm-12 text-center"> FORMA DE PAGO</legend>
    <label class="col-lg-3 control-label">MODO DE PAGO </label>
    <div class="col-sm-12">
      <label class="col-sm-2 control-label" for="adelanto">
        ADELANTO
      </label>
      <div class="col-lg-2 checkbox">
        <input data-id="" data-toggle="toggle" name="adelanto" id="adelanto" value="1" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
      </div>

      <!-- <label class="col-sm-2 col- control-label" for="id-forma-pago">
        Opcion
      </label>
      <div class="col-lg-4">
        <select name="id-forma-pago" id="id-forma-pago" class="select-box form-control" require>
          <option value="">Seleccionar</option>
          <option value="1">OPCION 1</option>
          <option value="2">OPCION 2</option>
        </select>
      </div>
 -->
    </div>
  </div>
  <!-- FORMA DE PAGO CON RESPECTO AL ANEXO 1 DEL EXCEL -->
  <div class="form-control">
    <div class="forma-pago">
    </div>

  </div>

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
  $(document).on("submit", "form", function(event) {
    var form = $(event.target);
    if (form.attr('action') == '<?= base_url('admin/items/update_group') ?>') {
      event.preventDefault();
      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize()
      }).done(function(response) {
        console.log(response);
        response = JSON.parse(response);
        console.log(response);
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

  $(document).on('change', '#cliente_id', function() {
    let el = $(this).val()
    console.log(el)
    $.ajax({
      type: "POST",
      url: base_url + 'admin/sede/cmb_x_cliente/' + el,
      dataType: "html",
      success: function(response) {
        $(".sede").html(response);

      },
      complete: function() {

      }
    });
  })

  // $(document).on('change', '#id-forma-pago', forma_pago)
  $(document).on('change', '#adelanto', function() {
    // if( $("#id-forma-pago").val() > 0 ){
    forma_pago()
    // }
  })

  function forma_pago() {

    // let el = $('#id-forma-pago').val(),
    let el = 1;
    opt = $('#adelanto'),
      option = 0
    if (opt.prop('checked')) {
      option = 1
    }

    console.log(el)
    $.ajax({
      type: "POST",
      url: base_url + 'admin/cotizacion/forma_pago/' + el + '/' + option,
      dataType: "html",
      success: function(response) {
        $(".forma-pago").html(response);

      },
      complete: function() {

      }
    });
  }
  $(document).ready(function() {
    forma_pago()
  })
  $('#myModal').on('loaded.bs.modal', function() {
    console.log('load')
    forma_pago()

    // $("#active-achivo").bootstrapToggle();
    $("input[type=checkbox]").bootstrapToggle();


  })

  $('#myModal').on('hide.bs.modal', function() {
    console.log('cerro')
  })
  $('#myModal').on('shown.bs.modal', function() {
    console.log('show')
    forma_pago()
  })
</script>
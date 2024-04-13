<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= $title ?>
  </header>
  <?php echo form_open(base_url('admin/visita_tecnica/save_visita'), array('id' => 'visita_tecnica', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>

  <div class="form-group">
    <label class="col-lg-3 control-label"> Cliente </label>
    <div class="col-lg-5">
      <div class="input-group">
        <select name="cliente_id" id="cliente_id" class="form-control select_box" style="width: 100%" required>
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

      </div>
    </div>
  </div>


  <div class="form-group">
    <label class="col-lg-3 control-label">Sede </label>
    <div class="col-lg-5">
      <div class=" sede">
        <select name="" id="" class="select_box form-control">
          <option value="">Seleccionar</option>
        </select>

      </div>
    </div>
  </div>




  <div class="form-group">
    <label class="col-sm-3 control-label">Servicio:</label>
    <div class="col-sm-5">
      <!--<input type="text" name="nombre" class="form-control" placeholder="<?= ('nombre') ?>" required>-->
      <select name="service_id" id="service_id" class=" form-control select_box require " style="width: 100%" required>
          <option value="">Seleccionar</option>
          <?php foreach ($services as $key => $service) : ?>
            <option value="<?php echo $service->service_id; ?>"><?php echo $service->service; ?></option>
          <?php endforeach; ?>
        </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Fecha:</label>
    <div class="col-sm-5">
      <input type="date" name="fecha" class="form-control start_date" placeholder="Fecha" required class="">
    </div>
  </div>


  <div class="form-group">
    <label class="col-sm-3 control-label">Monto:</label>
    <div class="col-sm-5">
      <input type="text" name="monto" class="form-control" placeholder="Monto ( 00.00)" required>
    </div>
  </div>




  <div class="form-group">
    <label class="col-sm-3 control-label">Archivo</label>
    <div class="col-sm-5">
      <input type="file" name="files" class="form-control" placeholder="Archivo" required accept=".xls, .xlsx">
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
        response = JSON.parse(response);
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
  $('#myModal').on('loaded.bs.modal', function () {
  $('.start_date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayBtn: "linked"
        // update "toDate" defaults whenever "fromDate" changes
    })
})

</script>
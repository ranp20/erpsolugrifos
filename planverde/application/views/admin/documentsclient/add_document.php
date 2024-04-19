<div class="panel panel-custom">
  <div class="panel-heading panelCtm__header">
    <header>
      <h3 class="mt-0"><?= $title;?></h3>
    </header>
    <button type="button" class="close" data-dismiss="modal">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Close</span>
    </button>
  </div>
  <?php echo form_open(base_url('admin/document_client/save_document'), array('id' => 'document_client', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>
  <div class="form-group">
    <label class="col-lg-3 control-label">Cliente</label>
    <div class="col-lg-6">
      <div class="input-group">
        <select name="cliente_id" id="cliente_id" class="form-control select_box" style="width: 100%" required>
          <option value=""><?= lang('select_a_client_1') ?></option>
          <?php
          if(!empty($all_clients)){
            foreach($all_clients as $client){
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
    <label class="col-lg-3 control-label">Sede</label>
    <div class="col-lg-6">
      <div class="input-group sede w-100">
        <select name="" id="" class="select-box form-control w-100">
          <option value=""><?= lang('select_a_location_1')?></option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label">Categoría</label>
    <div class="col-lg-6">
      <div class="input-group categorias w-100">
        <select name="" id="" class="select-box form-control w-100">
          <option value=""><?= lang('select_a_category_1')?></option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Nombre</label>
    <div class="col-lg-6">
      <input type="text" name="nombre" class="form-control" placeholder="<?= lang('name_of_document') ?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Año</label>
    <div class="col-lg-6">
      <select name="anio" class="form-control select_box" style="width: 100%" required>
      <option value=""><?= lang('select_a_year_1') ?></option>
      <?php
      if (!empty($all_anios)) {
        foreach ($all_anios as $anio) {
          $anio = (object) $anio;
      ?>
        <option value="<?= $anio->anio_id ?>"><?= $anio->anio ?></option>
      <?php
        }
      }
      ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 control-label">Mes</label>
    <div class="col-lg-6">
      <div class="input-group">
        <select name="mes" class="form-control select_box" style="width: 100%" required>
          <option value=""><?= lang('select_a_month_1') ?></option>
          <?php
          /* if (!empty($all_categories)) {
            foreach ($all_categories as $cate) {
              $cate = (object) $cate; */
          ?>
          <option value="ENERO">ENERO</option>
          <option value="FEBRERO">FEBRERO</option>
          <option value="MARZO">MARZO</option>
          <option value="ABRIL">ABRIL</option>
          <option value="MAYO">MAYO</option>
          <option value="JUNIO">JUNIO</option>
          <option value="JULIO">JULIO</option>
          <option value="AGOSTO">AGOSTO</option>
          <option value="SETIEMBRE">SETIEMBRE</option>
          <option value="OCTUBRE">OCTUBRE</option>
          <option value="NOVIEMBRE">NOVIEMBRE</option>
          <option value="DICIEMBRE">DICIEMBRE</option>
          <?php
          /*  }
          } */
          ?>
        </select>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Adjunto</label>
    <div class="col-lg-6">
      <input type="file" name="files" id="files" class="form-control" placeholder="<?= ('Año') ?>" required>
      <div class="progress_bar"><div class="percent">0%</div></div>
    </div>
  </div>
  <div class="form-group mt">
    <label class="col-lg-3"></label>
    <div class="col-lg-6">
      <button type="submit" class="btn btn-sm btn-primary btn-tocreated"><?= lang('save') ?></button>
      <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('Cerrar') ?></button>
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
  });
  $(document).on('change', '#sede_id', function() {
    let el = $(this).val()
    $.ajax({
      type: "POST",
      url: base_url + 'admin/categoria/cmb_x_sede/' + el,
      dataType: "html",
      success: function(response) {
        $(".categorias").html(response);
      },
      complete: function() {
      }
    });
  });
</script>
<script>
  var reader;
  var progress = document.querySelector('.percent');
  function abortRead() {
    reader.abort();
  }
  function errorHandler(evt) {
    switch(evt.target.error.code) {
      case evt.target.error.NOT_FOUND_ERR:
        alert('File Not Found!');
        break;
      case evt.target.error.NOT_READABLE_ERR:
        alert('File is not readable');
        break;
      case evt.target.error.ABORT_ERR:
        break; // noop
      default:
        alert('An error occurred reading this file.');
    };
  }
  function updateProgress(evt){
    if(evt.lengthComputable){
      var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
      if(percentLoaded < 100){
        evt.target.nextElementSibling.childNodes[0].style.width = percentLoaded + '%';
        evt.target.nextElementSibling.childNodes[0].textContent = percentLoaded + '%';
      }
    }
  }
  function handleFileSelect(evt){
    evt.target.nextElementSibling.childNodes[0].style.width = '0%';
    evt.target.nextElementSibling.childNodes[0].textContent = '0%';
    reader = new FileReader();
    reader.onerror = errorHandler;
    reader.onprogress = updateProgress;
    reader.onabort = function(e){
      alert('File read cancelled');
    };
    reader.onloadstart = function(e){
        evt.target.nextElementSibling.classList.add("loading");
        // document.getElementById('progress_bar').className = 'loading';
    };
    reader.onload = function(e){
        evt.target.nextElementSibling.childNodes[0].style.width = '100%';
        evt.target.nextElementSibling.childNodes[0].textContent = '100%';
        setTimeout(function(){evt.target.nextElementSibling.classList.remove('loading');}, 2000);
        // setTimeout("document.getElementById('progress_bar').className='';", 2000);
    }
    reader.readAsBinaryString(evt.target.files[0]);
  }
  document.getElementById('files').addEventListener('change', handleFileSelect, false);
</script>
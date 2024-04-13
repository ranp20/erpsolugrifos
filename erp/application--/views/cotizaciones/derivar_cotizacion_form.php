<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= ('Nuevo Documento Cliente') ?>
  </header>
  <?php echo form_open(base_url('admin/cotizacion/save_derivar_cotizacion'), array('id' => 'cotizacion', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>

  <div class="form-group">
    <label class="col-lg-3 control-label"> Derivar Doc. A: </label>
    <div class="col-lg-5">
      <div class="input-group">
        <select name="area_id" id="area_id" class="form-control select_box" style="width: 100%" required>
          <option value="">Seleccionar</option>
          <?php
          $data_area = $this->db->get('tbl_departments')->result_object();

          if (!empty($data_area)) {
            foreach ($data_area as $area) {
              echo '<optgroup label="' . $area->deptname . '">';
              $data_subarea = $this->db->where_not_in('designations_id', $this->session->userdata('designations_id'))->where(['departments_id' => $area->departments_id])->get('tbl_designations')->result_object();
              foreach ($data_subarea as $key => $sub) :
          ?>
                <option value="<?= $sub->designations_id ?>"><?= $sub->designations  ?></option>
          <?php
              endforeach;
            }
            echo '</optgroup>';
          }
          ?>
        </select>

      </div>
    </div>
  </div>



  <div class="form-group">
    <label class="col-sm-3 control-label">Observaciones</label>
    <div class="col-sm-5">
      <textarea name="observaciones" id="observaciones" cols="30" rows="5" class="form-control"></textarea>
    </div>
  </div>


  <div class="form-group">
    <label class=" control-label">Archivo
      <div class="col-sm-8 checkbox">

        <input type="checkbox" name="" id="active-achivo">
      </div>
    </label>
  </div>


  <div class="document" style="visibility: hidden;">
    <div class="form-group">
      <label class="col-sm-3 control-label">Archivo</label>
      <div class="col-sm-5">
        <input type="file" name="files" class="form-control" placeholder="Archivo">
      </div>
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
</script>
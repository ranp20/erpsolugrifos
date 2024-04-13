<?php
    if ($this->session->userdata('user_type') == 1) { ?>
    <div class="row btnGroups-hTop">
        <div class="col-sm-12">
            <a class="btn btn-success" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/document_client/add_document">
                <i class="fa fa-plus"></i>
                <span>Crear Documento</span>
            </a>
        </div>
    </div>
<?php
}
?>
<div class="panel panel-custom">
  <header class="panel-heading ">
    <div class="panel-title"><strong><?php echo $page; ?></strong></div>
  </header>
  <div class="table-responsive">
    <table class="table table-striped DataTables bulk_table docsClientList" id="DataTables" cellspacing="0" width="100%">
      <thead>
        <tr>
          <?php if (!empty($deleted)) { ?>
            <th data-orderable="false">
              <div class="checkbox c-checkbox">
                <label class="needsclick">
                  <input id="select_all" type="checkbox">
                  <span class="fa fa-check"></span>
                </label>
              </div>
            </th>
          <?php } ?>
          <th class="col-sm-1 c-th_ids"><?= ('#') ?></th>
          <th class="col-sm-3"><?= ('Nombre') ?></th>
          <th class="col-sm-3"><?= "Cliente" ?></th>
          <th class="col-sm-1 c-th_ids"><?= "Año" ?></th>
          <th class="col-sm-1"><?= ('Mes')  ?></th>
          <th class="col-sm-1"><?= lang('action') ?></th>
        </tr>
      </thead>
      <tbody>
        <script type="text/javascript">
          $(document).ready(function() {
            list = base_url + "admin/document_client/documentsClientList";
            bulk_url = base_url + "admin/items/bulk_delete";
            $('.filtered > .dropdown-toggle').on('click', function() {
                if ($('.group').css('display') == 'block') {
                    $('.group').css('display', 'none');
                } else {
                    $('.group').css('display', 'block')
                }
            });
            $('.filter_by').on('click', function() {
                $('.filter_by').removeClass('active');
                $('.group').css('display', 'block');
                $(this).addClass('active');
                var filter_by = $(this).attr('id');
                if (filter_by) {
                    filter_by = filter_by;
                } else {
                    filter_by = '';
                }
                table_url(base_url + "admin/document_client/documentsClientList/" + filter_by);
            });
          });
        </script>
      </tbody>
    </table>
  </div>
</div>
<script>
  $(document).on('click', '.delete-document', function() {
    if (confirm('Desea elminar El Registro??')) {
      let id = $(this).data('id')
      $.ajax({
        type: "POST",
        url: base_url + 'admin/document_client/delete_document/' + id,
        dataType: "json",
        success: function(data) {
          toastr[data.type](data.message)
          reload_table()
        }
      });
    }
  });
</script>
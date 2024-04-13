<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<div class="row">
  <div class="col-sm-12">

    <div class="panel panel-custom">
      <header class="panel-heading ">
        <div class="panel-title"><strong><?= ('Comprobantes de pago') ?></strong>
        </div>
      </header>
      <div class="table-responsive">

        <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0" width="100%">
          <thead>
            <tr>

              <th class="col-sm-1">Cliente</th>
              <th class="col-sm-1">Sede</th>
              <th class="col-sm-1">Cotizacion</th>
              <th class="col-sm-1">Fecha</th>
              <th class="col-sm-1">Importe</th>
              <th class="col-sm-1">Banco</th>
              <th class="col-sm-1">N° Operacion</th>
              <th class="col-sm-1">Factura</th>


              <th class="col-sm-1"></th>
            </tr>
          </thead>
          <tbody>
            <script type="text/javascript">
              $(document).ready(function() {
                let tipo = $("#tipo").val()
                list = base_url + "admin/comprobante_pago/reportList";

                // bulk_url = base_url + "admin/items/bulk_delete";
                $('.filtered > .dropdown-toggle').on('click', function() {
                  if ($('.group').css('display') == 'block') {
                    $('.group').css('display', 'none');
                  } else {
                    $('.group').css('display', 'block')
                  }
                });
                /* $('.filter_by').on('click', function() {
                    $('.filter_by').removeClass('active');
                    $('.group').css('display', 'block');
                    $(this).addClass('active');
                    var filter_by = $(this).attr('id');
                    if (filter_by) {
                        filter_by = filter_by;
                    } else {
                        filter_by = '';
                    }
                    table_url(list + "/" + filter_by);
                }); */

              });
            </script>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
</div>
<script>
  $(document).on('mouseenter', '[data-toggle="tooltip"]', function() {
    $('[data-toggle="tooltip"]').tooltip({
      'html': true
    })
    $(this).tooltip('show')
  })
</script>
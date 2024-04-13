<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<div class="row">
  <div class="col-sm-12">

    <div class="panel panel-custom">
      <header class="panel-heading ">
        <div class="panel-title"><strong><?= ('Comprobantes de pago') ?></strong>
        <a class="btn btn-primary" target="_blank" href="<?= base_url() ?>admin/comprobante_pago/export_excel"><i class="fa fa-file-excel-o"></i> Reporte de Pagos</a>
        </div>
        <?php
        /**
        if (isset($btn_add_cotizacion) && $btn_add_cotizacion) : ?>
          <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/cotizacion/add_cotizacion"><i class="fa fa-plus"></i> Crear cotizacion</a>
        <?php endif;  */ ?>
      </header>
      <div class="table-responsive">

        <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0" width="100%">
          <thead>
            <tr>


              <th class="col-sm-1"><?= "ID" ?></th>
              <th class="col-sm-1"><?= "Cliente" ?></th>
              <th class="col-sm-1"><?= "Sede" ?></th>
              <!--<th class="col-sm-1"><?= ('Fecha')  ?></th>
              <th class="col-sm-1"><?= ('Area Actual')  ?></th>
              <th> Valorizacion </th>
              <th class="col-sm-1"><?= ('Estado') ?></th>-->
              <th class="col-sm-1"><?= ('Cotizacion') ?>


              <th class="col-sm-1"><?= 'Accion' ?></th>
            </tr>
          </thead>
          <tbody>
            <script type="text/javascript">
              $(document).ready(function() {
                let tipo = $("#tipo").val()
                list = base_url + "admin/comprobante_pago/comprobantePagoList/<?php echo (isset($action) ? $action : '') ?>";

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
                $(document).on('change', '#tipo', function() {
                  if ($(this).val() == 1) {
                    list = base_url + "admin/comprobante_pago/comprobante_pago_list/<?php echo (isset($action) ? $action : '') ?>";
                  } else if ($(this).val() == 2) {
                    list = base_url + "admin/comprobante_pago/comprobante_pago_list/<?php echo (isset($action) ? $action : '') ?>";
                  }
                  table_url(list);
                })
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
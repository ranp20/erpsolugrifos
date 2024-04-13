<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<div class="row">
  <div class="col-sm-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#cotizaciones" data-toggle="tab">Cotizaciones</a></li>
        <li class=""><a href="#valorizaciones" data-toggle="tab">Valorizaciones</a></li>
      </ul>
      <div class="tab-content bg-white">
        <!-- Stock Category List tab Starts -->
        <div class="tab-pane active" id="cotizaciones" style="position: relative;">
          <div class="panel panel-custom">
            <header class="panel-heading ">
              <div class="panel-title"><strong><?= ('Todas Cotizaciones') ?></strong>
                <?php
                if (isset($btn_add_cotizacion) && $btn_add_cotizacion) : ?>
                  <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/cotizacion/add_cotizacion"><i class="fa fa-plus"></i> Crear cotizacion</a>
                <?php endif;  ?>
              </div>
              <?php
              /*
              if (isset($btn_add_cotizacion) && $btn_add_cotizacion) : ?>
                <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/cotizacion/add_cotizacion"><i class="fa fa-plus"></i> Crear cotizacion</a>
              <?php endif; ?>
              <?php  ?>
                            <select name="tipo" id="tipo">
                                <option value="1">COTIZACIONES</option>
                                <option value="2">VALORIZACIONES</option>
                            </select>
                            <?php */ ?>
            </header>
            <div class="table-responsive">

              <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0" width="100%">
                <thead>
                  <tr>


                    <th class="col-sm-1"><?= "Nombre" ?></th>
                    <th class="col-sm-1"><?= "Cliente" ?></th>
                    <th class="col-sm-1"><?= ('Sede')  ?></th>
                    <th class="col-sm-1"><?= ('Monto')  ?></th>
                    <th class="col-sm-1"><?= ('Fecha')  ?></th>
                    <th class="col-sm-1"><?= ('Fecha Vigencia')  ?></th>
                    <th class="col-sm-1"><?= ('Area Actual')  ?></th>
                    <th> Valorizacion </th>
                    <th class="col-sm-1"><?= ('Estado')  ?></th>
                    <th class="col-sm-1"><?= ('Proceso')  ?></th>


                    <th class="col-sm-1"><?= 'Accion' ?></th>
                  </tr>
                </thead>
                <tbody>
                  <script type="text/javascript">
                    $(document).ready(function() {
                      let tipo = $("#tipo").val()
                      list = base_url + "admin/cotizacion/CotizacionList/<?php echo (isset($action) ? $action : '') ?>";

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
                          list = base_url + "admin/cotizacion/CotizacionList/<?php echo (isset($action) ? $action : '') ?>";
                        } else if ($(this).val() == 2) {
                          list = base_url + "admin/cotizacion/ValorizacionesList/<?php echo (isset($action) ? $action : '') ?>";
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
        <!-- </div>

            <div class="tab-content bg-white"> -->
        <!-- Stock Category List tab Starts -->
        <div class="tab-pane" id="valorizaciones" style="position: relative;">
          <div class="panel panel-custom">
            <header class="panel-heading ">
              <div class="panel-title"><strong><?= ('Todas Valorizaciones') ?></strong></div>

            </header>
            <div class="table-responsive">

              <table class="table DataTables table-striped " id="tabla-valorizaciones" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th class="col-sm-1">Item</th>
                    <th class="col-sm-1"><?= "Nombre" ?></th>
                    <th class="col-sm-1"><?= "Cliente" ?></th>
                    <th class="col-sm-1"><?= ('Sede')  ?></th>
                    <th class="col-sm-1"><?= ('Monto')  ?></th>
                    <th class="col-sm-1"><?= ('Fecha')  ?></th>

                    <th class="col-sm-1"><?= ('Area Actual')  ?></th>

                    <th class="col-sm-1"><?= ('Estado')  ?></th>
                    <th class="col-sm-1"><?= ('Proceso')  ?></th>


                    <th class="col-sm-1"><?= 'Accion' ?></th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<script>
  $(document).ready(function() {
    lista = base_url + "admin/valorizacion_servicio/valorizacion_servicio_cotizacion_list";
    table = $("#tabla-valorizaciones").DataTable({
      ajax: lista
    })
  })
</script>
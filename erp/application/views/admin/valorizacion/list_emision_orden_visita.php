<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<div class="row">
  <div class="col-sm-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#emitir_orden" data-toggle="tab">Emitir orden de visita tecnica</a></li>
        <li class=""><a href="#visita_program" data-toggle="tab">Visitas tecnicas programadas</a></li>
      </ul>
      <div class="tab-content bg-white">
        <!-- Stock Category List tab Starts -->
        <div class="tab-pane active" id="emitir_orden" style="position: relative;">
          <div class="panel panel-custom">
            <header class="panel-heading ">
              <div class="panel-title"><strong><?= ('Todas valorizaciones') ?></strong></div>
              <?php
              if (isset($btn_add) && $btn_add) : ?>
                <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/valorizacion/add_valorizacion"><i class="fa fa-plus"></i> Crear Valorizacion</a>
              <?php endif; ?>
              <?php /* ?>
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
                        <th class="col-sm-1"><?= ('Area Actual')  ?></th>
                        <th class="col-sm-1"><?= ('Estado')  ?></th>


                        <th class="col-sm-1"><?= 'Accion' ?></th>
                    </tr>
                </thead>
            <tbody>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            list = base_url + "admin/valorizacion/ValorizacionList/<?php echo (isset( $action ) ? $action : '') ?>" ;
                            // bulk_url = base_url + "admin/items/bulk_delete";
                            $('.filtered > .dropdown-toggle').on('click', function() {
                                if ($('.group').css('display') == 'block') {
                                    $('.group').css('display', 'none');
                                } else {
                                    $('.group').css('display', 'block')
                                }
                            });
                            /*$('.filter_by').on('click', function() {
                                $('.filter_by').removeClass('active');
                                $('.group').css('display', 'block');
                                $(this).addClass('active');
                                var filter_by = $(this).attr('id');
                                if (filter_by) {
                                    filter_by = filter_by;
                                } else {
                                    filter_by = '';
                                }
                                table_url( list + "/" + filter_by);
                            });*/

                            $(document).on('change', '#tipo', function() {
                        if ($(this).val() == 1) {
                          list = base_url + "admin/valorizacion/ValorizacionList/<?php echo (isset($action) ? $action : '') ?>";
                        } else if ($(this).val() == 2) {
                          list = base_url + "admin/valorizacion/VisitasProgramadasList/<?php echo (isset($action) ? $action : '') ?>";
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
        <div class="tab-pane" id="visita_program" style="position: relative;">
          <div class="panel panel-custom">
            <header class="panel-heading ">
              <div class="panel-title"><strong><?= ('Visitas tecnicas') ?></strong></div>

            </header>
            <div class="table-responsive">

            <table class="table table-striped DataTables bulk_table" id="tabla_ordenes" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    

                        <th class="col-sm-1"><?= "Nombre" ?></th>
                        <th class="col-sm-1"><?= "Cliente" ?></th>
                        <th class="col-sm-1"><?= ('Sede')  ?></th>
                        <th class="col-sm-1"><?= ('Monto')  ?></th>
                        <th class="col-sm-1"><?= ('Fecha Inicio')  ?></th>
                        <th class="col-sm-1"><?= ('Fecha Final')  ?></th>
                       


                        <th class="col-sm-1"><?= 'Accion' ?></th>
                    </tr>
                </thead>
            <tbody>
                    <script type="text/javascript">
                        
                        /*$(document).ready(function() {
                            list = base_url + "admin/valorizacion/VisitasProgramadasList/<?php //echo (isset( $action ) ? $action : '') ?>" ;
                            // bulk_url = base_url + "admin/items/bulk_delete";
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
                                table_url( list + "/" + filter_by);
                            });
                        });*/
                    </script>
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
    lista = base_url + "admin/valorizacion/VisitasProgramadasList/<?php echo (isset($action) ? $action : '') ?>";
    table = $("#tabla_ordenes").DataTable({
      ajax: lista
    })
  })
</script>
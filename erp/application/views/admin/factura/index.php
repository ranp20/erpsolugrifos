<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<div class="btn-group pull-right btn-with-tooltip-group _filter_data filtered" data-toggle="tooltip" data-title="<?php echo 'Filtrar por'; ?>">
            <!-- <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                FILTRAR<i class="fa fa-filter" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-left group" style="width:300px;">
                <li class="filter_by"><a href="#"><?php echo 'Todo'; ?></a></li>
                <li class="divider"></li>
                <li class="filter_by" id="1">
                    <a href="#">INGRESADOS</a>
                </li>
                <li class="filter_by" id="10">
                    <a href="#">NO APROBADOS</a>
                </li>
                <li class="filter_by" id="11">
                    <a href="#">APROBADOS</a>
                </li>
                <li class="filter_by" id="12">
                    <a href="#">APROBADOS C/ORDEN</a>
                </li>
                <li class="filter_by" id="2">
                    <a href="#">COMPLETADOS</a>
                </li>
                <div class="clearfix"></div>
            </ul> -->
        </div>
<div class="panel panel-custom">
    <header class="panel-heading ">
        <div class="panel-title"><strong>FACTURAS EMITIDAS</strong></div>
        
    </header>
    <div class="table-responsive">
        <?php
        /* echo "<pre>";
    print_r( $_SESSION );
    echo "</pre>"; */
        ?>
        <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0" width="100%">
            <thead>
                <tr>


                    <th class="col-sm-1">N°</th>
                    <th class="col-sm-1">Factura</th>
                    <th class="col-sm-1">RUC</th>
                    <th class="col-sm-1">Cliente</th>
                    <th class="col-sm-1">Sede</th>
                    <th class="col-sm-1">Monto</th>
                    <th class="col-sm-1">Descripcion</th>
                    <th class="col-sm-1">Fecha Emision</th>
                    <th class="col-sm-1">Fecha Vencimiento</th>
                    <th class="col-sm-1">Dias Vencido</th>
                    <th class="col-sm-1">Estado</th>
                    <th class="col-sm-1">Tipo Ingreso</th>
                    <!-- <th class="col-sm-1">Proceso</th> -->
                    
                    
                    <th class="col-sm-2">Accion</th>
                </tr>
            </thead>
            <tbody>
                <script type="text/javascript">
                    $(document).ready(function() {
                        list = base_url + "admin/factura/FacturaList/";
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
                            table_url(list + filter_by);
                        });

                    });
                </script>
            </tbody>
        </table>
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
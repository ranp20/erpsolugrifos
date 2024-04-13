<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php

if (isset($btn_add) && $btn_add) :
?>
    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/pv_order/add_pv_activitie_order/<?php echo $order_id; ?>"><i class="fa fa-plus"></i> Emitir Orden de Trabajo </a>
    <a class="btn btn-success" href="<?= base_url() ?>admin/pv_order/activities_calendar"><i class="fa fa-calendar"></i> Calendario </a>
<?php endif; ?>
<div class="pull-right">
<a class="btn btn-green" href="<?= base_url() ?>admin/pv_order"><i class="fa fa-arrow-left"></i> Retornar a listado</a>
</div>

<div class="panel panel-custom">
    <header class="panel-heading ">
        <div class="panel-title"><strong> <?php echo $title; ?> </strong></div>
    </header>

    <div class="table-responsive">
        
                <table class="table table-striped DataTables" id="DataTables" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="col-sm-1">Item</th>
                            <th class="col-sm-1">Actividad</th>
                            <th class="col-sm-1">Fecha Ini</th>
                            <th class="col-sm-1">Fecha Fin</th>

                            <th class="col-sm-1"><?= 'Area' ?></th>
                            <th class="col-sm-1"><?= 'Estado' ?></th>
                            <th class="col-sm-1"><?= 'Accion' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                list = base_url + "admin/pv_order/pvActivitiesOrderList/" + <?php echo $order_id; ?> +'/';
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
        // $(function () {
        $(document).on('mouseenter', '[data-toggle="popover"]', function() {
            console.info('click')
            console.info('popover')
            $(this).popover({
                trigger: 'hover'
            })
            $(this).popover('show')
        })
        $(document).on('mouseenter', '[data-toggle="tooltip"]', function() {
            $('[data-toggle="tooltip"]').tooltip({
                'html': true
            })
            $(this).tooltip('show')
        })
        
    </script>
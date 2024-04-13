<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<style type="text/css">
    .pdb-1{padding-bottom: 1rem;}
</style>
<div class="pdb-1">
    <?php
    if (isset($btn_add) && $btn_add) :
    ?>
        <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/pv_order/add_pv_order"><i class="fa fa-plus"></i> Generar Orden </a>
    <?php endif; ?>
    <?php
    if (isset($btn_calendar) && $btn_calendar) :
    ?>  
        <a class="btn btn-success" href="<?= base_url(); ?>admin/pv_order/activities_calendar"><i class="fa fa-calendar"></i> Calendario </a>
        <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url(); ?>admin/pv_order/add_pv_cliente"><i class="fa fa-user"></i> Agregar Orden </a>
    <?php endif; ?>
</div>
<div class="panel panel-custom">
    <header class="panel-heading ">
        <div class="panel-title"><strong>Ordenes Trabajo </strong></div>
    </header>
    <div class="table-responsive">
        <table class="table table-striped DataTables" id="DataTables" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="col-sm-1">Item</th>
                    <th class="col-sm-1">Cliente</th>
                    <th class="col-sm-1">Sede</th>
                    <th class="col-sm-1"><?= 'Accion' ?></th>
                </tr>
            </thead>
            <tbody>
                <script type="text/javascript">
                    $(document).ready(function() {
                        list = base_url + "admin/pv_order/pvOrderList/";
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
$(document).on('mouseenter', '[data-toggle="popover"]', function() {
    //console.info('click');
    //console.info('popover');
    $(this).popover({
        trigger: 'hover'
    });
    $(this).popover('show');
});
$(document).on('mouseenter', '[data-toggle="tooltip"]', function() {
    $('[data-toggle="tooltip"]').tooltip({
        'html': true
    });
    $(this).tooltip('show');
});
</script>
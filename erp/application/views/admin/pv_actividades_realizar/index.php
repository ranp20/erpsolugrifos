<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
    /*
    echo "<pre>";
    echo "<span>designations_id: </span>";
    print_r($_SESSION['designations_id']);
    //echo "<span>user_id: </span>";
    //print_r($_SESSION['user_id']);
    echo "</pre>";
    */
?>
<div class="panel panel-custom">
    <header class="panel-heading ">
        <div class="panel-title"><strong>ACTIVIDADES A REALIZAR</strong></div>
        <a class="btn btn-success" href="<?= base_url() ?>admin/pv_order/activities_calendar"><i class="fa fa-calendar"></i> Calendario </a>
    </header>
    <div class="table-responsive">
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped DataTables" id="DataTables" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="col-sm-1"><?= "N°" ?></th>
                            <th class="col-sm-2"><?= "Actividad" ?></th>
                            <th class="col-sm-2"><?= "Cliente" ?></th>
                            <th class="col-sm-2"><?= "Sede" ?></th>
                            <th class="col-sm-2"><?= "Fecha Inicio" ?></th>
                            <th class="col-sm-2"><?= "Fecha Fin" ?></th>
                            <th class="col-sm-2"><?= "Estado" ?></th>
                            <th class="col-sm-1"><?= 'Accion' ?></th>
                        </tr>
                    </thead>
                    <tbody id="tbl_listActivitiesOrder">
                        
                    </tbody>
                </table>
            </div>
            <div>
            </div>
        </div>
    </div>
<script type="text/javascript">
// LISTAR TODAS LAS ACTIVIDADES...
$(document).ready(function(){
    list = base_url + "admin/pv_actividades_realizar/pvActivitieRealizarList/";
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
        $("#tbl_listActivitiesOrder").html(table_url(list + filter_by));
    });
});
$(document).on('mouseenter', '[data-toggle="popover"]', function() {
    console.info('click');
    console.info('popover');
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
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php

if (isset($btn_add) && $btn_add) :
?>
    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal_large" href="<?= base_url() ?>admin/Service/add_service"><i class="fa fa-plus"></i> Servicio </a>
<?php endif; ?>
<div class="btn-group pull-right btn-with-tooltip-group _filter_data filtered" data-toggle="tooltip" data-title="<?php echo 'Filtrar por'; ?>">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                FILTRAR<i class="fa fa-filter" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-left group" style="width:300px;">
                <li class="filter_by"><a href="#"><?php echo 'Todo'; ?></a></li>
                <li class="divider"></li>
                <li class="filter_by" id="1">
                    <a href="#">INGRESADOS</a>
                </li>
                <div class="clearfix"></div>
            </ul>
        </div>
<div class="panel panel-custom">
    <header class="panel-heading ">
        <div class="panel-title"><strong>SERVICIOS</strong></div>
        
    </header>
    <div class="table-responsive">
        <table class="table table-striped DataTables" id="DataTables" cellspacing="0" width="100%">
            <thead>
                <tr>


                    <th class="col-sm-1"><?= "N°" ?></th>
                    <th class="col-sm-1"><?= "Servicio" ?></th>
                    <th class="col-sm-2"><?= 'Accion' ?></th>
                </tr>
            </thead>
            <tbody>
                <script type="text/javascript">
                    $(document).ready(function() {
                        list = base_url + "admin/Service/serviceList/";
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
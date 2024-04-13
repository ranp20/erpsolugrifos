<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<style type="text/css">
    .pdb-1{padding-bottom: 1rem;}
    .mgl-05{margin-left:0.5rem;}
    .mgl-1{margin-left:1rem;}
</style>
<div class="pdb-1">
    <?php if (isset($btn_add) && $btn_add) : ?>
    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/pv_order/add_pv_activitie_order/<?php echo $order_id; ?>"><i class="fa fa-plus"></i> Emitir Orden de Trabajo </a>
    <a class="btn btn-success" href="<?= base_url() ?>admin/pv_order/activities_calendar"><i class="fa fa-calendar"></i> Calendario </a>
    <?php endif; ?>
    <div class="pull-right">
        <a class="btn btn-green" href="<?= base_url() ?>admin/pv_order"><i class="fa fa-arrow-left"></i> Retornar a Ordenes</a>
    </div>
</div>
<div class="panel panel-custom">
    <header class="panel-heading ">
        <div class="panel-title">
            <strong> <?php echo $title; ?> </strong>
            <?php if (in_array($this->session->userdata('designations_id'), [5])) : ?>
                <a class="btn btn-success mgl-1" href="<?= base_url() ?>admin/pv_order/send_activities">
                    <i class="fa fa-send"></i> 
                    <span class="mgl-05">Enviar ordenes de trabajo Actividades</span>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div class="table-responsive">
        <table class="table table-striped DataTables" id="DataTables" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="col-sm-1">Item</th>
                    <th class="col-sm-1">Actividad</th>
                    <th class="col-sm-1">Fecha Inicio</th>
                    <th class="col-sm-1">Fecha Fin</th>
                    <th class="col-sm-1"><?= 'Area' ?></th>
                    <th class="col-sm-1"><?= 'Estado' ?></th>
                    <th class="col-sm-1"><?= 'Accion' ?></th>
                </tr>
            </thead>
            <tbody>
                <script type="text/javascript">
                    $(document).ready(function() {
                        list = base_url + "admin/pv_order/pvActivitiesOrderList/" + <?php echo $order_id; ?> + '/';
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
                    $(document).ajaxComplete(function() {

                        /* $.extend(true, $.fn.dataTable.defaults, {
                            
                        }); */
                    })
                </script>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    console.log(document.readyState)
    switch (document.readyState) {
        case "loading":
            // The document is still loading.
            break;
        case "interactive":
            // The document has finished loading. We can now access the DOM elements.
            var span = document.createElement("span");
            span.textContent = "A <span> element.";
            document.body.appendChild(span);
            break;
        case "complete":
            // The page is fully loaded.
            console.log("The first CSS rule is: " + document.styleSheets[0].cssRules[0].cssText);
            break;
    }
    

    /* $('#DataTables').on('draw.dt', function() {
        // $(".dt-buttons").css({'display':'none'})
        $(".dt-buttons").html('')
        console.log("draw table")
        
    }); */
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
    $(document).ajaxComplete(function() {

        /* new $.fn.dataTable.Buttons(table, {
            buttons: [{
                extend: 'print',
                text: "<i class='fa fa-print'> </i>",
                className: 'btn btn-danger btn-xs mr',
                exportOptions: {
                    format: {
                        body: function(data, column, row) {
                            data = data.replace(/(<([^>]+)>)/ig, "");
                            return $.trim(data);
                        }
                    },
                    columns: ':not(:last-child)',
                }
            }, ]
        }); */
        /* $.fn.DataTable.Buttons.defaults.buttons = []
        table.ajax.reload(); */
    })
</script>
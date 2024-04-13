<?php if( isset( $btn_add ) && $btn_add ): ?>
    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/cotizacion/add_cotizacion"><i class="fa fa-plus"></i> Crear cotizacion</a>
<?php endif; ?>

<div class="panel panel-custom">
    <header class="panel-heading ">
        <div class="panel-title"><strong><?= ('Todas Cotizaciones') ?></strong></div>
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
                        list = base_url + "admin/cotizacion/CotizacionList/<?php echo (isset( $action ) ? $action : '') ?>" ;
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
                    });
                </script>
            </tbody>
        </table>
    </div>
</div>

<?php /** ?>
<script>
    $(document).on('click', '.delete-document', function() {
        if (confirm('Desea elminar El Registro??')) {
            let id = $(this).data('id')
            $.ajax({
                type: "POST",
                url: base_url + 'admin/document_client/delete_document/' + id,

                dataType: "json",
                success: function(data) {
                    toastr[data.type](data.message)
                    reload_table()
                }
            });
        }
    })
</script>
<?php */ ?>
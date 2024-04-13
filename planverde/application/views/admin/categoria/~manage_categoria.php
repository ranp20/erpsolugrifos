<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
/* echo "<pre>";
print_r($this->uri->segment_array());
echo "</pre>"; */

$id        = $this->uri->segment(5);
$search_by = $this->uri->segment(4);
$created   = can_action('4', 'created');
$edited    = can_action('4', 'edited');
$deleted   = can_action('4', 'deleted');
?>
<div class="row">
    <div class="col-sm-12">
        <?php $is_department_head = is_department_head();
        if ($this->session->userdata('user_type') == 1 || !empty($is_department_head)) { ?>
            <div class="btn-group pull-right btn-with-tooltip-group _filter_data filtered" data-toggle="tooltip"
                 data-title="<?php echo lang('filter_by'); ?>">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-left group"
                    style="width:300px;">
                    <li class="filter_by"><a href="#"><?php echo lang('all'); ?></a></li>
                    <li class="divider"></li>
                    <?php if (count($all_customer_group) > 0) { ?>
                        <?php foreach ($all_customer_group as $group) {
                            ?>
                            <li class="filter_by" id="<?= $group->customer_group_id ?>">
                                <a href="#"><?php echo $group->customer_group; ?></a>
                            </li>
                        <?php }
                        ?>
                        <div class="clearfix"></div>
                    <?php } ?>
                </ul>
            </div>
            <?php
        }
         ?>
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#categoria_list"
                                                                   data-toggle="tab"><?= lang('categoria_list') ?></a></li>
                <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#new_categoria"
                                                                   data-toggle="tab"><?= lang('new_categoria') ?></a></li>
            </ul>
            <style type="text/css">
                .custom-bulk-button {
                    display: initial;
                }
            </style>
            <div class="tab-content bg-white">
                <!-- Stock Category List tab Starts -->
                <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="categoria_list" style="position: relative;">
                    
                        <div class="box">
                            <table class="table table-striped DataTables bulk_table " id="DataTables" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                   
                                    <th><?= lang('name') ?> </th>
                                    
                                    <th class="hidden-print"><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        list = base_url + "admin/categoria/categoriaList";
                                        bulk_url = base_url + 'admin/categoria/bulk_delete';
                                        $('.filtered > .dropdown-toggle').on('click', function () {
                                            if ($('.group').css('display') == 'block') {
                                                $('.group').css('display', 'none');
                                            } else {
                                                $('.group').css('display', 'block')
                                            }
                                        });
                                        $('.filter_by').on('click', function () {
                                            $('.filter_by').removeClass('active');
                                            $('.group').css('display', 'block');
                                            $(this).addClass('active');
                                            var filter_by = $(this).attr('id');
                                            if (filter_by) {
                                                filter_by = filter_by;
                                            } else {
                                                filter_by = '';
                                            }
                                            table_url(base_url + "admin/categoria/categoriaList/" + filter_by);
                                        });
                                    });
                                </script>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                        <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="new_categoria"
                        style="position: relative;">
                        <form role="form" enctype="multipart/form-data" id="form" data-parsley-validate="" novalidate=""
                              action="<?php echo base_url(); ?>admin/categoria/save_categoria/<?php
                              if (!empty($categoria_info)) {
                                  echo $categoria_info->categoria_id;
                              }
                              ?>" method="post" class="form-horizontal  ">
                            <div class="panel-body">
                                <label class="control-label col-sm-3"></label>
                                <div class="col-sm-6">
                                    <div class="nav-tabs-custom">
                                        <!-- Tabs within a box -->
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#general_compnay"
                                                                  data-toggle="tab"><?= lang('general') ?></a>
                                            </li>
                                        </ul>
                                        <div class="tab-content bg-white">
                                            <!-- ************** general *************-->
                                            <div class="chart tab-pane active" id="general_compnay">
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label">nombre Categoria 
                                                        <span
                                                            class="text-danger"> *</span></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" required=""
                                                               value="<?php
                                                               if (!empty($categoria_info->nombre_categoria)) {
                                                                   echo $categoria_info->nombre_categoria;
                                                               }
                                                               ?>" name="nombre_categoria">
                                                    </div>
                                                </div>
                                                
                                                <?php
                                                if (!empty($categoria_info)) {
                                                    $categoria_id = $categoria_info->categoria_id;
                                                } else {
                                                    $categoria_id = null;
                                                }
                                                ?>
                                                <?= custom_form_Fields(12, $categoria_id); ?>
                                            </div><!-- ************** general *************-->

                                            
                                        </div>
                                    </div><!-- /.nav-tabs-custom -->

                                    <div class="btn-bottom-toolbar text-right">
                                        <?php
                                        if (!empty($categoria_info)) { ?>
                                            <button type="submit"
                                                    class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                            <button type="button" onclick="goBack()"
                                                    class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                                        <?php } else {
                                            ?>
                                            <button type="submit"
                                                    class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                                        <?php }
                                        ?>
                                    </div>

                                </div>
                        </form>
                    <?php } else { ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
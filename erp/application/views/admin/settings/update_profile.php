<?php include_once 'assets/admin-ajax.php'; ?>
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$user_id = $this->session->userdata('user_id');
$profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();

$user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
$languages = $this->db->where('active', 1)->order_by('name', 'ASC')->get('tbl_languages')->result();
$locales = $this->db->order_by('name')->get('tbl_locales')->result();
?>
<style type="text/css">
    #id_error_msg {
        display: none;
    }

    .form-groups-bordered > .form-group {
        padding-bottom: 0px
    }
</style>
<div class="row">
    <div class="col-sm-6 wrap-fpanel">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Actualizar Perfil</strong>
                </div>
            </div>
            <div class="panel-body">
                <form role="form" id="update_profile" enctype="multipart/form-data" style="display: initial"
                      action="<?php echo base_url(); ?>admin/settings/profile_updated" method="post"
                      class="form-horizontal form-groups-bordered">

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><strong>Nombres</strong> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="fullname"
                                   value="<?= $profile_info->fullname ?>" required>
                        </div>
                    </div>
                    <input type="hidden" id="user_id" class="form-control" value="<?= my_id() ?>">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><strong>Celular</strong></label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="phone" value="<?= $profile_info->phone ?>">
                        </div>
                    </div>

                
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><strong>Foto de Perfil</strong></label>
                        <div class="col-lg-7">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 210px;">
                                    <?php if ($profile_info->avatar != '') : ?>
                                        <img src="<?php echo base_url() . $profile_info->avatar; ?>">
                                    <?php else: ?>
                                        <img src="http://placehold.it/350x260" alt="Please Connect Your Internet">
                                    <?php endif; ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            <input type="file" name="avatar" value="upload"
                                                   data-buttonText="Seleccionar imagen" id="myImg"/>
                                            <span class="fileinput-exists">Cambiar</span>    
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists"
                                           data-dismiss="fileinput">Quitar</a>
                                </div>
                                <div id="valid_msg" style="color: #e11221"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"></label>
                        <div class="col-lg-8">
                            <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-sm-6 wrap-fpanel">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Cambiar Contraseña</strong>
                </div>
            </div>
            <div class="panel-body">
                <form role="form" data-parsley-validate="" novalidate=""
                      action="<?php echo base_url(); ?>admin/settings/set_password"
                      method="post" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4 control-label">Contraseña Actual<span
                                class="required"> *</span></label>
                        <div class="col-sm-7">
                            <input type="password" id="old_password"
                                   name="old_password" value="" class="form-control"
                                   placeholder="Ingrese su contraseña Actual"/>
                            <span class="required" id="old_password_error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4 control-label"> Nueva Contraseña<span
                                class="required"> *</span></label>
                        <div class="col-sm-7">
                            <input type="password"
                                   name="new_password" id="new_password" value="" class="form-control"
                                   placeholder="Ingrese su Nueva Contraseña"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4 control-label">Repetir contraseña <span
                                class="required"> *</span></label>
                        <div class="col-sm-7">
                            <input type="password" id="confirm_password" data-parsley-equalto="#new_password"
                                   name="confirm_password" value="" class="form-control"
                                   placeholder="Ingrese su Nueva contraseña"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-5">
                            <button id="old_password_button" type="submit"
                                    class="btn btn-primary">Cambiar Contraseña</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>   
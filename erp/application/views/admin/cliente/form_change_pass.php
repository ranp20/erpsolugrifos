
<div class="row">
    <div class="col-sm-12 wrap-fpanel">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong><?= lang('change_password') ?></strong>
                </div>
            </div>
            <div class="panel-body">
                <form role="form" data-parsley-validate="" novalidate=""
                      action="<?php echo base_url(); ?>admin/cliente/set_password"
                      method="post" class="form-horizontal form-groups-bordered" onsubmit=" return validar_pass()">

                    <div class="form-group">
                        <label for="field-1" class="col-sm-4 control-label">Nueva contraseña<span
                                class="required"> *</span></label>
                        <div class="col-sm-7">
                            <input type="password"
                                   name="new_password" id="new_password" value="" class="form-control"
                                   placeholder="Enter Your nueva contraseña"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4 control-label">Repetir Contraseña <span
                                class="required"> *</span></label>
                        <div class="col-sm-7">
                            <input type="password" id="confirm_password" data-parsley-equalto="#new_password"
                                   name="confirm_password" value="" class="form-control"
                                   placeholder="Enter Your Contraseña"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-5">
                            <button id="old_password_button" 
                                    class="btn btn-primary">Cambiar Contraseña</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    function validar_pass(){
        let new_pass = $("#new_password").val(),
        pass = $("#confirm_password").val()

        if(new_pass == pass){
            return true
        }else{
            toastr.error('Contraseñas no coinciden ')
            return false
        }
    }
</script>
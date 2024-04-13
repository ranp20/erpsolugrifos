<div class="panel panel-custom">
    <div class="" data-collapsed="0">
        <div class="panel-heading panelCtm__header">
            <header>
                <h3><?= lang('change_password');?></h3>
            </header>
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
      </div>
        <div class="panel-body">
            <form role="form" data-parsley-validate="" novalidate="" action="<?php echo base_url(); ?>admin/cliente/set_password" method="post" class="form-horizontal form-groups-bordered" onsubmit=" return validar_pass()">
                <div class="form-group">
                    <label for="field-1" class="col-sm-4 control-label">Nueva contraseña<span class="required"> *</span></label>
                    <div class="col-sm-7">
                        <input autocomplete="off" type="password" name="new_password" id="new_password" value="" class="form-control" placeholder="Ingresa tu nueva contraseña"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-4 control-label">Repetir Contraseña <span class="required"> *</span></label>
                    <div class="col-sm-7">
                        <input autocomplete="off" type="password" id="confirm_password" data-parsley-equalto="#new_password" name="confirm_password" value="" class="form-control" placeholder="Repite la contraseña"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-5">
                        <button type="submit" id="old_password_button" class="btn btn-primary">Cambiar Contraseña</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function validar_pass(){
        let new_pass = $("#new_password").val(), pass = $("#confirm_password").val()

        if(new_pass == pass){
            return true;
        }else{
            toastr.error('Las contraseñas no coinciden');
            return false;
        }
    }
</script>
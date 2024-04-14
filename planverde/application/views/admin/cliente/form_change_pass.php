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
      <form role="form" data-parsley-validate="" novalidate="" action="<?php echo base_url(); ?>admin/cliente/set_password" method="post" class="form-horizontal form-groups-bordered" onsubmit="return validar_pass()">
        <div class="form-group ipt-psslogg_plv">
          <div class="col-sm-12">
            <label class="control-label">
              <span>Nueva contraseña</span>
              <span class="text-danger"> *</span>
            </label>
            <div class="pos-relative">
              <input autocomplete="off" type="password" name="new_password" id="new_password" value="" class="form-control" placeholder="Ingresa tu nueva contraseña" required/>
              <span class="icon-changePassControlAdm_ipt">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="cLogin__cont--fLogin--form--controls--cIcon--pass"><path d="M19.604 2.562l-3.346 3.137c-1.27-.428-2.686-.699-4.243-.699-7.569 0-12.015 6.551-12.015 6.551s1.928 2.951 5.146 5.138l-2.911 2.909 1.414 1.414 17.37-17.035-1.415-1.415zm-6.016 5.779c-3.288-1.453-6.681 1.908-5.265 5.206l-1.726 1.707c-1.814-1.16-3.225-2.65-4.06-3.66 1.493-1.648 4.817-4.594 9.478-4.594.927 0 1.796.119 2.61.315l-1.037 1.026zm-2.883 7.431l5.09-4.993c1.017 3.111-2.003 6.067-5.09 4.993zm13.295-4.221s-4.252 7.449-11.985 7.449c-1.379 0-2.662-.291-3.851-.737l1.614-1.583c.715.193 1.458.32 2.237.32 4.791 0 8.104-3.527 9.504-5.364-.729-.822-1.956-1.99-3.587-2.952l1.489-1.46c2.982 1.9 4.579 4.327 4.579 4.327z"></path></svg>
              </span>
            </div>
          </div>
        </div>
        <div class="form-group ipt-psslogg_plv">
          <div class="col-sm-12">
            <label class="control-label">
              <span>Repetir contraseña</span>
              <span class="text-danger"> *</span>
            </label>
            <div class="pos-relative">
              <input autocomplete="off" type="password" id="confirm_password" data-parsley-equalto="#new_password" name="confirm_password" value="" class="form-control" placeholder="Repite la contraseña" required/>
              <span id="mssg_cConfirmTwoPass"></span>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-12 text-right" id="vlid-btn_passequals">
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
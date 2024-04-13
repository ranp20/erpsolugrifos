<div class="panel panel-custom">
  <div class="panel-heading panelCtm__header">
            <header>
                <h3><?= $title;?></h3>
            </header>
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
      </div>
  <div class="panel-body">
    <form role="form" id="update_photo" enctype="multipart/form-data" style="display: initial" action="<?php echo base_url(); ?>client/settings/update_photo" method="post" class="form-horizontal form-groups-bordered">
      <div class="form-group">
        <label class="col-lg-3 control-label"><strong> Foto de perfil </strong></label>
        <div class="col-lg-7">
          <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail" style="width: 210px;">
              <?php if ($profile_info->avatar != '') : ?>
                <img src="<?php echo base_url() . $profile_info->avatar; ?>">
              <?php else : ?>
                <img src="http://placehold.it/350x260" alt="Please Connect Your Internet">
              <?php endif; ?>
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
            <div>
              <span class="btn btn-default btn-file">
                <span class="fileinput-new">
                  <input type="file" name="avatar" value="upload" data-buttonText="Seleccionar Foto" id="myImg" />
                  <span class="fileinput-exists">Cambiar</span>
                </span>
                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a>
            </div>
            <div id="valid_msg" style="color: #e11221"></div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-4 control-label"></label>
        <div class="col-lg-8">
          <button type="submit" class="btn btn-sm btn-primary">Actualizar foto</button>
        </div>
      </div>
    </form>
  </div>
</div>
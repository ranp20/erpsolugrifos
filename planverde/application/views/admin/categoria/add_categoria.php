<div class="panel panel-custom">
  <div class="panel-heading panelCtm__header">
    <header>
      <h3 class="mt-0"><?= $title;?></h3>
    </header>
    <button type="button" class="close" data-dismiss="modal">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Close</span>
    </button>
  </div>
  <form role="form" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/categoria/save_categoria/<?php echo (!empty($categoria_info)) ? $categoria_info->categoria_id : '';?>" method="post" class="form-horizontal  ">
    <div class="form-group">
      <div class="col-sm-12">
        <label class="control-label">
          <span>Nombre Categoría</span>
          <span class="text-danger"> *</span>
        </label>
        <input type="text" name="nombre_categoria" class="form-control" placeholder="Nombre de la categoría" value="<?php echo (!empty($categoria_info->nombre_categoria)) ?  $categoria_info->nombre_categoria : '';?>" required>
      </div>
    </div>
    <div class="form-group mt">
      <label class="col-lg-3"></label>
      <div class="col-lg-6">
        <?php if (isset($categoria_info)) : ?>
          <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
        <?php else : ?>
          <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
        <?php endif; ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </form>
</div>
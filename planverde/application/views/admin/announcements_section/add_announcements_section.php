<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
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
  <?php echo form_open(base_url('admin/announcements_section/save_announcements_section/' . (isset($announcements_sec_info) ? $announcements_sec_info->id : '')), array('id' => 'announcements_section', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>
  <div class="form-group">
    <div class="col-sm-12">
      <label class="control-label">Título</label>
      <input type="text" name="titulo" class="form-control" placeholder="Título del la sección" value="<?php echo (isset($announcements_sec_info->name)) ? $announcements_sec_info->name : ''; ?>" required>
    </div>
  </div>
  <div class="form-group mt">
    <div class="col-lg-12 text-right">
      <?php if (isset($announcements_sec_info)) : ?>
        <button type="submit" class="btn btn-sm btn-primary btn-toupdated">Actualizar</button>
      <?php else : ?>
        <button type="submit" class="btn btn-sm btn-primary btn-tocreated">Guardar</button>
      <?php endif; ?>
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>
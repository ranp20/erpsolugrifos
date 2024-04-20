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
  <?php echo form_open(base_url('admin/anuncio/save_anuncio/' . (isset($anuncio_info) ? $anuncio_info->anuncio_id : '')), array('id' => 'anuncio', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>
  <div class="form-group">
    <div class="col-sm-12">
      <label class="control-label">Título</label>
      <input type="text" name="titulo" class="form-control" placeholder="Título del anuncio" value="<?php echo (isset($anuncio_info->titulo)) ? $anuncio_info->titulo : ''; ?>" required>
    </div>
    <div class="col-sm-12">
      <label class="control-label">Sección</label>
      <div class="">
        <div class="input-group">
          <select name="section_id" class="form-control select_box" style="width: 100%" required>
            <option value="">Selecciona una opción</option>
            <?php
            if (!empty($all_sections)){
              foreach ($all_sections as $sec){
                $sec = (object) $sec;
            ?>
              <option value="<?= $sec->id ?>" <?php echo (isset($anuncio_info->section_id) && $anuncio_info->section_id == $sec->id) ? 'selected' : ''; ?>><?= $sec->name ?></option>
            <?php
              }
            }
            ?>
          </select>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <label class="control-label">Descripción</label>
      <textarea name="descripcion" id="" cols="30" rows="5" placeholder="Descripcion del anuncio" class="form-control"><?php echo (isset($anuncio_info->descripcion)) ? $anuncio_info->descripcion : ''; ?></textarea>
    </div>
  </div>
  <div class="form-group mb0">
    <div class="col-sm-12">
      <label class="control-label">Foto</label>
      <input type="file" name="foto" id="foto" class="form-control" accept=".png, .jpg, .jpeg" <?php echo (isset($anuncio_info) ? '' : 'required') ?> value="<?php echo (isset($anuncio_info->foto)) ? $anuncio_info->foto : ''; ?>">
      <span class="file-custom text-left"><?php echo (isset($anuncio_info->foto)) ? $anuncio_info->foto : ''; ?></span>
      <div class="progress_bar"><div class="percent">0%</div></div>
    </div>
  </div>
  <div class="form-group mb0">
    <div class="col-sm-12">
      <label class="control-label">Adjunto</label>
      <input type="file" name="adjunto" id="adjunto" class="form-control">
      <span class="file-custom text-left"><?php echo (isset($anuncio_info->adjunto)) ? $anuncio_info->adjunto : ''; ?></span>
      <div class="progress_bar"><div class="percent">0%</div></div>
    </div>
  </div>
  <div class="form-group mt">
    <div class="col-lg-12 text-right">
      <?php if (isset($anuncio_info)) : ?>
        <button type="submit" class="btn btn-sm btn-primary btn-toupdated">Actualizar</button>
      <?php else : ?>
        <button type="submit" class="btn btn-sm btn-primary btn-tocreated">Guardar</button>
      <?php endif; ?>
      <a href="<?php echo base_url(); ?>admin/cliente" class="btn btn-default">Cancelar</a>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/back/adm_anuncios.js"></script>
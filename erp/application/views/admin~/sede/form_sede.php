<div class="panel panel-custom sede-panel" style="border: solid #CECECE; padding:3px; margin:10px; ">
  <header class="panel-heading ">
    Sede Operativa
    <span class="btn btn-danger delete-sede"><i class="fa fa-trash"></i> Eliminar Sede</span>
  </header>
  <div class="panel-body">
    <div class="form-group">
      <div class="col-sm-8">
        <label class=" control-label">Nombre Sede</label>
        <input type="text" name="sede_new[]" class="form-control" placeholder="Nombre de Sede" value="" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-8">
        <label class=" control-label">Direccion</label>
        <input type="text" name="direccion_sede_new[]" class="form-control" placeholder="Dirección" value="" required>
      </div>
      <div class="col-sm-4">
        <label class=" control-label">Distrito</label>
        <input type="text" name="distrito_sede_new[]" class="form-control" placeholder="Distrito" value="" required>
      </div>
      <div class="col-sm-4">
        <label class=" control-label">Provincia</label>
        <input type="text" name="provincia_sede_new[]" class="form-control" placeholder="Provincia" value="" required>
      </div>

      <div class="col-sm-4">
        <label class=" control-label">Correo</label>
        <input type="text" name="correo_sede_new[]" class="form-control" placeholder="Correo" value="" required>
      </div>
      <div class="col-sm-4">
        <label class=" control-label">Celular</label>
        <input type="text" name="celular_sede_new[]" class="form-control" placeholder="Celular" value="" required>
      </div>

      <div class="col-sm-6">
        <label class=" control-label">Administrador</label>
        <input type="text" name="administrador_sede_new[]" class="form-control" placeholder="Administrador" value="" required>
      </div>
      <div class="col-sm-6">
        <label class=" control-label">Administrador SST</label>
        <input type="text" name="administrador_sst_sede_new[]" class="form-control" placeholder="Administrador SST" value="" required>
      </div>
    </div>
    <?php /* ?>
    <div class="panel panel-custom">
      <header class="panel-heading ">
        Permisos
      </header>
      <div class="panel-body">
        <div class="row">
          <?php
          $data_categories = $this->db->get('tbl_categoria')->result_object();

          foreach ($data_categories as $key => $cat) :
            $data_subcategories = $this->db->where(['categoria_id' => $cat->categoria_id])->get('tbl_subcategoria')->result_object();
          ?>
            <div class="col-md-4 col-xs-12 col-sm-6">
              <div class="panel panel-success">
                <div class="panel-heading">
                  <label class="cursor:pointer;"><?php echo $cat->nombre_categoria ?>

                    <input data-id="" data-toggle="toggle" name="permissions-all-new[]" class="permissions-all-new" class="permissions-all-new" value="" <?php echo $checked; ?> data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">

                  </label>
                </div>
                <?php
                foreach ($data_subcategories as $key_sub => $subcat) :

                ?>

                  <div class="form-group">
                    <label class="col-lg-6 control-label"><?= $subcat->nombre_subcategoria ?></label>
                    <div class="col-lg-5 checkbox">
                      <input class="permisos permission-check-new" data-id="" data-toggle="toggle" name="permisos_new[]" value="<?= $subcat->subcategoria_id ?>" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
                    </div>
                  </div>

                <?php
                endforeach;
                ?>
              </div>
            </div>
          <?php
          endforeach;
          ?>
        </div>
      </div>
    </div>

    <?php */ ?>
  </div>

  <div class="panel-footer text-center">
    <span class="btn btn-danger delete-sede"><i class="fa fa-trash"></i> Eliminar Sede</span>
  </div>

</div>
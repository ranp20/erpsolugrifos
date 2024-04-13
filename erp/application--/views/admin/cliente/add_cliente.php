<?php

use function GuzzleHttp\json_decode;

include_once 'assets/admin-ajax.php'; ?>
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

  .form-group {
    padding-left: 10px;
    padding-right: 10px
  }
</style>
<div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10 wrap-fpanel" style="margin: 10px;">
    <div class="panel panel-custom">
      <header class="panel-heading ">
        <?= $title ?>
      </header>
      <?php echo form_open(base_url('admin/cliente/save_cliente/' . (isset($cliente_info) ? $cliente_info->cliente_id : '')), array('id' => 'cliente', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>


      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label"><?= ('Razon social') ?></label>
          <input type="text" name="razon_social" class="form-control" placeholder="RAZON SOCIAL DEL CLIENTE" value="<?php echo (isset($cliente_info->razon_social)) ? $cliente_info->razon_social : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label"><?= ('Ruc') ?></label>
          <input type="text" name="ruc" class="form-control" placeholder="NUMERO DE RUC" value="<?php echo (isset($cliente_info->ruc)) ? $cliente_info->ruc : ''; ?>" required>
        </div>
      </div>


      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label">Direccion Legal</label>
          <input type="text" name="direccion_legal" class="form-control" placeholder="DIRECCION LEGAL" value="<?php echo (isset($cliente_info->direccion_legal)) ? $cliente_info->direccion_legal : ''; ?>" required>
        </div>
      </div>


      <div class="form-group">
        <div class="col-sm-6">
          <label class=" control-label">Distrito</label>
          <input type="text" name="distrito" class="form-control" placeholder="DISTRITO" value="<?php echo (isset($cliente_info->distrito)) ? $cliente_info->distrito : ''; ?>" required>
        </div>
        <div class="col-sm-6">
          <label class=" control-label">Provincia</label>
          <input type="text" name="provincia" class="form-control" placeholder="PROVINCIA" value="<?php echo (isset($cliente_info->provincia)) ? $cliente_info->provincia : ''; ?>" required>
        </div>
      </div>


      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label">Representante Legal</label>
          <input type="text" name="representante_legal" class="form-control" placeholder="REPRESENTANTE LEGAl" value="<?php echo (isset($cliente_info->representante_legal)) ? $cliente_info->representante_legal : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">DNI</label>
          <input type="text" name="dni_representante" class="form-control" placeholder="DNI REPRESENTANTE" value="<?php echo (isset($cliente_info->dni_representante)) ? $cliente_info->dni_representante : ''; ?>" required>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label">Gerente Legal</label>
          <input type="text" name="gerente_legal" class="form-control" placeholder="GERENTE LEGAl" value="<?php echo (isset($cliente_info->gerente_legal)) ? $cliente_info->gerente_legal : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">DNI</label>
          <input type="text" name="dni_gerente" class="form-control" placeholder="DNI GERENTE" value="<?php echo (isset($cliente_info->dni_gerente)) ? $cliente_info->dni_gerente : ''; ?>" required>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-8">
          <label class=" control-label">Supervisor</label>
          <input type="text" name="supervisor" class="form-control" placeholder="SUPERVISOR" value="<?php echo (isset($cliente_info->supervisor)) ? $cliente_info->supervisor : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">Correo</label>
          <input type="text" name="correo" class="form-control" placeholder="CORREO SUPERVISOR" value="<?php echo (isset($cliente_info->correo)) ? $cliente_info->correo : ''; ?>" required>
        </div>
        <div class="col-sm-4">
          <label class=" control-label">Celular</label>
          <input type="text" name="celular" class="form-control" placeholder="Celular Supervisor" value="<?php echo (isset($cliente_info->celular)) ? $cliente_info->celular : ''; ?>" required>
        </div>
      </div>

      <div id="sedes">
        <?php
        if (isset($sedes)) {
          $data_sede = json_decode($sedes);
          foreach ($data_sede as $key => $sede) :
            // $permission = json_decode( $sede->permission );
        ?>

            <div class="panel panel-custom">
              <header class="panel-heading ">

                Sede Operativa
              </header>
              <div class="panel-body">
                <div class="form-group">
                  <div class="col-sm-8">
                    <label class=" control-label">Nombre Sede</label>
                    <input type="text" name="sede[<?php echo $sede->sede_id; ?>]" class="form-control" placeholder="Nombre de Sede" value="<?php echo (isset($sede->sede)) ? $sede->sede : ''; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-8">
                    <label class=" control-label">Direccion</label>
                    <input type="text" name="direccion_sede[<?php echo $sede->sede_id; ?>]" class="form-control" placeholder="Dirección" value="<?php echo (isset($sede->direccion)) ? $sede->direccion : ''; ?>" required>
                  </div>
                  <div class="col-sm-4">
                    <label class=" control-label">Distrito</label>
                    <input type="text" name="distrito_sede[<?php echo $sede->sede_id; ?>]" class="form-control" placeholder="Distrito" value="<?php echo (isset($sede->distrito)) ? $sede->distrito : ''; ?>" required>
                  </div>
                  <div class="col-sm-4">
                    <label class=" control-label">Provincia</label>
                    <input type="text" name="provincia_sede[<?php echo $sede->sede_id; ?>]" class="form-control" placeholder="Provincia" value="<?php echo (isset($sede->provincia)) ? $sede->provincia : ''; ?>" required>
                  </div>

                  <div class="col-sm-4">
                    <label class=" control-label">Correo</label>
                    <input type="text" name="correo_sede[<?php echo $sede->sede_id; ?>]" class="form-control" placeholder="Correo" value="<?php echo (isset($sede->correo)) ? $sede->correo : ''; ?>" required>
                  </div>
                  <div class="col-sm-4">
                    <label class=" control-label">Celular</label>
                    <input type="text" name="celular_sede[<?php echo $sede->sede_id; ?>]" class="form-control" placeholder="Celular" value="<?php echo (isset($sede->celular)) ? $sede->celular : ''; ?>" required>
                  </div>

                  <div class="col-sm-6">
                    <label class=" control-label">Administrador</label>
                    <input type="text" name="administrador_sede[<?php echo $sede->sede_id; ?>]" class="form-control" placeholder="Administrador" value="<?php echo (isset($sede->administrador)) ? $sede->administrador : ''; ?>" required>
                  </div>
                  <div class="col-sm-6">
                    <label class=" control-label">Administrador SST</label>
                    <input type="text" name="administrador_sst_sede[<?php echo $sede->sede_id; ?>]" class="form-control" placeholder="Administrador SST" value="<?php echo (isset($sede->administrador_sst)) ? $sede->administrador_sst : ''; ?>" required>
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
                      $permissions = json_decode($sede->permission);

                      $data_categories = $this->db->get('tbl_categoria')->result_object();

                      foreach ($data_categories as $key => $cat) :
                        $data_subcategories = $this->db->where(['categoria_id' => $cat->categoria_id])->get('tbl_subcategoria')->result_object();

                        $countCheck = 0;
                        foreach( $data_subcategories as $key => $v ):
                          $countCheck += (int)(( in_array( $v->subcategoria_id, $permissions ) ) ? 1 : 0);
                        endforeach;

                         $checked = (count($data_subcategories) == ($countCheck)) ? 'checked' : '';

                      ?>
                        <div class="col-md-4 col-xs-12 col-sm-6">
                          <div class="panel panel-primary">
                            <div class="panel-heading">
                              <?php echo $cat->nombre_categoria. '-'. count($data_subcategories).'-'. ($countCheck) ?>
                              <div class=" checkbox">
                                <input data-id="" data-toggle="toggle" name="permissions-all[]" class="permissions-all" value="" <?php echo $checked; ?> data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
                              </div>
                            </div>
                            <?php
                            foreach ($data_subcategories as $key_sub => $subcat) :

                            ?>

                              <div class="form-group">
                                <label class="col-lg-6 control-label"><?= $subcat->nombre_subcategoria ?></label>
                                <div class="col-lg-5 checkbox">
                                  <input class="permission-check" data-id="" data-toggle="toggle" name="permisos_<?php echo $sede->sede_id . '[]'; ?>" value="<?= $subcat->subcategoria_id ?>" <?php echo (!empty($permissions) && in_array($subcat->subcategoria_id, $permissions)) ? 'checked' : ''; ?> data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
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
            </div> <?php
                  endforeach;
                }
                    ?>
      </div>
      <div class="text-center">
        <span id="new-sede" class="btn btn-primary">Agregar Sede</span>
      </div>

      <div class="form-group mt">
        <label class="col-lg-3"></label>
        <div class="col-lg-3">
          <?php if (isset($cliente_info)) : ?>
            <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
          <?php else : ?>
            <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
          <?php endif; ?>
          <a href="<?php echo base_url(); ?>admin/cliente" class="btn btn-default">Cancelar</a>
        </div>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>

</div>

<script>
  var permiso = 0
  $(document).ready(function() {})
  $(document).on('click', '#new-sede', function() {
    $.ajax({
      type: "POST",
      url: base_url + 'admin/sede/form',
      dataType: "html",
      success: function(response) {
        $("#sedes").append(response);

      },
      complete: function() {
        console.log('sis')
        $(".permisos").addClass('new_permission')
        $('.new_permission').attr('name', 'permisos_new_' + permiso + '[]')
        permiso += 1
        $(".permisos").removeClass('new_permission')
        $(".permisos").removeClass('permisos')
      }
    });

  })

  $(document).on('click', '.delete-sede', function() {
    let el = $(this)
    el.parent().parent('.sede-panel').remove()
  })

  $(document).on('change', '.permissions-all', function() {
    let el = $(this),
      check = el.parent().parent().parent().parent().find('.permission-check')

    $.each(check, function(i, v) {
      item = $(this)
      // console.log(item)
      if (el.prop('checked') == true) {
        item.prop('checked', true)
        item.parent().addClass('btn-success on')
        item.parent().removeClass('btn-danger off')
      } else {
        item.prop('checked', false)
        item.parent().removeClass('btn-success on')
        item.parent().addClass('btn-danger off')
      }
      console.log(item.parent().parent())
    });
  })
  $(document).on('change', '.permissions-all-new', function() {
    let el = $(this),
      check = el.parent().parent().parent().parent().find('.permission-check-new')

    $.each(check, function(i, v) {
      item = $(this)
      // console.log(item)
      if (el.prop('checked') == true) {
        item.prop('checked', true)
        /* item.parent().addClass('btn-success on')
        item.parent().removeClass('btn-danger off') */
      } else {
        item.prop('checked', false)
        /* item.parent().removeClass('btn-success on')
        item.parent().addClass('btn-danger off') */
      }
      console.log(item.parent().parent())
    });
  })
</script>
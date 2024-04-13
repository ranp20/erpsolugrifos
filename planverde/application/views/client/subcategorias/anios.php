<div class="panel panel-custom">
  <header class="panel-heading ">
    <div class="panel-title">
      <strong><?= $categoria . ' - ' . $subcategoria?></strong>
      <!--
      <a class="btn btn-primary" href="<?php /* echo base_url()*/ ?>client/subcategoria/list/<?php /* echo $id_categoria; */?>"><i class="fa fa-undo"></i> Volver</a>
      -->
        <button type="button" class="btn btn-primary ml-auto float-right" onclick="returnPageBack();">
          <i class="fa fa-undo"></i>
          <span>Volver</span>
        </button>
    </div>
  </header>
  <!--
  <div class="table-responsive">
    <table class="table table-striped " cellspacing="0" width="100%">
      <thead>
        <tr>
          <th><?= ('Año') ?></th>
          <th class="col-sm-1">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        /*
        if ($all_anios) :
          foreach ($all_anios as $key => $anio) :
        */    
        ?>
            <tr>
              <td><?php // echo $anio->anio; ?></td>
              <td>
                <a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Click Para ver" href="<?php // echo base_url() . 'client/document/list/' . $anio->anio_id . '/' . $subcategoria_id ?>"><span class="fa fa-eye"></span></a>
              </td>
            </tr>
          <?php
        /*  
          endforeach;
        */  
          ?>

        <?php /*else :*/ ?>
          <tr>
            <td colspan="2">No existe Informacion</td>
          </tr>
        <?php /*endif;*/ ?>
      </tbody>
    </table>
    </div>
    -->
    
    <div class="row">
        <?php if (!empty($all_anios)) {
        foreach ($all_anios as $key => $anio){
        ?>
          <div class="col-lg-2 col-sm-4 box-folder">
            <a class="link-folder" href="<?= base_url() . 'client/document/list/' . $anio->anio_id . '/' . $subcategoria_id ?>" data-toggle="tooltip" title="<?php echo $anio->anio;?>">
              <div class="panel widget mb0 b0 folder">
                <div class="row-table row-flush">
                  <div class="col-xs-12  text-center">
                    <em class="fa fa-folder fa-3x"></em>
                    <h5 class="title-folder"><?php echo $anio->anio;?></h5>
                  </div>
                </div>
              </div>
            </a>
          </div>
        <?php
        }
        }
        ?>
    </div>
  
</div>
<script type="text/javascript">
    function returnPageBack(){
        window.history.back();
    }
</script>
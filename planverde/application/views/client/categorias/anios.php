<?php
// print_r(($all_anios) ? $all_anios : ' no hay infor');
echo "<pre>";
// print_r( $_SESSION );
echo "</pre>";
?>

<a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>client/document/add_document/<?= $id_categoria?>"><i class="fa fa-plus"></i> Crear Documento</a>

<div class="panel panel-custom">
  <header class="panel-heading ">
    <div class="panel-title"><strong><?= $categoria; ?></strong></div>
  </header>
  <div class="table-responsive">
    <table class="table table-striped "  cellspacing="0" width="100%">
      <thead>
        <tr>
          
          <th><?= ('Año') ?></th>

          <th class="col-sm-1">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if( $all_anios ): 
          foreach ($all_anios as $key => $anio) :
        ?>
            <tr>
              <td><?php echo $anio->anio; ?></td>
              <td>
                <a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Click Para ver" href="<?= base_url() . 'client/document/list/' . $anio->anio . '/' . $anio->categoria_id ?>"><span class="fa fa-eye"></span></a>
              </td>
            </tr>
        <?php
          endforeach;
        ?>
        
        <?php else: ?>
        <tr>
          <td colspan="2">No existe Informacion</td>
        </tr>
        <?php endif; ?>
        <!-- <script type="text/javascript">
          $(document).ready(function() {
            list = base_url + "client/categoria/list_anios/" + <?php echo $id_categoria; ?>;
            bulk_url = base_url + "client/categoria/bulk_delete";
            $('.filtered > .dropdown-toggle').on('click', function() {
              if ($('.group').css('display') == 'block') {
                $('.group').css('display', 'none');
              } else {
                $('.group').css('display', 'block')
              }
            });
            $('.filter_by').on('click', function() {
              $('.filter_by').removeClass('active');
              $('.group').css('display', 'block');
              $(this).addClass('active');
              var filter_by = $(this).attr('id');
              if (filter_by) {
                filter_by = filter_by;
              } else {
                filter_by = '';
              }
              table_url(list + filter_by);
            });
          });
        </script> -->
      </tbody>
    </table>
  </div>
</div>
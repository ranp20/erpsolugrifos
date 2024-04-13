<a class="btn btn-warning" href="<?= base_url() ?>client/subcategoria/anio/<?= $id_subcategoria?>" style="margin-bottom: 1rem;">
    <i class="fa fa-undo"></i>
    <span style="margin-left: 0.5rem;"> Ver años</span>
</a>
<div>
    <?php 
        /*
        echo "sede_id: ". $_SESSION['sede']."<br>";
        echo "idcategor���a: " . $id_subcategoria."<br>";
        echo "a���o: " . $anio."<br>";
        echo "cliente_id: " . $cliente_id."<br>";
        */
        /*
        echo "<pre>";
        print_r($all_documents);
        echo "</pre>";
        echo "<pre>";
        print_r($info_client);
        echo "</pre>";
        */
        /*
        $client_id = $info_client[0]['client_id'];
        echo $client_id;
        */
        
        function cambiaf_mysql($date){
            $originalDate = $date;
            $newDate = date("d/m/Y - h:i:s a", strtotime($originalDate));
            return $newDate;
        }
    ?>
</div>
<div class="panel panel-custom">
  <header class="panel-heading ">
    <div class="panel-title"><strong><?= $categoria; ?></strong></div>
  </header>
  <div class="table-responsive">
    <table class="table table-striped "  cellspacing="0" width="100%">
      <thead>
        <tr>
          <th><?= 'F. Creación' ?></th>
          <th><?= ('Año') ?></th>
          <th><?= ('Mes') ?></th>
          <th><?= ('Nombre') ?></th>
          <th class="col-sm-1">Descargar</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if( isset($all_documents) && count( $all_documents ) > 0 ): 
          foreach ($all_documents as $key => $document) :
            $document = (object) $document;
        ?>
            <tr>
              <td><?php echo cambiaf_mysql($document->created_at);?></td>
              <td><?php echo $document->anio;?></td>
              <td><?php echo $document->mes;?></td>
              <td><?php echo $document->nombre;?></td>
              <td>
                <a target="_blank" data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-xs" title="Click Para ver" href="<?=  $document->ruta ?>">
                    <span class="fa fa-download"></span>
                </a>
              </td>
            </tr>
        <?php
          endforeach;
        ?>
        <?php else: ?>
        <tr>
          <td colspan="5">No existe Informacion</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
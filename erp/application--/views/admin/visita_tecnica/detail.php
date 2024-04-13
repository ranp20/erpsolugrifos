<?php
/* echo "<pre>";
print_r( $detail );
echo "</pre>"; */
?>
<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    Detalle de visita técnica <strong><?php echo strtoupper( ( $visita = $this->db->where( ['visita_tecnica_id' => $id ] )->get( 'tbl_visita_tecnica' )->row()->servicio ) ? $visita : '' ); ?></strong>
  </header>
  <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0" width="100%">
    <thead>
      <tr>

        <th class="col-sm-1">Usuario</th>
        <th class="col-sm-1">Area</th>
        <th class="col-sm-1">Detalle</th>
        <th class="col-sm-1">Proceso</th>
        <th class="col-sm-1">Comentario</th>
        <th class="col-sm-1">Fecha</th>
        <!-- <th class="col-sm-1">Accion</th> -->
      </tr>
    </thead>
    <tbody>
      <?php
      if (isset($detail) && count($detail) > 0) :
        foreach ($detail as $key => $d) :
      ?>
          <tr>
            <td><?php echo $this->db->where('user_id', $d->user_id)->get('tbl_account_details')->row()->fullname; ?></td>
            <td><?php echo $this->db->where('designations_id', $d->designations_id)->get('tbl_designations')->row()->designations; ?></td>
            <td><?php echo ($d->detail); ?></td>
            <td><?php echo ($d->proceso); ?></td>
            <td><?php echo $d->comentario; ?></td>
            <td><?php echo $d->created_at; ?></td>
          </tr>
        <?php
        endforeach;
      else : ?>

      <?php endif; ?>
    </tbody>
  </table>
</div>
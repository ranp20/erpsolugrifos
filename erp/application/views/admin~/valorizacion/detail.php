<?php 
/* echo "<pre>";
print_r( $detail );
echo "</pre>"; */
?>
</div>
        <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0" width="100%">
            <thead>
                <tr>
                    

                    <th class="col-sm-1">Accion</th>
                    <th class="col-sm-1">Usuario</th>
                    <th class="col-sm-1">Area</th>
                    <th class="col-sm-1">Estado</th>
                    <th class="col-sm-1">Comentario</th>
                    <th class="col-sm-1">Fecha</th>
                    <!-- <th class="col-sm-1">Accion</th> -->
                </tr>
            </thead>
            <tbody>
                <?php 
                if( isset( $detail ) && count( $detail ) > 0 ): 
                  foreach ($detail as $key => $d):
                ?>
                <tr>
                  <td><?php echo $d->accion. ' ' . $d->valor_accion; ?></td>
                  <td><?php echo $this->db->where( 'user_id', $d->user_id )->get( 'tbl_account_details' )->row()->fullname ; ?></td>
                  <td><?php echo $this->db->where( 'designations_id', $d->designation_id  )->get('tbl_designations')->row()->designations; ?></td>
                  <td><?php echo ($d->status == 1) ? 'Correcto' : 'Fallo' ; ?></td>
                  <td><?php echo $d->comment; ?></td>
                  <td><?php echo $d->created_at; ?></td>
                  <!-- <td><?php echo $d->valorizacion_id; ?></td> -->
                </tr>
                <?php 
                endforeach;
              else: ?>
                
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

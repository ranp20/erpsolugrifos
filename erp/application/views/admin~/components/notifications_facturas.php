<?php
$this->db->select('f.*');
$this->db->from('tbl_facturas f');
$this->db->join('tbl_cotizacion_pago cp', 'f.cotizacion_pago_id = cp.cotizacion_pago_id');
$this->db->where('cp.ruta IS NULL');
$data_facturas = $this->db->get()->result();
$unread_notifications = 0;

foreach ($data_facturas as $key => $factura) :
  $dias_vencido ;
  $fecha_actual = strtotime(date('Y-m-d'), time());
  $fecha_vencimiento = strtotime($factura->fecha_vencimiento);
  if ($fecha_actual > $fecha_vencimiento) :
    // Calculando dias vencido
    $fecha_actual = new DateTime(date('Y-m-d'));
    $fecha_vencimiento = new DateTime($factura->fecha_vencimiento);
    $dif = $fecha_actual->diff($fecha_vencimiento);
    $dias_vencido = $dif->days;

  endif;
  if( $dias_vencido > 0 ):
    $facturas[] = $factura;
    $unread_notifications += 1;
  endif;
endforeach;



?>

<a href="#" data-toggle="dropdown">
  <em class="icon-tag"></em>
  <?php
  
  if ($unread_notifications > 0) { ?>
    <div class="label label-danger unraed-total icon-notifications"><?php echo $unread_notifications; ?></div>
  <?php } ?>
</a>
<!-- START Dropdown menu-->
<ul class="dropdown-menu animated zoomIn notifications-list-facturas" data-total-unread="<?php echo $unread_notifications; ?>" style="width: 350px">
  <?php
  if ($unread_notifications > 0) :
    foreach ($facturas as $key => $factura) :
      
  ?>
      <li class="notification-li" data-notification-id="<?php echo '$notification->notifications_id'; ?>">
        <a href="<?php echo base_url() . 'admin/factura/'; ?>" class="n-top n-link list-group-item ">
          <div class="n-box media-box ">
            <div class="pull-left">
              <h4><?php echo $factura->num_factura; ?></h4>
            </div>
            <div class="media-box-body clearfix">
              <?php
              $description = 'FACTURA VENCIDA';
              echo '<span class="n-title text-sm block">' . $description . '</span>'; ?>
              <small class="text-muted pull-left" style="margin-top: -4px"><i class="fa fa-clock-o"></i> <?php echo $dias_vencido . ' dia(s) vencida'; ?></small>

            </div>
          </div>
        </a>
      </li>
    <?php
    endforeach;
  else :
    ?>
    <li class="text-center">
      No Existen facturas vencidas.
    </li>
  <?php
  endif;
  ?>
  <!-- END list group-->
</ul>
<!-- END Dropdown menu-->
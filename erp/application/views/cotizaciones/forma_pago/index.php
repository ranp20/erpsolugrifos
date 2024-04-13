<?php /* ?>
forma de pago
-poner el check ( Adelanto ) si tiene adelanto re alcular los inputs de pagos
jalaremos un w q es el tipo de pago

tabla
  cabecera( cotizacion  ) campo adelanto (0,1) 0 no hay adelanto// 1=> si tiene adelanto

  detalle ===> tabla de pagos segun la cotiacion 
    nombre_pago   porcentaje  status
    si hay adelanto 
    adelanto => 50 => status(0,1)(0 debe; 1 pago)
    pago1=> 30 => status
<?php */ ?>
<div class="form-group">
  <legend class="col-sm-12">Forma de pago</legend>
  <?php
  /* echo $partes;
  echo (isset($subview)) ? $subview : 'no hay ';
  echo (isset($adelanto)) ? $adelanto : 'no hays'; */
  if( $adelanto == 1 ):
    $partes -= 1;
  ?>
  <div class="col-sm-4">
    <label for="">
      adelanto ( % ): 
      <input type="text" name="pago_adelanto" value="50" class="form-control" require>
    </label>
  </div>
  <?php 
  endif; 
  // echo $partes;
  for ($i=1; $i <= $partes ; $i++):
    ?>
    <div class="col-sm-4">
    <label for="">
      pago <?php echo $i . ' ( % ) '?>: 
      <input type="text" name="pago[]" value="" class="form-control" require>
    </label>
  </div>
    <?php
  endfor;
  ?>
</div>
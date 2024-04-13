<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COTIZACIÓN N° <?php echo $info->cotizacion_id; ?> - <?php echo date(strftime("Y", $info->fecha)); ?></title>
  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>

  <style>
    /* LOAD GOOGLE FONT */

    /* latin-ext */

    @import url('https://fonts.googleapis.com/css2?family=Oxygen&display=swap');

    *,
    body,
    html {
      box-sizing: border-box;
      font-family: 'Oxygen', 'sans-serif';

    }

    h2,
    h3,
    h4 {
      font-family: 'sans-serif';
      font-weight: 800;
    }



    header {
      position: static;
      left: -65px !important;
      margin-left: -45px;
      margin-top: -45px;
      margin-right: -40px;
      margin-bottom: 10px;
      line-height: 17px;
    }

    .texto-left {
      padding-left: 10px;
      padding-top: 10px;
    }

    .img_banco {
      width: 50px;
      height: 10px;
      color: #0D5799;
      border: 0px red solid;
      font-size: 50pt;
      margin-top: -45px;
    }
  </style>
</head>

<body>

  <header>
    <div><img src="<?php echo './assets/img/head_color.png' ?>" alt="" height="17.75px"></div>
    <div class="clear-fix"></div>
    <div class="pull-right" style="padding: 10px;">
      <img src="assets/img/solugrifos-nuevo-2.png" alt="">
    </div>
    <div class="texto-left">
      SOLUGRIFOS S.A.C <br>
      RUC: 20600068319 <br>
      Emily Car N° 162 Surquillo, Lima. <br>
<br>

    </div>
  </header>
  <div class="container-fluid">
    <style>
      .num_cotizacion {
        border: 0px solid red;
        text-align: center;
      }

      .cotizacion {
        border: 0px solid green;
        margin: auto;
        width: 400px;
        color: white;
        background-color: #0D5799;
        padding: 5px;
        border-radius: 10px;
      }

      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }
    </style>
    <div class="num_cotizacion">
      <h3 class="cotizacion">COTIZACIÓN N° <?php echo $info->cotizacion_id; ?> - <?php echo date(strftime("Y", $info->fecha)); ?></h3>
    </div>
    <div style="padding-top:10px;">
      <div class="pull-right" style="width:50%;">
        <div  class="" ><strong>DATOS DEL CLIENTE</strong></div>
        <strong>Razon Social:</strong><br>
        <?php echo $info->razon_social; ?> <br>
        <strong>RUC:</strong><br>
        <?php echo $info->ruc; ?> <br>
        <strong>Direccion:</strong><br>
        <?php echo $info->direccion_legal; ?> <br>
        <strong>Celular: </strong><?= $info->celular ?><br>
        <strong>Sede: </strong> <?= $info->sede; ?> <br>
      </div>
      <div class="" style="width:49%;">
        <strong>Fecha: </strong><?php echo display_date(($info->fecha)); ?><br>
        <strong>Vigencia: </strong><?php echo display_date(($info->fecha_vigencia)); ?>

        <br><br>
        <strong class="text-center">PAGOS</strong><br>
        <?php foreach( $pagos as $key => $pago ): ?>
        <strong><?php echo ucwords( $pago->descripcion ) ?>:</strong> <?php echo $pago->porcentaje;?> %<br>
        <?php endforeach; ?>
        <br><br><br><br><br><br>
      </div>

    </div>


    <div class="clearfix"></div>

    <br>
    <br>
    <div class="clearfix"></div>
    <div class='panel panel-primary'>

      <div class='panel-heading text-center'>

        <h4 style=""><?= $service->service;?></h4>

      </div>

      <div class='panel-body'>
      <?= $service->descripcion; ?>
      </div>
      <div class="panel-footer">
        <div class="pull-right h4"><strong>Total: S/. <?= display_money($info->monto);?></strong></div>
      </div>
    </div>

  </div>
  <footer style="position: fixed; bottom: 46px; border:1px solid white;">
    <div class="img_banco">
      BBVA
    </div>
    <style>
      .cuentas {
        margin-left: 200px;
        line-height: 15px;
      }

      footer ul {
        list-style: none;
        float: left;
        display: block;
        text-align: center;
        align-items: center;
        padding-top: 5px;
      }

      footer ul li {
        display: inline-block;
        padding: 0px 20px 5px 20px;

      }
    </style>
    <div class="cuentas">
      Número de Cuenta <br>
      Soles: 0011 0138 0100054206 <br>
      CCI: 011 138 000 100054206 56 <br>
    </div>
    <div class="clear-fix"></div>
    <div class="contacto">
      <ul>
        <li>(01) 71154328 / 949618736</li>
        <li>ventas@solugrifos.com</li>
        <li> www.solugrifos.com</li>
      </ul>
    </div>
  </footer>
</body>

</html>
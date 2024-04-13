<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
      margin-right: -45px;
      margin-bottom: 10px;
      line-height: 17px;
    }

    .texto-left {
      padding-left: 10px;
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
    <div><img src="<?php echo './assets/img/head_ot.png' ?>" alt="" width="100%"></div>

  </header>
  <div class="container-fluid">
    <style>
      .caja {
        border: 0px solid red;
        text-align: center;
        padding: 0px !important;
      }

      .subcaja {
        text-align: left;
        width: 170px;
        color: black;
        padding: 5px;

        border-radius: 5px;
      }

      .cotizacion {
        border: 1px solid #CECECE;
      }

      .titulo {
        color: white;
        background-color: #0c031a;
        text-align: center;
        width: 100%;
      }

      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }

      .t-title {
        width: 20%;
      }
      .panel-primary > .panel-heading{
        background-color: #0c031a !important;
        padding: 0px;
      }
      .panel-primary{
        border: 1px solid #0c031a;
      }
    </style>
    <div class="caja">
      <h3 class="subcaja cotizacion"> N°: <?php echo $info->cotizacion_ot_id; ?> - <?php echo date(strftime("Y",$info->fecha));?></h3>
    </div>
    <div class="caja">
      <h3 class="subcaja titulo"> ORDEN DE TRABAJO SOLUGRIFOS</h3>
    </div>
    <table class="table table-bordered table-condensed">
      <tr>
        <th class="t-title">Emitido por: </th>
        <td class="col-sm-3"><?php echo $info->fullname; ?></td>
        <th class="t-title">Fecha de Emision </th>
        <td class="col-sm-2"><?php echo $info->start_date; ?> </td>
      </tr>
    </table>

    <div class="caja">
      <h4 class="subcaja titulo"> DATOS DEL AREA RESPONSABLE </h4>
    </div>
    <table class="table table-bordered table-condensed">
      <tr>
        <th class="t-title">Area</th>
        <td colspan="4" ><?php echo $info->designations; ?></td>
      </tr>
    </table>

    <div class="caja">
      <h4 class="subcaja titulo"> DATOS DE LA ACTIVIDAD </h4>
    </div>
    <table class="table table-bordered table-condensed">
      <tr>
        <th class="t-title">RUC</th>
        <td colspan="4" ><?php echo $info->ruc; ?></td>
        <th class="t-title">Cliente</th>
        <td colspan="4" ><?php echo $info->razon_social; ?></td>
      </tr>
      <tr>
        <th class="t-title">Sede</th>
        <td colspan="4" ><?php echo $info->sede; ?></td>
      </tr>
    </table>

    <div class="caja">
      <h4 class="subcaja titulo"> DESCRIPCION DE LA ACTIVIDAD </h4>
    </div>
    <table class="table table-bordered table-condensed">
      <tr class="text-center">
        <td><?php echo $info->service; ?></td>
      </tr>
    </table>



    <div class='panel panel-primary'>

      <div class='panel-heading text-center'>

        <h4 >Detalles:</h4>

      </div>

      <div class='panel-body'>
      <?php echo $info->comment; ?>
      </div>
    </div>

  </div>
  <style>
    footer {
      position: fixed;
      bottom: -30px;
      left: -46.5px;
      right: -46px;
      border: 1px solid white;
    }
  </style>
  <footer>
    <div><img src="<?php echo './assets/img/footer_ot.png' ?>" alt="" width="100%"></div>
  </footer>
</body>

</html>
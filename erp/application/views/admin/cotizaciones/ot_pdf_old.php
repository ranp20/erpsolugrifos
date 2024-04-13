<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title><?php echo "OT - 000000000".$info->cotizacion_ot_id; ?></title>
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" id="bscss">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.css" id="maincss"> -->
  <style type="text/css">
        @font-face{
            font-family: "Source Sans Pro", sans-serif;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        #logo{
          width: 250px;
          height: 70px;
          background-size: 100% 100%;
          background-repeat:no-repeat;
          background: url(assets/img/solugrifos-nuevo-2.png);
          background-size: cover;
          border: 0px red solid;
        }
        #img_banco{
          width: 50px;
          height: 10px;
          color: #0D5799;
          border: 0px red solid;
          font-size: 30pt;
        }
        #tit_cot{
          width: 400px;
          height: 40px;
          background: #002F4D;
          color: #FFFFFF;
          position: relative;
          margin: 0px 200px;
          border-radius: 10px;
          padding: 5px 0px;
        }
        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            color: #555555;
            background: #FFFFFF;
            font-size: 14px;
            font-family: "Source Sans Pro", sans-serif;
            width: 100%;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        .name {
            font-size: 15px;
            font-weight: normal;
            margin: 0;
        <?php if(!empty($RTL)){?> text-align: right; /* RTL confidation*/
        <?php }?>
        }

        /* RTL confidation*/
        #details tr td {
        <?php if(!empty($RTL)){?> text-align: right; /* RTL confidation*/
        <?php }?>
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 1.5em;
            line-height: 1em;
            font-weight: normal;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-top: 10px;
        <?php if(!empty($RTL)){?> text-align: right; /* RTL confidation*/
        <?php }?>
        }

        table.items th {
            white-space: nowrap;
            font-weight: normal;
            background: #1F4E79;
            color: #fff;
            padding: .5rem .5rem;
        <?php if(!empty($RTL)){?> text-align: right; /* RTL confidation*/
        <?php }?>
        }

        table.items td {
            padding: .5rem .5rem;
            border: 1px solid #c6cad5;
            border-top: 0px;
            border-right: 0px;
        <?php if(!empty($RTL)){?> text-align: right; /* RTL confidation*/
        <?php }?>
        }

        table.items td:last-child {
            border-right: 1px solid #c6cad5;
        <?php if(!empty($RTL)){?> text-align: right; /* RTL confidation*/
        <?php }?>
        }
        .label{
          color: white;
          padding: 1px;
        }
        .label-success{
          background-color:  #27c24c;

        }
    </style>
</head>

<body>
<table id="details" class="clearfix">
    <!--<tr>-->
      <td style="width: 40%;float: right">
        <div>
        <h5 class=""><?php echo $info->razon_social; ?>
        <br>RUC : <?php echo $info->ruc; ?>
        <br><?php echo $info->direccion_legal; ?></h5>
        </div>
      </td>  
      <td style="width: 40%;float: right"></td>  
      <td style="width: 40%;float: right">
        <div id="logo"></div>
      </td>  
</table>
<table id="details" class="clearfix">
    <!--<tr>-->
      <td style="width: 40%;float: right"></td>  
      <td style="width: 40%;float: right">
      <center><h2 id="tit_cot">OT N° </strong>0000<?php echo $info->cotizacion_ot_id; ?> - <?php echo date(strftime("Y",$info->fecha));?> </h2></center>
      </td>  
      <td style="width: 40%;float: right"></td>  
</table>
  <table id="details" class="clearfix">
    <!--<tr>-->
      <td style="width: 40%;float: right">
        <div>
          <strong style="font-size: 18px"><?= "Inf de Orden de Trabajo"?></strong>
          <div class="name"><strong><?= "Servicio " ?>: </strong><?= $info->service; ?></div>
          <div class="name"><strong><?= lang('Estado ') ?> : </strong><?= lang($info->accion); ?></div>
          <div class="name"><strong><?= "Fecha Inicio"?> : </strong><?= strftime(config_item('date_format'), strtotime($info->start_date))?></div>
          <div class="name"><strong><?= "Fecha Final"?> : </strong><?= strftime(config_item('date_format'), strtotime($info->end_date))?></div>
          <div class="name"><strong><?= "Observación"?> : </strong><?= $info->comment?></div>
          <br>
          
        </div>
        
        
      

      <div>
          <strong style="font-size: 18px"><?= "Informacion de la Sede" //lang('client_info') ?> </strong>
          <div class="name"><strong>Dirección :</strong><?php echo $info->direccion; ?></div>
          <div class="name"><strong><?= lang('Correo ') ?> : </strong><?= lang($info->correo); ?></div>
          <div class="name"><strong><?= "Celular "?> : </strong><?= $info->celular?></div>

      </div>
      
    <br>
        
       
       
        <div class="name"><strong><?= lang('DETALLES DE COTIZACIÓN'); ?> : </strong></div>
        <div class="name"><strong><?= "N° " ?>: </strong> COT - 000000000<?= $info->cotizacion_id; ?></div>
        <div class="name"><strong><?= "Fecha de Vigencia" ?>: </strong><?= strftime(config_item('date_format'), strtotime($info->fecha_vigencia))?></div>
        <!--<div class="name"><strong><?= lang('Monto de Adelanto') ?> : </strong><?="S/.". display_money($info->monto) ?></div>-->

      </td>
      <?php
      $paid_expense = 0;
      foreach ($all_expense_info as $v_expenses) {
        if ($v_expenses->invoices_id != 0) {
          $paid_expense += $this->invoice_model->calculate_to('paid_amount', $v_expenses->invoices_id);
        }
      }

      ?>
      
      <td style="width: 30%;float: right">
      
        <div>
          <strong style="font-size: 18px"><?= "Informacion del Cliente" //lang('client_info') ?> </strong>
          <div class="name"><strong>Razon Social :</strong><br><?php echo $info->razon_social; ?></div>
          <div class="name"><strong>RUC :</strong><br><?php echo $info->ruc; ?></div>
          <div class="name"><strong>Direccion Legal :</strong><br><?php echo $info->direccion_legal; ?></div>
          <div class="name"><strong>Correo :</strong><br><?php echo $info->cor_cl; ?></div>
          <div class="name"><strong>Celular :</strong><br><?php echo $info->cel_cl; ?></div>
          </div>
        </div>
        <!--
        <div>
          <strong style="font-size: 18px"><?= "Detalles de Pagos" //lang('client_info') ?> </strong>                    
          <?php
          
          foreach ($pagos as $pago) {
           
          
          ?>   
          <div class="name"><strong>Descricion :</strong><?php echo $pago->descripcion; ?></div>
          <div class="name"><strong>Porcentaje :</strong><?php echo $pago->porcentaje; ?> % </div>
          <?php      
              
            }
          ?>    
          
         
          </div>-->
        

        </div>

        <br />
        <div class="name"><strong><?= lang('Monto') . ' ' . lang('Total') ?>
            :</strong><?="S/.". display_money($info->monto_tot_cot); ?></div>
        

    <!--</tr>-->
  </table>
  <br />
  <br />

  <table class="items" width="100%">
    <thead>
    <tr>
        <th><?= "Descricpcion"?></th>
        <th><?= "Porcentaje %" ?></th>
        <th><?= "Monto" ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
      foreach ($pagos as $pago) {
    ?>
        <tr>
            <td><?= $pago->descripcion; ?></td>
            <td style="text-align: center"><?= $pago->porcentaje ?> % </td>
            <td style="text-align: center">S/. <?= display_money($pago->monto_upload); ?></td>
            
        </tr>
    <?php }
    ?>
    <tr>
            <td></td>
            
            <th style="text-align: center"><?= "Total Pago" ?> </th>
            
            <td style="text-align: center">S/. <?= display_money($info->monto_tot_cot);?></td>
            
        </tr>
    </tbody>
</table>    

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<table class="" width="100%">
    <thead>
    <tr>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    
        <tr>
            <td id="img_banco">BBVA</td>
            <td style="text-align: center">
            Número de Cuenta
            Soles: 0011 0138 0100054206
            CCI: 011 138 000 100054206 56</td>
            <td style="text-align: center"></td>
        <tr>
    </tbody>
</table>    
         
<table class="" width="100%">
    <thead>
    <tr>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    
        <tr>
        <td style="text-align: center" >(01) 71154328 / 949618736</td>
            <td style="text-align: center">
           ventas@solugrifos.com</td>
            <td style="text-align: center">
            www.solugrifos.com
            </td>
        <tr>
    </tbody>
</table>     


</body>

</html>
<!doctype html>
<html>
    <head>
    <style>
    .contenedor{
        position:absolute;
    }
    .pdf{
        position:relative;
    }
    .bloqueo{
        position:relative;
        background-color:rgba(255,255,255,0.00);
       /* border:1px solid red;*/
        width:1024px!important;
        height:208px!important;
    }
    </style>
    </head>
    <body>
<div class="contenedor">
 
    <div class="pdf">
        <?php

	$link=$_GET['key'];
    $url= base64_decode($link);
    echo '<embed   src="https://plataforma-csscretivos.ml/control-verde/'.$url.'" width=100% height=250 type="application/PDF"></embed  >';
?>
        <!--<object data="recibos.pdf" type="application/PDF" width="850px" height="850px" align="right"></object>-->
        </div>
        
        <div class="bloqueo">
        </div>
</div>
    </body>
</html>



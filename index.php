<?php
//COMPRIMIR ARCHIVOS DE TEXTO...
(substr_count($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) ? ob_start("ob_gzhandler") : ob_start();
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$urlInitProject = $actual_link . '/erpsolugrifos/';
$urlPlanVerde =  $actual_link . '/planverde/';
$urlErp =  $actual_link . '/erp/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8; X-Content-Type-Options=nosniff"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=6.0, minimum-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
    <title>Home | SOLUGRIFOS</title>
    <meta name="keywords" content="erpsolugrifos, Estaciones de Servicio, SOLUGRIFOS, erp solugrifos"/>
    <meta name="description" content="¡Expertos en Estaciones de Servicio!"/>
    <meta name="theme-color" content="#3A3F51"/>
    <meta name="author" content="R@np-2021"/>
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <meta name="twitter.card" content="summary"/>
    <meta property="og:locale" content="es_ES"/>
    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="Home | SOLUGRIFOS"/>
    <meta property="og:url" name="twitter.url" content="./planverde/login"/>
    <meta property="og:title" name="twitter.title" content="Home | SOLUGRIFOS"/>
    <meta property="og:description" name="twitter.description" content="Solugrifos S.A.C. es una consultora ambiental registrada ante el SENACE."/>
    <meta property="og:image" name="twitter.image" content="<?= $urlInitProject;?>erpsolugrifos-isotipo-icon.png"/>
    <link rel="icon" type="image/x-icon" href="<?= $urlInitProject;?>erpsolugrifos-isotipo-icon.png"/>
    <link rel="apple-touch-icon" href="<?= $urlInitProject;?>erpsolugrifos-isotipo-icon.png"/>
    <link rel="canonical" href="./planverde/login"/>
    <link rel="icon" href="<?= $urlInitProject;?>erpsolugrifos-isotipo-icon.png" type="image/ico"/>
    <link rel="icon" href="<?= $urlInitProject;?>erpsolugrifos-isotipo-icon.png" type="image/jpg">
    <link rel="stylesheet" href="css/solugrifos.css">
</head>
<body>
  <div class="c_mainPgSelModule">
    <img class="c_mainPgSelModule--imgbck img-fluid" src="./img/solugrifos-background-imagen-1.jpg" alt="background-img__erpsolugrifos" width="100" height="100" decoding="sync">
    <div class="c_mainPgSelModule__c">
      <div class="c_mainPgSelModule__c--c">
        <div class="c_mainPgSelModule__c--c--cSel">
          <div class="c_mainPgSelModule__c--c--cSel--cLogo">
            <a class="c_mainPgSelModule__c--c--cSel--cLogo--link" href="./" title="ERPSOLUGRIFOS">
              <img class="img-fluid" src="./img/logotipo-imagen-1.png" alt="logo-img__erpsolugrifos" width="100" height="100" decoding="sync">
            </a>
          </div>
          <div class="c_mainPgSelModule__c--c--cSel--cGroupBtns">
            <a class="c_mainPgSelModule__c--c--cSel--cGroupBtns__link" href="./planverde/" title="Ir a Plan Verde">
              <span class="c_mainPgSelModule__c--c--cSel--cGroupBtns__link--cTxt">PLAN VERDE</span>
              <span class="c_mainPgSelModule__c--c--cSel--cGroupBtns__link--cIcon">
                <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" version="1.1" viewBox="0 0 700 700"><path d="m126.94 19.461 465.23 160c15.496 5.4062 23.785 21.98 18.379 37.477-2.8828 8.6484-9.3711 14.773-16.938 17.656l-205.41 83.605-83.605 205.41c-6.125 15.137-23.062 22.344-38.199 16.578-8.2891-3.6055-14.055-10.09-16.938-18.02l-160-465.23c-5.4062-15.496 2.8828-32.07 18.379-37.477 6.4883-2.1641 12.973-2.1641 19.098 0z"/></svg>
              </span>
            </a>
            <a class="c_mainPgSelModule__c--c--cSel--cGroupBtns__link" href="./erp/" title="Ir a ERP">
              <span class="c_mainPgSelModule__c--c--cSel--cGroupBtns__link--cTxt">ERP</span>
              <span class="c_mainPgSelModule__c--c--cSel--cGroupBtns__link--cIcon">
                <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" version="1.1" viewBox="0 0 700 700"><path d="m126.94 19.461 465.23 160c15.496 5.4062 23.785 21.98 18.379 37.477-2.8828 8.6484-9.3711 14.773-16.938 17.656l-205.41 83.605-83.605 205.41c-6.125 15.137-23.062 22.344-38.199 16.578-8.2891-3.6055-14.055-10.09-16.938-18.02l-160-465.23c-5.4062-15.496 2.8828-32.07 18.379-37.477 6.4883-2.1641 12.973-2.1641 19.098 0z"/></svg>
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>  
</html>
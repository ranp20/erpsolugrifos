<?php
//COMPRIMIR ARCHIVOS DE TEXTO...
(substr_count($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) ? ob_start("ob_gzhandler") : ob_start();

function getExtension($file, $tolower=true){
    $file = basename($file);
    $pos = strrpos($file, '.');
    if($file == '' || $pos === false){
        return '';
    }
    $extension = substr($file, $pos+1);
    if($tolower){
        $extension = strtolower($extension);
    }
    return $extension;
}
/* VARIABLES DE AJUSTES GLOBALES */
$admsett_icon_favicon = "";
$admsett_login_background = "";
$admsett_company_logo = "";
$admsett_login_position = "";
$admsett_login_background_reverse = "";
$admsett_snetwork_facebook_url = "";
$admsett_snetwork_instagram_url = "";
$admsett_snetwork_linkedin_url = "";

$admsett_icon_favicon = (config_item('favicon') != "") ?  base_url() . config_item('favicon') :  "http://placehold.it/16x16";
$admsett_login_background = (config_item('login_background') != "") ?  base_url() . config_item('login_background') :  base_url() . "uploads/p3.jpg";
$admsett_company_logo = (config_item('company_logo') != "") ?  base_url() . config_item('company_logo') :  base_url() . "assets/img/plan-verde.jpg";
$admsett_login_position = (config_item('login_position') != "") ?  config_item('login_position') :  "center";
$admsett_login_background_reverse = (config_item('login_background-reverse') != "") ?  config_item('login_background-reverse') :  "";

$admsett_snetwork_facebook_url = (config_item('socialnetwork_facebook_url') != "") ?  config_item('socialnetwork_facebook_url') :  "";
$admsett_snetwork_instagram_url = (config_item('socialnetwork_instagram_url') != "") ?  config_item('socialnetwork_instagram_url') :  "";
$admsett_snetwork_linkedin_url = (config_item('socialnetwork_linkedin_url') != "") ?  config_item('socialnetwork_linkedin_url') :  "";
/*
$urlPlanVerdeUploads = $actual_link . "/erpsolugrifos";
*/
$urlPlanVerdeUploads = $actual_link . "/erpsolugrifos/planverde/uploads";
$urlInitProject = $actual_link . '/erpsolugrifos/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=6.0, minimum-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
    <title><?php echo $title; ?></title>
    <meta name="keywords" content="erpsolugrifos, plan verde, planverde, solugrifos"/>
    <meta name="description" content="Solugrifos S.A.C. es una consultora ambiental registrada ante el SENACE."/>
    <meta name="theme-color" content="#9DC140"/>
    <meta name="author" content="R@np-2021"/>
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <meta name="twitter.card" content="summary"/>
    <meta property="og:locale" content="es_ES"/>
    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="SOLUGRIFOS | Planverde"/>
    <meta property="og:url" name="twitter.url" content="https://www.erpsolugrifos.com/planverde/login"/>
    <meta property="og:title" name="twitter.title" content="SOLUGRIFOS | Planverde"/>
    <meta property="og:description" name="twitter.description" content="Solugrifos S.A.C. es una consultora ambiental registrada ante el SENACE."/>
    <meta property="og:image" name="twitter.image" content="<?= $admsett_icon_favicon; ?>"/>
    <link rel="icon" type="image/x-icon" href="<?= $admsett_icon_favicon; ?>"/>
    <link rel="apple-touch-icon" href="<?= $admsett_icon_favicon; ?>"/>
    <link rel="canonical" href="https://www.erpsolugrifos.com/planverde/login"/>
    <link rel="icon" href="<?= $admsett_icon_favicon; ?>" type="image/ico"/>
    <link rel="icon" href="<?= $admsett_icon_favicon; ?>" type="image/jpg">
    <!-- PRELOADER FILES -->
    <link rel="preload" href="<?php echo base_url(); ?>assets/css/styles.min.css" as="style"/>
    <link rel="preload" href="<?php echo base_url(); ?>assets/plugins/jquery/dist/jquery.min.js" as="script"/>
    <!-- STYLES CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/toastr.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.min.css">
    <!-- JQUERY-->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo $urlInitProject;?>node_modules/lazysizes/lazysizes.min.js" async=""></script>
    <!-- GOOGLE FONTS -->
<!--     
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;1,400&display=swap" rel="stylesheet">
    -->
</head>
<?php
    if (!empty($admsett_login_position) && $admsett_login_position != ''){
        if (!empty($admsett_login_background)){
            $body_style = $admsett_login_background;
        } else{
            $body_style = '';
        }
    } else{
        $body_style = '';
    }
    $type = $this->session->userdata('c_message');
?>

<body id="bdyPLV_log-in">
    <div class="c_mainPgLog-inPLV">
        <?php if(getExtension($admsett_login_background) == "mp4" || getExtension($admsett_login_background) == "3gp"){ ?>      
            <video id="myvideoBgckLogin" autoplay muted loop class="c_mainPgLog-inPLV--videogbck"><source src="<?= $body_style;?>" type="video/mp4"></video>
            <!--<iframe class="c_mainPgLog-inPLV--videogbck" width="560" height="315" src="https://www.youtube.com/embed/Y4wGNyxW3ug?autoplay=1&loop=1&mute=1&controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="outline: none;position: absolute;z-index: 99;width: 100%;height: 100%;top: 0;left: 0;aspect-ratio: 16/9;"></iframe>-->
        <?php }else if(getExtension($admsett_login_background) == "jpg" || getExtension($admsett_login_background) == "png"){ ?>
            <img class="c_mainPgLog-inPLV--imgbck img-fluid <?= $admsett_login_background_reverse;?> lazyload" src="<?= $body_style;?>" alt="background-img__planverde" width="100" height="100" decoding="sync">
        <?php }else{ ?>
            <img class="c_mainPgLog-inPLV--imgbck img-fluid <?= $admsett_login_background_reverse;?> lazyload" src="<?= $body_style;?>" alt="background-img__planverde" width="100" height="100" decoding="sync">
        <?php } ?>
        <div class="c_mainPgLog-inPLV__c">
            <?php
                if(!empty($admsett_login_position) && $admsett_login_position == 'left'){
                    $lcol = 'col-lg-4 col-sm-6 left-login posit-left';
                }else if(!empty($admsett_login_position) && $admsett_login_position == 'right'){
                    $lcol = 'col-lg-4 col-sm-6 left-login pull-right posit-right';
                }else{
                    $lcol = 'login-center posit-center';
                } 
            ?>
            <div class="c_mainPgLog-inPLV__c--cLogo">
                <a data-toggle="tooltip" data-placement="bottom" title="SOLUGRIFOS" data-original-title="SOLUGRIFOS" class="red-tooltip" href="https://www.erpsolugrifos.com/">
                    <img src="<?= $urlPlanVerdeUploads;?>/large-solugrifos.png" alt="background_login_planverde" class="img-fluid lazyload" width="100" height="100" decoding="sync">
                </a>
            </div>
            <div class="c_mainPgLog-inPLV__c--c">
                <div class="c_mainPgLog-inPLV__c--c--cSel block-center <?= $lcol;?>">
                    <div class="c_mainPgLog-inPLV__c--c--cSel--cLogo text-center">
                        <a class="c_mainPgLog-inPLV__c--c--cSel--cLogo--link" href="https://www.erpsolugrifos.com/planverde" title="PLAN VERDE">
                            <img src="<?= $admsett_company_logo;?>" class="m-r-sm img-fluid lazyload" alt="logo-img__planverde" width="100" height="100" decoding="sync">
                        </a>
                    </div>
                    <?= message_box('success'); ?>
                    <?= message_box('error'); ?>
                    <div class="error_login">
                        <?php
                        $validation_errors = validation_errors();
                        if (!empty($validation_errors)){ ?>
                            <div class="alert alert-danger"><?php echo $validation_errors; ?></div>
                        <?php
                        }
                        $error = $this->session->flashdata('error');
                        $success = $this->session->flashdata('success');
                        if (!empty($error)){
                        ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php } ?>
                        <?php if (!empty($success)){ ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php } ?>
                    </div>
                    <?php if (!empty($type)){
                    ?>
                        <script>
                            $(document).ready(function(){
                                toastr.success('<?= $type; ?>');
                            });
                        </script>
                    <?php
                        $this->session->unset_userdata('c_message');
                    } ?>
                    <div class="c_mainPgSelModule__c--c--cSel--cFrm">
                        <?= $subview; ?>
                    </div>
                    <div class="c_mainPgSelModule__c--c--cSel--cSMedia">
                        <p>Visítanos en: </p>
                        <div class="c_mainPgSelModule__c--c--cSel--cSMedia__c">
                            <div class="c_mainPgSelModule__c--c--cSel--cSMedia__c--cIcons">
                                <a data-toggle="tooltip" data-placement="top" title="Facebook" data-original-title="Facebook" class="red-tooltip" href="<?= $admsett_snetwork_facebook_url;?>" aria-label="Facebook" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-label="Facebook" role="img" viewBox="0 0 512 512"><rect width="512" height="512" rx="15%" fill="#1877f2"/><path d="M355.6 330l11.4-74h-71v-48c0-20.2 9.9-40 41.7-40H370v-63s-29.3-5-57.3-5c-58.5 0-96.7 35.4-96.7 99.6V256h-65v74h65v182h80V330h59.6z" fill="#ffffff"/></svg>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="Instagram" data-original-title="Instagram" class="red-tooltip" href="<?= $admsett_snetwork_instagram_url;?>" aria-label="Instagram" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-label="Instagram" role="img" viewBox="0 0 512 512"><rect width="512" height="512" rx="20%" id="b"/><use fill="url(#a)" xlink:href="#b"/><use fill="url(#c)" xlink:href="#b"/><radialGradient id="a" cx=".4" cy="1" r="1"><stop offset=".1" stop-color="#fd5"/><stop offset=".5" stop-color="#ff543e"/><stop offset="1" stop-color="#c837ab"/></radialGradient><linearGradient id="c" x2=".2" y2="1"><stop offset=".1" stop-color="#3771c8"/><stop offset=".5" stop-color="#60f" stop-opacity="0"/></linearGradient><g fill="none" stroke="#ffffff" stroke-width="30"><rect width="308" height="308" x="102" y="102" rx="81"/><circle cx="256" cy="256" r="72"/><circle cx="347" cy="165" r="6"/></g></svg>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="Linkedin" data-original-title="Linkedin" class="red-tooltip" href="<?= $admsett_snetwork_linkedin_url;?>" aria-label="Linkedin" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35px" height="35px" viewBox="0 0 24 24" style="border-radius: 4px;">
                                    <path fill="#0077B5" fill-rule="evenodd" d="M20.45175,20.45025 L16.89225,20.45025 L16.89225,14.88075 C16.89225,13.5525 16.86975,11.844 15.04275,11.844 C13.191,11.844 12.90825,13.2915 12.90825,14.7855 L12.90825,20.45025 L9.3525,20.45025 L9.3525,8.997 L12.765,8.997 L12.765,10.563 L12.81375,10.563 C13.2885,9.66225 14.4495,8.71275 16.18125,8.71275 C19.78575,8.71275 20.45175,11.08425 20.45175,14.169 L20.45175,20.45025 Z M5.33925,7.4325 C4.1955,7.4325 3.27375,6.50775 3.27375,5.36775 C3.27375,4.2285 4.1955,3.30375 5.33925,3.30375 C6.47775,3.30375 7.4025,4.2285 7.4025,5.36775 C7.4025,6.50775 6.47775,7.4325 5.33925,7.4325 L5.33925,7.4325 Z M7.11975,20.45025 L3.5565,20.45025 L3.5565,8.997 L7.11975,8.997 L7.11975,20.45025 Z M23.00025,0 L1.0005,0 C0.44775,0 0,0.44775 0,0.99975 L0,22.9995 C0,23.55225 0.44775,24 1.0005,24 L23.00025,24 C23.55225,24 24,23.55225 24,22.9995 L24,0.99975 C24,0.44775 23.55225,0 23.00025,0 L23.00025,0 Z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="c_mainPgSelModule__c--c--cSel--cInfVersion text-center">
                        <small>
                            <span>&copy;</span>
                            <span>2021 - <?= date('Y');?></span>
                            <span>-</span>
                            <span>v1.0</span>
                        </small>
                    </div>
                </div>
            </div>
            <?php
                if (!empty($admsett_login_position) && $admsett_login_position == 'left'){
                $col = 'col-lg-8 col-sm-6';
                if (!empty($back_img)){
                    $leftstyle = 'style="background: url(' . $back_img . ') no-repeat center center fixed;
                     -webkit-background-size: cover;
                     -moz-background-size: cover;
                     -o-background-size: cover;
                     background-size: cover;min-height: 100%;"';
                    } else{
                        $leftstyle = '';
                    }
                } else if (!empty($admsett_login_position) && $admsett_login_position == 'right'){
                    $col = 'col-lg-8 col-sm-6 left-login pull-right';
                    if (!empty($back_img)){
                        $leftstyle = 'style="background: url(' . $back_img . ') no-repeat center center fixed;
                         -webkit-background-size: cover;
                         -moz-background-size: cover;
                         -o-background-size: cover;
                         background-size: cover;min-height: 100%;"';
                    } else{
                        $leftstyle = '';
                    }
                } else{
                    $col = '';
                    $leftstyle = '';
                }
            ?>
            <div class="<?= $col ?> hidden-xs" <?= $leftstyle ?>></div>
        </div>
    </div>
    <footer id="footPLV_log-in">
        <div class="FLog-in__c">
            <div class="FLog-in__c--cListBrands">
                <ul class="FLog-in__c--cListBrands--m">
                    <li class="FLog-in__c--cListBrands--m--i">
                        <a data-toggle="tooltip" data-placement="top" title="PRIMAX" data-original-title="PRIMAX" href="https://primax.com.pe/" class="FLog-in__c--cListBrands--m--link" target="_blank">
                            <img src="<?php echo base_url();?>uploads/marcas/logo_primax.png" class="img-fluid lazyload" alt="logo_primax" width="100" height="100" decoding="sync">
                        </a>
                    </li>
                    <li class="FLog-in__c--cListBrands--m--i">
                        <a data-toggle="tooltip" data-placement="top" title="TERPEL" data-original-title="TERPEL" href="https://www.terpel.com/" class="FLog-in__c--cListBrands--m--link" target="_blank">
                            <img src="<?php echo base_url();?>uploads/marcas/logo_terpel.png" class="img-fluid lazyload" alt="logo_terpel" width="100" height="100" decoding="sync">
                        </a>
                    </li>
                    <li class="FLog-in__c--cListBrands--m--i">
                        <a data-toggle="tooltip" data-placement="top" title="LLAMAGAS" data-original-title="LLAMAGAS" href="https://www.llamagas.com.pe/" class="FLog-in__c--cListBrands--m--link" target="_blank">
                            <img src="<?php echo base_url();?>uploads/marcas/logo_llamagas.png" class="img-fluid lazyload" alt="logo_llamagas" width="100" height="100" decoding="sync">
                        </a>
                    </li>
                    <li class="FLog-in__c--cListBrands--m--i">
                        <a data-toggle="tooltip" data-placement="top" title="PATROAMERICA" data-original-title="PATROAMERICA" href="https://www.petroamerica.com.pe/" class="FLog-in__c--cListBrands--m--link" target="_blank">
                            <img src="<?php echo base_url();?>uploads/marcas/logo_petroamerica.png" class="img-fluid lazyload" alt="logo_petroamerica" width="100" height="100" decoding="sync">
                        </a>
                    </li>
                    <li class="FLog-in__c--cListBrands--m--i">
                        <a data-toggle="tooltip" data-placement="top" title="PYX" data-original-title="PYX" href="https://pyx.pe/" class="FLog-in__c--cListBrands--m--link" target="_blank">
                            <img src="<?php echo base_url();?>uploads/marcas/logo_pyx.png" class="img-fluid lazyload" alt="logo_pyx" width="100" height="100" decoding="sync">
                        </a>
                    </li>
                    <li class="FLog-in__c--cListBrands--m--i">
                        <a data-toggle="tooltip" data-placement="top" title="PETROSUR" data-original-title="PETROSUR" href="https://www.petrosur.com.py/" class="FLog-in__c--cListBrands--m--link" target="_blank">
                            <img src="<?php echo base_url();?>uploads/marcas/logo_petrosur.png" class="img-fluid lazyload" alt="logo_petrosur" width="100" height="100" decoding="sync">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/toastr.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/dist/js/bootstrap-4.0.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/plv-login.js"></script>
</body>
</html>
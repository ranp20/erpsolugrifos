<?php
//COMPRIMIR ARCHIVOS DE TEXTO...
(substr_count($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) ? ob_start("ob_gzhandler") : ob_start();
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
    <meta name="keywords" content="erpsolugrifos, erp, erp solugrifos, solugrifos erp"/>
    <meta name="description" content="Solugrifos S.A.C. es una consultora ambiental registrada ante el SENACE."/>
    <meta name="theme-color" content="#9DC140"/>
    <meta name="author" content="R@np-2021"/>
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <meta name="twitter.card" content="summary"/>
    <meta property="og:locale" content="es_ES"/>
    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="SOLUGRIFOS | ERP"/>
    <meta property="og:url" name="twitter.url" content="https://www.erpsolugrifos.com/erp/"/>
    <meta property="og:title" name="twitter.title" content="SOLUGRIFOS | ERP"/>
    <meta property="og:description" name="twitter.description" content="Solugrifos S.A.C. es una consultora ambiental registrada ante el SENACE."/>
    <meta property="og:image" name="twitter.image" content="<?php echo base_url('assets/img/plan-verde.jpg'); ?>"/>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/img/plan-verde.jpg'); ?>"/>
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/img/plan-verde.jpg'); ?>"/>
    <link rel="canonical" href="https://www.erpsolugrifos.com/erp/"/>
    <link rel="icon" href="<?php echo base_url('assets/img/plan-verde.jpg'); ?>" type="image/ico"/>
    <link rel="icon" href="<?php echo base_url('assets/img/plan-verde.jpg'); ?>" type="image/jpg">
    <!-- STYLES CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/toastr.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" id="bscss">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" id="maincss">
    <!-- JQUERY-->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery/dist/jquery.min.js"></script>
</head>
<?php
 if(!empty($login_background[0]) && $login_background[0] == 'image'){
    $login_background = config_item('login_background');
    if(!empty($login_background)){
        $back_img = base_url() . '/' . config_item('login_background');
    }
} 
$back_img = base_url() . '/' . config_item('login_background');
?>
<style>
    body {
        background-color: #ffffff;
    }

    .left-login {
        height: auto;
        min-height: 100%;
        background: #fff;
        -webkit-box-shadow: 2px 0px 7px 1px rgba(0, 0, 0, 0.08);
        -moz-box-shadow: 2px 0px 7px 1px rgba(0, 0, 0, 0.08);
        box-shadow: 2px 0px 7px 1px rgba(0, 0, 0, 0.08);
    }

    .left-login-panel {
        -webkit-box-shadow: 0px 0px 28px -9px rgba(0, 0, 0, 0.74);
        -moz-box-shadow: 0px 0px 28px -9px rgba(0, 0, 0, 0.74);
        box-shadow: 0px 0px 28px -9px rgba(0, 0, 0, 0.74);
    }

    .apply_jobs {
        position: absolute;
        z-index: 1;
        right: 0;
        top: 0
    }

    .login-center {
        width: 400px;
        margin: 0 auto;
    }
    .error_login{
        background-color: #fff;
    }
    .p-lg{
        background-color: #fff;
    }
    @media only screen and (max-width: 380px) {
        .login-center {
            width: 320px;
            padding: 10px;
        }

        .wd-xl {
            width: 260px;
        }
    }
</style>
<?php
$login_position = 'center';
if (!empty($login_position) && $login_position == 'center') {
    if (!empty($back_img)) {
        $body_style = 'style="background: url(' . $back_img . ') no-repeat center center fixed;
 -webkit-background-size: cover;
 -moz-background-size: cover;
 -o-background-size: cover;
 background-size: cover;min-height: 100%;width:100%"';
    } else {
        $body_style = '';
    }
} else {
    $body_style = '';
}
$type = $this->session->userdata('c_message');
?>
<body <?= $body_style ?>>
<?php if (!empty($login_position) && $login_position == 'left') {
    $lcol = 'col-lg-4 col-sm-6 left-login';
} else if (!empty($login_position) && $login_position == 'right') {
    $lcol = 'col-lg-4 col-sm-6 left-login pull-right';
} else {
    $lcol = 'login-center';
} ?>
<div class="<?= $lcol ?>">
    <div class="wrapper" style="margin: 0 0 0 0">
        <div class="block-center mt-xl wd-xl">
            <div class="text-center" style="margin-bottom: 20px">
                <a data-toggle="tooltip" data-placement="bottom" title="SOLUGRIFOS" data-original-title="SOLUGRIFOS" class="red-tooltip" href="https://www.erpsolugrifos.com/" style="display: table;">
                    <img style="width: 80%;height: auto;" src="<?= base_url() . config_item('company_logo') ?>" alt="logo_solugrifos" class="m-r-sm" width="100" height="100" decoding="sync">
                </a>
            </div>
            <?= message_box('success'); ?>
            <?= message_box('error'); ?>
            <div class="error_login">
                <?php
                $validation_errors = validation_errors();
                if (!empty($validation_errors)) { ?>
                    <div class="alert alert-danger"><?php echo $validation_errors; ?></div>
                    <?php
                }
                $error = $this->session->flashdata('error');
                $success = $this->session->flashdata('success');
                if (!empty($error)) {
                    ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>
                <?php if (!empty($success)) { ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php } ?>
            </div>
            <div class="panel panel-dark panel-flat left-login-panel">
                <div class="panel-heading text-center">
                    <a href="#" style="color: #ffffff">
                        <span style="font-size: 15px;"><?php echo config_item('website_name'); ?>
                    </a>
                </div>
                <?php if (!empty($type)) {
                    ?>
                    <script>
                        $(document).ready(function () {
                            // show when page load
                            toastr.success('<?= lang($type)?>');
                        });
                    </script>
                    <?php
                    $this->session->unset_userdata('c_message');
                } ?>
                <div class="panel-body">
                    <?= $subview; ?>
                </div>
            </div>
            <div class="p-lg text-center">
                <small>
                    <span>&copy;</span>
                    <span>2021-<?= date('Y') ?></span>
                    <span>-</span>
                    <span>v1.0</span>
                </small>
            </div>
        </div>
    </div>
</div>
<?php
if (!empty($login_position) && $login_position == 'left') {
    $col = 'col-lg-8 col-sm-6';
    if (!empty($back_img)) {
        $leftstyle = 'style="background: url(' . $back_img . ') no-repeat center center fixed;
 -webkit-background-size: cover;
 -moz-background-size: cover;
 -o-background-size: cover;
 background-size: cover;min-height: 100%;"';
    } else {
        $leftstyle = '';
    }
} else if (!empty($login_position) && $login_position == 'right') {
    $col = 'col-lg-8 col-sm-6 left-login pull-right';
    if (!empty($back_img)) {
        $leftstyle = 'style="background: url(' . $back_img . ') no-repeat center center fixed;
 -webkit-background-size: cover;
 -moz-background-size: cover;
 -o-background-size: cover;
 background-size: cover;min-height: 100%;"';
    } else {
        $leftstyle = '';
    }
} else {
    $col = '';
    $leftstyle = '';
}
?>
<div class="<?= $col ?> hidden-xs" <?= $leftstyle ?>></div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/toastr.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- STORAGE API -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/parsleyjs/parsley.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("a").tooltip();
    });
</script>
</body>
</html>
<?php
//COMPRIMIR ARCHIVOS DE TEXTO...
// (substr_count($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) ? ob_start("ob_gzhandler") : ob_start();
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

$admsett_icon_favicon = (config_item('favicon') != "") ?  base_url() . config_item('favicon') :  "http://placehold.it/16x16";
$admsett_icon_favicon_ext = getExtension($admsett_icon_favicon);

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
/*
$url = $actual_link . "/";
*/
$url = $actual_link . "/erpsolugrifos/";
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
    <meta name="description" content="attendance, client management, finance, freelance, freelancer, goal tracking, Income Managment, lead management, payroll, project management, project manager, support ticket, task management, timecard">
    <meta name="keywords" content="	attendance, client management, finance, freelance, freelancer, goal tracking, Income Managment, lead management, payroll, project management, project manager, support ticket, task management, timecard">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="<?= $admsett_icon_favicon;?>" type="image/<?= $admsett_icon_favicon_ext;?>">
    <!-- STYLES CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/simple-line-icons/css/simple-line-icons.css">
    <!-- <link rel="stylesheet" href="<?php //echo base_url(); ?>assets/plugins/animate.css/animate.min.css"> -->
    <!-- APP SETTINGS STYLES -->
    <?php
    $custom_color = config_item('active_custom_color');
    if (!empty($custom_color) && $custom_color == 1) {
        include_once 'assets/css/bg-custom.php';
    } else {
        ?>
        <link id="autoloaded-stylesheet" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?= config_item('sidebar_theme') ?>.css">
    <?php }
    ?>
    <!-- STYLES CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2-bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/timepicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/dataTables.colVis.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/summernote/summernote.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-slider/bootstrap-slider.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/morris/morris.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/chat/chat.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- PRELOADER -->
    <link rel="preload" href="<?php echo base_url(); ?>assets/js/jquery/jquery-3.6.4.min.js" as="script"/>
    <link rel="preload" href="<?php echo base_url();?>assets/js/bootstrap/bootstrap-3.3.5/css/bootstrap.min.css" as="style"/>
    <link rel="preload" href="<?php echo base_url();?>assets/js/bootstrap/bootstrap-3.3.5/js/bootstrap.min.js" as="script"/>
    <link rel="preload" href="<?php echo base_url() ?>assets/css/styles.min.css" as="style">
    <!-- JS FILES -->
    <!-- <script type="text/javascript" src="<?php //echo base_url() ?>assets/js/jquery.min.js"></script> -->
    <!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/jquery/jquery-1.9.1.min.js"></script> -->
    <!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/jquery/jquery-3.5.1.min.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery/jquery-3.6.4.min.js"></script>
    <!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/jquery/jquery-3.7.1.min.js"></script> -->
    <!-- JQUERY-UI -->
    <!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/jquery-ui/jquery-ui-1.12.1.min.js"></script> -->
    <!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/jquery-ui/jquery-ui-1.13.1.min.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url() ?>asset/js/bootstrap-toggle.min.js"></script>
    <!-- MATERIAL ICONS -->
    <link rel="stylesheet" href="<?php echo $url;?>node_modules/@mdi/font/css/materialdesignicons.min.css">
    <?php
    if (empty($unread_notifications)) {
        $unread_notifications = 0;
    }
    ?>
    <script>
        var total_unread_notifications = <?php echo $unread_notifications; ?>,
            autocheck_notifications_timer_id = 0,
            list = null,
            bulk_url = null,
            time_format = <?= (config_item('time_format') == 'H:i' ? 'false' : true)?>,
            ttable = null,
            base_url = "<?php echo base_url(); ?>",
            new_notification = "<?php lang('new_notification'); ?>",
            credit_amount_bigger_then_remaining_credit = "<?= lang('credit_amount_bigger_then_remaining_credit'); ?>",
            credit_amount_bigger_then_invoice_due = "<?= lang('credit_amount_bigger_then_due_amount'); ?>",
            auto_check_for_new_notifications = <?php echo config_item('auto_check_for_new_notifications'); ?>,
            file_upload_instruction = "<?php echo lang('file_upload_instruction_js'); ?>",
            filename_too_long = "<?php echo lang('filename_too_long'); ?>";
        desktop_notifications = "<?php echo config_item('desktop_notifications'); ?>";
        lsetting = "<?php echo lang('settings'); ?>";
        lfull_conversation = "<?php echo lang('full_conversation'); ?>";
        ledit_name = "<?php echo lang('edit') . ' ' . lang('name') ?>";
        ldelete_conversation = "<?php echo lang('delete_conversation') ?>";
        lminimize = "<?php echo lang('minimize') ?>";
        lclose = "<?php echo lang('close') ?>";
        lnew = "<?php echo lang('new') ?>";
        ldelete_confirm = "<?php echo lang('delete_alert') ?>";

    </script>
    <!-- NUEVO CONTENIDO (BOOTSTRAP) -->
    <!-- <link rel="stylesheet" href="<?php //echo $url;?>node_modules/bootstrap/dist/css/bootstrap.min.css"> -->
    <!-- <script type="text/javascript" src="<?php //echo $url;?>node_modules/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/js/bootstrap/bootstrap-3.3.5/css/bootstrap.min.css">
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap/bootstrap-3.3.5/js/bootstrap.min.js"></script>
    <!-- <link rel="stylesheet" href="<?php //echo base_url();?>assets/js/bootstrap/bootstrap-3.3.7/css/bootstrap.min.css"> -->
    <!-- <script type="text/javascript" src="<?php //echo base_url();?>assets/js/bootstrap/bootstrap-3.3.7/js/bootstrap.min.js"></script> -->
    <!-- <link rel="stylesheet" href="<?php //echo base_url();?>assets/js/bootstrap/bootstrap-4.6.2/css/bootstrap.min.css"> -->
    <!-- <script type="text/javascript" src="<?php //echo base_url();?>assets/js/bootstrap/bootstrap-4.6.2/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script type="text/javascript" src="<?php //echo base_url();?>assets/js/bootstrap/bootstrap-4.6.2/js/bootstrap.min.js"></script> -->
    <!-- <link rel="stylesheet" href="<?php //echo base_url();?>assets/js/bootstrap/bootstrap-5.3.2/css/bootstrap.min.css"> -->
    <!-- <script type="text/javascript" src="<?php //echo base_url();?>assets/js/bootstrap/bootstrap-5.3.2/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script type="text/javascript" src="<?php //echo base_url();?>assets/js/bootstrap/bootstrap-5.3.2/js/bootstrap.min.js"></script> -->
    <!-- <script type="text/javascript" src="<?php //echo base_url();?>assets/js/popper/popper-2.9.2.min.js"></script> -->
    <!-- NUEVO CONTENIDO (CUSTOM CSS) -->
    <!-- <link rel="stylesheet" href="<?php //echo base_url() ?>assets/css/custom.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/styles.min.css" rel="stylesheet">
    <!-- NUEVO CONTENIDO (SWEETALERT2) -->
    <!-- <link rel="stylesheet" href="<?php //echo $url;?>node_modules/sweetalert2/dist/sweetalert2.min.css"> -->
    <!-- <script type="text/javascript" src="<?php //echo $url;?>node_modules/sweetalert2/dist/sweetalert2.min.js"></script> -->
    <!-- NUEVO CONTENIDO (CUSTOMS-HOME) -->
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/customs-home.min.js"></script>
</head>
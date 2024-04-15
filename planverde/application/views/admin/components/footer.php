<?php
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
// $url = $actual_link . "/";
$url = $actual_link . "/erpsolugrifos/";
?>
<div class="pusher"></div>
<!-- SCRIPTS -->
<!-- MODERNIZR-->
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/modernizr/modernizr.custom.js"></script>
<!-- BOOTSTRAP-->
<!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/plugins/bootstrap/dist/js/bootstrap-3.3.5.min.js"></script> -->
<!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/plugins/bootstrap/dist/js/bootstrap-4.0.0.min.js"></script> -->
<!-- NUEVO CONTENIDO (SWEETALERT2) -->
<link rel="stylesheet" href="<?php echo $url;?>/node_modules/sweetalert2/dist/sweetalert2.min.css">
<script type="text/javascript" src="<?php echo $url;?>/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- STORAGE API-->
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<!-- ANIMO-->
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/animo.js/animo.min.js"></script>
<?php if (empty($select_2)) { ?>
    <!-- SELECT2-->
    <script type="text/javascript" src="<?= base_url() ?>assets/plugins/select2/dist/js/select2.min.js"></script>
<?php } ?>
<!-- Data Table -->
<?php if (empty($dataTables)) { ?>
    <?php include_once 'assets/plugins/dataTables/js/jquery.dataTables.min.php'; ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/buttons.colVis.min.js"></script>
    <!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/plugins/dataTables/js/jszip.min.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/dataTables/js/dataTables.bootstrapPagination.js"></script>
<?php } ?>
<!-- summernote Editor -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/summernote/summernote.min.js"></script>
<?php if (empty($datepicker)) { ?>
    <!-- Date and time picker -->
    <?php include_once 'assets/js/bootstrap-datepicker.php'; ?>
<?php } ?>
<script type="text/javascript" src="<?= base_url() ?>assets/js/timepicker.min.js"></script>
<!-- bootstrap-slider -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/bootstrap-slider/bootstrap-slider.min.js"></script>
<!-- bootstrap-editable -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/bootstrap-editable/bootstrap-editable.min.js"></script>
<!-- jquery-classyloader -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/jquery-classyloader/jquery.classyloader.min.js"></script>
<!-- Toastr -->
<script type="text/javascript" src="<?= base_url() ?>assets/js/toastr.min.js"></script>
<!-- Toastr -->
<script type="text/javascript" src="<?= base_url() ?>assets/js/jasny-bootstrap.min.js"></script>
<!-- EASY PIE CHART-->
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/easy-pie-chart/jquery.easypiechart.min.js"></script>
<!-- sparkline CHART-->
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/sparkline/index.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/parsleyjs/parsley.min.js"></script>
<!--- bootstrap-select ---->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<!--- push_notification ---->
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/push_notification/push_notification.min.js"></script>
<script src='<?= base_url() ?>assets/plugins/jquery-validation/jquery.validate.min.js'></script>
<script src='<?= base_url() ?>assets/plugins/jquery-validation/jquery.form.min.js'></script>
<!--- dropzone ---->
<?php if (!empty($dropzone)) { ?>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.css">
    <script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.custom.min.js"></script>
<?php } ?>
<!--- malihu-custom-scrollbar ---->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.min.css">
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<?php
$realtime_notification = config_item('realtime_notification');
if (!empty($realtime_notification)) { ?>
    <!--    <script src="--><?php //echo base_url() ?><!--assets/plugins/pusher/pusher.min.js"></script>-->
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="text/javascript">
        // Enable pusher logging - don't include this in production
        // Pusher.logToConsole = true;
        <?php $pusher_options = array();
        if (!isset($pusher_options['cluster']) && config_item('pusher_cluster') != '') {
            $pusher_options['cluster'] = config_item('pusher_cluster');
        } ?>
        var pusher_options = <?php echo json_encode($pusher_options); ?>;
        var pusher = new Pusher("<?php echo config_item('pusher_app_key'); ?>", pusher_options);
        var channel = pusher.subscribe('notifications-channel-<?php echo $this->session->userdata('user_id'); ?>');
        channel.bind('notification', function (data) {
            fetch_notifications();
        });
    </script>
<?php } ?>
<!-- APP SCRIPTS -->
<script type="text/javascript" src="<?= base_url() ?>assets/js/app.js"></script>
<?php if (empty($dataTables)) { ?>
    <?php include_once 'assets/plugins/dataTables/js/dataTables.php'; ?>
<?php } ?>
<?php
$wtps_number = str_replace(" ", "", config_item('socialnetwork_whatsapp_telephonenumber'));
$wtps_text = config_item('socialnetwork_whatsapp_textsendmessage');
?>
<div class="wtps_btn">
    <div class="wtps_btn__c">
        <a href="https://api.whatsapp.com/send?phone=51<?php echo $wtps_number;?>&text=<?php echo $wtps_text;?>" target="_blank" class="scroll-to-top">
            <img src="<?= base_url() . 'uploads/whatsapp.png' ?>" alt="App Logo" class="img-responsive" width="100" height="100" decoding="sync">
        </a>
    </div>
</div>
</body>
</html>
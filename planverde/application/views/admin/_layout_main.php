<?php
$this->load->view('admin/components/htmlheader');
$opened = $this->session->userdata('opened');
$this->session->unset_userdata('opened');
?>
<?php
  if (!empty($opened)){
    $offsidebar_open = 'offsidebar-open';
  }
  $wproy_settConf = config_item('layout-h') . ' ' . config_item('aside-float') . ' ' . config_item('aside-collapsed') . ' ' . config_item('layout-boxed') . ' ' . config_item('layout-fixed') . ' '.config_item('show-scrollbar');
?>
<body id="cBtn_admToggFullscreen" class="<?php echo $offsidebar_open." ".$wproy_settConf;?>">
<div class="wrapper">
  <?php $this->load->view('admin/components/header'); ?>
  <?php $this->load->view('admin/components/sidebar'); ?>
  <?php $this->load->view('client/components/offsidebar'); ?>
  <section>
    <?php
    $active_pre_loader = config_item('active_pre_loader');
    if (!empty($active_pre_loader) && $active_pre_loader == 1){
        ?>
      <div id="loader-wrapper">
        <div id="loader"></div>
      </div>
    <?php } ?>
    <div class="content-wrapper cMain__cwrpp">
      <div class="row">
        <div class="col-lg-12">
          <?php echo $subview ?>
        </div>
      </div>
    </div>
  </section>
  <footer></footer>
</div>
<?php
$this->load->view('admin/components/footer');
$direction = $this->session->userdata('direction');
if (!empty($direction) && $direction == 'rtl'){
    $RTL = 'on';
} else {
    $RTL = config_item('RTL');
}
?>

<script type="text/javascript">
    $(document).ready(function (){
        $(".clock_in_button").click(function (){
            var ubtn = $(this);
            ubtn.html('<?= lang('please_wait')?>' + '...');
            ubtn.addClass('disabled');
        });

        $('[data-ui-slider]').slider({
            <?php
            if (!empty($RTL)){?>
            reversed: true,
            <?php }
            ?>
        });
        /*
         * Multiple drop down select
         */
        $(".select_box").select2({
            <?php

            if (!empty($RTL)){?>
            dir: "rtl",
            <?php }
            ?>
        });
        $(".select_2_to").select2({
            tags: true,
            <?php
            if (!empty($RTL)){?>
            dir: "rtl",
            <?php }
            ?>
            allowClear: true,
            placeholder: 'To : Select or Write',
            tokenSeparators: [',', ' ']
        });
        $(".select_multi").select2({
            tags: true,
            <?php
            if (!empty($RTL)){?>
            dir: "rtl",
            <?php }
            ?>
            allowClear: true,
            placeholder: 'Select Multiple',
            tokenSeparators: [',', ' ']
        });
    })
</script>

<script type="text/javascript">
    $(document).on("click", '.is_complete input[type="checkbox"]', function (){
            var task_id = $(this).data().id;
            var task_complete = $(this).is(":checked");
            var formData = {
                'task_id': task_id,
                'task_progress': 100,
                'task_status': 'completed'
            };
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>admin/tasks/completed_tasks/' + task_id, // the url where we want to POST
                data: formData, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res){
                    if (res){
                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })
    });
</script>
<script type="text/javascript">
    $(document).ready(function (){
        $('#permission_user_1').hide();
        $("div.action_1").hide();
        $("input[name$='permission']").click(function (){
            $("#permission_user_1").removeClass('show');
            if ($(this).attr("value") == "custom_permission"){
                $("#permission_user_1").show();
            } else {
                $("#permission_user_1").hide();
            }
        });
        $("input[name$='assigned_to[]']").click(function (){
            var user_id = $(this).val();
            $("#action_1" + user_id).removeClass('show');
            if (this.checked){
                $("#action_1" + user_id).show();
            } else {
                $("#action_1" + user_id).hide();
            }

        });
    });
</script>

<?php $this->load->view('admin/_layout_modal'); ?>
<?php $this->load->view('admin/_layout_modal_lg'); ?>
<?php $this->load->view('admin/_layout_modal_large'); ?>
<?php $this->load->view('admin/_layout_modal_extra_lg'); ?>

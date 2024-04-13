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
<body id="cBtn_toggfullscreen" class="<?php echo $offsidebar_open." ".$wproy_settConf;?>">
<div class="wrapper">
  <?php $this->load->view('client/components/header');?>
  <?php $this->load->view('client/components/sidebar');?>
  <?php $this->load->view('client/components/offsidebar');?>
  <section>
    <div class="content-wrapper cMain__cwrpp">
      <div class="content-heading cMain__cwrpp-ds_non">
        <?php echo $breadcrumbs;?>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="cMain__cwrpp__cHeading">
            <?php if($breadcrumbs != "") : ?>
            <h3><?php echo $breadcrumbs;?></h3>
            <?php endif; ?>
          </div>
          <?php echo $subview;?>
        </div>
      </div>
    </div>
  </section>
  <footer></footer>
</div>
<?php $this->load->view('admin/components/footer');?>
<?php $this->load->view('admin/_layout_modal');?>
<?php $this->load->view('admin/_layout_modal_lg');?>
<?php $this->load->view('admin/_layout_modal_extra_lg');?>
<!-- <script>
$(document).on("submit", "#update_photo", function(e) {
    e.preventDefault()
    let form = $(e.target),
      url = form.attr("action")
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize()
    }).done(function(response) {
      toastr[response.type](response.message)
      if (response.type == 'success') {
        $('.modal').modal('hide');
        $('.modal').find('.modal-content').removeData();

      }
    })
  })
</script> -->
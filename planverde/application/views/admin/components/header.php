<?php
$user_id = $this->session->userdata('user_id');
$profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
$user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
$languages = $this->db->where('active', 1)->order_by('name', 'ASC')->get('tbl_languages')->result();
/*
$url = $actual_link . "/planverde";
*/
$urlPlanVerde = $actual_link . "/erpsolugrifos/planverde";
?>
<header class="topnavbar-wrapper">
  <nav role="navigation" class="navbar topnavbar cHTopNav__c">
    <?php $display = config_item('logo_or_icon'); ?>
    <div class="navbar-header cNav-header cHTopNav__c__header">
      <?php if ($display == 'logo' || $display == 'logo_title') { ?>
        <a href="#/" class="navbar-brand cNav-header__link">
          <div class="brand-logo cNav-header__link--cLogo">
            <img src="<?= base_url() . 'assets/img/logo-interno.png' ?>" alt="App Logo" class="img-responsive img-fluid" witdh="100" height="100" decoding="sync">
          </div>
          <div class="brand-logo-collapsed cNav-header__link--cLogo--collapsed">
            <img src="<?= base_url() . 'assets/img/plan-verde.jpg' ?>" alt="App Logo" class="img-responsive img-fluid" witdh="100" height="100" decoding="sync">
          </div>
        </a>
      <?php }
      ?>
    </div>
    <div class="nav-wrapper cNav-wrapper cHTopNav__c__navWrapper">
      <ul class="nav navbar-nav cNav-wrapper__lft cHTopNav__c__navWrapper__lft">
        <li class="cNav-wrapper__lft--cBtnsTogg">
          <a href="#" data-toggle-state="aside-collapsed" class="hidden-xs">
            <em class="fa fa-navicon"></em>
          </a>
          <a href="#" data-toggle-state="aside-toggled" data-no-persist="true" class="visible-xs sidebar-toggle">
            <em class="fa fa-navicon"></em>
          </a>
        </li>
        <li class="hidden-xs cNav-wrapper__lft--cLogo">
          <a href="" class="text-center">
            <img src="<?= $urlPlanVerde;?>/uploads/solugrifos.png" alt="logo_planverde" class="img-fluid" witdh="100" height="100" decoding="sync">
          </a>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right cNav-wrapper__rgt cHTopNav__c__navWrapper__rgt">
        <li class="visible-lg">
          <a href="#" data-toggle-fullscreen="" id="btn_admToggFullscreen" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Pantalla completa">
            <!--<em class="fa fa-expand"></em>-->
            <!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M200 32H56C42.7 32 32 42.7 32 56V200c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l40-40 79 79-79 79L73 295c-6.9-6.9-17.2-8.9-26.2-5.2S32 302.3 32 312V456c0 13.3 10.7 24 24 24H200c9.7 0 18.5-5.8 22.2-14.8s1.7-19.3-5.2-26.2l-40-40 79-79 79 79-40 40c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H456c13.3 0 24-10.7 24-24V312c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2l-40 40-79-79 79-79 40 40c6.9 6.9 17.2 8.9 26.2 5.2s14.8-12.5 14.8-22.2V56c0-13.3-10.7-24-24-24H312c-9.7 0-18.5 5.8-22.2 14.8s-1.7 19.3 5.2 26.2l40 40-79 79-79-79 40-40c6.9-6.9 8.9-17.2 5.2-26.2S209.7 32 200 32z"/></svg>-->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z"/></svg>
          </a>
        </li>
        <li class="dropdown dropdown-list notifications cNav-wrapper__rgt--cLNotif">
          <?php $this->load->view('admin/components/notifications'); ?>
        </li>
        <li class="dropdown user user-menu cNav-wrapper__rgt--cUsrMenu">
          <a href="#" class="dropdown-toggle cNav-wrapper__rgt--cUsrMenu--toggle" data-toggle="dropdown">
            <img src="<?= base_url() . $profile_info->avatar ?>" class="img-xs user-image" alt="User Image"/>
            <span class="hidden-xs"><?= $profile_info->fullname ?></span>
          </a>
          <ul class="dropdown-menu animated zoomIn">
            <li class="user-header">
              <img src="<?= base_url() . $profile_info->avatar ?>" class="img-circle" alt="User Image"/>
              <p>
                <?= $profile_info->fullname ?>
                <small><?= lang('last_login') . ':' ?>
                  <?php
                  if ($user_info->last_login == '0000-00-00 00:00:00' || empty($user_info->last_login)) {
                    $login_time = "-";
                  } else {
                    $login_time = strftime(config_item('date_format'), strtotime($user_info->last_login)) . ' ' . display_time($user_info->last_login);
                  }
                  echo $login_time;
                  ?>
                </small>
              </p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="<?= base_url() ?>admin/settings/form_change_password" class="btn btn-default btn-flat"  data-toggle="modal" data-target="#myModal">Cambiar Contraeña</a>
              </div>
              <form method="post" action="<?= base_url() ?>login/logout" class="form-horizontal">
                <input type="hidden" name="clock_time" value="" id="time">
                <div class="pull-right">
                  <button type="submit" class="btn btn-default btn-flat">Cerrar Session</button>
                </div>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
<script type="text/javascript">
    /* Get into full screen */
function GoInFullscreen(element) {
	if(element.requestFullscreen)
		element.requestFullscreen();
	else if(element.mozRequestFullScreen)
		element.mozRequestFullScreen();
	else if(element.webkitRequestFullscreen)
		element.webkitRequestFullscreen();
	else if(element.msRequestFullscreen)
		element.msRequestFullscreen();
}

/* Get out of full screen */
function GoOutFullscreen() {
	if(document.exitFullscreen)
		document.exitFullscreen();
	else if(document.mozCancelFullScreen)
		document.mozCancelFullScreen();
	else if(document.webkitExitFullscreen)
		document.webkitExitFullscreen();
	else if(document.msExitFullscreen)
		document.msExitFullscreen();
}

/* Is currently in full screen or not */
function IsFullScreenCurrently() {
	var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;
	
	// If no element is in full-screen
	if(full_screen_element === null)
		return false;
	else
		return true;
}

$("#btn_admToggFullscreen").on('click', function() {
	if(IsFullScreenCurrently())
		GoOutFullscreen();
	else
		GoInFullscreen($("#cBtn_admToggFullscreen").get(0));
});

$(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
	if(IsFullScreenCurrently()) {
		console.log("Est���s en modo pantalla completa");
		document.querySelector("body").classList.add("Mod-fullscreen");
	}
	else {
		console.log("Saliste del modo pantalla completa");
		document.querySelector("body").classList.remove("Mod-fullscreen");
	}
});
</script>
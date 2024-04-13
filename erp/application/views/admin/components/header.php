<?php
$user_id = $this->session->userdata('user_id');
$profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
$user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
$languages = $this->db->where('active', 1)->order_by('name', 'ASC')->get('tbl_languages')->result();

?>
<style type="text/css">
    .logoimg_hNotCollapse{
        width: 100%;
        max-height: 42px;
    }
    .logoimg_hCollapse{
        width: 100%;
        height: 41px;
        border-radius: 50px;
        object-fit: cover;
        object-position: 0 0;
    }
</style>
<header class="topnavbar-wrapper">
    <nav role="navigation" class="navbar topnavbar">
        <?php $display = config_item('logo_or_icon'); ?>
        <div class="navbar-header">
            <?php if ($display == 'logo' || $display == 'logo_title') { ?>
                <a href="#/" class="navbar-brand">
                    <div class="brand-logo">
                        <img src="<?= base_url() . config_item('company_logo') ?>" alt="App Logo" class="logoimg_hNotCollapse img-responsive" width="100" height="100">
                    </div>
                    <div class="brand-logo-collapsed">
                        <img src="<?= base_url() . config_item('company_logo') ?>" alt="App Logo" class="logoimg_hCollapse img-responsive" width="100" height="100">
                    </div>
                </a>
            <?php }
            ?>
        </div>
        <div class="nav-wrapper">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#" data-toggle-state="aside-collapsed" class="hidden-xs">
                        <em class="fa fa-navicon"></em>
                    </a>
                    <a href="#" data-toggle-state="aside-toggled" data-no-persist="true" class="visible-xs sidebar-toggle">
                        <em class="fa fa-navicon"></em>
                    </a>
                </li>
                <li class="hidden-xs">
                    <a href="" class="text-center" style="vertical-align: middle;font-size: 20px;"><?php
                        if ($display == 'logo_title' || $display == 'icon_title') {
                            if (config_item('website_name') == '') {
                                echo config_item('company_name');
                            } else {
                                echo config_item('website_name');
                            }
                        }
                        ?>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                    if(in_array($this->session->userdata('designations_id'), [1, 2, 3])):
                ?>
                <li class="dropdown dropdown-list notifications-facturas">
                    <?php $this->load->view('admin/components/notifications_facturas'); ?>
                </li>
                <?php
                    endif;
                ?>
                <li class="dropdown dropdown-list notifications">
                    <?php $this->load->view('admin/components/notifications'); ?>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= base_url() . $profile_info->avatar ?>" class="img-xs user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= $profile_info->fullname ?></span>
                    </a>
                    <ul class="dropdown-menu animated zoomIn">
                        <li class="user-header">
                            <img src="<?= base_url() . $profile_info->avatar ?>" class="img-circle" alt="User Image"/>
                            <p>
                                <?= $profile_info->fullname ?>
                                <small>Ultimo inicio de sesion: 
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
                                <a href="<?= base_url() ?>admin/settings/update_profile" class="btn btn-default btn-flat">Actualizar perfil</a>
                            </div>
                            <form method="post" action="<?= base_url() ?>login/logout" class="form-horizontal">
                                <input type="hidden" name="clock_time" value="" id="time">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-default btn-flat">Cerrar Sesion</button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </li>                
            </ul>
        </div>
    </nav>
</header>
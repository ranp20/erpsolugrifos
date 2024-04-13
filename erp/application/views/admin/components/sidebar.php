<style>

    .menu-border-transparent {
        border-color: transparent !important;
        height: 40px;
        color: #a9a3a3;
        background-color: rgba(255, 255, 255, .1);
        /*width: 100%;*/
    }

    input[type="search"]::-webkit-search-cancel-button {
        -webkit-appearance: searchfield-cancel-button;
    }
    .inner-addon {
        position: relative;
    }
    .left-addon .fa {
        left: 0px;
    }
    .inner-addon .fa {
        position: absolute;
        pointer-events: none;
        padding: 13px;
    }
    .left-addon input {
        padding-left: 30px;
    }


</style>
<aside class="aside">
    <!-- START Sidebar (left)-->
    <?php
    $user_id = $this->session->userdata('user_id');
    $profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
    $user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
    ?>
    <div class="aside-inner">
        <nav data-sidebar-anyclick-close="" class="sidebar <?= config_item('show-scrollbar') ?>">
            <!-- START sidebar nav-->
            <ul class="nav">
                <!-- START user info-->
                <li class="has-user-block">
                    <a href="#">
                        <div id="user-block" class="block">
                            <div class="item user-block">
                                <!-- User picture-->
                                <div class="user-block-picture">
                                    <div class="user-block-status">
                                        <img src="<?= base_url() . $profile_info->avatar ?>" alt="Avatar" width="60"
                                             height="60"
                                             class="img-thumbnail img-circle">
                                        <div class="circle circle-success circle-lg"></div>
                                    </div>
                                </div>
                                <!-- Name and Job-->
                                <div class="user-block-info">
                                    <span class="user-block-name"><?= $profile_info->fullname ?></span>
                                    <span class="user-block-role"></i> En Linea</span>
                                    <span id="txt"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- END user info-->
            <div class="inner-addon left-addon" style="width: 95%">
                <i class="fa fa-search"></i>
                <input type="search" id="s-menu" class="form-control menu-border-transparent" placeholder="Buscar en Menu"/>
            </div>
            <br/>

            <?php
            echo $this->menu->dynamicMenu();
            $all_pinned_details = $this->db->where('user_id', $this->session->userdata('user_id'))->get('tbl_pinaction')->result();
            if (!empty($all_pinned_details)) {
                foreach ($all_pinned_details as $v_pinned_details) {
                    $pinned_details[$v_pinned_details->module_name] = $this->db->where('pinaction_id', $v_pinned_details->pinaction_id)->get('tbl_pinaction')->result();
                }
            }
            ?>
            <!-- END sidebar nav-->
        </nav>
    </div>
    <!-- END Sidebar (left)-->
</aside>

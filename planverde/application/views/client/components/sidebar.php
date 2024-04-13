<aside class="aside">
    <?php
    $user_id = $this->session->userdata('user_id');
    $profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
    $user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
    ?>
    <div class="aside-inner">
        <nav data-sidebar-anyclick-close="" class="sidebar cSdbarNav-c">
            <ul class="nav">
                <li class="has-user-block">
                    <div id="user-block" class="block">
                        <div class="item user-block">
                            <div class="user-block-picture">
                                <a href="<?= base_url() . 'client/settings/upload_photo/'; ?>" data-toggle="modal" data-target="#myModal" title="CAMBIAR FOTO DE PERFIL">
                                    <div class="user-block-status">
                                        <img src="<?= base_url() . $profile_info->avatar ?>" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle">
                                        <div class="circle circle-success circle-lg"></div>
                                    </div>
                                </a>
                            </div>
                            <div class="user-block-info">
                                <span class="user-block-name"><?= $profile_info->fullname ?></span>
                                <span class="user-block-role"></i> <?= lang('online') ?></span>
                            </div>
                        </div>
                    </div>

                </li>
            </ul>
            <?php
            $data = $this->db->get_where('tbl_subcategoria', ['upload_client' => '1'])->result_array();
            echo $this->menu->clientMenu_aQ($data); ?>
        </nav>
    </div>
</aside>
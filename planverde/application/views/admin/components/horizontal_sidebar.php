<aside class="aside">
    <?php
    $user_id = $this->session->userdata('user_id');
    // $clientInfo = $this->check_by(array('user_id' => $user_id), 'tbl_account_details');
    $clientInfo = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
    // if(!empty($clientInfo)){
    //     echo "asadasdadas";
    // }else{
    //     echo "ggasdasd";
    // }
    // exit();
    $profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
    $user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
    if (!empty(config_item('layout-h'))) {
        $ul_class = 'navbar-nav';
    }
    ?>
    <div class="aside-inner">
        <nav data-sidebar-anyclick-close="" class="sidebar">
            <?php echo $this->menu->dynamicMenu();?>
        </nav>
    </div>
</aside>

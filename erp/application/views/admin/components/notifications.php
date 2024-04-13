<style type="text/css">
    .c-fromArea-letter{float: right;min-width: 20px;width: auto;height: auto;border-radius: 5px;display: inline-block;align-items: center;justify-content: center;text-align: center;padding: 0 1rem;vertical-align: bottom;margin-top: 0.5rem;color: #444;}
    /* FONDO POR ÁREA DE DONDE VIENE */
    .c-fromArea-letter-by-3{background-color: lightblue;}
    .c-fromArea-letter-by-5{background-color: lightcoral;}
    .c-fromArea-letter-by-6{background-color: lightgray;}
    .c-fromArea-letter-by-6{background-color: transparent;border: thin solid #888;}
    .c-fromArea-letter-by-8{background-color: cornflowerblue;color:#fff;}
    .c-fromArea-letter-by-10{background-color: burlywood;}
    .c-fromArea-letter-by-11{background-color: yellow;}
    .c-fromArea-letter-by-14{background-color: greenyellow;}
    .c-cont_circle{top:-2px;right:9px;position:absolute;}
    .c-cont_circle small{background-color:orange;color: orange;outline: thin solid orange;border-radius: 50%;width: 6px;height: 6px;display: inline-block;align-items: center;justify-content:center;}
    .posit-relative{position:relative !important;}
    .revw-item-notific{box-shadow: 3px 0 0 0 #32ad8e inset !important;background-color:#F5F5F5;}
    .notifications-list{max-width: 350px;width: 350px;}
    .notifications-list li.first-i-list{border-bottom: 1px solid rgb(238, 238, 238);}
    .notifications-list li a:hover{background-color: #FAEBD7 !important;}
    .notifications-list li a.revw-item-notific{background-color: #E6E6FA !important;}
</style>
<a href="#" data-toggle="dropdown">
    <em class="icon-bell"></em>
    <?php if ($unread_notifications > 0) { ?>
        <div class="label label-danger unraed-total icon-notifications"><?php echo $unread_notifications; ?></div>
    <?php } ?>
</a>
<?php
    /*
    echo "<pre>";
    print_r($_SESSION['user_id']);
    echo "</pre>";
    */
?>
<ul class="dropdown-menu animated zoomIn notifications-list" data-total-unread="<?php echo $unread_notifications; ?>">
    <li class="text-sm text-right first-i-list">
        <a href="#" class="list-group-item" onclick="mark_all_as_read(); return false;"><?php echo lang('mark_all_as_read'); ?></a>
    </li>
    <?php
    $user_notifications = $this->global_model->get_user_notifications(false);
    if (!empty($user_notifications)) {
        foreach ($user_notifications as $notification) { ?>
            <li class="notification-li" data-notification-id="<?php echo $notification->notifications_id; ?>">
                <?php 
                    if (!empty($notification->link)) {
                        $link = base_url() . $notification->link;
                    } else {
                        $link = '#';
                    }
                ?>
                <a href="<?php echo base_url() . $notification->link; ?>"
                   class="posit-relative n-top n-link list-group-item <?= ($notification->read_inline == 0) ? ' unread' : 'revw-item-notific';?>">
                    <!--<span><?php //echo $notification->notifications_id; ?></span>-->
                    <div class="n-box media-box ">
                        <div class="pull-left">
                            <?php
                            if ($notification->from_user_id != 0) {
                                $img = base_url() . staffImage($notification->from_user_id);
                            } else {
                                $img = 'https://raw.githubusercontent.com/encharm/Font-Awesome-SVG-PNG/master/black/png/128/' . $notification->icon . '.png';
                            } ?>
                            <img src="<?= $img ?>" alt="Avatar" width="40" height="40" class="img-thumbnail img-circle n-image">
                        </div>
                        <div class="media-box-body clearfix">
                            <?php
                            $description = lang($notification->description, $notification->value);
                            if ($notification->from_user_id != 0) {
                                //$description = fullname($notification->from_user_id) . ' - ' . $description;
                                $description = $description;
                            }
                            echo '<span class="n-title text-sm block">' . $description . '</span>'; ?>
                            <small class="text-muted pull-left" style="margin-top: -4px">
                                <i class="fa fa-clock-o"></i> 
                                <span><?php echo time_ago($notification->date); ?></span>
                            </small>
                            <?php if ($notification->read_inline == 0) { ?>
                                <span class="text-muted pull-right mark-as-read-inline c-cont_circle"
                                      onclick="read_inline(<?php echo $notification->notifications_id; ?>);"
                                      data-placement="top"
                                      data-toggle="tooltip" data-title="<?php echo lang('mark_as_read'); ?>">
                                    <small></small>
                                </span>
                            <?php } ?>
                            <?php
                                $letter_fromuserid = "";
                                $name_fromuserid = fullname($notification->from_user_id);
                                $letter_uppercase_name = strtoupper($name_fromuserid);
                                if ($notification->from_user_id != 0) {
                                    if ($notification->from_user_id == 3) {
                                        $letter_fromuserid = "Miguel";
                                        echo "<span class='c-fromArea-letter c-fromArea-letter-by-{$notification->from_user_id}'>
                                                <small>".$letter_uppercase_name."</small>
                                            </span>";
                                    }else if($notification->from_user_id == 5){
                                        $letter_fromuserid = "Gerencia";
                                        echo "<span class='c-fromArea-letter c-fromArea-letter-by-{$notification->from_user_id}'>
                                                <small>".$letter_uppercase_name."</small>
                                            </span>";
                                    }else if($notification->from_user_id == 6){
                                        $letter_fromuserid = "Administración";
                                        echo "<span class='c-fromArea-letter c-fromArea-letter-by-{$notification->from_user_id}'>
                                                <small>".$letter_uppercase_name."</small>
                                            </span>";
                                    }else if($notification->from_user_id == 7){
                                        $letter_fromuserid = "Calidad";
                                        echo "<span class='c-fromArea-letter c-fromArea-letter-by-{$notification->from_user_id}'>
                                                <small>".$letter_uppercase_name."</small>
                                            </span>";
                                    }else if($notification->from_user_id == 10){
                                        $letter_fromuserid = "HSE";
                                        echo "<span class='c-fromArea-letter c-fromArea-letter-by-{$notification->from_user_id}'>
                                                <small>".$letter_uppercase_name."</small>
                                            </span>";
                                    }else if($notification->from_user_id == 11){
                                        $letter_fromuserid = "Mantenimiento";
                                        echo "<span class='c-fromArea-letter c-fromArea-letter-by-{$notification->from_user_id}'>
                                                <small>".$letter_uppercase_name."</small>
                                            </span>";
                                    }else if($notification->from_user_id == 14){
                                        $letter_fromuserid = "Plan Verde";
                                        echo "<span class='c-fromArea-letter c-fromArea-letter-by-{$notification->from_user_id}'>
                                                <small>".$letter_uppercase_name."</small>
                                            </span>";
                                    }else{
                                        echo "<span class='c-fromArea-letter c-fromArea-letter-by-{$notification->from_user_id}'>
                                                <small>".$letter_uppercase_name."</small>
                                            </span>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </a>
            </li>
        <?php }
    }
    ?>
    <li class="text-center">
        <?php if (count($user_notifications) > 0) { ?>
            <a href="<?php echo base_url(); ?>admin/user/user_details/<?= $this->session->userdata('user_id') ?>/notifications"><?php echo lang('view_all_notifications'); ?></a>
        <?php } else { ?>
            <?php echo lang('no_notification'); ?>
        <?php } ?>
    </li>
    <!-- END list group-->
</ul>
<!-- END Dropdown menu-->

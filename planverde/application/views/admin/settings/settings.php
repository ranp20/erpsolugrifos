<div class="cSettgs row">
    <div class="cSettgs__c col-lg-12">
        <div class="cSettgs__c__cGrpLinks col-md-3 row">
            <ul class="cSettgs__c__cGrpLinks__m nav nav-pills nav-stacked navbar-custom-nav">
                <?php
                $can_do = can_do(111);
                if (!empty($can_do)) { ?>
                    <li class="cSettgs__c__cGrpLinks__m--itm <?php echo ($load_setting == 'general') ? 'active' : ''; ?>">
                        <a class="cSettgs__c__cGrpLinks__m--link" href="<?= base_url() ?>admin/settings">
                            <i class="fa fa-fw fa-info-circle"></i>
                            <?php echo lang('company_details') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(120);
                if (!empty($can_do)) { ?>
                    <li class="cSettgs__c__cGrpLinks__m--itm <?php echo ($load_setting == 'theme') ? 'active' : ''; ?>">
                        <a class="cSettgs__c__cGrpLinks__m--link" href="<?= base_url() ?>admin/settings/theme">
                            <i class="fa fa-fw fa-code"></i>
                            <?php echo lang('theme_settings') ?>
                        </a>
                    </li>
                <?php  } ?>
                <?php 
                $can_do = can_do(120);
                if (!empty($can_do)) {  ?>
                
                    <li class="cSettgs__c__cGrpLinks__m--itm <?php  echo ($load_setting == 'social_networks') ? 'active' : ''; ?>">
                        <a class="cSettgs__c__cGrpLinks__m--link" href="<?php  echo base_url() ?>admin/settings/social_networks">
                            <i class="fa fa-fw fa-code"></i>
                            <?php  echo lang('social_networks_settings') ?>
                        </a>
                    </li>
               
                <?php  } ?>
            </ul>
        </div>
        <section class="cSettgs__c__cRefsLinksItems col-sm-9">
            <div class="d-block">
                <?php if ($load_setting == 'email') { ?>
                    <div style="margin-bottom: 10px;margin-left: -15px" class="<?php
                    if ($load_setting != 'email') {
                        echo 'hidden';
                    }
                    ?>">
                        <a href="<?= base_url() ?>admin/settings/email" class="btn btn-info">
                            <i class="fa fa fa-inbox text"></i>
                            <span class="text"><?php echo lang('alert_settings') ?></span>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <section class="">
                <?php $this->load->view('admin/settings/' . $load_setting) ?>
            </section>
        </section>
    </div>
</div>
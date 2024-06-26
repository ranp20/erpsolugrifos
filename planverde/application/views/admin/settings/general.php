<?php echo message_box('success') ?>
<div class="row">
    <div class="col-lg-12">
        <form role="form" id="form" action="<?php echo base_url(); ?>admin/settings/save_settings" method="post" class="form-horizontal">
            <section class="panel panel-custom">
                <?php
                $can_do = can_do(111);
                if (!empty($can_do)) { ?>
                    <header class="panel-heading">
                        <h3 class="mt-0"><?= ucfirst(lang('company_details'));?></h3>
                    </header>
                    <div class="panel-body">
                        <input type="hidden" name="settings" value="<?= $load_setting ?>">
                        <input type="hidden" name="languages" value="<?php
                        if (!empty($translations)) {
                            echo implode(",", $translations);
                        }
                        ?>">
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('company_name'));?> <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                                <input type="text" name="company_name" class="form-control" value="<?= $this->config->item('company_name') ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('company_legal_name'));?> <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                                <input type="text" name="company_legal_name" class="form-control" value="<?= $this->config->item('company_legal_name') ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('contact_person'));?> </label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?= $this->config->item('contact_person') ?>" name="contact_person">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('company_address'));?> <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                                <textarea class="form-control" name="company_address" required><?= $this->config->item('company_address') ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('country'));?></label>
                            <div class="col-lg-7">
                                <select class="form-control select_box" style="width:100%" name="company_country">
                                    <optgroup label="<?= lang('selected_country') ?>">
                                        <option value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                    </optgroup>
                                    <optgroup label="<?= lang('other_countries') ?>">
                                        <?php foreach ($countries as $country): ?>
                                            <option value="<?= $country->value ?>"><?= $country->value ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('city'));?></label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?= $this->config->item('company_city') ?>" name="company_city">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('zip_code'));?> </label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" value="<?= $this->config->item('company_zip_code') ?>" name="company_zip_code">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('company_phone'));?></label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" value="<?= $this->config->item('company_phone') ?>" name="company_phone" data-valformat="withspacesforthreenumbers">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('company_email'));?></label>
                            <div class="col-lg-7">
                                <input type="email" class="form-control" value="<?= $this->config->item('company_email') ?>" name="company_email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('company_domain'));?></label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?= $this->config->item('company_domain') ?>" name="company_domain">
                            </div>
                        </div>
                        <!--
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('company_vat'));?></label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?= $this->config->item('company_vat') ?>" name="company_vat">
                            </div>
                        </div>
                        -->
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('company_ruc'));?></label>
                            <div class="col-lg-7">
                                <input type="number" class="form-control" value="<?= $this->config->item('company_ruc') ?>" name="company_ruc">
                            </div>
                        </div>
                    </div>
                    <header class="panel-heading">
                        <h3 class="mt-0">Redes sociales</h3>
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('socialnetwork_facebook_url'));?> <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?= $this->config->item('socialnetwork_facebook_url') ?>" name="socialnetwork_facebook_url">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('socialnetwork_instagram_url'));?> <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?= $this->config->item('socialnetwork_instagram_url') ?>" name="socialnetwork_instagram_url">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('socialnetwork_linkedin_url'));?> <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?= $this->config->item('socialnetwork_linkedin_url') ?>" name="socialnetwork_linkedin_url">
                            </div>
                        </div>
                    </div>
                    <header class="panel-heading">
                        <h3 class="mt-0">WhatsApp</h3>
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('socialnetwork_whatsapp_telephonenumber'));?> <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?= $this->config->item('socialnetwork_whatsapp_telephonenumber') ?>" name="socialnetwork_whatsapp_telephonenumber" data-valformat="withspacesforthreenumbers" maxlength="11">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-3 col-xl-3 cxs-txt-l cxlg-txt-r control-label"><?= ucfirst(lang('socialnetwork_whatsapp_textsendmessage'));?> <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                                <textarea class="form-control" name="socialnetwork_whatsapp_textsendmessage"><?= $this->config->item('socialnetwork_whatsapp_textsendmessage') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3"></label>
                        <div class="col-lg-7">
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                        </div>
                    </div>
                    <?php
                } else {
                    echo lang('nothing_to_display');
                }
                ?>
            </section>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/back/adm_settings.js"></script>
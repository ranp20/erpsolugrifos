
<div class="row">
    <div class="col-sm-12 wrap-fpanel">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong><?= lang('change_password') ?></strong>
                </div>
            </div>
            <div class="panel-body">
                <form role="form" data-parsley-validate="" novalidate=""
                      action="<?php echo base_url(); ?>client/settings/set_password_aQ"
                      method="post" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4 control-label"><?= lang('old_password') ?><span
                                class="required"> *</span></label>
                        <div class="col-sm-7">
                            <input type="password" id="old_password"
                                   name="old_password" value="" class="form-control"
                                   placeholder="<?= lang('enter_your') . ' ' . lang('old_password') ?>"/>
                            <span class="required" id="old_password_error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4 control-label"><?= lang('new_password') ?><span
                                class="required"> *</span></label>
                        <div class="col-sm-7">
                            <input type="password"
                                   name="new_password" id="new_password" value="" class="form-control"
                                   placeholder="Enter Your <?= lang('new_password') ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4 control-label"><?= lang('confirm_password') ?> <span
                                class="required"> *</span></label>
                        <div class="col-sm-7">
                            <input type="password" id="confirm_password" data-parsley-equalto="#new_password"
                                   name="confirm_password" value="" class="form-control"
                                   placeholder="Enter Your <?= lang('confirm_password') ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-5">
                            <button id="old_password_button" 
                                    class="btn btn-primary"><?= lang('change_password') ?></button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<p class="text-center pv">INICIAR SESIÓN</p>
<form data-parsley-validate="" novalidate="" action="<?php echo base_url();?>login" method="post">
    <div class="form-group has-feedback">
        <input type="text" name="user_name" required="true" class="form-control" placeholder="Usuario"/>
        <span class="fa fa-envelope form-control-feedback text-muted"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" name="password" required="true" autocomplete="true" class="form-control" placeholder="Contraseña"/>
        <span class="fa fa-lock form-control-feedback text-muted"></span>
    </div>
    <div class="clearfix">
        <!-- <div class="checkbox c-checkbox pull-left mt0">
            <label>
                <input type="checkbox" value="" name="remember">
                <span class="fa fa-check"></span><?= lang('remember_me') ?></label>
        </div>
        <div class="pull-right"><a href="<?= base_url() ?>login/forgot_password" class="text-muted"><?= lang('forgot_password') ?></a>
        </div> -->
    </div>
    <button type="submit" class="btn btn-primary <?= $class ?> btn-flat"><?= lang('sign_in') ?> <i class="fa fa-arrow-right"></i></button>
</form>
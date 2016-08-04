<?php $__env->startSection('content'); ?>

    <div class="col_12">
        <ol class="breadcrumb">
            <li><a href="<?php echo AppHelper::UrlTo('/home/config')?>">Ustawienia</a></li>
            <li class="active">Edycja</li>
        </ol>

        <h1>Ustawienia</h1>
        <div class="editor">
            <div class="col-md-4">
                <fieldset>
                    <legend>Opcje ogólne</legend>
                    <?php echo Form::open(AppHelper::UrlTo('/home/config_edit')) ?>

                    <label for="site_url" class="control-label">Adres URL strony:</label>
                    <?php echo Form::text('site_url', $config['site_url']) ?>

                    <label for="admin_email" class="control-label">E-mail admina:</label>
                    <?php echo Form::text('admin_email', $config['admin_email']) ?>

                    <p class="padding-b-25"></p>
                    <?php echo Form::submit('Zatwierdź') ?>

                    <?php echo Form::close() ?>
                </fieldset>
            </div>
            <div class="col-md-4">
                <fieldset>
                    <legend>Meta tagi</legend>
                    <?php echo Form::open(AppHelper::UrlTo('/home/config_edit')) ?>

                    <label for="meta_title" class="control-label">Title:</label>
                    <?php echo Form::text('meta_title', $config['meta_title']) ?>

                    <label for="meta_keywords" class="control-label">Keywords:</label>
                    <?php echo Form::text('meta_keywords', $config['meta_keywords']) ?>

                    <label for="meta_description" class="control-label">Description:</label>
                    <?php echo Form::text('meta_description', $config['meta_description']) ?>

                    <p class="padding-b-25"></p>
                    <?php echo Form::submit('Zatwierdź') ?>

                    <?php echo Form::close() ?>
                </fieldset>
            </div>
            <div class="col-md-4">
                <fieldset>
                    <legend>Konfiguracja poczty</legend>
                    <?php echo Form::open(AppHelper::UrlTo('/home/config_edit')) ?>

                    <label for="mail_typ" class="control-label">Typ wysyłki:</label>
                    <?php echo Form::select('mail_typ', $config['mail_typ'], array(1 => "Serwer SMTP", 2 => "funkcja mail()")) ?>

                    <div class="smtp_field">

                    <label for="smtp_host" class="control-label">SMTP host:</label>
                    <?php echo Form::text('smtp_host', $config['smtp_host']) ?>

                    <label for="smtp_port" class="control-label">SMTP port:</label>
                    <?php echo Form::text('smtp_port', $config['smtp_port']) ?>

                    <label for="smtp_login" class="control-label">SMTP login:</label>
                    <?php echo Form::text('smtp_login', $config['smtp_login']) ?>

                    <label for="smtp_haslo" class="control-label">SMTP hasło:</label>
                    <?php echo Form::text('smtp_haslo', $config['smtp_haslo']) ?>

                    </div>
                    <p class="padding-b-25"></p>
                    <?php echo Form::submit('Zatwierdź') ?>

                    <?php echo Form::close() ?>
                </fieldset>
                <script type="text/javascript">

                    if($("#mail_typ").val() == 1){
                        $(".smtp_field").show();
                    } else {
                        $(".smtp_field").hide();
                    }

                    $("#mail_typ").change(function(){

                        if($("#mail_typ").val() == 1){
                            $(".smtp_field").show();
                        } else {
                            $(".smtp_field").hide();
                        }
                    })

                </script>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
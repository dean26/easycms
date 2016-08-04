<?php $__env->startSection('content'); ?>

    <div class="col_12">
        <ol class="breadcrumb">
            <li><a href="<?php echo AppHelper::UrlTo('/home/kontakt_form')?>">Wysłanie wiadomości</a></li>
            <li class="active">Formularz</li>
        </ol>

        <h1>Wysłanie wiadomości</h1>
        <div class="editor">
            <div class="col-md-18">

                <?php echo Form::open(AppHelper::UrlTo('/home/kontakt'),
                        ['files' => true, 'id' => 'edit_form']) ?>

                    <label for="odbiorca" class="control-label">Odbiorca:</label>
                    <?php echo Form::text('odbiorca', @$data['odbiorca']) ?>
                    <?php echo Form::error('odbiorca', $errors) ?>

                    <label for="nadawca" class="control-label">Nadawca:</label>
                    <?php echo Form::text('nadawca', @$data['nadawca']) ?>
                    <?php echo Form::error('nadawca', $errors) ?>

                    <label for="temat" class="control-label">Temat:</label>
                    <?php echo Form::text('temat', @$data['temat']) ?>
                    <?php echo Form::error('temat', $errors) ?>

                    <label for="tresc" class="control-label">Treść:</label>
                    <?php echo Form::textarea('tresc', @$data['tresc']) ?>
                    <?php echo Form::error('tresc', $errors) ?>
                    <?php echo AppHelper::cke('tresc', '') ?>

                    <label for="plik" class="control-label">Załącznik:</label>
                    <?php echo Form::file('plik') ?>
                    <?php echo Form::error('plik', $errors) ?>

                    <p class="padding-b-25"></p>
                    <a class="btn btn-warning warning_11" href="#" onclick="sendForm('#edit_form'); return false;">Wyślij</a>

                <?php echo Form::close() ?>

            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
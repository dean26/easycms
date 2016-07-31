<?php $__env->startSection('content'); ?>

    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo AppHelper::UrlTo('/user')?>">Użytkownicy CMS</a></li>
            <?php if($object->id > 0): ?>
                <li><a href="<?php echo AppHelper::UrlTo('/user/edit?id='.$object->id)?>">Edycja użytkownika</a></li>
            <?php else: ?>
                <li><a href="<?php echo AppHelper::UrlTo('/user/new')?>">Nowy użytkownik</a></li>
            <?php endif; ?>
        </ol>

        <?php if($object->id > 0): ?>
            <h1>Edycja użytkownika</h1>
        <?php else: ?>
            <h1>Nowy użytkownik</h1>
        <?php endif; ?>

        <div class="editor">

            <?php echo Form::open(($object->id > 0) ? AppHelper::UrlTo('/user/update?id='.$object->id) : AppHelper::UrlTo('/user/create'),
            ['files' => true, 'id' => 'edit_form']) ?>

                <div class="col-md-6">
                    <div class="col-md-6">
                        <label for="login" class="control-label">Login:</label>
                        <?php echo Form::text('login', Form::FieldValue('login', $object, $data)) ?>
                        <?php echo Form::error('login', $errors) ?>

                        <label for="imie" class="control-label">Imię:</label>
                        <?php echo Form::text('imie', Form::FieldValue('imie', $object, $data)) ?>
                        <?php echo Form::error('imie', $errors) ?>

                        <label for="nazwisko" class="control-label">Nazwisko:</label>
                        <?php echo Form::text('nazwisko', Form::FieldValue('nazwisko', $object, $data)) ?>
                        <?php echo Form::error('nazwisko', $errors) ?>

                        <label for="email" class="control-label">Email:</label>
                        <?php echo Form::text('email', Form::FieldValue('email', $object, $data)) ?>
                        <?php echo Form::error('email', $errors) ?>

                        <p class="padding-b-25"></p>
                        <a class="btn btn-warning warning_11" href="#" onclick="sendForm('#edit_form'); return false;">Zapisz</a>
                        <a class="btn btn-success warning_11" href="#" onclick="sendFormZastosuj('#edit_form'); return false;">Zastosuj</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="col-md-6">
                        <?php echo Form::error('typ', $errors) ?>
                        <label for="typ" class="control-label">Typ użytkownika:</label>
                        <?php echo Form::select('typ', Form::FieldValue('typ', $object, $data), User::$typy ) ?>

                        <?php echo Form::error('status', $errors) ?>
                        <label for="status" class="control-label">Status użytkownika:</label>
                        <?php echo Form::select('status', Form::FieldValue('status', $object, $data), User::$status ) ?>

                        <label for="haslo" class="control-label">Nowe hasło:</label>
                        <?php echo Form::password('haslo', "", ["autocomplete" => "false"]) ?>
                        <?php echo Form::error('haslo', $errors) ?>

                        <label for="haslo2" class="control-label">Powtórz nowe hasło:</label>
                        <?php echo Form::password('haslo2', "", ["autocomplete" => "false"]) ?>
                        <?php echo Form::error('haslo2', $errors) ?>

                        <label for="plik" class="control-label">Zdjęcie:</label>
                        <?php if($object->plik): ?>
                            <div class="clearfix"></div>
                            <img src="<?php echo AppHelper::BaseUrl(); ?>public/uploads/users/<?php echo $object->plik; ?>" alt=""/>
                            <input type="checkbox" value="1" name="usun_plik"/> usuń ten plik
                        <?php else: ?>
                            <?php echo Form::file('plik') ?>
                            <?php echo Form::error('plik', $errors) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <input type="hidden" value="0" id="zastosuj_inp" name="zastosuj"/>
            <?php echo Form::close() ?>

            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
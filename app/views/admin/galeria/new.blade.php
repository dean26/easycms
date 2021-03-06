@extends('admin.layout')

@section('content')

    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo AppHelper::UrlTo('/galeria')?>">Galeria</a></li>
            @if($object->id > 0)
                <li><a href="<?php echo AppHelper::UrlTo('/galeria/edit?id='.$object->id)?>">Edycja</a></li>
            @else
                <li><a href="<?php echo AppHelper::UrlTo('/galeria/new')?>">Nowy</a></li>
            @endif
        </ol>

        @if($object->id > 0)
            <h1>Galeria - edycja</h1>
        @else
            <h1>Galeria - nowy</h1>
        @endif

        <div class="editor">

            <?php echo Form::open(($object->id > 0) ? AppHelper::UrlTo('/galeria/update?id='.$object->id) : AppHelper::UrlTo('/galeria/create'),
            ['files' => true, 'id' => 'edit_form']) ?>

                <div class="col-md-6">
                    <div class="col-md-10">
                        <label for="tytul" class="control-label">Tytuł:</label>
                        <?php echo Form::text('tytul', Form::FieldValue('tytul', $object, $data)) ?>
                        <?php echo Form::error('tytul', $errors) ?>

                        <label for="slug" class="control-label">Link: (<?php echo AppHelper::BaseUrl(); ?>galeria/)</label>
                        <?php echo Form::text('slug', Form::FieldValue('slug', $object, $data)) ?>
                        <?php echo Form::error('slug', $errors) ?>

                        <label for="wstep" class="control-label">Krótka treść:</label>
                        <?php echo Form::textarea('wstep', Form::FieldValue('wstep', $object, $data)) ?>
                        <?php echo Form::error('wstep', $errors) ?>

                        <label for="tresc" class="control-label">Treść:</label>
                        <?php echo Form::textarea('tresc', Form::FieldValue('tresc', $object, $data)) ?>
                        <?php echo Form::error('tresc', $errors) ?>
                        <?php echo AppHelper::cke('tresc', '') ?>


                        <p class="padding-b-25"></p>
                        <a class="btn btn-warning warning_11" href="#" onclick="sendForm('#edit_form'); return false;">Zapisz</a>
                        <a class="btn btn-success warning_11" href="#" onclick="sendFormZastosuj('#edit_form'); return false;">Zastosuj</a>
                        @if($object->id > 0)
                            <a class="btn btn-info warning_11" href="<?php echo AppHelper::UrlTo('/foto?ref=galeria&ref_id='.$object->id)?>">
                                <i class="lnr lnr-picture"></i><span>&nbsp;Zdjęcia</span>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="col-md-10">

                        <?php echo Form::error('status', $errors) ?>
                        <label for="status" class="control-label">Status:</label>
                        <?php echo Form::select('status', Form::FieldValue('status', $object, $data), Galeria::$status ) ?>

                        <fieldset>
                            <legend>Meta tagi</legend>

                            <label for="meta_title" class="control-label">Meta Tag - title:</label>
                            <?php echo Form::text('meta_title', Form::FieldValue('meta_title', $object, $data)) ?>
                            <?php echo Form::error('meta_title', $errors) ?>

                            <label for="meta_description" class="control-label">Meta Tag - decription:</label>
                            <?php echo Form::text('meta_description', Form::FieldValue('meta_description', $object, $data)) ?>
                            <?php echo Form::error('meta_description', $errors) ?>

                            <label for="meta_keywords" class="control-label">Meta Tag - keywords:</label>
                            <?php echo Form::text('meta_keywords', Form::FieldValue('meta_keywords', $object, $data)) ?>
                            <?php echo Form::error('meta_keywords', $errors) ?>
                        </fieldset>

                        <fieldset>
                            <legend>Miniaturka</legend>
                            <label for="plik" class="control-label">Zdjęcie:</label>
                            @if($object->plik)
                                <div class="clearfix"></div>
                                <img src="<?php echo AppHelper::BaseUrl(); ?>public/uploads/galeria/<?php echo $object->plik; ?>" alt=""/>
                                <input type="checkbox" value="1" name="usun_plik"/> usuń ten plik
                            @else
                                <?php echo Form::file('plik') ?>
                                <?php echo Form::error('plik', $errors) ?>
                            @endif
                        </fieldset>
                    </div>
                </div>

                <input type="hidden" value="0" id="zastosuj_inp" name="zastosuj"/>
            <?php echo Form::close() ?>

            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){


            $("input#tytul").blur(function () {

                $.post("<?php echo AppHelper::UrlTo('/home/slug') ?>", { text: $(this).val() })
                        .done(function (data) {
                            $('input#slug').val(data);
                        });


            })
        });
    </script>
@stop

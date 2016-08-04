<?php $__env->startSection('content'); ?>

    <script src="<?php echo AppHelper::BaseUrl(); ?>public/js/jquery.sortable.js"></script>
    <link href="<?php echo AppHelper::BaseUrl(); ?>public/js/lightbox/css/lightbox.css" rel="stylesheet">
    <script src="<?php echo AppHelper::BaseUrl(); ?>public/js/lightbox/js/lightbox.js"></script>

    <div class="col_12">
        <ol class="breadcrumb">
            <li><a href="<?php echo AppHelper::UrlTo('/foto?ref_id=' . $data['ref_id'] . '&ref=' . $data['ref'])?>">Zdjęcia
                    dla obiektu <?php echo e($ref_obj); ?></a></li>
            <li class="active">Lista</li>
        </ol>
        <h1>Zdjęcia dla obiektu <?php echo e($ref_obj); ?> <a
                    href="<?php echo AppHelper::UrlTo('/' . $data['ref'] . '/edit?id=' . $data['ref_id'])?>"
                    class="btn btn-success warning_11">Wróć</a></h1>

        <div class="editor">

            <div class="col-md-8">

                <p class="padding-b-5">Ilość znalezionych wpisów: <b><?php echo e($lista["total"]); ?></b></p>

                <?php if(count($lista["wyniki"]) > 0): ?>

                    <p class="padding-b-25">Aby ustalić kolejność przesuń i upuść zdjęcie w wybranym miejscu.</p>

                    <div id="serialize_output" style="display: none;" class="padding-b-25">
                        <img src="<?php echo AppHelper::BaseUrl(); ?>public/images/ajax-loader.gif" alt=""/>
                        Czekaj...</div>

                    <ol id="lista_f" class="example">
                        <?php foreach($lista["wyniki"] as $rec): ?>
                            <li id="foto_<?php echo e($rec->id); ?>">
                                <?php if($rec->plik): ?>
                                    <a data-title="<?php echo e($rec->opis); ?>" href="<?php echo AppHelper::BaseUrl(); ?>public/uploads/galeria/<?php echo $rec->plik ?>" data-lightbox="galeria">
                                        <img src="<?php echo AppHelper::BaseUrl(); ?>public/uploads/galeria/mini_<?php echo $rec->plik; ?>"
                                         alt=""/>
                                    </a>
                                <?php endif; ?>
                                <br/>
                                <a title="usuń"
                                   href="<?php echo AppHelper::UrlTo('/foto/delete_many?obj[]=' . $rec->id . '&ref_id=' . $data['ref_id'] . '&ref=' . $data['ref'])?>"
                                   type="button" class="btn btn-xs btn-success" onclick="return confirm('Na pewno?')">
                                    <i class="lnr lnr-cross-circle"></i>
                                </a>
                                <a title="edytuj"
                                   href="<?php echo AppHelper::UrlTo('/foto?id=' . $rec->id . '&ref_id=' . $data['ref_id'] . '&ref=' . $data['ref'] . '#form')?>"
                                   type="button" class="btn btn-xs warning_33 btn-warning">
                                    <i class="lnr lnr-pencil"></i>
                                </a>
                                <br/>
                                <i><?php echo e($rec->opis); ?></i>
                            </li>
                        <?php endforeach; ?>
                        <div class="clearfix"></div>
                    </ol>

                    <script type="text/javascript">

                        $(function () {
                            $("ol.example").sortable({
                                onDrop: function (item, container, _super) {
                                    $('#serialize_output').show();
                                    var kolej = $("ol.example").sortable("serialize").get().join("\n");

                                    $("ol.example").sortable('disable');
                                    $.post("<?php echo AppHelper::UrlTo('/foto/kolejnosc?ref_id=' . $data['ref_id'] . '&ref=' . $data['ref'])?>", {'kolej': kolej}, function (data) {
                                        $("ol.example").sortable('enable');
                                        $('#serialize_output').hide();
                                    });

                                    _super(item, container);


                                },
                                serialize: function (parent, children, isContainer) {
                                    return isContainer ? children.join() : parent.attr('id')
                                }
                            })
                        })

                    </script>

                    <?php echo $lista["pagination"] ?>
                <?php endif; ?>

            </div>

            <div class="col-md-4">
                <a name="form"></a>
                <?php echo Form::open(AppHelper::UrlTo('/foto?from_form=1&ref_id=' . $data['ref_id'] . '&ref=' . $data['ref'] . '&id=' . $object_foto->id), array('files' => true)) ?>
                <div class="col-md-12">
                    <label for="opis" class="control-label">Opis zdjęcia:</label>
                    <?php echo Form::error('opis', $errors) ?>
                    <?php echo Form::text('opis', Form::FieldValue('opis', $object_foto, $data)) ?>
                </div>
                <div class="col-md-12">
                    <label for="plik" class="control-label">Plik:</label>
                    <?php if($object_foto->plik): ?>
                        <div class="clearfix"></div>
                        <img src="<?php echo AppHelper::BaseUrl(); ?>public/uploads/galeria/mini_<?php echo $object_foto->plik; ?>"
                             alt=""/>
                        <br/><input type="checkbox" value="1" name="usun_plik"/> usuń ten plik
                    <?php else: ?>
                        <?php echo Form::error('plik', $errors) ?>
                        <?php if($object_foto->id > 0): ?>
                            <?php echo Form::file('plik') ?>
                        <?php else: ?>
                            <?php echo Form::file('plik[]', array('multiple' => 'multiple')) ?>
                                <small>Możesz zaznaczyć więcej niż jedno zdjęcie.</small>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-12">
                    <label for="linie" class="control-label">&nbsp;</label><br/>
                    <?php echo Form::submit(($object_foto->id > 0) ? "Edytuj" : "Dodaj") ?>
                </div>
                <?php echo Form::close() ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
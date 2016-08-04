<?php $__env->startSection('content'); ?>

    <script src="<?php echo AppHelper::BaseUrl(); ?>public/js/jquery.sortable.js"></script>
    <link href="<?php echo AppHelper::BaseUrl(); ?>public/js/lightbox/css/lightbox.css" rel="stylesheet">
    <script src="<?php echo AppHelper::BaseUrl(); ?>public/js/lightbox/js/lightbox.js"></script>

    <div class="col_12">
        <ol class="breadcrumb">
            <li><a href="<?php echo AppHelper::UrlTo('/menu')?>">Konfiguracja menu</a></li>
            <li class="active">Lista</li>
        </ol>
        <h1>Konfiguracja menu</h1>

        <div class="editor">

            <div class="col-md-8">
                <p class="padding-b-25">Aby ustalić kolejność przesuń i upuść w wybranym miejscu.</p>

                <div id="serialize_output" style="display: none;" class="padding-b-25">
                    <img src="<?php echo AppHelper::BaseUrl(); ?>public/images/ajax-loader.gif" alt=""/>
                    Czekaj...</div>

                <?php foreach(Menu::$pozycja as $k => $v): ?>
                    <fieldset class="menu_pozycje">
                        <legend><?php echo $v ?></legend>

                        <ol class="example<?php echo $k ?>">
                            <?php foreach($lista['wyniki'][$k] as $menu_k => $menu_v): ?>

                            <li id="foto_<?php echo $menu_v->id ?>">
                                <?php echo $menu_v->tytul ?>
                                <a href="<?php echo AppHelper::UrlTo('/menu?id='.$menu_v->id.'#form')?>"><i class="lnr lnr-pencil"></i></a>
                                <a href="<?php echo AppHelper::UrlTo('/menu/delete_many?obj[]='.$menu_v->id)?>" onclick="return confirm('Na pewno?')">
                                    <i class="lnr lnr-cross"></i></a>

                                <?php $dzieci = $object_menu->dzieci($menu_v->id) ?>

                                <?php if(count($dzieci) > 0): ?>
                                    <ol class="example_dz_<?php echo $menu_v->id ?>">
                                        <?php foreach($dzieci as $dz_k => $dz_v): ?>
                                            <li id="foto_<?php echo $dz_k ?>">
                                                <?php echo $dz_v ?>
                                                <a href="<?php echo AppHelper::UrlTo('/menu?id='.$dz_k.'#form')?>"><i class="lnr lnr-pencil"></i></a>
                                                <a href="<?php echo AppHelper::UrlTo('/menu/delete_many?obj[]='.$dz_k)?>" onclick="return confirm('Na pewno?')">
                                                    <i class="lnr lnr-cross"></i></a>
                                            </li>
                                        <?php endforeach ?>
                                    </ol>
                                <?php endif ?>
                            </li>
                            <?php endforeach ?>
                        </ol>

                    </fieldset>

                    <script type="text/javascript">

                        $(function () {
                            $("ol.example<?php echo $k ?>").sortable({
                                group: 'example<?php echo $k ?>',
                                onDrop: function (item, container, _super) {
                                    $('#serialize_output').show();
                                    var kolej = $("ol.example<?php echo $k ?>").sortable("serialize").get().join("\n");
                                    var kolej2 = $("ol.example<?php echo $k ?> ol").sortable("serialize").get().join("\n");

                                    $("ol.example<?php echo $k ?>").sortable('disable');
                                    $.post("<?php echo AppHelper::UrlTo('/menu/kolejnosc')?>", {'kolej': kolej+","+kolej2}, function (data) {
                                        $("ol.example<?php echo $k ?>").sortable('enable');
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

                <?php endforeach ?>

            </div>

            <div class="col-md-4">
                <a name="form"></a>
                <?php echo Form::open(AppHelper::UrlTo('/menu?from_form=1'.(($object_menu->id > 0) ? "&id=".$object_menu->id : ""))) ?>
                <div class="col-md-12">
                    <label for="tytul" class="control-label">Tytuł:</label>
                    <?php echo Form::error('tytul', $errors) ?>
                    <?php echo Form::text('tytul', Form::FieldValue('tytul', $object_menu, $data)) ?>
                </div>
                <div class="col-md-12">
                    <label for="link" class="control-label">Link:</label>
                    <?php echo Form::error('link', $errors) ?>
                    <?php echo Form::select2('zasob', @$data['zasob'], $object_menu->zasoby()) ?>
                    <?php echo Form::text('link', Form::FieldValue('link', $object_menu, $data)) ?>
                    <script type="text/javascript">
                        $("#zasob").change(function(){
                            $("#link").val($(this).val());
                        })
                    </script>
                </div>
                <div class="col-md-12">
                    <label for="pozycja" class="control-label">Pozycja w menu:</label>
                    <?php echo Form::error('pozycja', $errors) ?>
                    <?php echo Form::select('pozycja', Form::FieldValue('pozycja', $object_menu, $data), Menu::$pozycja) ?>
                </div>
                <div class="col-md-12">
                    <label for="parent_id" class="control-label">Rodzic:</label>
                    <?php echo Form::error('parent_id', $errors) ?>
                    <?php echo Form::select('parent_id', Form::FieldValue('parent_id', $object_menu, $data), $object_menu->rodzice()) ?>
                </div>
                <div class="col-md-12">
                    <label for="status" class="control-label">Status:</label>
                    <?php echo Form::error('status', $errors) ?>
                    <?php echo Form::select('status', Form::FieldValue('status', $object_menu, $data), Menu::$status) ?>
                </div>
                <div class="col-md-12">
                    <label for="target" class="control-label">Sposób otwierania:</label>
                    <?php echo Form::error('target', $errors) ?>
                    <?php echo Form::select('target', Form::FieldValue('target', $object_menu, $data), Menu::$target) ?>
                </div>
                <div class="col-md-12">
                    <label for="linie" class="control-label">&nbsp;</label><br/>
                    <?php echo Form::submit(($object_menu->id > 0) ? "Edytuj" : "Dodaj") ?>
                </div>
                <?php echo Form::close() ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
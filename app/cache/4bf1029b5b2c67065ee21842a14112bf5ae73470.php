<?php $__env->startSection('content'); ?>

    <div class="col_12">
        <ol class="breadcrumb">
            <li><a href="<?php echo AppHelper::UrlTo('/news')?>">Aktualności</a></li>
            <li class="active">Lista</li>
        </ol>
        <h1>Aktualności <a href="<?php echo AppHelper::UrlTo('/news/new')?>" class="btn btn-success warning_11">Dodaj</a></h1>
        <div class="editor">
            <?php echo Form::open(AppHelper::UrlTo('/news')) ?>
            <div class="col-md-2">
                <label for="tytul" class="control-label">Tytuł:</label>
                <?php echo Form::text('tytul', $req->getParam('tytul')) ?>
            </div>
            <div class="col-md-2">
                <label for="linie" class="control-label">&nbsp;</label><br/>
                <?php echo Form::submit('Szukaj') ?>
            </div>
            <?php echo Form::close() ?>
            <div class="clearfix"></div>
        </div>
        <div class="editor">

            <p class="padding-b-5">Ilość znalezionych wpisów: <b><?php echo e($lista["total"]); ?></b></p>

            <?php if(count($lista["wyniki"]) > 0): ?>
            <div class="table-responsive">
                <form method="post" action="#" id="action_form">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="width: 30px">
                            <input type="checkbox" name="" value="1" id="checked_box" class="check_uncheck"/>
                        </th>
                        <th>Tytuł</th>
                        <th>Status</th>
                        <th>Data dodania</th>
                        <th>Ważność</th>
                        <th class="col-md-2">
                            <div class="btn-group">
                                <button class="btn btn-mini">Sortowanie</button>
                                <button class="btn dropdown-toggle btn-mini" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo AppHelper::UrlTo('/news?sort_by=nazwisko&sort_d=ASC'.$lista["pag_params_no_sort"])?>">nazwisko - rosnąco</a></li>
                                    <li><a href="<?php echo AppHelper::UrlTo('/news?sort_by=nazwisko&sort_d=DESC'.$lista["pag_params_no_sort"])?>">nazwisko - malejąco</a></li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lista["wyniki"] as $rec): ?>
                    <tr>
                        <th scope="row" style="text-align: center">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" name="obj[]" value="<?php echo e($rec->id); ?>" class="ptaszki"/>
                                    <?php echo e($rec->id); ?>

                                </label>
                            </div>
                        </th>
                        <td><b><?php echo e($rec->tytul); ?></b><br/><small><?php echo e($rec->slug); ?></small></td>
                        <td><?php echo e(News::$status[$rec->status]); ?></td>
                        <td><?php echo e(date('d.m.Y H:i', strtotime($rec->created_at))); ?></td>
                        <td><?php echo e($rec->poziom); ?></td>
                        <td>
                            <a href="<?php echo AppHelper::UrlTo('/news/delete_many?obj[]='.$rec->id)?>" type="button" class="btn btn-xs btn-success" onclick="return confirm('Na pewno?')">Usuń</a>
                            <a href="<?php echo AppHelper::UrlTo('/news/edit?id='.$rec->id)?>" type="button" class="btn btn-xs warning_33 btn-warning">Edytuj</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7">
                            <div class="btn-group">
                                <button class="btn btn-mini">Akcja</button>
                                <button class="btn dropdown-toggle btn-mini" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" class="check_uncheck">zaznacz/odznacz wszystkie</a></li>
                                    <li><a href="#" onclick="sendForm('#action_form', <?php echo e("'".AppHelper::UrlTo('/news/delete_many')."'"); ?>); return false;">usuń zaznaczone</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
                <input type="hidden" value="<?php echo $req->getAttribute('csrf_name')?>" name="csrf_name" />
                <input type="hidden" value="<?php echo $req->getAttribute('csrf_value')?>" name="csrf_value" />
                </form>
            </div>
            <?php echo $lista["pagination"] ?>
            <?php endif; ?>

        </div>
        <div class="clearfix"></div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
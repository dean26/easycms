<?php $__env->startSection('content'); ?>

<div class="col_12">

    <h1>Logi serwera</h1>

    <div class="editor">

        <?php echo Form::open(AppHelper::UrlTo('/home/logi')) ?>
        <div class="col-md-2">
            <label for="linie" class="control-label">Miesiąc/Rok:</label>
            <?php echo Form::select('plik', $req->getParam('plik', 0), $pliki) ?>
        </div>
        <div class="col-md-2">
            <label for="linie" class="control-label">Ilość wpisów:</label>
            <?php echo Form::select('linie', $req->getParam('linie', 100), array(10 => 10, 50 => 50, 100 => 100, 500 => 500, 1000 => 1000)) ?>
        </div>
        <div class="col-md-2">
            <label for="linie" class="control-label">&nbsp;</label><br/>
            <?php echo Form::submit('Pokaż') ?>
        </div>
        <?php echo Form::close() ?>

        <div class="clearfix padding-b-25"></div>

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Data</th>
                <th>Miejsce</th>
                <th>Typ</th>
                <th>Treść</th>
                <th>Dodatkowe dane</th>
            </tr>
            </thead>
            <tbody>
            <?php $licznik = 0 ?>
            <?php foreach($lista as  $rec): ?>
                <?php if($rec): ?>
                <?php
                    //pobranie daty
                    preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $rec, $matches);
                    $data = @$matches[0];

                    preg_match('/(\w+)\.([A-Z]+):\s(...+)\s\{(...+)\}/', $rec, $matches);
                    $dane = $matches;

                    $class_tr = $dane[2];
                    if($dane[2] == "ERROR") $class_tr = "DANGER";
                ?>
                <tr class="<?php echo strtolower($class_tr) ?>">
                    <td><?php echo ++$licznik ?>. <?php echo $data ?></td>
                    <td><?php echo $dane[1] ?></td>
                    <td><?php echo $dane[2] ?></td>
                    <td><?php echo $dane[3] ?></td>
                    <td>
                        <?php $zmienne = json_decode("{".$dane[4]."}", true) ?>
                        <?php foreach($zmienne as $k => $v): ?>
                            <b><?php echo e($k); ?></b>: <?php echo e($v); ?><br/>
                        <?php endforeach ?>
                    </td>
                </tr>
                <?php endif ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    <div class="clearfix"></div>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
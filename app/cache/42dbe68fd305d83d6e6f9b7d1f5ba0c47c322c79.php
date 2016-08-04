<?php $__env->startSection('content'); ?>

    <div class="col_12">
        <div class="col-md-6">
            <div class="activity_box">
                <h3>Wiadomości</h3>
                <div class="editor" id="style-2">
                    <p class="padding-b-25">Ilość znalezionych wpisów: <b><?php echo e($lista["total"]); ?></b></p>
                    <?php if(count($lista["wyniki"]) > 0): ?>
                        <?php foreach($lista["wyniki"] as $rec): ?>
                            <div class="activity-row">
                                <div class="col-xs-2 activity-img"><img
                                    src='<?php echo AppHelper::BaseUrl(); ?>public/admin/images/1.png'
                                    class="img-responsive" alt=""/></div>
                                <div class="col-xs-7 activity-desc">
                                    <h5><a href="#" onclick="return false;"><?php echo e($rec["osoba"]); ?> <small>(ip: <?php echo e($rec["ip"]); ?>)</small></a></h5>
                                    <p><?php echo e($rec["tresc"]); ?></p>
                                </div>
                                <div class="col-xs-3 activity-desc1">
                                    <h6>Data: <?php echo e(date('d.m.Y H:i', strtotime($rec["created_at"]))); ?></h6>
                                    <h6>Email: <?php echo e(@$rec['email']); ?></h6>
                                    <h6 class="padding-b-25">Telefon: <?php echo e(@$rec['telefon']); ?></h6>
                                    <h2>
                                        <a title="usuń" href="<?php echo AppHelper::UrlTo('/home/delete_wia?id='.$rec['id'])?>" onclick="return confirm('Na pewno?')">
                                            <i class="lnr lnr-cross-circle"></i></a>
                                        <?php if(@$rec['email']): ?>
                                        <a title="odpisz" href="<?php echo AppHelper::UrlTo('/home/kontakt_form?odbiorca='.$rec['email'])?>">
                                            <i class="lnr lnr-arrow-right-circle"></i></a>
                                        <?php endif ?>
                                    </h2>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        <?php endforeach; ?>
                        <?php echo $lista["pagination"] ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="switch-right-grid">
                <div class="switch-right-grid1">
                    <fieldset>
                    <legend>Miesięczne statystyki</legend>

                    <?php echo Form::open(AppHelper::UrlTo('/home/index')) ?>

                        <div class="col-md-4">
                            <label for="miesiac" class="control-label">Miesiąc:</label>
                            <?php echo Form::select('miesiac', $data['miesiac'], array_combine(range(1,12), range(1,12)) ) ?>
                        </div>
                        <div class="col-md-4">
                            <label for="rok" class="control-label">Rok:</label>
                            <?php echo Form::select('rok', $data['rok'], array_combine(range(2016, date('Y')), range(2016, date('Y'))) ) ?>
                        </div>
                        <div class="col-md-3">
                            <label for="submit" class="control-label">&nbsp;</label>
                            <?php echo Form::submit('Pokaż') ?>
                        </div>

                    <?php echo Form::close() ?>
                    </fieldset>
                    <p class="padding-b-25"></p>
                    <canvas id="bar" height="150" width="480" style="width: 480px; height: 150px;"></canvas>
                    <script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/Chart.js"></script>
                    <p class="padding-b-25"></p>
                    <div id="szczegolyStat" style="display: none;"></div>
                    <script>
                        //var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

                        var barChartData = {
                            labels : [<?php echo implode(", ", range(1, $data['ilosc_dni_w_mie'])) ?>],
                            datasets : [
                                {
                                    fillColor : "rgba(220,220,220,0.5)",
                                    strokeColor : "rgba(220,220,220,0.8)",
                                    highlightFill: "rgba(220,220,220,0.75)",
                                    highlightStroke: "rgba(220,220,220,1)",
                                    data : [<?php echo implode(",", $lista['staty']) ?>]
                                }
                            ]

                        }
                        window.onload = function(){
                            var ctx = document.getElementById("bar").getContext("2d");
                            var myChart = new Chart(ctx).Bar(barChartData, {
                                responsive : true
                            });

                            $("#szczegolyStat").show();
                            $("#szczegolyStat").html('<img src="<?php echo AppHelper::BaseUrl() ?>images/ajax-loader.gif" alt=""/> czekaj...');

                            $.post("<?php echo AppHelper::UrlTo('/home/staty_ajax')?>", { dzien: <?php echo date('d') ?>, rok: <?php echo $data['rok']?>, miesiac: <?php echo $data['miesiac'] ?> },
                                    function(data) {
                                        $("#szczegolyStat").html(data);
                                    });

                            $("#bar").click(function(e) {
                                var activeBars = myChart.getBarsAtEvent(e);

                                $("#szczegolyStat").show();
                                $("#szczegolyStat").html('<img src="<?php echo AppHelper::BaseUrl() ?>public/images/ajax-loader.gif" alt=""/> czekaj...');

                                $.post("<?php echo AppHelper::UrlTo('/home/staty_ajax')?>", { dzien: (activeBars[0].label), rok: <?php echo $data['rok']?>, miesiac: <?php echo $data['miesiac'] ?> },
                                        function(data) {
                                            $("#szczegolyStat").html(data);
                                });

                            });

                        }


                    </script>
                    <p class="padding-b-25"></p>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
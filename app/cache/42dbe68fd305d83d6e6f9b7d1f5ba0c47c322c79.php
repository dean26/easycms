<?php $__env->startSection('content'); ?>

    <div class="col_12">
        <div class="col-md-6">
            <div class="activity_box">
                <h3>Wiadomości</h3>
                <div class="scrollbar scrollbar1" id="style-2">
                    <div class="activity-row">
                        <div class="col-xs-3 activity-img"><img
                                    src='<?php echo AppHelper::BaseUrl(); ?>public/admin/images/1.png'
                                    class="img-responsive" alt=""/></div>
                        <div class="col-xs-7 activity-desc">
                            <h5><a href="#">John Smith</a></h5>
                            <p>Hey ! There I'm available.</p>
                        </div>
                        <div class="col-xs-2 activity-desc1"><h6>13:40 PM</h6></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="activity-row">
                        <div class="col-xs-3 activity-img"><img
                                    src='<?php echo AppHelper::BaseUrl(); ?>public/admin/images/1.png'
                                    class="img-responsive" alt=""/></div>
                        <div class="col-xs-7 activity-desc">
                            <h5><a href="#">Andrew Jos</a></h5>
                            <p>Hey ! There I'm available.</p>
                        </div>
                        <div class="col-xs-2 activity-desc1"><h6>13:40 PM</h6></div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="switch-right-grid">
                <div class="switch-right-grid1">
                    <h3>MONTHLY STATS</h3>
                    <p></p>
                    <canvas id="bar" height="150" width="480" style="width: 480px; height: 150px;"></canvas>
                    <script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/Chart.js"></script>

                    <script>
                        var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

                        var barChartData = {
                            labels : ["January","February","March","April","May","June","July"],
                            datasets : [
                                {
                                    fillColor : "rgba(220,220,220,0.5)",
                                    strokeColor : "rgba(220,220,220,0.8)",
                                    highlightFill: "rgba(220,220,220,0.75)",
                                    highlightStroke: "rgba(220,220,220,1)",
                                    data : [22,55,21,5,78]
                                }
                            ]

                        }
                        window.onload = function(){
                            var ctx = document.getElementById("bar").getContext("2d");
                            var myChart = new Chart(ctx).Bar(barChartData, {
                                responsive : true
                            });

                            $("#bar").click(function(e) {
                                var activeBars = myChart.getBarsAtEvent(e);
                                //alert(activeBars[0].label);
                            });

                        }


                    </script>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
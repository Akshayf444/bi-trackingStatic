<script src="<?php echo asset_url(); ?>js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo asset_url(); ?>js/exporting.js" type="text/javascript"></script>
<div class="row">
    <div class="col-lg-12">
        <ul align="center" class="nav nav-tabs ">
            <?php
            if (!empty($productlist)) {
                $count = 1;
                foreach ($productlist as $product) {

                    $dashboardDetails[$product->id] = array();
                    if (isset($_GET['Product_Id']) && $_GET['Product_Id'] > 0) {
                        ?>
                        <li class="<?php echo isset($_GET['Product_Id']) && $_GET['Product_Id'] == $product->id ? 'active' : ''; ?>"><a style="padding: 12px;" href="<?php echo site_url('Report/dashboard?Product_Id=' . $product->id); ?>" ><?php echo $product->Brand_Name ?></a></li>

                    <?php } else { ?>
                        <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a style="padding: 12px;" href="<?php echo site_url('Report/dashboard?Product_Id=' . $product->id); ?>" ><?php echo $product->Brand_Name ?></a></li>

                    <?php }
                    ?>
                    <?php
                    $count ++;
                }
            }
            ?>
        </ul>
    </div>
    <?php
    if (isset($_GET['Product_Id'])) {
        $object = new stdClass();
        $object->id = $this->input->get('Product_Id');
        $productlist = array($object);
    }
    ?>

    <div class="tab-content">
        <?php
        if (!empty($productlist)) {
            $count = 1;

            foreach ($productlist as $product) {
                $Product_id = $product->id;
                //echo $Product_id;
                $Planned_count = 0;
                $Actual_count = 0;
                $Activity_count = 0;
                $Activity_report = 0;
                $condition = array();
                // $condition[0] = "rp.Product_id = " . $Product_id;

                if (isset($this->Zone) && $this->Zone != '' && $this->Zone != '-1') {
                    $condition[1] = "em.Zone = '" . $this->Zone . "'";
                }
                if (isset($this->Division) && $this->Division != '' && $this->Division != 'Both') {
                    $condition[2] = "em.Division = '" . $this->Division . "'";
                }


                $DashboardStatus = $this->admin_model->getDashboardStatus($this->nextMonth, $this->nextYear, $Product_id, $condition);
                $plannedrx = $this->admin_model->getPlannedRx($Product_id, $this->nextYear, $condition);
                $actualrx = $this->admin_model->getActualRx($Product_id, $this->nextYear, $condition);
                
                if (!empty($DashboardStatus)) {
                    foreach ($DashboardStatus as $value) {
                        $Activity_count += $value->No_of_Doctors_planned;
                        $Activity_report += $value->checkk;
                    }
                }
                
                $Planned_count = $plannedrx->Planned_Rx;
                $Actual_count = $actualrx->Actual_Rx;
                
                
                $xAxisData1 = array();
                $monthData = array();
                $monthData1 = array();
                $monthData2 = array();
                $monthData3 = array();
                for ($i = 1; $i <= 12; $i++) {
                    $monthname = date('M', mktime(0, 0, 0, $i, 1, date('Y'))); //Month Name
                    array_push($xAxisData1, $monthname);
                }

                $activityCount = $this->admin_model->getDashboardStatus2($this->nextYear, $Product_id, $condition);
                if (!empty($activityCount)) {
                    foreach ($activityCount as $ActivityCount) {
                        array_push($monthData, $ActivityCount->m1);
                        array_push($monthData, $ActivityCount->m2);
                        array_push($monthData, $ActivityCount->m3);
                        array_push($monthData, $ActivityCount->m4);
                        array_push($monthData, $ActivityCount->m5);
                        array_push($monthData, $ActivityCount->m6);
                        array_push($monthData, $ActivityCount->m7);
                        array_push($monthData, $ActivityCount->m8);
                        array_push($monthData, $ActivityCount->m9);
                        array_push($monthData, $ActivityCount->m10);
                        array_push($monthData, $ActivityCount->m11);
                        array_push($monthData, $ActivityCount->m12);
                        array_push($monthData1, $ActivityCount->Ac1);
                        array_push($monthData1, $ActivityCount->Ac2);
                        array_push($monthData1, $ActivityCount->Ac3);
                        array_push($monthData1, $ActivityCount->Ac4);
                        array_push($monthData1, $ActivityCount->Ac5);
                        array_push($monthData1, $ActivityCount->Ac6);
                        array_push($monthData1, $ActivityCount->Ac7);
                        array_push($monthData1, $ActivityCount->Ac8);
                        array_push($monthData1, $ActivityCount->Ac9);
                        array_push($monthData1, $ActivityCount->Ac10);
                        array_push($monthData1, $ActivityCount->Ac11);
                        array_push($monthData1, $ActivityCount->Ac12);
                    }
                }
                $activityCount2 = $this->admin_model->getDashboardStatus3($this->nextYear, $Product_id, $condition);
                if (!empty($activityCount2)) {
                    foreach ($activityCount2 as $ActivityCount) {
                        array_push($monthData2, $ActivityCount->m1);
                        array_push($monthData2, $ActivityCount->m2);
                        array_push($monthData2, $ActivityCount->m3);
                        array_push($monthData2, $ActivityCount->m4);
                        array_push($monthData2, $ActivityCount->m5);
                        array_push($monthData2, $ActivityCount->m6);
                        array_push($monthData2, $ActivityCount->m7);
                        array_push($monthData2, $ActivityCount->m8);
                        array_push($monthData2, $ActivityCount->m9);
                        array_push($monthData2, $ActivityCount->m10);
                        array_push($monthData2, $ActivityCount->m11);
                        array_push($monthData2, $ActivityCount->m12);
                        array_push($monthData3, $ActivityCount->Ac1);
                        array_push($monthData3, $ActivityCount->Ac2);
                        array_push($monthData3, $ActivityCount->Ac3);
                        array_push($monthData3, $ActivityCount->Ac4);
                        array_push($monthData3, $ActivityCount->Ac5);
                        array_push($monthData3, $ActivityCount->Ac6);
                        array_push($monthData3, $ActivityCount->Ac7);
                        array_push($monthData3, $ActivityCount->Ac8);
                        array_push($monthData3, $ActivityCount->Ac9);
                        array_push($monthData3, $ActivityCount->Ac10);
                        array_push($monthData3, $ActivityCount->Ac11);
                        array_push($monthData3, $ActivityCount->Ac12);
                    }
                }
                ?>
                <div id="<?php echo $Product_id ?>" class="tab-pane fade in active">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-medkit"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Planned Rx</span>
                                <span class="info-box-number"><?php
                                    echo $Planned_count;
                                    ?></span>

                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div>
                    <div class="clearfix visible-sm-block"></div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="ion ion-ios-cart-outline"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Reported Rx</span>
                                <span class="info-box-number"><?php
                                    echo $Actual_count;
                                    ?></span>

                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="ion ion-ios-cart-outline"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Activities Planned</span>
                                <span class="info-box-number"><?php
                                    echo $Activity_count;
                                    ?></span>

                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="ion ion-ios-cart-outline"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Activities Reported</span>
                                <span class="info-box-number"><?php
                                    echo $Activity_report;
                                    ?></span>

                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <div id="container" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <div id="container2" >
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <script>
                $(function () {
                    $('#container').highcharts({
                        title: {
                            text: 'Prescription Trends',
                            x: -20 //center
                        },
                        xAxis: {
                            categories: <?php echo json_encode($xAxisData1) ?>
                        },
                        yAxis: {
                            plotLines: [{
                                    value: 0,
                                    width: 1,
                                    color: '#808080'
                                }]
                        },
                        credits: {
                            enabled: false,
                            text: 'Techvertica.com',
                            href: 'http://www.techvertica.com'
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle',
                            borderWidth: 0
                        },
                        series: [{
                                name: 'Planned Rx',
                                data: <?php echo json_encode($monthData, JSON_NUMERIC_CHECK) ?>
                            }, {
                                name: 'Actual Rx',
                                data: <?php echo json_encode($monthData1, JSON_NUMERIC_CHECK) ?>
                            }]
                    });
                    $('#container2').highcharts({
                        title: {
                            text: 'Activity Trends',
                            x: -20 //center
                        },
                        xAxis: {
                            categories: <?php echo json_encode($xAxisData1) ?>
                        },
                        yAxis: {
                            plotLines: [{
                                    value: 0,
                                    width: 1,
                                    color: '#808080'
                                }]
                        },
                        credits: {
                            enabled: false,
                            text: 'Techvertica.com',
                            href: 'http://www.techvertica.com'
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle',
                            borderWidth: 0
                        },
                        series: [{
                                name: 'Planned',
                                data: <?php echo json_encode($monthData2, JSON_NUMERIC_CHECK) ?>
                            }, {
                                name: 'Completed',
                                data: <?php echo json_encode($monthData3, JSON_NUMERIC_CHECK) ?>
                            }]
                    });
                });

            </script>
            <?php
            $count ++;
            break;
        }
    }
    ?>

</div>
</div>
<script>
    function getTabDetails(Product_id) {
        $("#loader").show();
        $.ajax({
            //Send request
            type: 'POST',
            data: {Product_Id: Product_id},
            url: '<?php echo site_url('Report/dashboardTab'); ?>',
            success: function (data) {
                $("#loader").hide();
                $(".tab-content").html(data);
            }
        });
    }

</script>
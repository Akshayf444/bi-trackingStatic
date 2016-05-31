<script src="<?php echo asset_url(); ?>js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo asset_url(); ?>js/exporting.js" type="text/javascript"></script>

<div class="row">
    <div class="col-lg-12">
        <ul align="center" class="nav nav-tabs " style="border-bottom: 0;padding-bottom: 10px;">
            <?php
            if (!empty($productlist)) {
                $count = 1;
                foreach ($productlist as $product) {

                    $dashboardDetails[$product->id] = array();
                    if (isset($_GET['Product_Id']) && $_GET['Product_Id'] > 0) {
                        ?>
                        <li class="<?php echo isset($_GET['Product_Id']) && $_GET['Product_Id'] == $product->id ? 'active' : ''; ?>"><a style="padding: 12px;" href="<?php echo site_url('User/dashboard?Product_Id=' . $product->id); ?>" ><?php echo $product->Brand_Name ?></a></li>
                    <?php } else { ?>
                        <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a style="padding: 12px;" href="<?php echo site_url('User/dashboard?Product_Id=' . $product->id); ?>" ><?php echo $product->Brand_Name ?></a></li>

                    <?php }
                    ?>
                    <?php
                    $count ++;
                }
            }
            ?>
        </ul>
    </div>
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
            if ($Product_id == 1){
                $this->Individual_Type = 'Hospital';
            }
            
            if (isset($this->Zone) && $this->Zone != '' && $this->Zone != '-1') {
                $condition[1] = "em.Zone = '" . $this->Zone . "'";
            }
            if (isset($this->Division) && $this->Division != '' && $this->Division != 'Both') {
                $condition[2] = "em.Division = '" . $this->Division . "'";
            }
            if (isset($this->VEEVA_Employee_ID) && $this->VEEVA_Employee_ID != '') {
                $condition[2] = "em.VEEVA_Employee_ID = '" . $this->VEEVA_Employee_ID . "'";
            }

            $activity_planned = $this->User_model->activity_planned($this->VEEVA_Employee_ID, $Product_id);
            $activitya_actual = $this->User_model->activity_actual($this->VEEVA_Employee_ID, $Product_id);
            $Activity_count += $activity_planned['activity_planned'];
            $Activity_report += $activitya_actual['activity_actual'];

            //// Get Target
            $target = $this->User_model->Rx_Target_month2($this->VEEVA_Employee_ID, $Product_id, $this->nextMonth);

            ///Get Rx_Actual
            $actualrx = $this->User_model->Actual_Rx_Count();
            ///Get Rx_Planned                
            $plannedrx = $this->User_model->Planned_Rx_Count();


            $Planned_count = isset($plannedrx['Planned_Rx']) ? $plannedrx['Planned_Rx'] : 0;
            $Actual_count = isset($actualrx['Actual_Rx']) ? $actualrx['Actual_Rx'] : 0;
            $target = isset($target['target']) && $target['Status'] == 'Submitted' ? $target['target'] : 0;

            /// KPI Calculation
            if (isset($target['target']) && $target['target'] > 0) {
                $kpi1 = ($actualrx['Actual_Rx'] / $target['target']) * 100;
            } else {
                $kpi1 = 0;
            }
            if ($activity_planned ['activity_planned'] > 0) {
                $kpi2 = ($activitya_actual['activity_actual'] / $activity_planned ['activity_planned']) * 100;
            } else {
                $kpi2 = 0;
            }

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


            ////Doctor Profiling
            $doctorCount = $this->Doctor_Model->CountDoctor($this->VEEVA_Employee_ID, $this->Individual_Type);
            $profileCount = $this->User_model->ProfilingCount($this->VEEVA_Employee_ID, $Product_id);
            $rxlabel = $Product_id == 1 ? 'Vials' : 'Rx';
            $hospital = $Product_id == 1 ? 'Hospital' : 'Doctor';
            if ($doctorCount["DoctorCount"] > 0) {
                $PROFILE = ($profileCount["profile_count"] / $doctorCount["DoctorCount"]) * 100;
            } else {
                $PROFILE = 0;
            }
            ?>
            <div id="<?php echo $Product_id ?>" class="row">
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="info-box bg-green">
                        <span class="info-box-icon"><i class="fa fa-bullseye"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Target Assigned</span>
                            <span class="info-box-number"><?php
                                echo $target;
                                ?></span>

                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="info-box bg-red">
                        <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Reported Rx</span>
                            <span class="info-box-number"><?php
                                echo $Actual_count;
                                ?></span>

                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="info-box bg-red">
                        <span class="info-box-icon"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Activities Planned</span>
                            <span class="info-box-number"><?php
                                echo $Activity_count;
                                ?></span>

                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="info-box bg-red">
                        <span class="info-box-icon"><i class="fa fa-files-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Activities Reported</span>
                            <span class="info-box-number"><?php
                                echo $Activity_report;
                                ?></span>

                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-xs-12 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <div id="container1" class="col-xs-11"  >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-12 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <div id="container2" class="col-xs-11"    >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="<?php echo asset_url() ?>js/knob.js" type="text/javascript"></script>
            <script src="<?php echo asset_url() ?>js/jquery.knob.js" type="text/javascript"></script>
            <link href="<?php echo asset_url() ?>css/style.css" rel="stylesheet" type="text/css"/>
            <div class="row">
                <div class="col-lg-4 col-xs-12 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <div class="col-xs-8">
                                <h4>Profiling </h4><p>(Profiled / Total <?php echo $hospital; ?>) </p><label class="badge badge-success"><?php echo $profileCount["profile_count"] ?> / <?php echo $doctorCount["DoctorCount"] ?></label>
                            </div>
                            <div class="col-xs-4">
                                <input type="text" readonly="readonly" style="display: none" data-angleOffset=-125 data-angleArc=250 value="<?php echo $PROFILE; ?>" id="dial3">
                                <script>
                                    $("#dial3").knob({
                                        'change': function (v) {
                                            console.log(v);
                                        }
                                    });
                                </script>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-12 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <div class="col-xs-8">
                                <h4>KPI 1 </h4><p>(Reported Rx / Target) </p> <label class="badge label-danger"><?php echo $Actual_count ?> / <?php echo $target ?></label>
                            </div>
                            <div class="col-xs-4">
                                <input type="text" readonly="readonly" style="display: none"  data-angleOffset=-125 data-angleArc=250 value="<?php echo $kpi1; ?>" id="dial1">
                                <script>
                                    $("#dial1").knob({
                                        'change': function (v) {
                                            console.log(v);
                                        }
                                    });
                                </script>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-12 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <div class="col-xs-8">
                                <h4>KPI 2 </h4><p>(Doctor Engaged in Activity / Planned )</p> <label class="badge label-primary"><?php echo $Activity_report ?> / <?php echo $Activity_count ?></label>
                            </div>
                            <div class="col-xs-4">
                                <input type="text" readonly="readonly" style="display: none"  data-angleOffset=-125 data-angleArc=250 value="<?php echo $kpi2; ?>" id="dial2">
                                <script>
                                    $("#dial2").knob({
                                        'change': function (v) {
                                            console.log(v);
                                        }
                                    });
                                </script>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(function () {
                    $('#container1').highcharts({
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
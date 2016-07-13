<script src="<?php echo asset_url(); ?>js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo asset_url(); ?>js/exporting.js" type="text/javascript"></script>
<?php $this->load->view('User/ProductList', array('site_url' => 'User/viewDetail/' . $Doctor_Id . '/' . $Account_Name)); ?>
<?php
    $object = new stdClass();
    $object->id = $this->Product_Id;
    $productlist = array($object);
?>
<div class="tab-content">
    <?php
    if (!empty($productlist)) {
        $count = 1;

        foreach ($productlist as $product) {
            $Product_id = $product->id;
            $this->Product_Id = $Product_id;
            //echo $Product_id;
            $Planned_count = 0;
            $Actual_count = 0;
            $Activity_count = 0;
            $Activity_report = 0;
            $condition = array();
            // $condition[0] = "rp.Product_id = " . $Product_id;

            $condition[1] = "Doctor_Id  = '" . $Doctor_Id . "'";
            $condition[2] = "VEEVA_Employee_ID = '" . $this->VEEVA_Employee_ID . "'";

            $xAxisData1 = array();
            $monthData = array();
            $monthData1 = array();
            $monthData2 = array();
            $monthData3 = array();

            for ($i = 1; $i <= 12; $i++) {
                $monthname = date('M', mktime(0, 0, 0, $i, 1, date('Y'))); //Month Name
                array_push($xAxisData1, $monthname);
            }

            $activityCount = $this->admin_model->getDoctorStatus2($this->nextYear, $Product_id, $condition);
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
            $activityCount2 = $this->admin_model->getDoctorStatus3($this->nextYear, $Product_id, $condition);
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
        </div>
        <script src="<?php echo asset_url() ?>js/knob.js" type="text/javascript"></script>
        <script src="<?php echo asset_url() ?>js/jquery.knob.js" type="text/javascript"></script>
        <link href="<?php echo asset_url() ?>css/style.css" rel="stylesheet" type="text/css"/>

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
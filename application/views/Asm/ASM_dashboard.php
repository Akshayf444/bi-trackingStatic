<script src="<?php echo asset_url() ?>js/knob.js" type="text/javascript"></script>
<script src="<?php echo asset_url() ?>js/jquery.knob.js" type="text/javascript"></script>
<link href="<?php echo asset_url() ?>css/style.css" rel="stylesheet" type="text/css"/>

<!--
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="panel panel-default ">
        <div class="panel-body " style="text-align: center">
            <div class="col-md-4 col-sm-4">
                <a class="btn btn-block" style="    border: 1px solid;background-color: " onclick="window.location = '<?php echo site_url('ASM/target'); ?>';" >
                    Assign Target
                </a>
            </div>
            <div class="col-md-4 col-sm-4">
                <a class="btn btn-block" style="    border: 1px solid;background-color: " onclick="window.location = '<?php echo site_url('ASM/Planning'); ?>';" > Approve Planning </a>
            </div>
            <div class="col-md-4 col-sm-4">
                <a class="btn btn-block" style="    border: 1px solid;background-color: " onclick="window.location = '<?php echo site_url('ASM/reporting'); ?>';" > Approve Reporting </a>
            </div>


        </div>
    </div>
</div>-->
<?php $dashboardDetails = array(); ?>
<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <ul align="center" class="nav nav-tabs ">
            <?php
            if (!empty($productlist2)) {
                $count = 1;
                foreach ($productlist2 as $product) {

                    $dashboardDetails[$product->id] = array();
                    if (isset($_GET['Product_Id']) && $_GET['Product_Id'] > 0) {
                        ?>
                        <li class="<?php echo isset($_GET['Product_Id']) && $_GET['Product_Id'] == $product->id ? 'active' : ''; ?>"><a style="padding: 12px;" href="<?php echo site_url('ASM/dashboard?Product_Id=' . $product->id); ?>" ><?php echo $product->Brand_Name ?></a></li>

                    <?php } else { ?>
                        <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a style="padding: 12px;" href="<?php echo site_url('ASM/dashboard?Product_Id=' . $product->id); ?>" ><?php echo $product->Brand_Name ?></a></li>

                    <?php }
                    ?>
                    <?php
                    $count ++;
                }

                if (isset($this->Division) && strtoupper($this->Division) == strtoupper('Thrombi')) {
                    ?> 

                    <li>  <a style="padding:12px"     onclick="window.location = '<?php echo site_url('ASM/reporting_info'); ?>';" > Actilyse Dashboard </a></li>

                    <?php
                }
            }
            ?>
        </ul>
    </div>
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="margin-top: 10px">
        <?php if (!empty($productlist)) { ?>

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
                    $ApproveCount = 0;
                    $UnApproveCount = 0;
                    $Pending = 0;
                    $Submitted = 0;
                    foreach ($productlist as $product) {
                        if ($product->id == 1) {
                            $doctor = 'Hospital';
                            $rx = 'Vials';
                        } else {
                            $doctor = 'Doctors';
                            $rx = 'Rx';
                        }
                        ?>

                        <div id="<?php echo $product->id ?>11" class="tab-pane fade <?php echo isset($count) && $count == 1 ? 'in active' : ''; ?>">

                            <?php
                            $Status = $this->User_model->report($this->VEEVA_Employee_ID, $this->nextMonth, $this->nextYear, $product->id);
                            if (!empty($Status)) {
                                $nod = 0;
                                $profiled = 0;
                                $target = 0;
                                $planned = 0;
                                $actual = 0;
                                $dplanned = 0;
                                $actplaned = 0;
                                $kpi1 = 0;
                                $kpi2 = 0;
                                $actualLastMonth = 0;
                                $lastMonth = $this->User_model->calculateMonth($this->nextMonth, 1);
                                $lastYear = $this->User_model->calculateYear($this->nextMonth, 1);

                                foreach ($Status as $value) {
                                    $LastMonthRx = $this->User_model->product_detail($value->VEEVA_Employee_ID, $product->id, $lastMonth, $lastYear);
                                    $currentMonthRx = $this->User_model->product_detail($value->VEEVA_Employee_ID, $product->id, $this->nextMonth, $this->nextYear);
                                    if ($value->Target_New_Rxn_for_the_month > 0) {
                                        $kpi1 = ($currentMonthRx['Actual_Rx'] / $value->Target_New_Rxn_for_the_month) * 100;
                                    } else {
                                        $kpi1 = 0;
                                    }
                                    if ($value->No_of_Doctors_planned > 0) {
                                        $kpi2 = ($value->checkk / $value->No_of_Doctors_planned) * 100;
                                    } else {
                                        $kpi2 = 0;
                                    }

                                    $dashboardDetails[$product->id][$value->VEEVA_Employee_ID] = array(
                                        $value->Full_Name,
                                        $value->No_of_Doctors,
                                        $value->No_of_Doctors_profiled,
                                        $value->Target_New_Rxn_for_the_month,
                                        $value->Planned_New_Rxn,
                                        $currentMonthRx['Actual_Rx'],
                                        $value->No_of_Doctors_planned,
                                        $value->checkk,
                                        $LastMonthRx['Actual_Rx'],
                                        $kpi1,
                                        $kpi2
                                    );
                                    $profiled += $value->No_of_Doctors_profiled;
                                    $target += $value->Target_New_Rxn_for_the_month;
                                    $planned += $value->Planned_New_Rxn;
                                    $nod += $value->No_of_Doctors;
                                    $actplaned += $value->checkk;
                                    $dplanned+= $value->No_of_Doctors_planned;
                                    $actual += $currentMonthRx['Actual_Rx'];
                                    $actualLastMonth += $LastMonthRx['Actual_Rx'];
                                }
                                $dashboardDetails[$product->id]['Total'] = array(
                                    'Total', $nod, $profiled, $target, $planned, $actual, $dplanned, $actplaned, $actualLastMonth
                                );
                            }
                            ?>
                            <div class="row">

                                <div class="col-lg-6 col-xs-12 col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-body ">
                                            <div class="col-xs-8">
                                                <h4>KPI 1 </h4><p>(Reported Rx / Target) </p> <label class="badge label-danger"><?php $kp1 = isset($target) && $target > 0 ? ($actual / $target) * 100 : 0; echo $actual ?> / <?php echo $target ?></label><label  class="badge">= <?php echo number_format($kp1,2)." %";?></label>
                                            </div>
                                            <div class="col-xs-4">
                                                <input type="text" readonly="readonly" style="display: none"  data-angleOffset=-125 data-angleArc=250 value="<?php echo $kp1; ?>" id="dial1">
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
                                <div class="col-lg-6 col-xs-12 col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-body ">
                                            <div class="col-xs-8">
                                                <h4>KPI 2 </h4><p>(Doctor Engaged in Activity / Planned )</p> <label class="badge label-primary"><?php $kp2 = isset($dplanned) && $dplanned > 0 ? ($actplaned / $dplanned) * 100 : 0; echo $actplaned ?> / <?php echo $dplanned ?></label><label class="badge">= <?php echo number_format($kp2,2)." %";?></label>
                                            </div>
                                            <div class="col-xs-4">
                                                <input type="text" readonly="readonly" style="display: none"  data-angleOffset=-125 data-angleArc=250 value="<?php echo $kp2; ?>" id="dial2">
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

                        </div>


                        <?php
                        $count ++;
                        $kpi1 = 0;
                        $kpi2 = 0;
                    }
                }
                ?>
            </div>

        <?php } //var_dump($dashboardDetails);    ?>

    </div>
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php if (!empty($productlist)) { ?>
            <div class="panel panel-default"> 
                <div class="panel-heading">  Status  </div>
                <div class="panel-body">

                    <div class="tab-content">
                        <?php
                        if (!empty($productlist)) {
                            $count = 1;
                            foreach ($productlist as $product) {
                                if ($product->id == 1) {
                                    $doctor = 'Hospital';
                                    $rx = 'Vials';
                                } else {
                                    $doctor = 'Doctors';
                                    $rx = 'Rx';
                                }
                                ?>

                                <div id="<?php echo $product->id ?>2" class="tab-pane fade <?php echo isset($count) && $count == 1 ? 'in active' : ''; ?>">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 20%">BDM Name</th>
                                            <th>No. of <?php echo $doctor; ?> in MCL</th>
                                            <th>No. of <?php echo $doctor; ?> profiled</th>
                                            <th>Target New <?php echo $rx; ?> for the month</th>
                                            <th>Planned New <?php echo $rx; ?> for the month</th>
                                            <th>Achieved New <?php echo $rx; ?> for the month to date</th>
                                            <th>No. of <?php echo $doctor; ?> planned for activities</th>
                                            <th>Achieved No. of <?php echo $doctor; ?> planned for activities</th>
                                            <th>Total Actual <?php echo $rx; ?> Generated In Last Month</th>
                                        </tr>
                                        <?php
                                        if (!empty($dashboardDetails)) {

                                            foreach ($dashboardDetails[$product->id] as $value) {
                                                echo '<tr>';
                                                //foreach ($value as $detail) {
                                                echo '<td>' . $value[0] . '</td>';
                                                echo '<td>' . $value[1] . '</td>';
                                                echo '<td>' . $value[2] . '</td>';
                                                echo '<td>' . $value[3] . '</td>';
                                                echo '<td>' . $value[4] . '</td>';
                                                echo '<td>' . $value[5] . '</td>';
                                                echo '<td>' . $value[6] . '</td>';
                                                echo '<td>' . $value[7] . '</td>';
                                                echo '<td>' . $value[8] . '</td>';
                                                //}
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </table>
                                </div>

                                <?php
                                $count ++;
                                unset($dashboardDetails[$product->id]['Total']);
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>  
        <?php } ?>
    </div>
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php if (!empty($productlist)) { ?>
            <div class="panel panel-default"> 
                <div class="panel-heading"> KPI Status  </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <?php
                        if (!empty($productlist)) {
                            $count = 1;
                            foreach ($productlist as $product) {
                                ?>

                                <div id="<?php echo $product->id ?>1" class="tab-pane fade <?php echo isset($count) && $count == 1 ? 'in active' : ''; ?>">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 20%">BDM Name</th>
                                            <th>KPI1 Prescription</th>
                                            <th>KPI2 Activity</th>

                                        </tr>
                                        <?php
                                        $kpi1 = 0;
                                        $kpi2 = 0;
                                        if (!empty($dashboardDetails)) {
                                            foreach ($dashboardDetails[$product->id] as $value) {
                                                $name = $value[0];
                                                echo '<tr><td style="width: 20%">' . $name . '</td>'
                                                . '<td>' . $value[9] . '</td>'
                                                . '<td>' . $value[10] . '</td></tr>';
                                                $kpi1 += $value[9];
                                                $kpi2 += $value[10];
                                            }
                                        }

                                        echo '<tr><th>Total</th><td>' . $kpi1 . '</td><td>' . $kpi2 . '</td></tr>';
                                        ?>
                                    </table>
                                </div>
                                <?php
                                $count ++;
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>  
        <?php } ?>
    </div>
</div>
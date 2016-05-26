<script src="<?php echo asset_url() ?>js/knob.js" type="text/javascript"></script>
<script src="<?php echo asset_url() ?>js/jquery.knob.js" type="text/javascript"></script>
<link href="<?php echo asset_url() ?>css/style.css" rel="stylesheet" type="text/css"/>
<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        border-top: 0px solid #dddddd;
    }
    audio, canvas, progress, video {
        height: 90px;
        margin-left: 208px;
        margin-top: 2px;
        margin-bottom: 30px;
    }
    audio,#kp1, progress, video {
        height: 90px;
        margin-left: 208px;
        margin-top: -12px;
        margin-bottom: -22px;
    }
    a{
        font-weight: bold;
    }
</style>
<?php $dashboardDetails = array(); ?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="panel col-lg-2 col-md-2"><a class="btn btn-block" href="<?php echo site_url('Report/dashboard'); ?>">View Reports</a></div>
    <br/>
    <br/>
    <br/>

    <?php if (!empty($productlist)) { ?>
        <div class="panel panel-default"> 
            <div class="panel-heading"> Achievement Status  </div>
            <div class="panel-body">

                <ul align="center" class="nav nav-tabs ">
                    <?php
                    if (!empty($productlist)) {
                        $count = 1;
                        foreach ($productlist as $product) {
                            $dashboardDetails[$product->id] = array();
                            ?>
                            <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a data-toggle="tab" style="    padding: 12px;" href="#<?php echo $product->id ?>11"><?php echo $product->Brand_Name ?></a></li>
                            <?php
                            $count ++;
                        }
                    }
                    ?>
                </ul>

                <div class="tab-content">
                    <?php
                    if (!empty($productlist)) {
                        $count = 1;
                        $ApproveCount = 0;
                        $UnApproveCount = 0;
                        $Pending = 0;
                        $Submitted = 0;
                        foreach ($productlist as $product) {
                            ?>

                            <div id="<?php echo $product->id ?>11" class="tab-pane fade <?php echo isset($count) && $count == 1 ? 'in active' : ''; ?>">

                                <?php
                                $Status = $this->User_model->Zsmreport($this->VEEVA_Employee_ID, $this->nextMonth, $this->nextYear, $product->id);
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
                                    $lastMonth = date('m', strtotime('-1 month'));
                                    $lastYear = date('Y', strtotime('-1 month'));
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
                                <div class="col-lg-5 col-md-5 col-xs-5">
                                    <div class="demo"  >        

                                        <input class="knob" id="kp3" readonly="" style="display: none" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="<?php
                                        if ($target > 0) {
                                            echo ($actual / $target) * 100;
                                        } else {
                                            echo 0;
                                        }
                                        ?>">
                                        <span style="margin-left: 116px;position: absolute;margin-top: -50px;"><?php
                                            if ($target > 0) {
                                                echo number_format(($actual / $target) * 100, 2, '.', '');
                                            }
                                            ?>%</span>
                                        <span style="margin-left: 70px;position: absolute;margin-top: -35px">Prescription </span>
                                        <span style="margin-left: 66px;position: absolute;margin-top: -17px;"> Actual / Target</span>

                                    </div>
                                </div>

                                <div class="col-lg-5 col-md-5 col-xs-5">
                                    <div class="demo" >       

                                        <input class="knob" id="kp4"  readonly=""style="display: none"  data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="<?php
                                        if ($dplanned > 0) {
                                            echo ($actplaned / $dplanned) * 100;
                                        } else {
                                            echo 0;
                                        }
                                        ?>">
                                        <span style="margin-left: 116px;position: absolute;margin-top: -50px;"><?php
                                            if ($dplanned > 0) {
                                                echo number_format(($actplaned / $dplanned) * 100, 2, '.', '');
                                            }
                                            ?>%</span>
                                        <span style="margin-left: 58px;position: absolute;margin-top: -35px"> <?php
                                            if ($this->Product_Id == 1) {
                                                echo "Hospital";
                                            } else {
                                                echo "Doctor";
                                            }
                                            ?> Engaged in Activity</span>
                                        <span style="margin-left: 58px;position: absolute;margin-top: -17px;"> Engaged in Activity / Planned</span>
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
            </div>
        </div>  

    <?php } //var_dump($dashboardDetails);   ?>

</div>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <?php if (!empty($productlist)) { ?>
        <div class="panel panel-default"> 
            <div class="panel-heading">  Status  </div>
            <div class="panel-body">

                <ul align="center" class="nav nav-tabs ">
                    <?php
                    if (!empty($productlist)) {
                        $count = 1;
                        foreach ($productlist as $product) {
                            ?>
                            <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a data-toggle="tab" style="    padding: 12px;" href="#<?php echo $product->id ?>2"><?php echo $product->Brand_Name ?></a></li>
                            <?php
                            $count ++;
                        }
                    }
                    ?>
                </ul>

                <div class="tab-content">
                    <?php
                    if (!empty($productlist)) {
                        $count = 1;
                        foreach ($productlist as $product) {
                            ?>

                            <div id="<?php echo $product->id ?>2" class="tab-pane fade <?php echo isset($count) && $count == 1 ? 'in active' : ''; ?>">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 20%">BDM Name</th>
                                        <th>No. of Doctors in MCL</th>
                                        <th>No. of Doctors profiled</th>
                                        <th>Target New Rxn for the month</th>
                                        <th>Planned New Rxn for the month</th>
                                        <th>Achieved New Rxn for the month to date</th>
                                        <th>No. of Doctors planned for activities</th>
                                        <th>Achieved No. of Doctors planned for activities</th>
                                        <th>Total Actual Rx Generated In Last Month</th>
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

                <ul align="center" class="nav nav-tabs ">
                    <?php
                    if (!empty($productlist)) {
                        $count = 1;
                        foreach ($productlist as $product) {
                            ?>
                            <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a data-toggle="tab" style="    padding: 12px;" href="#<?php echo $product->id ?>1"><?php echo $product->Brand_Name ?></a></li>
                            <?php
                            $count ++;
                        }
                    }
                    ?>
                </ul>

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
                                        <th>KPI1</th>
                                        <th>KPI2</th>

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
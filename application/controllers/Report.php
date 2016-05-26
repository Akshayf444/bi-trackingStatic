<?php
include APPPATH . 'libraries/ExcelExport.php';

class Report extends MY_Controller {

    public $nextMonth;
    public $nextYear;
    public $DivisionDropdown;
    public $ZoneDropdown;
    public $Errors = array();

    public function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('User_model');
        $this->load->model('Master_Model');
        $this->nextMonth = date('m');
        $this->nextYear = date('Y');

        if ($this->Designation == 'ZSM') {
            $this->ZoneDropdown = 'disabled';
        }
        if ($this->Designation == 'Marketing' || $this->Designation == 'NSM' || $this->Designation == 'ZSM') {
            $this->DivisionDropdown = 'disabled';
        }

        //$this->output->enable_profiler(TRUE);
    }

    public function ZSMdashboard() {
        $this->load->model('Master_Model');
        if ($this->is_logged_in('ZSM')) {
            $result2 = $this->Master_Model->BrandList($this->session->userdata('Division'));
            $data['productlist2'] = $result2;
            $result2 = array_shift($result2);
            $object = new stdClass();
            $object->id = $result2->id;
            $object->Brand_Name = $result2->Brand_Name;
            $productlist = array($object);
            $data['productlist'] = $productlist;

            $data = array('title' => 'Main', 'content' => 'ho/ZSMdashboard', 'view_data' => $data, 'page_title' => 'KPI Dashboard');
            $this->load->view('template4', $data);
        } else {
            $this->logout();
        }
    }

    public function getZSMdashboard() {
        if ($this->input->get('Product_Id')) {
            $object = new stdClass();
            $object->id = $this->input->get('Product_Id');
            $productlist = array($object);
            ?>
            <script src="<?php echo asset_url() ?>js/knob.js" type="text/javascript"></script>
            <script src="<?php echo asset_url() ?>js/jquery.knob.js" type="text/javascript"></script>
            <link href="<?php echo asset_url() ?>css/style.css" rel="stylesheet" type="text/css"/>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <?php if (!empty($productlist)) { ?>
                    <div class="panel panel-default"> 
                        <div class="panel-heading"> Achievement Status  </div>
                        <div class="panel-body">
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
                                                    <span style="margin-left: 70px;position: absolute;margin-top: -35px"><?php echo $rx; ?> </span>
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
                                                        echo $doctor;
                                                        ?> </span>
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

                <?php } //var_dump($dashboardDetails);        ?>
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
                                                    <th style="width: 20%">ASM Name</th>
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
                                                    <th style="width: 20%">ASM Name</th>
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
            <?php
        }
    }

    public function dashboard() {
        $this->load->model('Master_Model');
        $Planned_count = 0;
        $Actual_count = 0;
        $Activity_count = 0;
        $Activity_report = 0;
        $conditions = array();
        if ($this->Designation == 'Marketing' || $this->Designation == 'NSM' || $this->Designation == 'ZSM') {
            if (isset($this->Zone) && $this->Zone != '' && $this->Zone != '-1') {
                $conditions[1] = "em.Zone = '" . $this->Zone . "'";
            }
            $conditions[0] = "em.Division = '" . $this->Division . "'";
            $data['productlist'] = $this->Master_Model->BrandList($this->Division);
        } else {
            $data['productlist'] = $this->admin_model->show_pro_list();
        }

        $data = array('title' => 'Dashboard', 'content' => 'ho/dashboard', 'page_title' => 'Dashboard', 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    function dailyTrendGraph() {
        $this->load->model('User_model');
        $initial_date1 = '2016-02-01';
        $initial_date2 = '2016-02-10';
        $end_date = $initial_date2;
        $start_date = $initial_date1;
        $date = " BETWEEN '" . $start_date . "'  AND '" . $end_date . " ' ";

        $Product_id = 4;
        $conditions = array();
        if ($this->Designation == 'Marketing' || $this->Designation == 'NSM' || $this->Designation == 'ZSM') {
            if (isset($this->Zone) && $this->Zone != '' && $this->Zone != '-1') {
                $conditions[1] = "em.Zone = '" . $this->Zone . "'";
            }
            $conditions[0] = "em.Division = '" . $this->Division . "'";
        }
        $dailyTrend = $this->User_model->dailyTrend($date, $this->nextYear, $Product_id, $conditions, $start_date, $end_date);

        $xAxisData = array();
        $yAxisData = array();
        while (strtotime($start_date) <= strtotime($end_date)) {
            ${'d' . $start_date} = 0;
            array_push($xAxisData, date('d-m-Y', strtotime($start_date)));
            $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
        }

        if (!empty($dailyTrend)) {


//re-initialising Start and End Date
            foreach ($dailyTrend as $row) {
                $end_date = $initial_date2;
                $start_date = $initial_date1;
                while (strtotime($start_date) <= strtotime($end_date)) {
                    ${'d' . $start_date} = ${'d' . $start_date} + $row->{'d' . date('Ymd', strtotime($start_date))};
                    $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
                }
            }

//re-initialising Start and End Date
            $end_date = $initial_date2;
            $start_date = $initial_date1;
            while (strtotime($start_date) <= strtotime($end_date)) {
                array_push($yAxisData, ${'d' . $start_date});
                $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
            }
        }
        ?>
        <script>
            $(function () {
                $('#container').highcharts({
                    title: {
                        text: 'Prescription Trends',
                        x: -20 //center
                    },
                    xAxis: {
                        categories: <?php echo json_encode($xAxisData) ?>
                    },
                    yAxis: {
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                            name: 'Planned Rx',
                            data: <?php echo json_encode($yAxisData, JSON_NUMERIC_CHECK) ?>
                        }, {
                            name: 'Actual Rx',
                            data: <?php echo json_encode($monthData1, JSON_NUMERIC_CHECK) ?>
                        }]
                });
            });

        </script>
        <?php
    }

    function dailyTrend() {
        $perpage = 5000;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        //$this->load->model('Master_Model');
        //$this->load->model('User_model');

        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        $condition = array();
        $product = 0;
        $start_date = '';
        $data['Territory'] = '';
        $TerritoryConditions = array();
        array_push($TerritoryConditions, "em.Profile = 'BDM' ");

        ///Initial Product List
        $productlist = $this->admin_model->show_pro_list();
        $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));

        ///Zone Conditions
        if ($this->input->get('Zone') != '') {
            $condition[0] = "em.Zone = '" . $this->input->get('Zone') . "'";
            if ($this->Designation == 'ZSM') {
                $condition[0] = "em.Zone = '" . $this->Zone . "'";
            }
            array_push($TerritoryConditions, "em.Zone = '" . $this->input->get('Zone') . "'");
            $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $this->input->get('Zone'));
        }

        ///Territory Conditions
        if ($this->input->get('Territory') && $this->input->get('Territory') != '') {
            $condition[3] = "em.Territory = '" . $this->input->get('Territory') . "'";
        }

        ///Division Conditions
        if ($this->input->get('Division') != '') {
            $productlist = $this->Master_Model->BrandList($this->input->get('Division'));
            $Division = ($this->Designation == 'Marketing' || $this->Designation == 'NSM' || $this->Designation == 'ZSM' ) ? $this->Division : $this->input->get('Division');
            $condition[1] = "em.Division = '" . $Division . "'";
            array_push($TerritoryConditions, "em.Division = '" . $Division . "'");
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name');
        }

        ///ProductList Conditions
        if ($this->input->get('Product') != '' && $this->input->get('Product') != 'All') {
            $product = $this->input->get('Product');
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));
        } else {
            array_push($this->Errors, 'Please Select Product');
        }


        ///Fetch Daily trend Data
        if ($this->input->get('Start_date') != '' && $this->input->get('End_date') != '') {
            $start_date = " BETWEEN '" . $this->input->get('Start_date') . "'  AND '" . $this->input->get('End_date') . " ' ";

            if ((int) $product > 0) {
                $ProfileCount = $this->User_model->countDailyTrend($this->input->get('Start_date'), $this->input->get('End_date'), $product, $this->nextYear, $condition);
                if (isset($ProfileCount->PlanningCount)) {
                    $data['total_pages'] = ceil($ProfileCount->PlanningCount / $perpage);
                    $offset = ($page - 1) * $perpage;
                }
                $data['result'] = $this->User_model->dailyTrend($start_date, $this->nextYear, $product, $condition, $this->input->get('Start_date'), $this->input->get('End_date'), $perpage, $offset);

                //// EXPORT TO EXCEL ARRAY
                $array = json_decode(json_encode($data['result']), true, JSON_NUMERIC_CHECK);
                $fields = array('Zone', 'Territory', 'BDM Name', 'BDM Code', 'Doctor Code', 'Doctor Name', 'Product Name', 'Activity Planned', 'Activity Completed', 'Rx Planned');
                $start_date2 = $this->input->get('Start_date');
                $end_date = $this->input->get('End_date');
                while (strtotime($start_date2) <= strtotime($end_date)) {
                    array_push($fields, date('Y-m-d', strtotime($start_date2)) . "");
                    $start_date2 = date("Y-m-d", strtotime("+1 day", strtotime($start_date2)));
                }

                if ($this->input->get('Export') == 'Export') {
                    ExportToExcel($array, 'DailyTrend', $fields);
                }
            }
        } else {
            array_push($this->Errors, 'Please Select Start Date And End Date');
        }

        ///Generate Territory Dropdown
        $Territory = $this->User_model->getTerritory1($TerritoryConditions);
        $data['Territory'] = $this->Master_Model->generateDropdown($Territory, 'id', 'Territory', $this->input->get('Territory'));

        ///Display Errors
        if (!empty($this->Errors)) {
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert(join(".<br/>", array_unique($this->Errors))), 'danger');
        }

        ///Load Final View
        $data = array('title' => 'Daily Trend', 'content' => 'ho/dailyTrend', 'page_title' => 'Daily Rx Trend', 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    function monthlyTrend() {
        $offset = 0;
        $perpage = 5000;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        $TerritoryConditions = array();
        $product = 0;
        $data['Territory'] = '';
        $data['result'] = NULL;

        ///Initial Product Dropdown
        $productlist = $this->admin_model->show_pro_list();
        $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));


        array_push($TerritoryConditions, "em.Profile = 'BDM' ");

        $condition = array();
        if ($this->input->get('Zone') != '') {
            $Zone = $this->Designation == 'ZSM' ? $this->Zone : $this->input->get('Zone');
            $condition[0] = "em.Zone = '" . $Zone . "'";
            $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $this->input->get('Zone'));
            array_push($TerritoryConditions, "em.Zone = '" . $this->input->get('Zone') . "'");
        }

        if ($this->input->get('Division') != '') {
            $productlist = $this->Master_Model->BrandList($this->input->get('Division'));
            $Division = ($this->Designation == 'Marketing' || $this->Designation == 'NSM' || $this->Designation == 'ZSM' ) ? $this->Division : $this->input->get('Division');
            $condition[1] = "em.Division = '" . $Division . "'";
            array_push($TerritoryConditions, "em.Division = '" . $Division . "'");
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));
        }

        if ($this->input->get('Territory') && $this->input->get('Territory') != '') {
            $condition[3] = "em.Territory = '" . $this->input->get('Territory') . "'";
        }

        ///Fetch Monthly Trend Data
        if ($this->input->get('Product') != '' && $this->input->get('Product') != 'All') {
            $product = $this->input->get('Product');
            $ProfileCount = $this->User_model->countMonthlyTrend($product, $this->nextYear, $condition);
            //var_dump($ProfileCount);
            if (isset($ProfileCount->PlanningCount)) {
                $data['total_pages'] = ceil($ProfileCount->PlanningCount / $perpage);
                $offset = ($page - 1) * $perpage;
            }
            $data['result'] = $this->User_model->monthlyTrend2(1, $this->nextYear, $this->input->get('Product'), $condition, $perpage, $offset);
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));
        } elseif ($this->input->get('Product') == 'All') {
            array_push($this->Errors, 'Please Select Product');
            //$data['result'] = $this->User_model->monthlyTrend2(1, $this->nextYear, $this->input->get('Product'), $condition);
        } else {
            array_push($this->Errors, 'Please Select Product');
        }

        ///Generate Territory Dropdown
        $Territory = $this->User_model->getTerritory1($TerritoryConditions);
        $data['Territory'] = $this->Master_Model->generateDropdown($Territory, 'id', 'Territory', $this->input->get('Territory'));

        ///Display Errors
        if (!empty($this->Errors)) {
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert(join(".<br/>", array_unique($this->Errors))), 'danger');
        }

        ///EXPORT TO EXCEL ARRAY(Converting From Array To Object)
        $array = json_decode(json_encode($data['result']), true, JSON_NUMERIC_CHECK);
        $fields = array('Zone', 'Territory', 'BDM Name', 'BDM Code', 'Doctor Code', 'Doctor Name', 'Product Name', 'Activity Planned', 'Activity Completed', 'Rx Planned');
        for ($m = 1; $m <= 12; $m++) {
            $month = date('M', mktime(0, 0, 0, $m, 1, date('Y')));
            array_push($fields, $month);
        }
        if ($this->input->get('Export') == 'Export') {
            ExportToExcel($array, 'MonthlyTrend', $fields);
        }
        $data = array('title' => 'Monthly Trend', 'content' => 'ho/monthlyTrend', 'page_title' => 'Monthly Rx Trend', 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    function dailyTrend2() {
        $this->load->model('User_model');
        $this->User_model->dailyTrend2('2016-02-01', '2016-02-10', '');
    }

    function diabetesTrend() {
        //$this->load->model('Master_Model');
        //$this->load->model('User_model');
        $condition = array();
        $Errors = array();
        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        $start_date = '';
        $end_date = '';
        $data['result'] = NULL;
        if ($this->input->get('Zone')) {
            $Zone = ($this->Designation == 'ZSM') ? $this->Zone : $this->input->get('Zone');
            $condition[0] = "em.Zone = '" . $Zone . "'";
            $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $this->input->get('Zone'));
            $Territory = $this->User_model->getTerritory1(array("em.Zone = '" . $this->input->get('Zone') . "'", "em.Division = 'Diabetes'", "em.Profile = 'BDM' "));
            $data['Territory'] = $this->Master_Model->generateDropdown($Territory, 'id', 'Territory');
        }

        if ($this->input->get('Territory') && $this->input->get('Territory') != '') {
            $condition[1] = "em.Territory = '" . $this->input->get('Territory') . "'";
            $data['Territory'] = $this->Master_Model->generateDropdown($Territory, 'id', 'Territory', $this->input->get('Territory'));
        }

        if ($this->input->get('Start_date') != '' && $this->input->get('End_date') != '') {
            $start_date = $this->input->get('Start_date');
            $end_date = $this->input->get('End_date');
            $data['result'] = $this->User_model->DivisionWise($this->nextYear, '4,5,6', $condition, $start_date, $end_date);
        } else {
            array_push($Errors, 'Please Select Start Date And End Date');
        }

        if (!empty($Errors)) {
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert(join(".<br/>", array_unique($Errors))), 'danger');
        }
        $array = json_decode(json_encode($data['result']), true, JSON_NUMERIC_CHECK);
        $fields = array('Zone', 'Territory', 'BDM Name', 'BDM Code', 'Category Rxs for DPPIV', 'Category Rxs for SGLT2', 'Rxs planned-Jardiance', 'Rxs achieved - Jardiance', 'Rxs planned-Trajenta', 'Rxs achieved-Trajenta', 'Rxs planned-Trajenta Duo', 'Rxs achieved - Trajenta Duo');

        if ($this->input->get('Export') == 'Export') {
            ExportToExcel($array, 'DiabetesTrend', $fields);
        }
        $data = array('title' => 'Division Trend', 'content' => 'ho/DivisionTrend', 'page_title' => 'Diabetes Report', 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    function ThrombiTrend() {
        $Errors = array();
        $this->load->model('Master_Model');
        $this->load->model('User_model');
        $condition = array();
        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        $start_date = '';
        $end_date = '';
        $data['result'] = array();
        if ($this->input->get('Zone')) {
            $Zone = ($this->Designation == 'ZSM') ? $this->Zone : $this->input->get('Zone');
            $condition[0] = "em.Zone = '" . $Zone . "'";
            $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $this->input->get('Zone'));
            $Territory = $this->User_model->getTerritory1(array("em.Zone = '" . $this->input->get('Zone') . "'", "em.Division = 'ThromBI'", "em.Profile = 'BDM' "));
            $data['Territory'] = $this->Master_Model->generateDropdown($Territory, 'id', 'Territory');
        }

        if ($this->input->get('Territory') && $this->input->get('Territory') != '') {
            $condition[1] = "em.Territory = '" . $this->input->get('Territory') . "'";
            $data['Territory'] = $this->Master_Model->generateDropdown($Territory, 'id', 'Territory', $this->input->get('Territory'));
        }

        if ($this->input->get('Start_date') != '' && $this->input->get('End_date') != '') {
            $start_date = $this->input->get('Start_date');
            $end_date = $this->input->get('End_date');
            $data['result'] = $this->User_model->DivisionWise2($this->nextYear, '1,2,3', $condition, $start_date, $end_date);
        } else {
            array_push($Errors, 'Please Select Start Date And End Date');
        }

        if (!empty($Errors)) {
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert(join(".<br/>", array_unique($Errors))), 'danger');
        }

        $fields = array('Zone', 'Territory', 'BDM Name', 'BDM Code', 'Stroke/AIS patients', 'Category Rxs for NOAC', 'Category Rxs for 3rd Gen thrombolytic', 'Vials planned for Actilyse', 'Vials achieved - Actilyse', 'Rxs planned for Pradaxa', 'Rxs achieved- Pradaxa', 'Rxs planned for Metalyse', 'Rxs achieved - Metalyse');

        $array = json_decode(json_encode($data['result']), true, JSON_NUMERIC_CHECK);
        if ($this->input->get('Export') == 'Export') {
            ExportToExcel($array, 'ThrombiTrend', $fields);
        }

        $data = array('title' => 'Division Trend', 'content' => 'ho/DivisionTrend2', 'page_title' => 'Thrombi Report', 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    function ActivityTrend() {
        $this->load->model('Master_Model');
        $this->load->model('User_model');
        $start_date = '';
        $end_date = '';
        $Product_id = 0;
        $condition = array();
        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        $data['result'] = NULL;
        $data['Territory'] = '';
        $TerritoryConditions = array();
        array_push($TerritoryConditions, "em.Profile = 'BDM' ");

        ///Initial Product List Dropdown
        $productlist = $this->admin_model->show_pro_list();
        $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));

        if ($this->input->get('Zone') != '') {
            $Zone = $this->Designation == 'ZSM' ? $this->Zone : $this->input->get('Zone');
            $condition[0] = "em.Zone = '" . $Zone . "'";
            $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $this->input->get('Zone'));
            array_push($TerritoryConditions, "em.Zone = '" . $this->input->get('Zone') . "'");
        }

        if ($this->input->get('Territory') && $this->input->get('Territory') != '') {
            $condition[1] = "em.Territory = '" . $this->input->get('Territory') . "'";
        }

        if ($this->input->get('Division') != '') {
            $productlist = $this->Master_Model->BrandList($this->input->get('Division'));
            $division = ($this->Designation == 'Marketing' || $this->Designation == 'NSM' || $this->Designation == 'ZSM' ) ? $this->Division : $this->input->get('Division');
            $condition[2] = "em.Division = '" . $division . "'";
            array_push($TerritoryConditions, "em.Division = '" . $division . "'");
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name');
        }

        if ($this->input->get('Start_date') != '' && $this->input->get('End_date')) {
            $start_date = $this->input->get('Start_date');
            $end_date = $this->input->get('End_date');
        }

        if ($this->input->get('Product') != '' && $this->input->get('Product') != 'All') {
            $Product_id = $this->input->get('Product');
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));
            $data['result'] = $this->User_model->ActivityTrend($start_date, $end_date, $this->nextYear, $Product_id, $condition);
        } else {
            array_push($this->Errors, 'Please Select Product');
        }

        ///Generate Territory Dropdown
        $Territory = $this->User_model->getTerritory1($TerritoryConditions);
        $data['Territory'] = $this->Master_Model->generateDropdown($Territory, 'id', 'Territory', $this->input->get('Territory'));

        ///Display Errors
        if (!empty($this->Errors)) {
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert(join(".<br/>", array_unique($this->Errors))), 'danger');
        }

        $array = json_decode(json_encode($data['result']), true, JSON_NUMERIC_CHECK);
        $fields = array('Activity Name', 'Zone', 'Territory', 'BDM Name', 'BDM Code', 'Product Name', 'Number of Doctors Planned', 'Number of Doctors engaged', ' RXs Planned');
        for ($m = 1; $m <= 12; $m++) {
            $month = date('M', mktime(0, 0, 0, $m, 1, date('Y')));
            array_push($fields, $month);
        }

        if ($this->input->get('Export') == 'Export') {
            ExportToExcel($array, 'ActivityTrend', $fields);
        }

        $data = array('title' => 'Activity Trend', 'content' => 'ho/ActivityTrend', 'page_title' => 'Activity Report', 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    function LeaderBoard() {
        $this->load->model('Master_Model');
        $this->load->model('User_model');
        $Product_id = 0;
        $productlist = $this->admin_model->show_pro_list();
        $Parameter = array();
        $Errors = array();
        ///Parameter array
        $array = array(
            '1' => 'Top 10 Rx KPI',
            '2' => 'Top 10 By Doctor Engaged KPI',
            '3' => 'Top 10 By Rxs',
            '4' => 'Top 10 By Brand Rx Share',
            '5' => 'Top 10 By Dr engaged in Activities',
        );

        foreach ($array as $key => $value) {
            $Object = new stdClass();
            $Object->id = $key;
            $Object->Parameter = $value;
            array_push($Parameter, $Object);
        }
        $result = $this->admin_model->find_zone();
        $data['Parameter'] = $this->Master_Model->generateDropdown($Parameter, 'id', 'Parameter');
        $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        $start_date = '';
        $end_date = '';
        $condition = array();

        if ($this->input->get('Zone') != '') {
            $Zone = $this->Designation == 'ZSM' ? $this->Zone : $this->input->get('Zone');
            $condition[0] = "em.Zone = '" . $Zone . "'";
            $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $this->input->get('Zone'));
        }

        if ($this->input->get('Division') != '') {
            $productlist = $this->Master_Model->BrandList($this->input->get('Division'));
            $Division = ($this->Designation == 'Marketing' || $this->Designation == 'NSM' || $this->Designation == 'ZSM' ) ? $this->Division : $this->input->get('Division');
            $condition[1] = "em.Division = '" . $Division . "'";
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));
        }

        if ($this->input->get('Product') != '' && $this->input->get('Product') != 'All') {
            $Product_id = $this->input->get('Product');
            $data['productlist'] = $this->Master_Model->generateDropdown($productlist, 'id', 'Brand_Name', $this->input->get('Product'));
        }

        if ($this->input->get('Start_date') != '' && $this->input->get('End_date') != '') {
            $start_date = $this->input->get('Start_date');
            $end_date = $this->input->get('End_date');
        } else {
            array_push($Errors, 'Please Select Start Date And End Date');
        }

        if ($this->input->get('Parameter') > 0 && $this->input->get('Parameter') < 6) {
            if ((int) $this->input->get('Parameter') == 1) {
                $data['result'] = $this->User_model->Top10KPI($Product_id, $condition, ' ORDER BY KPI DESC LIMIT 10', $start_date, $end_date);
                $data['Parameter'] = $this->Master_Model->generateDropdown($Parameter, 'id', 'Parameter', (int) $this->input->get('Parameter'));
            } elseif ((int) $this->input->get('Parameter') == 2) {
                $data['result'] = $this->User_model->Top10ActivityKPI($Product_id, $condition, ' ORDER BY KPI DESC LIMIT 10', $start_date, $end_date);
                $data['Parameter'] = $this->Master_Model->generateDropdown($Parameter, 'id', 'Parameter', (int) $this->input->get('Parameter'));
            } elseif ((int) $this->input->get('Parameter') == 3) {
                $data['result'] = $this->User_model->Top10KPI($Product_id, $condition, ' ORDER BY Actual_Rx DESC LIMIT 10', $start_date, $end_date);
                $data['Parameter'] = $this->Master_Model->generateDropdown($Parameter, 'id', 'Parameter', (int) $this->input->get('Parameter'));
            } elseif ((int) $this->input->get('Parameter') == 4) {
                if ($Product_id > 0) {
                    $order_by = $Product_id > 1 ? ' ORDER BY BIShare1 DESC' : ' ORDER BY BIShare2 DESC';
                    $data['result'] = $this->User_model->Top10BIShare($Product_id, $condition, $order_by, $start_date, $end_date);
                } else {
                    array_push($Errors, 'Please Select Product');
                }
                $data['Parameter'] = $this->Master_Model->generateDropdown($Parameter, 'id', 'Parameter', (int) $this->input->get('Parameter'));
            } elseif ((int) $this->input->get('Parameter') == 5) {
                $data['result'] = $this->User_model->Top10ActivityKPI($Product_id, $condition, ' ORDER BY Doctor_engaged DESC LIMIT 10', $start_date, $end_date);
                $data['Parameter'] = $this->Master_Model->generateDropdown($Parameter, 'id', 'Parameter', (int) $this->input->get('Parameter'));
            }
        } else {
            array_push($Errors, 'Please Select Parameters');
        }

        if (!empty($Errors)) {
            $this->session->set_userdata('message', $this->Master_Model->DisplayAlert(join(".<br/>", array_unique($Errors))), 'danger');
        }


        $data = array('title' => 'Leader Board', 'content' => 'ho/leaderBoard', 'page_title' => 'Leader Board', 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    function TargetAssigned() {
        $condition = array();
        if ($this->input->get('Zone') != '' && $this->input->get('Zone') != '-1') {
            $condition[0] = "AND em.Zone = '" . $this->input->get('Zone') . "'";
        }
        if ($this->input->get('Division') != '') {
            $condition[1] = "AND em.Division = '" . $this->input->get('Division') . "'";
        }

        $month = $this->input->get('month');
        $year = $this->input->get('year');
        ?>
        <div id="target" class="tab-pane fade in active panel">
            <div class="tab-content">
                <a download="targetAssigned.xls" class="btn btn-success pull-right" href="#" onclick="return ExcellentExport.excel(this, 'export', 'Sheeting');">Export to Excel</a>

                <table class="table table-bordered panel" id="export" style="margin-bottom: 0px" > 
                    <tr>
                        <th>Zone</th>
                        <th>Division</th>
                        <th>ASM Name</th>
                    </tr>
                    <?php
                    $targetAssigned = $this->admin_model->getTargetAssigned($month, $year, $condition);
                    if (!empty($targetAssigned)) {
                        foreach ($targetAssigned as $target) {
                            echo '<tr>'
                            . '<td>' . $target->Zone . '</td>'
                            . '<td>' . $target->Division . '</td>'
                            . '<td>' . $target->Full_Name . '</td>'
                            . '</tr>';
                        }
                    }
                    ?>
                </table>
            </div>
        </div><?php
    }

    function SystemStatus() {
        $data = array();
        $data = array('title' => 'System Status', 'content' => 'ho/status', 'page_title' => 'Status Report', 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    function getSystemStatus() {
        $productlist = $this->admin_model->show_pro_list();
        $tabName = $this->input->get('tabName');
        $this->load->model('asm_model');
        $month = $this->input->get('month');
        $Year = $this->input->get('year');

        $condition = array();
        if ($this->input->get('Zone') != '' && $this->input->get('Zone') != '-1') {
            $condition[0] = "em.Zone = '" . $this->input->get('Zone') . "'";
        }
        if ($this->input->get('Division') != '') {
            $condition[1] = "em.Division = '" . $this->input->get('Division') . "'";
        }
        ?>
        <script src="<?php echo asset_url(); ?>js/excellentexport.min.js" type="text/javascript"></script>

        <div class="panel panel-default">
            <div id="<?php echo $tabName; ?>" class="tab-pane fade in active">
                <div class="tab-content">

                    <a download="<?php echo $this->input->get('tabName') . date('d_m_Y H_i_s') ?>.xls" class="btn btn-success pull-right" href="#" onclick="return ExcellentExport.excel(this, 'export', 'Sheeting');">Export to Excel</a>

                    <table class="table table-bordered panel datatable" style="margin-bottom: 0px" id="export">
                        <tr>
                            <th>Zone</th>
                            <th>Territory</th>
                            <th>BDM Name</th>
                            <th>BDM Code</th>
                            <th>Approved</th>
                            <th>Rejected</th>
                            <th>Pending</th>
                            <th>Approved & Submitted</th>
                            <th>Product</th>
                        </tr>
                        <?php
                        if (!empty($productlist)) {
                            $count = 1;
                            $ApproveCount = 0;
                            $UnApproveCount = 0;
                            $Pending = 0;
                            $Submitted = 0;
                            foreach ($productlist as $product) {


                                if ($this->input->get('tabName') == 'planning') {
                                    $Status = $this->asm_model->PlanningStatus2($product->id, $month, $Year, $condition);
                                } elseif ($this->input->get('tabName') == 'activityplan') {
                                    $Status = $this->asm_model->ActPlanningStatus2($product->id, $month, $Year, $condition);
                                } elseif ($this->input->get('tabName') == 'rxreport') {
                                    $Status = $this->asm_model->RxReportingStatus2($product->id, $month, $Year, $condition);
                                } elseif ($this->input->get('tabName') == 'activityreport') {
                                    $Status = $this->asm_model->ActReportingStatus2($product->id, $month, $Year, $condition);
                                }

                                if (!empty($Status)) {
                                    foreach ($Status as $value) {
                                        $ApproveCount += $value->ApproveCount;
                                        $UnApproveCount += $value->UnApproveCount;
                                        $Pending += $value->SFACount;
                                        $Submitted += $value->SubmitCount;
                                        echo '<tr><td>' . $value->Zone . '</td>'
                                        . '<td>' . $value->Territory . '</td>'
                                        . '<td>' . $value->Full_Name . '</td>'
                                        . '<td>' . $value->VEEVA_Employee_ID . '</td>'
                                        . '<td>' . $value->ApproveCount . '</td>'
                                        . '<td>' . $value->UnApproveCount . '</td>'
                                        . '<td>' . $value->SFACount . '</td>'
                                        . '<td>' . $value->SubmitCount . '</td>'
                                        . '<td>' . $product->Brand_Name . '</td></tr>';
                                    }
                                }
                                ?>

                                <?php
                                $count ++;
                            }
                        }
                        echo '<tr><th>Total</th><td></td><td></td><td></td><td>' . $ApproveCount . '</td><td>' . $UnApproveCount . '</td><td>' . $Pending . '</td><td>' . $Submitted . '</td></tr>';
                        ?>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    function actilyse_report() {
        $this->load->model('Master_Model');
        $this->load->model('User_model');
        $this->load->model('admin_model');
        $condition = array();
        $result = $this->admin_model->find_zone();
        $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone');
        if ($this->input->get('Zone') != '') {
            $condition[0] = "em.Zone = '" . $this->input->get('Zone') . "'";
            if ($this->Designation == 'ZSM') {
                $condition[0] = "em.Zone = '" . $this->Zone . "'";
            }
            $data['zone'] = $this->Master_Model->generateDropdown($result, 'Zone', 'Zone', $this->input->get('Zone'));
        }
        $data['show'] = $this->admin_model->find_hospital($condition);
        $data = array('title' => 'Actilyse', 'content' => 'ho/ActilyseReport', 'page_title' => 'Actilyse Report List', 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    function data_show() {
        $this->load->model('asm_model');
        $id = $this->input->get('id');
        $data['list'] = $this->asm_model->data_report($id);
        $view = $this->asm_model->data_report($id);
        $name = $view->Account_Name;
        $data = array('title' => 'Actilyse Report', 'content' => 'ho/actilysedata', 'page_title' => $name, 'view_data' => $data);
        $this->load->view('template4', $data);
    }

    public function get_terr() {
        $TerritoryConditions = array("em.Profile = 'BDM' ");
        if ($this->input->post('Zone') != '') {
            array_push($TerritoryConditions, "em.Zone = '" . $this->input->post('Zone') . "'");
        }
        if ($this->input->post('Division') != '') {
            array_push($TerritoryConditions, "em.Division = '" . $this->input->post('Division') . "'");
        }
        $result = $this->User_model->getTerritory1($TerritoryConditions);
        $data = '<option value="">Select Territory</option>';
        $data .= $this->Master_Model->generateDropdown($result, 'id', 'Territory');
        echo $data;
    }

    public function get_product() {
        $result = $this->Master_Model->BrandList($this->input->post('Division'));
        $data = '<option value="">Select Product</option>';
        $data .= $this->Master_Model->generateDropdown($result, 'id', 'Brand_Name');
        echo $data;
    }

}

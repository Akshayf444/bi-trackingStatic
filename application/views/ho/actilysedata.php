<?php
$row = $list;
$actilyse_data = $this->asm_model->find_actilyse_data($_GET['empid'], $this->input->get('id'));
?>
<a style="padding-left: 0px;padding-top: 0px;" onclick="goBack()" class="btn btn-link "><i class="fa fa-arrow-left btn-lg pull-left"></i></a>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<div class="col-lg-12">
    <table class="table  table-bordered panel ">
        <tr>
            <th>Segment</th>
            <th>Focus</th>
            <th>Gain Project</th>
            <th>Zone</th>
            <th>ASM Name</th>
            <th>BDM Name</th>
            <th>MSL</th>
        </tr>
        <tr>  
            <td> <?php
                if (!empty($actilyse_data)) {
                    echo $actilyse_data->Segment;
                }
                ?></td>
            <td> <?php
                if (!empty($actilyse_data)) {
                    echo $actilyse_data->Focus;
                }
                ?></td>
            <td> <?php
                if (!empty($actilyse_data)) {
                    echo $actilyse_data->Gain_Project;
                }
                ?></td>
            <td><?php echo $row->Zone; ?></td>                    
            <td><?php echo $row->Reporting_To; ?></td>
            <td><?php echo $row->Full_Name; ?></td>

            <td><?php
                if (!empty($actilyse_data)) {
                    echo $actilyse_data->MSL_Name;
                }
                ?></td>   
        </tr>
    </table>
</div> 
<div class="col-lg-12">
    <table class="table  table-bordered panel ">
        <tr>
            <th>Stroke Protocol</th>
            <th>Thrombolysing Unit </th>
            <th>No Of Beds</th>
            <th>In house   CT Scan facility</th>
            <th>In house MRI facility  </th>
            <th>Approach</th>
            <th>No of doctors in stroke team</th>
        </tr>
        <tr>
            <td><?php
                if (!empty($actilyse_data)) {

                    echo $actilyse_data->Stroke_Protocol;
                }
                ?></td>
            <td>
                <?php
                if (!empty($actilyse_data)) {

                    echo $actilyse_data->Thrombolysing_Unit;
                }
                ?></td>    
            <td><?php echo $row->No_Of_Beds; ?></td>
            <td><?php echo $row->CT_MRI_available; ?></td>
            <td><?php echo $row->CT_MRI_available; ?></td>

            <td><?php
                if (!empty($actilyse_data)) {

                    echo $actilyse_data->Approach;
                }
                ?></td> 
            <td><?php
                if (!empty($actilyse_data)) {
                    echo $actilyse_data->No_of_doctors_in_stroke_team;
                }
                ?></td> 
        </tr>
    </table>
</div>
<div class="col-lg-6">
    <table class="table  table-bordered panel ">
        <thead>
            <tr>
                <th>Stroke Champion Name</th>	<td>
                    <?php
                    if (!empty($actilyse_data)) {
                        echo $actilyse_data->Stroke_Champion_Name;
                    }
                    ?></td>
            </tr><tr>
                <th>Spec of Stroke Champion	</th>
                <td> <?php
                    if (!empty($actilyse_data)) {
                        echo $actilyse_data->Spec_of_Stroke_Champion;
                    }
                    ?></td></tr>
            <tr>
                <th>Neurologist1</th>  <td><?php
                    if (!empty($actilyse_data)) {
                        echo $actilyse_data->Neurologist1;
                    }
                    ?></td> </tr>
            <tr>
                <th>Cardiologist 1</th>  <td><?php
                    if (!empty($actilyse_data)) {
                        echo $actilyse_data->Cardiologist1;
                    }
                    ?></td></tr>
            <tr> <th>Cardiologist 2</th> <td><?php
                    if (!empty($actilyse_data)) {
                        echo $actilyse_data->Cardiologist2;
                    }
                    ?> </td> </tr>
        </thead>
    </table>
    <?php ?>
</div>
<div class="col-lg-6">
    <table class="table  table-bordered panel ">
        <thead>
            <tr>
                <th>Emergency Head </th>
                <td><?php
                    if (!empty($actilyse_data)) {
                        echo $actilyse_data->Emergency_Head;
                    }
                    ?></td></tr><tr>
                <th>Radiology Head </th>
                <td><?php
                    if (!empty($actilyse_data)) {
                        echo $actilyse_data->Radiology_Head;
                    }
                    ?></td></tr><tr>
                <th>Intensivist 1</th>
                <td><?php
                    if (!empty($actilyse_data)) {
                        echo $actilyse_data->Intensivist1;
                    }
                    ?> </td></tr><tr>
                <th>Intensivist 2</th>
                <td><?php
                    if (!empty($actilyse_data)) {
                        echo $actilyse_data->Intensivist2;
                    }
                    ?> </td></tr><tr>	
                <th>AIS patients/Month</th>
                <td>   <?php echo $row->Patient_Seen_month; ?></td>
            </tr>
        </thead>
    </table>
</div>
<div class="col-lg-12">
    <table class="table  table-bordered panel ">
        <thead>
            <tr>
                <th>Month</th>
                <th>2015 Yield</th>
                <th>2016 EOGO Plan</th>
                <th>2016 Yield</th>
                <th>2016 EOGO ach%</th>
                <th>Lysis Rate %</th>
                <th>+/-over last month </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $monthData = array();
            $Activitys = array();
            $Activitys1 = array();
            $Activitys2 = array();
            for ($i = 1; $i <= 12; $i++) {
                echo '<tr>';
                $month = date('M', mktime(0, 0, 0, $i, 1, date('Y')));
                array_push($monthData, $month);
                echo '<th>' . $month . '</th>';
                $MONTH1 = $this->asm_model->month1($_GET['id'], $i, $_GET['empid']);

                if ($i == 12) {
                    echo isset($MONTH1->actualp) ? '<td class="0s">' . $MONTH1->actualp . '</td>' : '<td class="0s">0</td>';
                } else {
                    echo isset($MONTH1->actualp) ? '<td>' . $MONTH1->actualp . '</td>' : '<td >0</td>';
                }
                array_push($Activitys, isset($MONTH1->actualp) ? $MONTH1->actualp : 0);

                $plan = isset($MONTH1->plan) && !is_null($MONTH1->plan) ? $MONTH1->plan : 0;
                echo '<td>' . $plan . '</td>';
                array_push($Activitys1, $plan);

                $actual = isset($MONTH1->actual) && !is_null($MONTH1->actual) ? $MONTH1->actual : 0;
                array_push($Activitys2, $actual);
                echo '<td class="' . $i . 's">' . $actual . '</td>';

                $per = isset($MONTH1->plan) && $MONTH1->plan > 0 ? ($MONTH1->actual / $MONTH1->plan) * 100 : 0;

                echo '<td>' . round($per, 2) . ' </td>';

                $lysis = isset($list->Patient_Seen_month) && $list->Patient_Seen_month > 0 ? ($MONTH1->actual / $list->Patient_Seen_month) * 100 : 0;
                echo '<td>' . $lysis . '</td>';
                echo '<td class="111' . ($i) . 's"></td>';
            }
            echo '</tr>';
            ?>
        </tbody>
    </table>
</div>
<div class="col-lg-12">
    <div id="chart" >
    </div>
</div>

<div class="col-lg-12">
    <table class="table  table-bordered panel ">
        <thead>
            <tr>
                <th>Month</th>
                <th>Activity Planned</th>
                <th>Activity Status</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <?php
        for ($i = 1; $i <= 12; $i++) {
            echo '<tr>';
            echo '<th>' . date('M', mktime(0, 0, 0, $i, 1, date('Y'))) . '</th>';
            $activities = $this->asm_model->activity_reportmonth($i, $this->input->get('id'), $_GET['empid']);
            if (!empty($activities)) {
                echo '<td>' . $activities->Act_Plan . '</td>';
                echo '<td>' . $activities->Activity_Detail . '</td>';
                echo '<td>' . $activities->Status . '</td>';
            }
        }
        ?>
    </table>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script>
    $(function () {
        $('#chart').highcharts({
            title: {
                text: '',
                x: -20 //center
            },
            xAxis: {
                categories: <?php echo json_encode($monthData) ?>
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
                    name: '2015 Yield',
                    data: <?php echo json_encode($Activitys, JSON_NUMERIC_CHECK) ?>
                }, {
                    name: '2016 EOGO Plan',
                    data: <?php echo json_encode($Activitys1, JSON_NUMERIC_CHECK) ?>
                }, {
                    name: '2016 Yield',
                    data: <?php echo json_encode($Activitys2, JSON_NUMERIC_CHECK) ?>
                }]
        });
    });
</script>
<script>
    $(window).load(function () {
        for (var i = 1; i < 13; i++) {
            var first = parseFloat($("." + i + "s").html()) || 0;
            var second = parseFloat($("." + (i - 1) + "s").html()) || 0;
            var final = first - second;
            $(".111" + i + "s").html(final);
        }
    });
</script>
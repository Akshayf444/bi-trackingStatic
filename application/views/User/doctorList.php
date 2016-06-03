<script src="<?php echo asset_url(); ?>js/jquery.dataTables.min.js" type="text/javascript"></script>
<style>
    .col-xs-9, .col-xs-3{
        padding: 0px;
    }
    .table-view-cell {
        padding: 11px 12px 11px 15px;
    }

    #datatable_filter{
        display: none;
    }
    table.dataTable tbody th, table.dataTable tbody td {
        padding: 2px 4px;
    }
</style>
<?php $this->load->view('User/ProductList', array('site_url' => 'User/Planning')); ?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
        <p>No Of New <?php
            if ($this->Product_Id == '1') {
                echo "Vials";
            } else {
                echo "Rx";
            }
            ?> Targeted For <?php echo date('M'); ?> <?php echo date('Y', strtotime($this->nextYear)); ?> : <b ><input type="text" class="form-control ck" style="width:40%" readonly="readonly" value="<?php echo isset($show4['target']) ? $show4['target'] : 0; ?>"></b></p>
        <p>Balanced <?php
            if ($this->Product_Id == '1') {
                echo "Vials";
            } else {
                echo "Rx";
            }
            ?> To Plan For <?php echo date('M'); ?> <?php echo date('Y', strtotime($this->nextYear)); ?>: <span class="ckk"></span></p>
    </div>
    <style>
        ul {
            list-style-type: none;
        }

        .input-color {
            position: relative;
        }
        .input-color input {
            padding-left: 20px;
        }
        .input-color .color-box {
            width: 10px;
            height: 10px;
            display: inline-block;
            background-color: #ccc;
            position: absolute;
            left: 5px;
            top: 5px;
        }
    </style>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <span class="pull-right">
            <select class="form-control" id="TableSort">
                <option value="1">Sort By</option>
                <option value="1">Winability</option>
                <option value="2">Dependency/Rx For Last Month</option>
                <option value="3">BI Market Share</option>
                <option value="7">Planned <?php
                    if ($this->Product_Id == '1') {
                        echo "Vials";
                    } else {
                        echo "Rx";
                    }
                    ?> Of Present Month</option>
            </select>
            <ul style="margin-top:5px" >
                <li>
                    <div class="input-color">
                        <input type="text" value="Un-Approved" readonly="readonly" style="height: 23px" />
                        <div class="color-box" style="background-color: #ff9999;"></div>
                        <!-- Replace "#FF850A" to change the color -->
                    </div>
                </li>
                <li>
                    <div class="input-color">
                        <input type="text" value="Approved" readonly="readonly" style="height: 23px" />
                        <div class="color-box" style="background-color: #c6ebd9;"></div>
                        <!-- Replace "navy" to change the color -->
                    </div>
                </li>
            </ul>
        </span>

    </div>
</div>
<?php
$attributes = array('id' => 'ProfilingForm');
echo form_open('User/Planning?Product_Id='.$this->Product_Id, $attributes);
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!--    <div class="panel panel-default">
                <div class="panel-heading">Planning</div>
                <div class="panel-body">
                    <div class="table-responsive">-->
        <table class="table table-bordered panel" id="datatable">
            <thead>
                <tr>
                    <th>
                        <?php
                        if ($this->Product_Id == 1) {
                            $vials = "Vials";
                            $hospital = "Hospital";
                        } else {
                            $vials = "Rx";
                            $hospital = "Doctor";
                        } echo $hospital;
                        ?> List</th>
                    <th>Winability</th>
                    <th>Dependency</th>
                    <?php if ($this->Product_Id == 1) { ?>
                        <th>LYSIS Share</th>
                    <?php } else { ?>
                        <th>BI Market Share</th>
                    <?php } ?>


                    <th><?php echo date('M', strtotime('-3 month')) . $vials; ?> </th>
                    <th><?php echo date('M', strtotime('-2 month')) . $vials; ?></th>
                    <th><?php echo date('M', strtotime('-1 month')) . $vials; ?></th>
                    <th>New <?php echo $vials; ?> Targeted For <?php echo date('M'); ?> </th>
                    <th>New <?php echo $vials; ?> Targeted For <?php echo date('M'); ?> </th>
                </tr>
            </thead>
            <tbody>

                <?php
                $month = date('n', strtotime('-1 month'));
                $lastMonthRx = $this->User_model->countLastMonthRx($month);
                $currentMonthRx = $this->User_model->countPlannedRx(date('n'));
                $allApproved = TRUE;
                if (isset($result) && !empty($result)) {
                    foreach ($result as $doctor) {
                        $planned_rx = isset($doctor->Planned_Rx) ? $doctor->Planned_Rx : "";
                        $actual_rx = isset($doctor->Actual_Rx) ? $doctor->Actual_Rx : "";


                        $month1 = date('n', strtotime('-3 month'));
                        $month2 = date('n', strtotime('-2 month'));
                        $month3 = date('n', strtotime('-1 month'));
                        $month4 = date('n');
                        $year1 = date('Y', strtotime('-3 month'));
                        $year2 = date('Y', strtotime('-2 month'));
                        $year3 = date('Y', strtotime('-1 month'));
                        $year4 = date('Y');

                        $month1Actual = 0;
                        $month2Actual = 0;
                        $month3Actual = 0;
                        $month4Actual = 0;

                        $last3MonthRx = $this->User_model->Last3MonthsRx($month1, $month2, $month3, $month4, $year1, $year2, $year3, $year4, $doctor->Account_ID);
                        if (!empty($last3MonthRx)) {
                            $count = 1;
                            foreach ($last3MonthRx as $value) {
                                if ($value->month === $month1) {
                                    $month1Actual = isset($value->Actual_Rx) ? $value->Actual_Rx : 0;
                                } elseif ($value->month === $month2) {
                                    $month2Actual = isset($value->Actual_Rx) ? $value->Actual_Rx : 0;
                                } elseif ($value->month === $month3) {
                                    $month3Actual = isset($value->Actual_Rx) ? $value->Actual_Rx : 0;
                                } elseif ($value->month === $month4) {
                                    $month4Actual = isset($value->Actual_Rx) ? $value->Actual_Rx : 0;
                                }
                            }
                        }
                        $winability = isset($doctor->Winability) ? $doctor->Winability : '';
                        $month4rx = $month4Actual;
                        if ($lastMonthRx->Actual_Rx > 0) {
                            $dependancy = round(($month3Actual / $lastMonthRx->Actual_Rx ) * 100, 0, PHP_ROUND_HALF_EVEN);
                        } else {
                            $dependancy = 0;
                        }
                        if ($this->Product_Id == 1) {
                            if (isset($doctor->Patient_Seen_month) && $doctor->Patient_Seen_month > 0) {
                                $BI_Share = round(($month3Actual / $doctor->Patient_Seen_month) * 100, 0, PHP_ROUND_HALF_EVEN);
                            } else {
                                $BI_Share = '';
                            }
                        } else {
                            if (isset($doctor->Patient_Rxbed_In_Month) && $doctor->Patient_Rxbed_In_Month > 0) {
                                $BI_Share = round(($month3Actual / $doctor->Patient_Rxbed_In_Month) * 100, 0, PHP_ROUND_HALF_EVEN);
                            } else {
                                $BI_Share = '';
                            }
                        }
                        ?>
                        <tr <?php
                        if (isset($doctor->Approve_Status) && $doctor->Approve_Status == 'Approved') {
                            echo 'style="background-color:#c6ebd9;"';
                        } elseif (isset($doctor->Approve_Status) && $doctor->Approve_Status == 'Un-Approved') {
                            $allApproved = FALSE;
                            echo 'style="background-color: #ff9999;"';
                        } else {
                            $allApproved = FALSE;
                        }
                        ?>>
                            <td><?php echo $doctor->Account_Name; ?><p>Speciality : <?php echo $doctor->Specialty; ?></p></a></td>
                            <td><?php echo $winability; ?></td><td><?php echo $dependancy; ?>%</td>
                            <td><?php echo $BI_Share; ?></td>
                            <td><?php echo $month1Actual; ?></td>
                            <td><?php echo $month2Actual; ?></td>
                            <td><?php echo $month3Actual; ?></td>
                            <td><?php echo $planned_rx; ?></td>
                            <?php if ($this->Product_Id == 1) { ?>
                                <td> <input name = "value[]" min="0" class = "val"  step="0.5"  type = "number" value = "<?php echo $planned_rx; ?>"/><input type = "hidden" name = "doc_id[]" value = "<?php echo $doctor->Account_ID; ?>"/></td>
                            <?php } else { ?>
                                <td> <input name = "value[]" min="0" class = "val"  type = "number" value = "<?php echo $planned_rx; ?>"/><input type = "hidden" name = "doc_id[]" value = "<?php echo $doctor->Account_ID; ?>"/></td>
                            <?php } ?>                               
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <!--            </div>-->

        <input type="hidden" id="Status" name="Planning_Status" value="Draft">
        <input type="hidden" id="Approve_Status" name="Approve_Status" value="">
        <input type="hidden" id="Button_click_status" name="Button_click_status" value="Save">
        <!--        </div>-->
        <?php if (isset($result) && !empty($result)) { ?>
            <div class="">                
                <?php if ($allApproved == TRUE) { ?>
                    <button type="button" id="Priority" class="btn btn-danger">Prioritize for activities</button>  
<!--                    <button type="submit" id="Submit" class="btn btn-success">Submit</button>-->
                <?php } else { ?>
<!--                    <button type="submit" id="Save" class="btn btn-primary">Save</button>-->
                    <button type="submit" id="Approve" class="btn btn-info">Save</button>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
</form>

<style>
    table.dataTable tbody tr {
        background-color: transparent;
    }
</style>
<script>


    $(document).ready(function () {
        $(".val").keyup(function () {
            RemainingBalance();
        });

        var oTable = $('#datatable').dataTable({
            "bPaginate": false,
            "bInfo": false,
            "info": false,
            "columnDefs": [
                {
                    "targets": [7],
                    "visible": false
                }
            ]
        });
        $('#TableSort').on('change', function () {
            var selectedValue = $(this).val();
            oTable.fnSort([[selectedValue, 'desc']]); //Exact value, column, reg
        });
    });



    $(window).load(function () {
        RemainingBalance();
    });
    function RemainingBalance() {

        var finalval = 0;
        $(".val").each(function () {
            var actual = parseInt($(this).val(), 10) || 0;
            finalval = parseInt(finalval, 10) + actual;
        });

        var grandTotal = $('.ck').val() - finalval;

        $('.ckk').html(grandTotal);

        if (grandTotal == 0) {

        } else {
            $("#Submit").attr('type', 'button');
        }
    }

    $("#Priority").click(function () {
        var formAction = '<?php echo site_url('User/generatePriority?Product_Id='.$this->Product_Id); ?>';
        $("#ProfilingForm").attr('action', formAction);
        $("#ProfilingForm").submit();
    });

    $("#Submit").click(function () {
        var finalval = 0;
        $(".val").each(function () {
            var actual = parseInt($(this).val(), 10) || 0;
            finalval = parseInt(finalval, 10) + actual;
        });

        var grandTotal = $('.ck').val() - finalval;
        $('.ckk').html(grandTotal);
        if (grandTotal == 0) {
            $("#Submit").attr('type', 'submit');
        } else if (grandTotal > 0) {
            var answer = confirm("Planned Rx is Less Than Set Target")
            if (answer) {
//$("#Submit").attr('type', 'submit');
            }
            else {
                $("#Submit").attr('type', 'button');
            }
        } else if (grandTotal < 0) {
            var answer = confirm("Planned Rx is More Than Set Target")
            if (answer) {
                $("#Submit").attr('type', 'submit');
            }
            else {
                $("#Submit").attr('type', 'submit');
            }
        }

        $("#Status").val('Submitted');
        $("#Button_click_status").val('Submit');

    });
    $('#Approve').click(function () {
        $("#Approve_Status").val('SFA');
        $("#Button_click_status").val('SaveForApproval');
    });
</script>
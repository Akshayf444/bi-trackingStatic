<style>

    .col-xs-9, .col-xs-3{
        padding: 0px;
    }

    #datatable_filter{
        display: none;
    }

</style>
<script src="<?php echo asset_url(); ?>js/jquery.dataTables.min.js" type="text/javascript"></script>
<style>
    table.dataTable tbody tr {
        background-color: transparent;
    }
</style>
<?php $this->load->view('User/ProductList', array('site_url' => 'User/Reporting')); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
        <span class="pull-left">Total Expected <?php
            if ($this->Product_Id == '1') {
                echo "Vials";
            } else {
                echo "Rx";
            }
            ?> For <?php echo $this->User_model->getMonthName($current_month); ?> <?php echo $this->nextYear ?> : <b><?php echo isset($show4['target']) ? $show4['target'] : 0; ?></b></span><br>

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
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
        <span class="pull-right">
            Sort By
            <select class="form-control" id="TableSort">
                <option>Select Criteria</option>
                <option value="4">Planned <?php
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
<?php echo form_open('User/Reporting?Product_Id=' . $this->Product_Id); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
<!--        <div class="panel panel-default">
            <div class="panel-heading">Reporting For <?php //echo $this->User_model->getMonthName($current_month); ?></div>-->
            <!--        <div class="panel-body">-->
            <table class="table table-bordered panel" id="datatable">
                <thead>
                    <tr>
                        <th>
                            <?php
                            //echo $current_month;
                            $month1 = $this->User_model->calculateMonth($current_month, 3);
                            $month2 = $this->User_model->calculateMonth($current_month, 2);
                            $month3 = $this->User_model->calculateMonth($current_month, 1);
                            $month4 = $this->User_model->calculateMonth($current_month, 0);

                            if ($this->Product_Id == 1) {
                                $vials = "Vials";
                                $hospital = "Hospital";
                            } else {
                                $vials = "Rx";
                                $hospital = "Doctor";
                            } echo $hospital;
                            ?> List</th>


                        <th><?php echo $this->User_model->getMonthName($month1) . $vials; ?> </th>
                        <th><?php echo $this->User_model->getMonthName($month2) . $vials; ?></th>
                        <th><?php echo $this->User_model->getMonthName($month3) . $vials; ?></th>
                        <th>New <?php echo $vials; ?> Targeted For <?php echo $this->User_model->getMonthName($month4); ?> </th>
                        <th>Cumulative Month to Date</th>
                        <th>Actual</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    /// var_dump($result);
                    $month = $month3;
                    $lastMonthRx = $this->User_model->countLastMonthRx($month);
                    $currentMonthRx = $this->User_model->countPlannedRx($month4);
                    $allApproved = TRUE;
                    if (isset($result) && !empty($result)) {
                        foreach ($result as $doctor) {
                            $planned_rx = isset($doctor->Planned_Rx) ? $doctor->Planned_Rx : "";
                            $actual_rx = isset($doctor->Actual_Rx) ? $doctor->Actual_Rx : "";

                            $year1 = $this->User_model->calculateYear($current_month, 3);
                            $year2 = $this->User_model->calculateYear($current_month, 2);
                            $year3 = $this->User_model->calculateYear($current_month, 1);
                            $year4 = $this->User_model->calculateYear($current_month, 0);

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
                            ///echo $doctor->Approve_Status;
                            if (isset($doctor->Approve_Status) && $doctor->Approve_Status == 'Approved') {
                                echo 'style="background-color:#c6ebd9;"';
                            } elseif (isset($doctor->Approve_Status) && $doctor->Approve_Status == 'Un-Approved') {
                                echo 'style="background-color: #ff9999;"';
                                $allApproved = FALSE;
                            } else {
                                $allApproved = FALSE;
                            }
                            ?>>
                                <td><?php echo $doctor->Account_Name; ?><p>Speciality : <?php echo $doctor->Specialty; ?></p></a></td>
                                <td><?php echo $month1Actual; ?></td>
                                <td><?php echo $month2Actual; ?></td>
                                <td><?php echo $month3Actual; ?></td>
                                <td><?php echo $planned_rx ?><input type = "hidden" name = "doc_id[]" value = "<?php echo $doctor->Account_ID ?>"/></td>
                                <td><?php echo $doctor->Actual_Rx ?></td>

                                <?php if ($this->Product_Id == 1) { ?>
                                    <td> <input name = "value[]" type = "number" step="0.5" class="val" min="0" value = "<?php echo $doctor->Actual_Rx2 ?>"/></td>
                                <?php } else { ?>
                                    <td> <input name = "value[]" type = "number" class="val" min="0" value = "<?php echo (int) $doctor->Actual_Rx2 ?>"/></td>
                                <?php } ?>    
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php //echo isset($doctorList) ? $doctorList : ''    ?>
            <input type="hidden" id="Status" name="Status" value="Draft">
            <input type="hidden" id="Approve_Status" name="Approve_Status" value="">
            <input type="hidden" id="Button_click_status" name="Button_click_status" value="Save">
            <!--        </div>-->
<!--            <div class="panel-footer">-->
                <!--                <button type="submit" id="Save" class="btn btn-primary">Save</button>-->
                <?php if ($allApproved == TRUE) { ?>
<!--                    <button type="submit" id="Submit" class="btn btn-success">Submit</button>-->
                <?php } else { ?>
                    <button type="submit" id="Approve" class="btn btn-info">Save</button>
                <?php } ?>

<!--            </div>
        </div>-->
    </div>
</div>
</form>
<style>
    table.dataTable tbody tr {
        background-color: transparent;
    }
</style>
<script>
    $("#Submit").click(function () {
        $("#Status").val('Submitted');

        var finalval = 0;
        $(".val").each(function () {
            var actual = parseInt($(this).val(), 10) || 0;
            finalval = parseInt(finalval, 10) + actual;
        });

        var grandTotal = $('.ck').val() - finalval;
        $('.ckk').html(grandTotal);
        if (grandTotal == 0) {
            $("#Save").show();
            $("#Submit").show();
        } else if (finalval == 0) {
            $("#Submit").attr('type', 'button');
            alert('Reporting Rx Should Be Greater Than 0');
        } else {
            $("#Submit").attr('type', 'submit');
        }
    });

    $(document).ready(function () {
        $(".val").keyup(function () {
            RemainingBalance();
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
            $("#Save").show();
            $("#Submit").show();
        }
    }

    var oTable = $('#datatable').dataTable({
        "bPaginate": false,
        "bInfo": false,
        "info": false,
    });
    $('#TableSort').on('change', function () {
        var selectedValue = $(this).val();
        oTable.fnSort([[selectedValue, 'desc']]); //Exact value, column, reg
    });
    $('#Approve').click(function () {
        $("#Approve_Status").val('SFA');
        $("#Button_click_status").val('SaveForApproval');
    });
</script>

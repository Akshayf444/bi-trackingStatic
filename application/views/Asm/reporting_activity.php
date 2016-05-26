<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php echo form_open('ASM/reporting_activity'); ?>
            <div class="col-sm-2 col-md-2"  >
                <select name="rx_id" class="form-control">
                    <option value="-1">Select BDM </option>
                    <?php echo $bdm; ?>
                </select>
            </div>
            <div class="col-sm-2 col-md-2"  >
                <select name="product_id" class="form-control">
                    <option value="-1">Select Product</option>
                    <?php echo $product; ?>
                </select>
            </div>
<!--            <div class="col-sm-2 col-md-2"  >
                <select name="month" class="form-control">
                    <option value="-1">Select Month</option>
                    <?php echo $month; ?>
                </select>
            </div>-->
            <div class="col-sm-1 col-md-1"  >
                <button type="submit" class="btn btn-primary" >FETCH</button>
            </div>
            <div class="col-sm-7 col-md-7"  >
                <p>Please tick the checkbox against the plan to approve and keep unticked to dis-approve </p>
            </div>
            </form>
        </div>
    </div>
</div>
<?php echo form_open('ASM/Approve_reporting_Activity'); ?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <input type="hidden" name="month" value="<?php echo $current_month ?>">
    <?php
    if (!empty($Doctorlist)) {
        echo $Doctorlist;
    }
    ?>
    <div class="modal fade" id="CommentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Comment</h4>
                </div>
                <div class="modal-body">
                    <label></label>
                    <?php if (isset($_POST['rx_id']) && isset($_POST['product_id'])) {
                        $CommentExist = $this->User_model->getComment($_POST['rx_id'], 'Activity_Reporting', $_POST['product_id']);
                    }
                    ?>
                    <input type="hidden" name="Com_id" value="<?php echo isset($CommentExist->Com_id) ? $CommentExist->Com_id : 0; ?>">
                    <textarea class="form-control" name="Comment"><?php echo isset($CommentExist->Comment) ? $CommentExist->Comment : ''; ?></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="Assign" >Approve</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="BDM_ID" value="<?php echo isset($_POST['rx_id']) ? $_POST['rx_id'] : '' ?>">
    <input type="hidden" name="product" value="<?php echo isset($_POST['product_id']) ? $_POST['product_id'] : '' ?>">
</div>
</form>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
<?php if (!empty($productlist)) { ?>
        <div class="panel panel-default"> 
            <div class="panel-heading">Activity Reporting Status For Approval   </div>
            <div class="panel-body">

                <ul align="center" class="nav nav-tabs ">
                    <?php
                    if (!empty($productlist)) {
                        $count = 1;
                        foreach ($productlist as $product) {
                            ?>
                            <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a data-toggle="tab" style="    padding: 12px;" href="#<?php echo $product->id ?>"><?php echo $product->Brand_Name ?></a></li>
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
                            $ApproveCount = 0;
                            $UnApproveCount = 0;
                            $Pending = 0;
                            $Submitted = 0;
                            ?>

                            <div id="<?php echo $product->id ?>" class="tab-pane fade <?php echo isset($count) && $count == 1 ? 'in active' : ''; ?>">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>BDM Name</th>
                                        <th>Approved</th>
                                        <th>Rejected</th>
                                        <th>Pending</th>
                                        <th>Approved&Submitted</th>
                                    </tr>
                                    <?php
                                    $Status = $this->asm_model->ActivityReportingStatus($product->id, $current_month);
                                    if (!empty($Status)) {
                                        foreach ($Status as $value) {
                                            $ApproveCount += $value->ApproveCount;
                                            $UnApproveCount += $value->UnApproveCount;
                                            $Pending += $value->SFACount;
                                            $Submitted += $value->SubmitCount;
                                            echo '<tr><td>' . $value->Full_Name . '</td><td>' . $value->ApproveCount . '</td><td>' . $value->UnApproveCount . '</td><td>' . $value->SFACount . '</td><td>' . $value->SubmitCount . '</td></tr>';
                                        }
                                        echo '<tr><th>Total</th><td>' . $ApproveCount . '</td><td>' . $UnApproveCount . '</td><td>' . $Pending . '</td><td>' . $Submitted . '</td></tr>';
                                    }
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
<script>
    $('#check-all').click(function (e) {
        $(this).closest('table').find('td .check-all').prop('checked', this.checked);
    });
    $('#uncheck-all').click(function (e) {
        $(this).closest('table').find('td .uncheck-all').prop('checked', this.checked);
    });
    function validate() {
        if (document.getElementById('check-all').checked) {
            $("#Assign").attr('type', 'submit');
        } else if (document.getElementById('uncheck-all').checked) {
            $("#Assign").attr('type', 'submit');
        } else {
            alert('Please Select Approve Or Reject Radio Button.');
            $("#Assign").attr('type', 'button');
        }

    }
</script>

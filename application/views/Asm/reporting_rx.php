<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-body ">
            <?php echo form_open('ASM/reporting_rx'); ?>
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
<?php echo form_open('ASM/Approvereporting'); ?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <?php if (!empty($show)) { ?>
        <div class="table-responsive panel">
            <table class="table table-bordered table-hover ">

                <tr>
                    <th>Doctor Name</th>
                    <th>Reporting Rx</th>
                    <th><input type="radio" name="toggle" id="check-all">Approve</th>
                    <th><input type="radio" name="toggle" id="uncheck-all"> Reject</th>
                </tr>

                <?php foreach ($show as $row) :
                    ?>
                    <tr>  
                        <td><?php echo $row->Account_Name; ?></td>  
                    <input type="hidden" name="Rxplan_id[]" value="<?php echo $row->Rxplan_id ?>">
                    <td><?php echo $row->Actual_Rx; ?> <input type="hidden" name="Doctor_Id[]" value="<?php echo $row->Account_ID ?>"> <input type="hidden" name="BDM_ID" value="<?php echo isset($_POST['rx_id']) ? $_POST['rx_id'] : '' ?>"> </td>
                    <td><input type="hidden" name="product" value="<?php echo isset($_POST['product_id']) ? $_POST['product_id'] : '' ?>"><input type="radio" class="check-all" <?php echo isset($row->Approve_Status) && $row->Approve_Status == 'Approved' ? 'checked' : '' ?> name="approve_<?php echo $row->Account_ID ?>" value="Approved"></td>
                    <td><input type="radio" class="uncheck-all" name="approve_<?php echo $row->Account_ID ?>" value="Un-Approved"></td>
                    </tr>
                    <?php
                endforeach;
                echo '</table><div><button type="button" data-toggle="modal" data-target="#CommentModal"  class=" btn btn-primary pull-right" > Approve</button></div>';
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
                            <?php
                            if (isset($_POST['rx_id']) && isset($_POST['product_id'])) {
                                $CommentExist = $this->User_model->getComment($_POST['rx_id'], 'Reporting', $_POST['product_id']);
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
    </div>

</div>
</form>
    <?php if (!empty($productlist)) { ?>
    <div class="resultarea"><img src="<?php echo asset_url(); ?>images/loader.gif" id="loader"></div>
<?php } ?>

<script>
    window.onload = function () {
        sendRequest();
    };


    function sendRequest() {
        var page = '';
        $.ajax({
            //Send request
            type: 'GET',
            data: {page: page},
            url: '<?php echo site_url('ASM/getApprovedStatusCount'); ?>',
            success: function (data) {
                $(".resultarea").html(data);
            }
        });
    }


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

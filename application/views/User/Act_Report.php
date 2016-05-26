<style>
    label{
        margin-bottom: 0px;
    }

    .toggle {
        margin:4px;
        background-color:#EFEFEF;
        border-radius:20px;
        border:1px solid #EFEFEF;
        overflow:auto;
        float:left;

    }

    .toggle label {
        float:left;
        //width:2.0em;

    }

    .toggle label span {
        text-align: center;
        padding: 0px 11px 5px 13px;
        display: block;
        cursor: pointer;
        overflow: hidden;
    }

    .toggle label input {
        visibility: hidden;
        position:absolute;
        top:-20px;
    }

    .toggle .input-checked {
        background-color:#000;
        color:red;
        -webkit-transition: 0.15s ease-out;
        -moz-transition: 0.15s ease-out;
        -o-transition: 0.15s ease-out;
        transition: 0.15s ease-out;
    }
</style>
<script src="<?php echo asset_url(); ?>js/bootstrap.min_1.js" type="text/javascript"></script>
<?php echo form_open('User/ActivityReporting'); ?>
<div class="col-lg-12 col-md-12 ">
    <div class="panel panel-default">
        <div class="panel-heading">Activity Reporting For <?php echo $this->User_model->getMonthName($current_month);?></div>
        <div class="panel-body">   
            <?php echo isset($doctorList) && !empty($doctorList) ? $doctorList : ''; ?>

            <input type="hidden" id="Status" name="Status" value="Draft">
            <input type="hidden" id="Approve_Status" name="Approve_Status" value="">
            <input type="hidden" id="Button_click_status" name="Button_click_status" value="Save">
        </div>
    </div>
</div>
</form>
<script>
    $('label').click(function () {
        $(this).children('span').addClass('input-checked');
        $(this).parent('.toggle').siblings('.toggle').children('label').children('span').removeClass('input-checked');

        var id = $(this).children('span').attr('id').split("-");
        id = id[0];

        if ($(this).children('span').text() === 'Yes') {
            $("#heading" + id).show();
            $("#act" + id).attr('required', true);
            $("#reason" + id).hide();
            $("#res" + id).attr('required', false);
        } else if ($(this).children('span').text() === 'No') {
            $("#heading" + id).hide();
            $("#res" + id).attr('required', true);
            $("#reason" + id).show();
            $("#act" + id).attr('required', false);
        }
    });

    $("#Submit").click(function () {
        $("#Status").val('Submitted');
        $("#Button_click_status").val('Submit');
    });

    $('#Approve').click(function () {
        $("#Approve_Status").val('SFA');
        $("#Button_click_status").val('SaveForApproval');
    });

</script>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">Asm Comment</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th> ASM Comment</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($asm_comment)) {
                            foreach ($asm_comment as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row->created_at ?> </td>
                                    <td><?php echo $row->Comment ?> </td>
                                    <td><?php echo $row->Comment_type ?> </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
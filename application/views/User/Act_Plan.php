<script src="<?php echo asset_url(); ?>js/formValidation.min.js" type="text/javascript"></script>
<script src="<?php echo asset_url(); ?>js/bootstrap.min.js" type="text/javascript"></script>
<?php
$attributes = array('id' => 'form1');
echo form_open('User/ActivityPlanning', $attributes);
?>
<div class="col-lg-12 col-md-12 ">
    <div class="panel panel-default">
        <div class="panel-heading">Activity Planning</div>
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
    $("#Submit").click(function () {
        $("#Status").val('Submitted');
        //$("#form1").submit();
    });
</script>
<script>
    $('document').ready(function () {

        $('#form1').formValidation({
            message: 'This value is not valid',
            icon: {
            },
            fields: {
                'Activity_Id[]': {
                    validators: {
                        notEmpty: {
                            message: 'Please Select Activity'
                        },
                    }
                }

            }
        });

    });

    $("#Doctor_id").change(function () {
        $('#loader').show();
        $.ajax({
            type: 'POST',
            data: {'Doctor_Id': $(this).val()},
            url: '<?php echo site_url('User/getProfilingData'); ?>',
            success: function (data) {
                //alert(data);
                if (data != '404') {
                    var obj = jQuery.parseJSON(data);
                    $("input[name='Patient_Seen']").val(obj.Patient_Seen);
                    $("input[name='Patient_Seen_month']").val(obj.Patient_Seen_month);
                    $("input[name='Patient_Rxbed_In_Month']").val(obj.Patient_Rxbed_In_Month);
                    $("input[name='Patient_Rxbed_In_Week']").val(obj.Patient_Rxbed_In_Week);

                    if (obj.CT_MRI_available == 'Yes') {
                        $("#CT_MRI_available_yes").prop('checked', true);
                    } else if (obj.CT_MRI_available == 'No') {
                        $("#CT_MRI_available_no").prop('checked', true);
                    }

                    if (obj.Win_Q1 == 'Yes') {
                        $("#Win_Q1_yes").prop('checked', true);
                    } else if (obj.Win_Q1 == 'No') {
                        $("#Win_Q1_no").prop('checked', true);
                    }
                    if (obj.Win_Q2 == 'Yes') {
                        $("#Win_Q2_yes").prop('checked', true);
                    } else if (obj.Win_Q2 == 'No') {
                        $("#Win_Q2_no").prop('checked', true);
                    }
                    if (obj.Win_Q3 == 'Yes') {
                        $("#Win_Q3_yes").prop('checked', true);
                    } else if (obj.Win_Q3 == 'No') {
                        $("#Win_Q3_no").prop('checked', true);
                    }

                    $(".spaf1 > option").each(function () {
                        if (this.value == obj.Primary_indication) {
                            $(this).attr("selected", true);
                        }
                    });
                    $("#Beds > option").each(function () {
                        if (this.value == obj.No_Of_Beds) {
                            $(this).attr("selected", true);
                        }
                    });

                } else {
                    $("input[name='Patient_Seen']").val('');
                    $("input[name='Patient_Seen_month']").val('');
                    $("input[name='Patient_Rxbed_In_Month']").val('');
                    $("input[name='Patient_Rxbed_In_Week']").val('');
                    $("#CT_MRI_available_yes").prop('checked', false);
                    $("#CT_MRI_available_no").prop('checked', false);
                    $("#Win_Q1_yes").prop('checked', false);
                    $("#Win_Q1_no").prop('checked', false);
                    $("#Win_Q2_yes").prop('checked', false);
                    $("#Win_Q2_no").prop('checked', false);
                    $("#Win_Q3_yes").prop('checked', false);
                    $("#Win_Q3_no").prop('checked', false);

                    $(".spaf1 > option").each(function () {
                        $(this).attr("selected", false);
                    });
                }

                $('#loader').hide();
            }
        });

    });
    $("input[type='number']").each(function () {
        $(this).attr('min', '0');
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
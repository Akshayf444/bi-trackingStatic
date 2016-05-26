<?php
$attribute = array('id' => 'valid');
echo form_open('admin/emp_add', $attribute);
?>
<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
    <div class="col-lg-4">
        VEEVA Employee ID:  <input type="text" class="form-control" value="" name="VEEVA_Employee_ID" placeholder="Enter VEEVA_Employee_ID" />
    </div>
    <div class="col-lg-4">
        Local Employee ID: <input type="text" class="form-control" value="" name="Local_Employee_ID" placeholder="Enter Local_Employee_ID" /> </div>
    <div class="col-lg-4">
        First Name:  <input type="text" class="form-control" value="" name="First_Name" placeholder="Enter First Name"/> </div>
    <div class="col-lg-4">
        Middle Name:<input type="text" class="form-control" value="" name="Middle_Name" placeholder="Enter Middle Name"/> </div>
    <div class="col-lg-4">
        Last Name:<input type="text" class="form-control" value="" name="Last_Name" placeholder="Enter Last Name"/> </div>
    <div class="col-lg-4">
        Full Name:<input type="text" class="form-control" value="" name="Full_Name" placeholder="Enter Full Name"/> </div>
    <div class="col-lg-4">
        Territory:
        <select  class="chosen-select form-control" id="types" name="Territory">
            <option value="">Select  Territory</option>
            <?php echo $Territory;
            ?>
            <option value="others">others</option>
        </select> 
        <input type="text" id="others" class="form-control" name="Territorys" placeholder="Enter Territory Name" style="display: none;margin-top: 5px"   />
    </div>
    <div class="col-lg-4">
        Gender:<select name="Gender"  class="form-control" placeholder="Enter Gender">
            <option>select</option>
            <option>Male</option>
            <option>Female</option>
        </select> 
    </div>
    <div class="col-lg-4">
        Mobile:<input type="text" class="form-control" value="" maxlength="10" name="Mobile" placeholder="Enter Mobile"/> </div>
    <div class="col-lg-4">
        Email ID:<input type="text" class="form-control" value="" id="email" name="Email_ID" placeholder="Enter Email_ID"/></div>
    <div class="col-lg-4">
        Username:<input type="text" class="form-control" value="" id="Username" name="Username" placeholder="Enter Username"/></div>
    <div class="col-lg-4">
        Address 1:  <input type="text" class="form-control" value="" name="Address_1" placeholder="Enter Address_1"/> </div>
    <div class="col-lg-4">
        Address 2:  <input type="text" class="form-control" value="" name="Address_2" placeholder="Enter Address_2"/> </div>
    <div class="col-lg-4">
        City:  <input type="text" class="form-control" value="" name="City" placeholder="Enter City"/> </div>
    <div class="col-lg-4">
        State:<select  class="form-control" id="type" name="State">
            <option value="-1">Select State</option>
            <?php echo $State;
            ?>
            <option value="others">others</option>
        </select> 
        <input type="text" id="other" class="form-control" name="States"    style="display: none;margin-top: 5px"   />
    </div>
    <div class="col-lg-4">
        Division:<select  class="form-control" name="Division" >
            <option value="">Select  Division</option>
            <?php echo $Division; ?>
        </select> 
    </div>
    <div class="col-lg-4">
        Zone:<select  class="form-control" name="Zone" >
            <option value="">Select Zone</option>
            <?php echo $zone ?>
        </select>
    </div>
    <div class="col-lg-4">
        Region:<select  class="form-control" id="reg" name="Region" >
            <option value="-">Select Region</option>
            <?php echo $region ?>
            <option value="others">others</option>
        </select> 
        <input type="text" id="regions" class="form-control" name="Regions"   style="display: none;margin-top: 5px"   />
    </div>
    <div class="col-lg-4">
        Designation:<select  class="form-control" name="Designation" >
            <option value="">Select Designation</option>
            <?php echo $Designation; ?>
        </select> 
    </div>
    <div class="col-lg-4">
        Date of Joining:  <input type="text" class="form-control" value=""  id="datepicker1"name="Date_of_Joining" placeholder="Enter Date_of_Joining"/>
    </div>
    <div class="col-lg-4">
        Profile:<select  class="form-control" name="Profile" id="profile" >
            <option value="-1">Select  Profile</option>
            <?php echo $Profile ?>
        </select>  </div>
    <div class="col-lg-4">
        Reporting To :<select  class="form-control chosen-select" name="Reporting_To" id="reporting_to" >
            <option value="" >Select Reporting To</option>
        </select>
    </div>
    <div class="col-lg-4">
        Reporting VEEVA ID:  <input type="text" class="form-control" value=""  readonly="" name="Reporting_VEEVA_ID" placeholder="Enter Reporting_VEEVA_ID"/> </div>
    <div class="col-lg-4">
        Reporting local ID:  <input type="text" class="form-control" value=""  readonly="" name="Reporting_Local_ID" placeholder="Enter Reporting_Local_ID"/> </div>
    <div class="col-lg-12 form-group">
        <br/>
        <button class="btn btn-success" type="submit">Submit</button>
    </div>
</div>
</form>
<script>
    $(function () {
        $("#datepicker1").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
<script type="text/javascript">
    $('#profile').change(function () {
        var profile = $(this).val();
        $.ajax({
            url: '<?php echo site_url('admin/ajax_data') ?>',
            data: {profile: profile},
            type: 'POST',
            success: function (data) {
                $("#reporting_to").empty();
                $("#reporting_to").append('<option value="-1">Select Reporting To</option>');
                $('#reporting_to').append(data);    //please note this, here we're focusing in that input field
                $('#reporting_to').trigger("chosen:updated");
            }
        });
    });

    $("#reporting_to").change(function () {
        var veevaid = $(this).find(':selected').data('veevaid');
        $("input[name='Reporting_VEEVA_ID']").val(veevaid);
    });
    $("#reporting_to").change(function () {
        var localid = $(this).find(':selected').data('localid');
        $("input[name='Reporting_Local_ID']").val(localid);
    });
    $('#email').change(function () {
        $("#username").val($(this).val());
        //please note this, here we're focusing in that input field

    });


</script>
<script src="<?php echo asset_url() ?>js/formValidation.min.js" type="text/javascript"></script>
<script src="<?php echo asset_url() ?>js/bootstrap.min.js" type="text/javascript"></script>
<script>
    $('document').ready(function () {
        $('#valid').formValidation({
            icon: {
            },
            fields: {
                VEEVA_Employee_ID: {
                    validators: {
                        notEmpty: {
                            message: 'The VEEVA Employee ID is required'
                        }
                    }
                },
                Local_Employee_ID: {
                    validators: {
                        notEmpty: {
                            message: 'The Local Employee ID is required'
                        }
                    }
                },
                First_Name: {
                    validators: {
                        notEmpty: {
                            message: 'The  First Name is required'
                        }
                    }
                },
                Username: {
                    validators: {
                        notEmpty: {
                            message: 'Please Enter Username'
                        }
                    }
                },
                Last_Name: {
                    validators: {
                        notEmpty: {
                            message: 'The Last Name is required'
                        }
                    }
                },
                Full_Name: {
                    validators: {
                        notEmpty: {
                            message: 'The Full Name is required'
                        }
                    }
                },
                Territory: {
                    validators: {
                        notEmpty: {
                            message: 'The Territory is required'
                        }
                    }
                },
                Email_ID: {
                    validators: {
                        notEmpty: {
                            message: 'The Email Id is required'
                        }
                    }
                },
                Division: {
                    validators: {
                        notEmpty: {
                            message: 'Please Select Division'
                        }
                    }
                },
                Zone: {
                    validators: {
                        notEmpty: {
                            message: 'Please Select Zone'
                        }
                    }
                },
                Region: {
                    validators: {
                        notEmpty: {
                            message: 'The  Region is required'
                        }
                    }
                },
                Designation: {
                    validators: {
                        notEmpty: {
                            message: 'The  Designation is required'
                        }
                    }
                },
                Profile: {
                    validators: {
                        notEmpty: {
                            message: 'The Profile is required'
                        }
                    }
                },
                Mobile: {
                    validators: {
                        integer: {
                            message: 'Mobile No Should Be In Digits'
                        }
                    }
                },
                Reporting_To: {
                    validators: {
                        notEmpty: {
                            message: 'The  Reporting To is required'
                        }
                    }
                },
            }

        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#types').change(function () {
            if ($(this).val() == 'others') {
                $('#others').show();
            } else {
                $('#others').hide();
            }
        });
    });



</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#type').change(function () {
            if ($(this).val() == 'others') {
                $('#other').show();
            } else {
                $('#other').hide();
            }
        });

        $('#reg').change(function () {
            if ($(this).val() == 'others') {
                $('#regions').show();
            } else {
                $('#regions').hide();
            }
        });

    });

    function deletedoc(url) {
        var r = confirm("Are you sure you want to delete");
        if (r == true)
        {
            window.location = url;

        }
        else
        {
            return false;
        }
    }


</script>
<?php
$attribute = array('id' => 'valid');
echo form_open('admin/update_emp?id=' . $rows['VEEVA_Employee_ID'], $attribute);
?>

<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">

    <?php
    if (!empty($rows)) {
        ?>

        <input type="hidden" class="form-control" value="<?php echo $rows['VEEVA_Employee_ID']; ?>" name="VEEVA_Employee_ID" />
        <div class="col-lg-4">
            First Name:  <input type="text" class="form-control" value="<?php echo $rows['First_Name']; ?>" name="First_Name" placeholder="Enter First Name"/> 
        </div>
        <div class="col-lg-4">
            Middle Name:<input type="text" class="form-control"value="<?php echo $rows['Middle_Name']; ?>" name="Middle_Name" placeholder="Enter Middle Name"/>
        </div>
        <div class="col-lg-4">
            Last Name:<input type="text" class="form-control"value="<?php echo $rows['Last_Name'] ?>" name="Last_Name" placeholder="Enter Last Name"/>
        </div>
        <div class="col-lg-4">
            Full Name:<input type="text" class="form-control"value="<?php echo $rows['Full_Name']; ?>" name="Full_Name" placeholder="Enter Full Name"/>
        </div>


        <div class="col-lg-4">

            Gender:<select name="Gender" class="form-control" >
                <option value="Female" <?php
                if ($rows['Gender'] == 'Female') {
                    echo 'selected';
                }
                ?>>Female</option>


                <option value="Male" <?php
                if ($rows['Gender'] == 'Male') {
                    echo 'selected';
                }
                ?>>Male</option>
            </select>
        </div>
        <div class="col-lg-4">
            Mobile:<input type="text" class="form-control"value="<?php echo $rows['Mobile']; ?>" name="Mobile" placeholder="Enter Mobile"/>
        </div>
        <div class="col-lg-4">
            Email ID:<input type="text" class="form-control"value="<?php echo $rows['Email_ID']; ?>" name="Email_ID" readonly="" placeholder="Enter Email_ID"/>
        </div>
        <div class="col-lg-4">
            Username:<input type="text" class="form-control"value="<?php echo $rows['Username']; ?>" name="Username" readonly="" placeholder="Enter Username"/>
        </div>

        <div class="col-lg-4">
            Address 1:  <input type="text" class="form-control" value="<?php echo $rows['Address_1']; ?>" name="Address_1" placeholder="Enter Address_1"/>
        </div>
        <div class="col-lg-4">
            Address 2:  <input type="text" class="form-control" value="<?php echo $rows['Address_2']; ?>" name="Address_2" placeholder="Enter Address_2"/>
        </div>
        <div class="col-lg-4">
            City:  <input type="text" class="form-control" value="<?php echo $rows['City']; ?>" name="City" placeholder="Enter City"/>
        </div>
        <div class="col-lg-4">
            State:  <input type="text" class="form-control" value="<?php echo $rows['State']; ?>" name="State" placeholder="Enter  State"/>
        </div>
        <div class="col-lg-4">
            Division:<select  class="form-control" name="Division" >
                <option value="">Select  Division</option>
                <?php echo $Division ?>
            </select> 
        </div>


        <div class="col-lg-4">
            Zone:<select  class="form-control" name="Zone" >
                <option value="">Select Zone</option>
                <?php echo $zone ?>
            </select> 
        </div>
        <div class="col-lg-4">
            Territory:<select  class="form-control chosen-select" name="Territory" >
                <option value="">Select Territory</option>
                <?php echo $Territory ?>
            </select> 
        </div>
        <div class="col-lg-4">
            Region:<select  class="form-control" name="Region" >
                <option value="">Select Region</option>
                <?php echo $region ?>
            </select>
        </div>

        <div class="col-lg-4">
            Designation:<select  class="form-control" name="Designation" >
                <option value="">Select Designation</option>
                <?php echo $Designation; ?>
            </select>   
        </div>
        <div class="col-lg-4">
            Date of Joining:  <input type="text" class="form-control"  id="datepicker2" name="Date_of_Joining"  value="<?php echo $rows['Date_of_Joining']; ?>" placeholder="Enter Date_of_Joining"/>
            <?php echo form_open('admin/get_record'); ?>
        </div>
        <div class="col-lg-4">
            Profile:<select  class="form-control" name="Profile" id="profile" >
                <option value="-1">Select  Profile</option>
                <?php echo $Profile ?>
            </select>  </div>
        <div class="col-lg-4">
            Reporting To :<select  class="form-control chosen-select" name="Reporting_To" id="reporting_to" >
                <option value="-1">Select Reporting_To</option>
                <?php echo $Reporting_To ?>
            </select>  </div>

        <div class="col-lg-4">
            Reporting VEEVA ID:  <input type="text" class="form-control" value="<?php echo $rows['Reporting_VEEVA_ID'] ?>"  readonly="" name="Reporting_VEEVA_ID" placeholder="Enter Reporting_VEEVA_ID"/> </div>

        <div class="col-lg-4">
            Reporting Local ID:  <input type="text" class="form-control" value="<?php echo $rows['Reporting_Local_ID'] ?>"  readonly="" name="Reporting_Local_ID" placeholder="Enter Reporting_Local_ID"/> </div>

    <?php }
    ?>
    <div class="col-lg-12 form-group">
        <br>
        <button class="btn btn-success">Submit</button>
    </div>
</div>
</form>
<script>
    $(function () {
        $("#datepicker2").datepicker({
            changeMonth: true,
            changeYear: true
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

</script>
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





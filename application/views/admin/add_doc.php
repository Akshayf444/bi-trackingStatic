<?php
$attribute = array('id' => 'valid');
echo form_open('admin/add_doc', $attribute);
?>
<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
    <div class="col-lg-4">
        Account ID:  <input type="text" class="form-control" value="" name="Account_ID" placeholder="Account_ID" />
    </div>
    <div class="col-lg-4">
        Salutation: <input type="text" class="form-control" value="" name="Salutation" placeholder="Salutation" /> </div>
    <div class="col-lg-4">
        First Name:  <input type="text" class="form-control" value="" name="First_Name" placeholder="Enter First Name"/> </div>
    <div class="col-lg-4">
        Last Name:<input type="text" class="form-control" value="" name="Last_Name" placeholder="Enter Last Name"/> </div>
    <div class="col-lg-4">
        Account Name:<input type="text" class="form-control" value="" name="Account_Name" placeholder="Enter Account Name"/> </div>
    <div class="col-lg-4">
        Record Type:
        <select name="Record_Type" class="form-control">
            <option value="">Select Record Type</option>
            <option value="Doctor">Doctor</option>
            <option value="Hospital">Hospital</option>
        </select> </div>
    <div class="col-lg-4">
        Specialty:<input type="text" class="form-control" value="" name="Specialty" placeholder="Enter Specialty"/> </div>
    <div class="col-lg-4">
        Specialty2:<input type="text" class="form-control" value="" name="Specialty2" placeholder="Enter Specialty2"/> </div>
    <div class="col-lg-4">
        Specialty3:<input type="text" class="form-control" value="" name="Specialty3" placeholder="Enter Specialty3"/> </div>
    <div class="col-lg-4">
        Specialty4:<input type="text" class="form-control" value="" name="Specialty4" placeholder="Enter Specialty4"/> </div>
    <div class="col-lg-4">
        Individual Type:
        <select name="Individual_Type" class="form-control">
            <option value="">Select Individual Type</option>
            <option value="Doctor">Doctor</option>
            <option value="Hospital">Hospital</option>
        </select> </div>
    <div class="col-lg-4">
        Email ID:<input type="text" class="form-control" value="" id="email" name="Email_ID" placeholder="Enter Email_ID"/> </div>
    <div class="col-lg-4">  Gender:<select name="Gender"  class="form-control" placeholder="Enter Gender">
            <option>select</option>
            <option>Male</option>
            <option>Female</option>
        </select> </div>
    <div class="col-lg-4">
        Mobile:<input type="text" class="form-control" value="" name="Mobile" placeholder="Enter Mobile"/> </div>

    <div class="col-lg-4">
        City:  <input type="text" class="form-control" value="" name="City" placeholder="Enter City"/> </div>
    <div class="col-lg-4">
        State:  <input type="text" class="form-control" value="" name="State" placeholder="Enter State"/> </div>
    <div class="col-lg-4">
        Pincode:  <input type="text" class="form-control" value="" name=" Pincode" placeholder="Enter Pincode"/> </div>
    <div class="col-lg-4">
        Address:  <input type="text" class="form-control" value="" name="Address" placeholder="Enter Address"/> </div>
    <div class="col-lg-12">
        <br>
        <button class="btn btn-success" type="submit">Submit</button>
    </div>
</div>
</form>
<script>
    $(function () {
        $("#datepicker1").datepicker({
            changeMonth: true,
            changeYear: true
        });
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
                            message: 'The VEEVA_Employee_ID is required'
                        }
                    }
                },
                Local_Employee_ID: {
                    validators: {
                        notEmpty: {
                            message: 'The Local_Employee_ID is required'
                        }
                    }
                },
                First_Name: {
                    validators: {
                        notEmpty: {
                            message: 'The  First_Name is required'
                        }
                    }
                }, Middle_Name: {
                    validators: {
                        notEmpty: {
                            message: 'The Middle_Name is required'
                        }
                    }
                },
                Last_Name: {
                    validators: {
                        notEmpty: {
                            message: 'The Last_Name is required'
                        }
                    }
                },
                Full_Name: {
                    validators: {
                        notEmpty: {
                            message: 'The Full_Name is required'
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
                Gender: {
                    validators: {
                        notEmpty: {
                            message: 'The username is required'
                        }
                    }
                },
                Mobile: {
                    validators: {
                        notEmpty: {
                            message: 'The Mobile is required'
                        }
                    }
                },
                Email_ID: {
                    validators: {
                        notEmpty: {
                            message: 'The username is required'
                        }
                    }
                },
                Address_1: {
                    validators: {
                        notEmpty: {
                            message: 'The  Address 1 is required'
                        }
                    }
                },
                Address_2: {
                    validators: {
                        notEmpty: {
                            message: 'The  Address 2 is required'
                        }
                    }
                },
                City: {
                    validators: {
                        notEmpty: {
                            message: 'The  City is required'
                        }
                    }
                },
                State: {
                    validators: {
                        notEmpty: {
                            message: 'The State is required'
                        }
                    }
                },
                Division: {
                    validators: {
                        notEmpty: {
                            message: 'The  Division is required'
                        }
                    }
                },
                Zone: {
                    validators: {
                        notEmpty: {
                            message: 'The Zone is required'
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
                Reporting_To: {
                    validators: {
                        notEmpty: {
                            message: 'The  Reporting_To is required'
                        }
                    }
                },
                Individual_Type: {
                    validators: {
                        notEmpty: {
                            message: 'Please Select Record Type'
                        }
                    }
                },
                Record_Type: {
                    validators: {
                        notEmpty: {
                            message: 'Please Select Individual Type'
                        }
                    }
                }
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
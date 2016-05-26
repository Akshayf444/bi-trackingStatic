<div class="container" style="margin-top: 2em;">

    <div class="col-lg-6 col-md-6 col-lg-offset-3 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                Change Password
            </div>
            <?php
            $attribute = array('id' => 'activate');
            echo form_open('User/Reset_Password', $attribute);
            ?>
            <div class="panel-body">
                <div class="form-group">
                    <input type="password" name="password" autocomplete="off" class="form-control" placeholder="Enter Your New Password"/>
                </div>
                <div class="form-group">
                    <input type="hidden" value="<?php echo isset($VEEVA_Employee_ID) ? $VEEVA_Employee_ID : ''; ?>" name="VEEVA_Employee_ID">
                    <input type="password" name="password2" autocomplete="off" class="form-control" placeholder="Retype Your Password"/>
                </div>
            </div>
            <div class="panel-footer">
                <input type="submit" class="btn btn-success"/>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('document').ready(function () {

        $('#activate').formValidation({
            message: 'This value is not valid',
            icon: {
            },
            fields: {
                password: {
                    validators: {
                        regexp: {
                            regexp: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/,
                            message: 'Password Must Contain 8 characters with  1 Uppercase Alphabet, 1 Lowercase Alphabet and 1 Number'
                        },
                        notEmpty: {
                            message: 'Please Enter Value'
                        }
                    }
                },
                password2: {
                    validators: {
                        regexp: {
                            regexp: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/,
                            message: 'Password Must Contain 8 characters with 1 Uppercase Alphabet, 1 Lowercase Alphabet and 1 Number'
                        },
                        identical: {
                            field: 'password',
                            message: 'The password and its Repeat are not the same'
                        },
                        notEmpty: {
                            message: 'Please Enter Value'
                        }
                    }
                },
            }
        });
    });
</script>

<script src="<?php echo asset_url() ?>js/formValidation.min.js" type="text/javascript"></script>
<script src="<?php echo asset_url() ?>js/bootstrap.min.js" type="text/javascript"></script>
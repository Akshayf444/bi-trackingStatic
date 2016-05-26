

<div align="center" class="col-md-4 col-lg-4">
    <div class="panel panel-success">
        <div class=" panel-heading">
            <?php echo $detail['Full_Name'] ?>
        </div>
        <div class=" panel-body">
            <img alt="image"/>
        </div>

    </div>
</div>
<h3><?php
    if (isset($error)) {
        echo $error;
    }
    ?></h3>
<div class="col-lg-8 col-md-8">
    <ul align="center" class="nav nav-tabs ">
        <li class="active"><a data-toggle="tab" style="    padding: 12px;" href="#home">Basic Detail</a></li>
        <li><a data-toggle="tab" style="    padding: 12px;" href="#menu1">Change Password</a></li>
    </ul>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <?php
            echo form_open('User/BDM_update');
            $Territory = $this->User_model->getTerritory($detail['Territory']);
            ?>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Mobile</th>
                        <td><input type="text" class="form-control" maxlength="10" minlength="10" name="mobile" value="<?php echo $detail['Mobile']; ?>"/></td>
                    </tr>
                    <tr>
                        <th>Reporting ZSM</th>
                        <td><input type="text" class="form-control" readonly=""  value="<?php echo $detail['ASM']; ?>"/></td>
                    </tr>
                    <tr>
                        <th>Reporting </th>
                        <td><input type="text" class="form-control" readonly=""  value="<?php echo $detail['ZSM']; ?>"/></td>
                    </tr>
                    <tr>
                        <th>Area</th>
                        <td><input type="text" class="form-control" readonly=""  value="<?php echo isset($Territory->Territory) ? $Territory->Territory : ''; ?>"/></td>
                    </tr>
                    <tr>
                        <th>Date Of Birth</th>
                        <td><input type="text" class="form-control" name="date" id="date"  value="<?php echo $detail['DOB']; ?>"/></td>
                    </tr>
                    <tr>
                        <th>Date of Joining</th>
                        <td><input type="text" class="form-control" readonly=""  value="<?php echo $detail['Date_of_Joining']; ?>"/></td>
                    </tr>
                </tbody>
            </table>
            <input type="Submit" value="Submit" class="btn btn-success pull-right"/>
            </form>
        </div>
        <div id="menu1" class="tab-pane fade">
            <div class="col-lg-12 panel-body">
                <?php
                $attribute = array('id' => 'activate');
                echo form_open('User/pwd_update', $attribute);
                ?>

                <div class="form-group">
                    <label>Old Password</label>
                    <input type="password" name="old" class="form-control"  />
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new" class="form-control"  />
                </div>
                <div class="form-group">
                    <label> Confirm Password</label>
                    <input type="password" name="confirm" class="form-control"  />
                </div>
                <div class="form-group">
                    <input type="submit" value="Save" class="btn btn-success pull-right"/>
                </div>
                </form>
            </div>
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
                new : {
                    validators: {
                        regexp: {
                            regexp: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/,
                            message: 'Password Must Contain 8 characters with 1 Uppercase Alphabet, 1 Lowercase Alphabet and 1 Number'
                        },
                        notEmpty: {
                            message: 'Please Enter Value'
                        }
                    }
                },
                confirm: {
                    validators: {
                        regexp: {
                            regexp: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/,
                            message: 'Password Must Contain 8 characters with 1 Uppercase Alphabet, 1 Lowercase Alphabet and 1 Number'
                        },
                        identical: {
                            field: 'new',
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
<script>
    $(function () {
    $("#date").datepicker({
            changeMonth:true,
            changeYear:true
    });
</script>

<script src="<?php echo asset_url() ?>js/formValidation.min.js" type="text/javascript"></script>
<script src="<?php echo asset_url() ?>js/bootstrap.min.js" type="text/javascript"></script>

















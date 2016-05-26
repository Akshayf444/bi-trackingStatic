<?php echo isset($message) ? $message : ''; ?>
<script src="<?php echo asset_url(); ?>js/formValidation.min.js" type="text/javascript"></script>
<script src="<?php echo asset_url(); ?>js/bootstrap.min.js" type="text/javascript"></script>
<div class="col-lg-12 col-md-12 col-xs-12">
    <?php
    $rxlabel = isset($Product_Id) && $Product_Id == 1 ? 'Vials' : 'Rx';
    $attributes = array('id' => 'form1', 'name' => 'myform');
    ?>
    <div class="panel panel-default">
<!--        <div class="panel-heading">Assigned <?php echo $rxlabel; ?></div>-->
        <div class="panel-heading">                No Of New <?php echo $rxlabel; ?> Targeted For <?php echo date('M'); ?> <?php echo date('Y', strtotime($this->nextYear)); ?></div>
        <div class="panel-body">
            <div class="form-group">
                <input type="number" min="1" class="form-control" disabled="disabled" required="required" name="value" value="<?php echo isset($target) ? $target : ''; ?>">
            </div>
        </div>
        </form>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            New <?php echo $rxlabel; ?> Generated
        </div>
        <div class="panel-body">

            <table class="table table-bordered">
                <tr>
                    <th></th>
                    <th><?php echo $month_mid; ?></th>
                    <th><?php echo $month_start; ?></th>
                    <th><?php echo $month_between; ?></th>
                    <th><?php echo $month_ends; ?></th>
    <!--                <th><?php echo $current_month; ?></th>-->
                </tr>
    <!--            <tr>
                    <td>Rx Target :</td>
                    <td><?php
                foreach ($show5 as $sh5) {
                    echo $sh5->target;
                }
                ?></td>
                    <td><?php
                foreach ($show1 as $sh1) {
                    echo $sh1->target;
                }
                ?></td>
                    <td><?php
                foreach ($show2 as $sh2) {
                    echo $sh2->target;
                }
                ?></td>
                    <td><?php
                foreach ($show3 as $sh3) {
                    echo $sh3->target;
                }
                ?></td>
                    <td><?php
                foreach ($show4 as $sh4) {
                    // echo $sh4->target;
                }
                ?></td>
                </tr>-->
                <tr>
                    <td>
                        Actual <?php
                        if ($this->Product_Id == '1') {
                            echo "Vials";
                        } else {
                            echo "Rx";
                        }
                        ?>
                    </td>
                    <td><?php
                        foreach ($Actual1 as $sh1) {
                            echo $sh1->Act;
                        }
                        ?></td>
                    <td><?php
                        foreach ($Actual2 as $sh2) {
                            echo $sh2->Act;
                        }
                        ?></td>
                    <td><?php
                        foreach ($Actual3 as $sh3) {
                            echo $sh3->Act;
                        }
                        ?></td>
                    <td><?php
                        foreach ($Actual4 as $sh4) {
                            echo $sh4->Act;
                        }
                        ?></td>
    <!--                <td><?php
                    foreach ($show4 as $sh4) {
                        // echo $sh4->target;
                    }
                    ?></td>-->
                </tr>

            </table>
        </div>
    </div>

</div>
<script>
    $('document').ready(function () {
        $('#form1').formValidation({
            message: 'This value is not valid',
            icon: {
            },
            fields: {
                value: {
                    validators: {
                        notEmpty: {
                            message: 'Please Enter Value'
                        },
                        integer: {
                            message: 'It Has To Be No'
                        }
                    }
                }

            }
        });
    });
    $("#Submit").click(function () {
        $("#Status").val('Submitted');
    });

</script>
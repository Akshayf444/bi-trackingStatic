<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <?php echo form_open('Admin/Reset_Target'); ?>
    <div class="panel panel-default">
        <table class="table table-bordered panel">
            <tr>
                <th>Name of BDM</th>
                <?php
                foreach ($show as $shows) {
                    if ($shows->division == 'Diabetes') {
                        echo '<th>Trajenta</th>';
                        echo '<th>Jardiance</th>';
                        echo '<th>Trajenta Duo</th>';
                    } else {
                        echo '<th>Actylise</th>';
                        echo '<th>Pradaxa</th>';
                        echo '<th>Metalyse</th>';
                    }
                }
                ?>
            </tr>
            <?php
            foreach ($table as $tab) {

                if ($tab->Division == 'Diabetes') {
                    $result = $this->admin_model->ASM_Assign_Target($tab->VEEVA_Employee_ID, 4, 5, 6);
                } else {
                    $result = $this->admin_model->ASM_Assign_Target($tab->VEEVA_Employee_ID, 1, 2, 3);
                }
                ?>
                <tr>
                    <td><input type="hidden" name="VEEVA_Employee_ID[]" value="<?php echo $tab->VEEVA_Employee_ID; ?>"><?php echo $tab->Full_Name ?></td>
                    <?php
                    $target1 = 0;
                    $target2 = 0;
                    $target3 = 0;

                    foreach ($result as $r) {

                        if ($tab->Division == 'Diabetes') {
                            if ($r->Product_Id == 4) {
                                $target1 = $r->target;
                            } elseif ($r->Product_Id == 5) {
                                $target2 = $r->target;
                            } elseif ($r->Product_Id == 6) {
                                $target3 = $r->target;
                            }
                        } else {
                            if ($r->Product_Id == 1) {
                                $target1 = $r->target;
                            } elseif ($r->Product_Id == 2) {
                                $target2 = $r->target;
                            } elseif ($r->Product_Id == 3) {
                                $target3 = $r->target;
                            }
                        }
                    }
                    ?>

                    <td><?php echo $target1 ?></td>
                    <td><?php echo $target2 ?></td>
                    <td><?php echo $target3 ?></td>
                    <td><input type="checkbox" name="VEEVA_Employee_ID[]" value="<?php echo $tab->VEEVA_Employee_ID; ?>" ></td>
<!--                    <td><a class="btn btn-primary" onclick="window.location = '<?php //echo site_url('admin/reset_target?id=') . $tab->VEEVA_Employee_ID; ?>';">Reset Target</a> </td>-->
                </tr>

            <?php } ?>
        </table>
        <br/>
        <input type="submit" value="Reset" class="btn btn-primary pull-right" name="Reset">
    </div>
</form>
</div>
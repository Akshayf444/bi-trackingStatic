<?php
$attribute = array('id' => 'form1');
echo form_open('ASM/approveTarget', $attribute);
?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading"> Set New Target For <?PHP echo date('M Y'); ?></div>
        <?php
        $allApproved = 1;
        if (isset($table) && !empty($table)) {
            ?>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Name of BDM</th>
                        <?php
                        if ($this->Division == 'Diabetes') {
                            echo '<th>Trajenta</th>';
                            echo '<th>Jardiance</th>';
                            echo '<th>Trajenta Duo</th>';
                        } else {
                            echo '<th>Actylise</th>';
                            echo '<th>Pradaxa</th>';
                            echo '<th>Metalyse</th>';
                        }
                        ?>
                    </tr>
                    <?php
                    $total1 = 0;
                    $total2 = 0;
                    $total3 = 0;
                    foreach ($table as $tab) {
                        if ($this->Division == 'Diabetes') {
                            $result = $this->asm_model->ASM_Assign_Target($tab->VEEVA_Employee_ID, 4, 5, 6);
                        } else {
                            $result = $this->asm_model->ASM_Assign_Target($tab->VEEVA_Employee_ID, 1, 2, 3);
                        }
                        ?>
                        <tr>
                            <td><input type="hidden" name="VEEVA_Employee_ID[]" value="<?php echo $tab->VEEVA_Employee_ID; ?>"><?php echo $tab->Full_Name; //." [".$tab->Local_Employee_ID ."]";    ?></td>
                            <?php
                            $target1 = 0;
                            $target2 = 0;
                            $target3 = 0;

                            foreach ($result as $r) {

                                if ($r->Status === 'Draft') {
                                    $allApproved = 2;
                                }

                                if ($this->Division == 'Diabetes') {
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
                            $total1 += $target1;
                            $total2 += $target2;
                            $total3 += $target3;
                            ?>

                            <td><input type="text" name="target1[]" value="<?php echo $target1 ?>"></td>
                            <td><input type="text" name="target2[]" value="<?php echo $target2 ?>"></td>
                            <td><input type="text" name="target3[]" value="<?php echo $target3 ?>"></td>
                        </tr>
                    <?php }
                    ?>
                    <tr><th>Total</th><td><?php echo $total1 ?></td> <td><?php echo $total2 ?></td> <td><?php echo $total3 ?></td>                 </tr>
                </table>
            </div>
            <div class="panel-footer">
                <?php
                if ($allApproved == 2) {
                    ?>
                    <input type="button"   class="btn btn-success" value="Assign" data-toggle="modal" data-target="#myModal">
                <?php } ?>

                <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Assign Target</h4>
                            </div>
                            <input type="hidden" id="Status" name="Status" value="Draft">
                            <div class="modal-body">
                                <p>Are You Sure Do You Want To Assign Target ? </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="Assign" >Assign</button>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary" value="Save">
            </div>
        <?php } ?>
    </div>
</div>
</form>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Assign Target</h4>
            </div>
            <div class="modal-body">
                <p> Once you click on <b>Assign</b>  button it will go to respective BDM & will not be allowed to change.  </p>  
                <p> Please ensure that you have allocated new RX Target to all BDM for all brands .</p>
                <p> Are you sure to assign new RX Target ? </p>           
                <p>If you are not sure please click on <b> Cancel</b> button . </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button onclick="hide()"  class="btn btn-primary" data-toggle="modal" data-target="#myModal2" >Assign</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#product_id').change(function () {
        $('input[name="product"]').val($(this).val());
    });

    $("#check-all").change(function () {
        alert();
        if ($(this).prop('checked', true)) {
            $('.approve').each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $('.approve').each(function () {
                $(this).prop('checked', false);
            });
        }
    });

    $("#Assign").click(function () {
        $("#Status").val('Submitted');
    });
</script>
<script>
    function myFunction() {
        $("#Status").val('Submitted');
    }

    function hide() {
        $('#myModal').modal('hide');
    }

    $("#form1").submit(function (e) {

        var url = "<?php echo site_url('ASM/approveTarget'); ?>"; // the script where you handle the form input.

        $.ajax({
            type: "POST",
            url: url,
            data: $("#form1").serialize(), // serializes the form's elements.
            success: function (data)
            {
                window.location = '<?php echo site_url('ASM/target'); ?>'; // show response from the php script.
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
</script>
<!-- Modal -->

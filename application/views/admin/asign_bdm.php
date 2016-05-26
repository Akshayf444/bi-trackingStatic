<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">

    <?php echo form_open('Admin/assign_reporting_to?type=' . $view['Profile'] . '&id=' . $view['VEEVA_Employee_ID'] . '&name=' . $view['Full_Name']); ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover " id="datatable">
<?php if (!empty($bdm)) { ?>
                <thead>
                    <tr>
                        <th>VEEVA_Employee_ID</th>
                        <th>Full Name</th>
                        <th>Zone</th>
                        <th>Territory</th>
                        <th>Status</th>
                        <th><input name="toggle" type="checkbox" name="" id="selecctall"> Approve</th>



                    </tr>
                </thead>
                <tbody>
                
    <?php
    foreach ($bdm as $row) :

        if ($_GET['id'] != $row->Reporting_VEEVA_ID) {
            $status = $row->Reporting_To;
        } elseif ($_GET['id'] == $row->Reporting_VEEVA_ID) {
            $status = '<b>' . $row->Reporting_To . '</b>';
        } elseif ($row->Reporting_VEEVA_ID == "") {
            $status = ' Not Assigned ';
        }
        ?>


                    <tr>  
                        <td><?php echo $row->VEEVA_Employee_ID; ?></td>  
                        <td><?php echo $row->Full_Name; ?></td>
                        <td><?php echo $row->Zone; ?></td>
                        <td><?php echo $row->Territory; ?></td>

                        <td><?php echo $status; ?></td>
                        <td>
        <?php if ($_GET['id'] != $row->Reporting_VEEVA_ID) { ?>
                                <input type="checkbox"  class="checkbox1"   name="veeva_id[]" value="<?php echo $row->VEEVA_Employee_ID; ?> "></td> <?php } ?>
        <?php
    endforeach;
}
?>

                </tbody>
        </table>
        <button type="submit" class="btn btn-primary pull-right">Assign</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#selecctall').click(function (event) {  //on click 
            if (this.checked) { // check select status
                $('.checkbox1').each(function () { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"               
                });
            } else {
                $('.checkbox1').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                       
                });
            }
        });

    });
</script>
<script>
    var oTable = $('#datatable').dataTable({
        "bPaginate": false,
        "bInfo": false,
        "info": false,
        "columnDefs": [
            {
                "visible": false
            }
        ]
    });
</script>
<div class="panel panel-default">
    <div class="panel-body ">
        <?php
        $attributes = array('method' => 'GET');
        echo form_open('Admin/emp_view', $attributes);
        ?>
        <select name="id" class="btn btn-default">
            <option value="-1">Select Zone </option>
            <?php echo $zone; ?>
        </select>
        <select name="profile" class="btn btn-default">
            <option value="-1">Select Profile </option>
            <?php echo $profile; ?>
        </select>
        <button type="submit" class="btn btn-primary">Fetch</button>
        </form>
    </div>
</div>
<div class="panel panel-default">
    <?php
    $attributes = array('method' => 'GET');
    echo form_open('Admin/emp_view', $attributes);
    ?>
    <div class="panel-body ">

        <input type="submit" name="Export" value="Export" class="btn btn-success " >  
        <a class="btn btn-primary " onclick="window.location = '<?php echo site_url('admin/emp_add'); ?>';"><i class="fa fa-plus-circle"></i> Add Employee</a>
        <input type="button"   class="btn btn-primary " value="Import CSV" data-toggle="modal" data-target="#myModal">
<!--        <a download="employee_master<?php // echo date('dM g-i-a');       ?>.xls" class="btn btn-success " href="#" onclick="return ExcellentExport.excel(this, 'datatable', 'Sheeting');"><i class="fa fa-arrow-circle-o-right"></i> Export to Excel </a>-->
        <a class="btn btn-success" href="<?php echo asset_url() ?>empsample.csv" >CSV Upload Sample File</a>

    </div>
</form>
</div>

<table class="table table-bordered table-hover panel" id="datatable">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Territory</th>
            <th>Mobile</th>
            <th>Division</th>
            <th>Zone</th> 
            <th>Status</th> 
            <th>Action</th>
            <th>Assign</th>
            <th>Doctors</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($show)) {
            foreach ($show as $row) :
                ?><tr>  
                    <td><?php echo $row->Full_Name; ?></td>  
                    <td><?php echo $row->Territory; ?> </td> 
                    <td><?php echo $row->Mobile; ?></td>  
                    <td><?php echo $row->Division; ?></td>
                    <td><?php echo $row->Zone; ?></td>
                    <td><?php
                        if ($row->Statuss == '1') {
                            echo 'Active';
                        } if ($row->Statuss == 'locked') {
                            echo 'Locked';
                        } if ($row->Statuss == '0') {
                            echo 'InActive';
                        }

                        ;
                        ?></td>
                    <td>  
                        <a class="fa fa-trash-o btn-danger btn-xs" class=""  onclick="deleteEmp('<?php echo site_url('admin/emp_del?id=') . $row->VEEVA_Employee_ID; ?>')"></a> 
                        <a class="fa fa-pencil btn-success btn-xs" onclick="window.location = '<?php echo site_url('admin/update_emp?id=') . $row->VEEVA_Employee_ID; ?>';"></a>                                 
                    </td>
                    <td><?php if ($row->Profile == 'ASM') { ?>
                            <a class="btn btn-primary btn-xs"  onclick="window.location = '<?php echo site_url('admin/assign_reporting_to?type=BDM&id=') . $row->VEEVA_Employee_ID . '&name=' . $row->Full_Name; ?>';">Assign BDM</a> 
                        <?php } elseif ($row->Profile == 'ZSM') { ?>
                            <a class="btn btn-primary btn-xs"  onclick="window.location = '<?php echo site_url('admin/assign_reporting_to?type=ASM&id=') . $row->VEEVA_Employee_ID . '&name=' . $row->Full_Name; ?>';">Assign ASM</a> 
                        </td>
                    <?php } ?>

                    <td><?php if ($row->Profile == 'BDM') { ?><a class="fa fa-user-md btn btn-link btn-xs btn-warning" class=""  onclick="window.location = '<?php echo site_url('admin/emp_doc?id=') . $row->VEEVA_Employee_ID; ?>';"></a> <?php } ?></td>
                </tr>
                <?php
            endforeach;
        }
        ?>
    </tbody>
</table>

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
<script>
    function deletemember(id) {
        var r = confirm("Are you sure you want to delete");
        if (r == true)
        {
            $.ajax({
                type: "GET",
                url: "delete_members.php",
                data: ({id: id}),
                success: function (data)
                {
                    window.location = "yourphppage.php";
                }
            });

        }
        else
        {
            window.location = "index.php";
        }
    }

    function deleteEmp(url) {
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Import CSV</h4>
            </div> 
            <?php
            $attribute = array('enctype' => 'multipart/form-data', 'name' => 'form1', 'id' => 'form1');
            echo form_open('Admin/emp_csv', $attribute);
            ?>
            <div class="modal-body">
                <input type="hidden" name="hide" id="csv1" class="form-control" />
                <div class="form_group">
                    Choose your file: <br /> 
                    <input name="csv" type="file" id="csv" class="form-control" />
                </div>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
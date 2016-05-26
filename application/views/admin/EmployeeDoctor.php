<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-body ">
            <?php
            $attributes = array('method' => 'GET');
            echo form_open('Admin/emp_view', $attributes);
            ?>

            <select name="id" >
                <option value="-1">Select BDM </option>
                <?php echo $bdm; ?>
            </select>
            <select name="id" >
                <option value="-1">Select Zone </option>
                <?php echo $zone; ?>
            </select>

            <select name="profile" >
                <option value="-1">Select Profile </option>
                <?php echo $profile; ?>
            </select>

            <button type="submit" class="btn btn-primary">Fetch</button>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body ">
            <a class="btn btn-primary " onclick="window.location = '<?php echo site_url('admin/emp_csv'); ?>';"> Import csv</a>
            <script src="<?php echo asset_url() ?>js/excellentexport.min.js" type="text/javascript"></script>
            <a class="btn btn-primary " onclick="window.location = '<?php echo site_url('admin/emp_add'); ?>';"> Add Employee</a>
            <a download="employee_master<?php echo date('dM g-i-a'); ?>.xls" class="btn btn-success " href="#" onclick="return ExcellentExport.excel(this, 'datatable', 'Sheeting');">Export to Excel </a>
            <a download="emp.xls" class="btn btn-success" href="<?php echo asset_url() ?>empsample.xlsx" >CSV Upload Sample File</a>

        </div>
    </div>
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover " id="datatable">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Territory</th>
                        <th>Division</th>
                        <th>Zone</th> 
                        <th>Doctor Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($result)) {
                        foreach ($result as $row) :
                            ?><tr>  
                                <td><?php echo $row->Full_Name; ?></td>  
                                <td><?php echo $row->Territory; ?> </td> 
                                <td><?php echo $row->Division; ?></td>
                                <td><?php echo $row->Zone; ?></td>
                            </tr>
                            <?php
                        endforeach;
                    }
                    ?>

                </tbody>

            </table>
        </div>
    </div>
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

        function deletemember(id)
        {
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

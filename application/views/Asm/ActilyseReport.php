
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 ">
    <div class="panel-default">
        <div class="panel-body">
            <header>Hospital List</header>
    </div>
    </div>
    <div class="panel-default">
        <div class="panel-body">
        
    <table class="table table-bordered table-hover panel" id="datatable">
        <thead>
            <tr>
                <th>ASM</th>
                <th>BDM</th>
                <th>Hospital Name</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($show)) {
                foreach ($show as $row) :
                    ?><tr>  
                        <td><?php echo $row->Reporting_To; ?></td>  
                        <td><?php echo $row->Full_Name; ?> </td> 
                        <td><?php echo $row->Account_Name; ?></td>  
                        <td>  
                            <a class="fa fa-eye btn-success btn-xs" onclick="window.location = '<?php echo site_url('ASM/data_show?id=') . $row->Account_ID . '&empid=' . $row->VEEVA_Employee_ID . '&name=' . $row->Account_Name .  '' ?>';"></a>                                 
                        </td>                             


                    </tr>
        <input type="hidden" name="name" class=""
                    <?php
                endforeach;
            }
            ?>
        </tbody>
    </table>
</div>
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
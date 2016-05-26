<table class="table table-bordered table-hover panel" id="datatable">
    <thead>
        <tr >
            <th>Full Name</th>
            <th>Target</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($show)) {
            foreach ($show as $row) :
                ?><tr>  
                    <td><?php echo $row->Full_Name; ?></td>  

                    <td>  
                        <a class="btn btn-primary" onclick="window.location = '<?php echo site_url('admin/asm_target_by_bdm?id=') . $row->VEEVA_Employee_ID; ?>';">Target</a> 
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
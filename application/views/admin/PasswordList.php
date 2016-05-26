 
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 ">
    <table class="table table-bordered table-hover panel" id="datatable">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Local ID</th>
                <th>VEEVA Employee ID</th>
                <th>Password</th>

            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($show)) {
                foreach ($show as $row) :
                    ?><tr>  
                        <td><?php echo $row->Full_Name; ?></td>  
                        <td><?php echo $row->Local_Employee_ID; ?></td>  
                        <td><?php echo $row->VEEVA_Employee_ID; ?> </td> 
                        <td><?php echo $this->Encryption->decode($row->password );?></td>
                       
                    </tr>

                       
                    <?php
                endforeach;
            }
            ?>
        </tbody>
    </table>
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
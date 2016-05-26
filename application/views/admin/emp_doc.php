<div class="col-lg-12 col-md-12">
    
</div>
<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
    <div class="table-responsive">
        <table class="table table-bordered table-hover " id="datatable" >
            <thead>
                <tr>
                <tr style="background-color: #428BCA">
                    <th>S.No</th>
                    <th>Full Name</th>
                    <th>Specialty</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 0;
                if (!empty($show)) {
                    foreach ($show as $row) :
                        ?><tr>  
                            <td><?php echo ++$counter; ?></td>
                            <td><?php echo $row->Name; ?></td>  
                            <td><?php echo $row->Specialty; ?></td> 
                            
                             
                            <td> 
                               
                                <a class="fa fa-trash-o btn-danger btn-xs" class=""  onclick="deletedoc('<?php echo site_url('admin/empdoc_del?id=') . $row->Account_ID; ?>')"></a> 
                            <a class="fa fa-pencil btn-success btn-xs" onclick="window.location = '<?php echo site_url('admin/update_doc?id=') .  $row->Account_ID; ?>';"></a>               </td>
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
    
    

    function deletedoc(url) {
        var r = confirm("Are you sure you want to delete");
        if (r == true)
        {
           window.location= url;

        }
        else
        {
            return false;
        }
    }
    
   
</script>

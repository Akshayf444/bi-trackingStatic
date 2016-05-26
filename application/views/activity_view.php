<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <a class="btn btn-primary" onclick="window.location = '<?php echo site_url('admin/add_activity'); ?>';"> Add  Activity</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover " id="datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Division</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($show)) {
                        foreach ($show as $row) :
                            ?><tr>  
                                <td><?php echo $row->Activity_Name; ?></td>  

                                <td><?php echo $row->Brand_Name; ?></td>  


                                <td>  
                                    <a class="fa fa-trash-o" onclick="window.location = '<?php echo site_url('admin/act_del?id=') . $row->Activity_id; ?>';"></a> 
                                    <a class="fa fa-pencil " onclick="window.location = '<?php echo site_url('admin/update_act?id=') . $row->Activity_id; ?>';"></a> </td>
                            </tr>      <?php
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

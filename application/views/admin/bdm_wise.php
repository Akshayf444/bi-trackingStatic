<style>
    .table-view .table-view-cell {
        background-position: 0px 100%;
    }
    .col-xs-9, .col-xs-3{
        padding: 0px;
    }
    .table-view-cell {
        padding: 11px 12px 11px 15px;
    }

    #datatable_filter{
        display: none;
    }
</style>
<!--<link href="http://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="Stylesheet" type="text/css">-->
<script src="<?php echo asset_url(); ?>js/jquery-1.11.0.js" type="text/javascript"></script>



<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover " id="datatable">
                <thead>
                    <tr style="background-color: #428BCA">
                        <th>Full Name</th>
                        <th>Region</th>

                        <th>State</th>

                        <th>Actual_Rx</th>

                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if (!empty($show)) {
                        foreach ($show as $row) :
                            ?><tr>  
                                <td><?php echo $row->Full_Name; ?></td>  
                                <td><?php echo $row->Region; ?>  
                                <td><?php echo $row->State; ?></td>  
                                <td><?php echo $row->Actual_Rx; ?></td>

                                <td>  
                                    <a class="fa fa-trash-o" onclick="window.location = '<?php echo site_url('admin/emp_del?id=') . $row->VEEVA_Employee_ID; ?>';"></a> 
                                    <a class="fa fa-pencil " onclick="window.location = '<?php echo site_url('admin/update_emp?id=') . $row->VEEVA_Employee_ID; ?>';"></a> </td>
                            </tr>  <?php
                        endforeach;
                    }
                    ?>

                </tbody>

            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<script src="<?php echo asset_url(); ?>js/jquery.dataTables.min.js" type="text/javascript"></script>

<script>
                                $(document).ready(function () {
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

                                });
</script>

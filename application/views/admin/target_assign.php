<script src="<?php echo asset_url() ?>js/excellentexport.min.js" type="text/javascript"></script>
<div class="col-lg-12">
    <a download="Target_assign.xls" class="btn btn-success pull-right" href="#" onclick="return ExcellentExport.excel(this, 'datatable', 'Sheeting');">Export to Excel</a>
</div>
<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
    <div class="table-responsive">
        <table class="table table-bordered table-hover " id="datatable">
            <?php if (!empty($show)) { ?>
                <thead>
                    <tr>
                        <th>VEEVA_Employee_ID</th>
                        <th>Full Name</th>
                        <th>Zone</th>
                        <th>Territory</th>


                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($show as $row) :
                        ?><tr>  
                            <td><?php echo $row->VEEVA_Employee_ID; ?></td>  
                            <td><?php echo $row->Full_Name; ?></td>
                            <td><?php echo $row->Zone; ?></td>
                            <td><?php echo $row->Territory; ?></td>

                            <?php
                        endforeach;
                    }
                    ?>
            </tbody>
        </table>
    </div>
</div>
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
    });</script>


<script src="<?php echo asset_url() ?>js/excellentexport.min.js" type="text/javascript"></script>
<div class="col-lg-12">
    <a download="Login_history<?php echo date('dM g-i-a'); ?>.xls" class="btn btn-success pull-right" href="#" onclick="return ExcellentExport.excel(this, 'datatable', 'Sheeting');">Export to Excel</a>
</div>

<table class="table table-bordered table-hover panel " id="datatable">
    <thead>
        <tr>
            <th>VEEVA_Employee_ID</th>
            <th>Full Name</th>
            <th>Zone</th>
            <th>Division </th>
            <th>Profile</th>
            <th>Territory</th>
            <th>Last Login</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($show)) {
            foreach ($show as $row) :
                $view = $this->admin_model->login_view($row->id);
                ?><tr>  
                    <td><?php echo $row->id; ?></td>  
                    <td><?php echo $row->Full_Name; ?></td>
                    <td><?php echo $row->Zone; ?></td>
                    <td><?php echo $row->Division; ?></td>
                    <td><?php echo $row->Profile; ?></td>
                    <td><?php echo $row->Territory; ?></td>
                    <td><?php echo $row->Last_Login; ?></td>
        <!--                            <td> <a href="#" data-toggle="popover"  id=" <?php echo $row->id ?>"  data-content=" <?php
//                                foreach ($view as $rs) :
//                                    echo date('d-M-Y g:i:a', strtotime($rs->created_at)) . ',';
//
//
//                                endforeach;
                    ?>"><?Php // echo $row->COUNT; ?></a></td>-->



                </tr>
                <?php
            endforeach;
        }
        ?>
    </tbody>
</table>

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
<script>
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>

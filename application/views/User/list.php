<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered panel" id="datatable">
            <thead>
                <tr>
                    <td>VEEVA ID</td>
                    <td>Doctor Name</td>
                    <td>Specialty</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($index = 0; $index < 5; $index++) {
                    echo '<tr><td>0019000001N8CkQAAV</td><td>ABCD</td><td>ABCD</td>
                <td><input type="button" class="btn btn-success btn-xs" value="View Detail" ></td></tr>';
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
    });
</script>
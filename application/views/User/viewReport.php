<div class="row">
    <div class="col-lg-12">
        <a download="DoctorReport.xls" class="btn btn-success" href="#" onclick="return ExcellentExport.excel(this, 'datatable2', 'Sheeting');">Export to Excel</a>
    </div>
</div>

<div id="datatable4">
    <?php if (isset($result) && !empty($result)) { ?>
        <table id="datatable2" class="table table-bordered">
            <thead>
                <tr>
                    <td>EMPLOYEE CODE</td>
                    <td>EMPLOYEE NAME</td>
                    <td>DESIGNATION</td>
                    <td>HQ</td>
                    <td>STATE</td>
                    <td>SM ZONE</td>
                    <td>MOBILE NO</td>
                    <td>ASM Name</td>
                    <td>RSM Name</td>
                    <td>DOCTOR NAME</td>
                    <td>SPECIALITY</td>
                    <td>CLASS</td>
                    <td>DR MOBILE</td>
                    <td>PLACE</td>
                    <td>REGISTRATION NO</td>
                    <td>CREATED</td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($result as $bdms) {
                    echo '<tr>';
                    echo '<td>' . $bdms->psr_empid . '</td>'
                    . '<td>' . $bdms->psr_name . '</td>'
                    . '<td>' . $bdms->designation . '</td>'
                    . '<td>' . $bdms->hq . '</td>'
                    . '<td>' . $bdms->state . '</td>'
                    . '<td>' . $bdms->sm_zone . '</td>'
                    . '<td>' . $bdms->psr_mobile . '</td>'
                    . '<td>' . $bdms->asm_name . '</td>'
                    . '<td>' . $bdms->rsm_name . '</td>'
                    . '<td>' . $bdms->fname . " " . $bdms->lname . '</td>'
                    . '<td>' . $bdms->speciality . '</td>'
                    . '<td>' . $bdms->class . '</td>'
                    . '<td>' . $bdms->mobile . '</td>'
                    . '<td>' . $bdms->place . '</td>'
                    . '<td>' . $bdms->unnati_id . '</td>'
                    . '<td>' . $bdms->created_at . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <?php
    }
    ?>
</div>
<script>
    $("#btn2").click(function () {
        var dtltbl = $('#datatable4').html();
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#datatable4').html()));
    });
</script>
<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered panel" id="datatable">
            <thead>
                <tr>
                    <th>VEEVA ID</th>
                    <th>Doctor/Hospital Name</th>
<!--                    <th>Specialty</th>-->
                    <th>Individual Type</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($doctorlist) && !empty($doctorlist))
                    foreach ($doctorlist as $value) {
                        echo '<tr><td>' . $value->Account_ID . '</td>'
                        . '<td><a href="' . site_url('User/viewDetail/' . $value->Account_ID . '/' . rawurlencode(trim(str_replace(".", "", $value->Account_Name)))) . '" >' . $value->Account_Name . '</a></td>'
                        //. '<td>' . $value->Specialty . '</td>'
                        . '<td>' . $value->Individual_Type . '</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
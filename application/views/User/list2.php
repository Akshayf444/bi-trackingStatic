<?php $this->load->view('User/ProductList', array('site_url' => $site_url)); ?>
<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered panel" id="datatable">
            <thead>
                <tr>
                    <th>VEEVA ID</th>
                    <th>Doctor Name</th>
                    <th>Specialty</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($doctorlist as $value) {
                    echo '<tr><td>' . $value->Account_ID . '</td><td>' . $value->Account_Name . '</td><td>' . $value->Specialty . '</td>';
                    echo '<td>';
                    echo is_null($value->Profile_id) ? '<a href="' . site_url('User/Profiling?Product_Id=' . $this->Product_Id . '&Doctor_Id=' . $value->Account_ID) . '" class="btn btn-danger btn-xs">Add Detail</a>' : '<a href="' . site_url('User/Profiling?Product_Id=' . $this->Product_Id . '&Doctor_Id=' . $value->Account_ID) . '" class="btn btn-success btn-xs">View Detail</a>';
                    echo '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
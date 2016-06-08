<div class="row">
    <div class="col-lg-12">

        <ul class="nav nav-tabs" style="margin-bottom: 10px"  >
            <li class="active"><a style="padding: 12px;" href="#Actilyse" role="tab" data-toggle="tab">Actilyse</a>
            </li>
        </ul>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered panel">
            <tr>
                <th>VEEVA ID</th>
                <th>Doctor Name</th>
                <th>Action</th>
            </tr>
            <?php
            foreach ($show as $value) {
             
                echo '<tr><td>' . $value->Account_ID . '</td><td>' . $value->Account_Name . '</td>';
                echo '<td>';
                
               
                echo is_null($value->Actilyse_id) ? 
               '<a href="' . site_url('User/Actilyse?Actilyse_id=' . $value->Actilyse_id   . '&Doctor_Id=' . $value->Account_ID) . '" class="btn btn-danger btn-xs">Add Detail</a>' : '<a href="' . site_url('User/Actilyse?Actilyse_id=' . $value->Actilyse_id  . '&Doctor_Id=' . $value->Account_ID) . '" class="btn btn-success btn-xs">Edit Detail</a>';
                echo '</td></tr>';
            }
            ?>
        </table>

    </div>
</div>

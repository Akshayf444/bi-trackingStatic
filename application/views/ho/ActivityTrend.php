<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <?php
    $attributes = array('method' => 'get');
    echo form_open('Report/ActivityTrend', $attributes);
    ?>
    <div class="panel panel-default">
        <div class="panel-body ">
            <select name="Zone" <?php echo isset($this->ZoneDropdown) ? $this->ZoneDropdown : ''; ?>>
                <option value="">Select Zone</option>
                <?php echo $zone ?>
            </select>
            <?php echo isset($this->ZoneDropdown) ? '<input type="hidden" name="Zone" value="' . $this->Zone . '">' : ''; ?>
            <select name="Territory" >
                <option value="">Select Territory</option>
                <?php echo $Territory ?>
            </select>
            <select name="Division" <?php echo isset($this->DivisionDropdown) ? $this->DivisionDropdown : ''; ?>>
                <option value="">Select Division</option>
                <option value="Thrombi" <?php echo isset($_GET['Division']) && strtolower($_GET['Division']) == 'thrombi' ? 'Selected="Selected"' : '' ?> >Thrombi</option>
                <option value="Diabetes"  <?php echo isset($_GET['Division']) && $_GET['Division'] == 'Diabetes' ? 'Selected' : '' ?>>Diabetes</option>
            </select>
            <?php echo isset($this->DivisionDropdown) ? '<input type="hidden" name="Division" value="' . $this->Division . '">' : ''; ?>
            <select name="Product" >
                <option value="">Select Product</option>
                <?php echo $productlist ?>
            </select>

            <input type="text" class="datepicker" name="Start_date" value="<?php echo isset($_GET['Start_date']) ? $_GET['Start_date'] : ''; ?>" placeholder="Start Date">
            <input type="text" class="datepicker" name="End_date" value="<?php echo isset($_GET['End_date']) ? $_GET['End_date'] : ''; ?>" placeholder="End Date">
            <input type="submit" value="Fetch" class="btn btn-primary btn-xs">
            <input type="submit" name="Export" value="Export" class="btn btn-success btn-xs" >
        </div>
    </div>
</form>
</div>
<?php
if (isset($_GET['Product']) && $_GET['Product'] == 1) {
    $Doctorname = 'Hospital';
    $rx = 'Vials';
} elseif (isset($_GET['Product']) && $_GET['Product'] > 1) {
    $Doctorname = 'Doctor';
    $rx = 'Rx';
} else {
    $Doctorname = 'Doctor/Hospital';
    $rx = 'Rx/Vials';
}
?>
<div class="col-lg-12 table-responsive">
    <table class="table table-bordered panel">
        <tr>
            <th>Activity Name</th>
            <th>Zone</th>
            <th>Territory</th>
            <th>BDM Name</th>
            <th>BDM Code</th>
            <th>Product Name</th>
            <th>Number of Activities Planned</th>
            <th>Number of Activities completed</th>
            <th><?php echo $rx; ?> Planned for each <?php echo $Doctorname; ?></th>

            <?php
            for ($m = 1; $m <= 12; $m++) {
                $month = date('M', mktime(0, 0, 0, $m, 1, date('Y')));
                echo "<th>" . $month . " " . $this->nextYear . "</th>";
            }
            ?>
        </tr>
        <?php
        if (isset($result) && !empty($result)) {
            foreach ($result as $row) {
                //$monthlyRx = $this->User_model->monthwiseData($row->Account_ID, $this->nextYear);
                echo '<tr>'
                . '<td>' . $row->Activity_Name . '</td>'
                . '<td>' . $row->Zone . '</td>'
                . '<td>' . $row->Territory . '</td>'
                . '<td>' . $row->Full_Name . '</td>'
                . '<td>' . $row->VEEVA_Employee_ID . '</td>';
                if ($this->input->get('Product') && $this->input->get('Product') != '' && $this->input->get('Product') != 'All') {
                    echo '<td>' . $row->Brand_Name . '</td>';
                } else {
                    echo '<td>All</td>';
                }
                echo '<td class="doctorPlanned">' . $row->No_of_Doctors_planned . '</td>'
                . '<td class="check">' . $row->checkk . '</td>'
                . '<td class="Planned">' . $row->Planned_Rx . '</td>'
                . '<td>' . $row->Jan . '</td>'
                . '<td>' . $row->Feb . '</td>'
                . '<td>' . $row->Mar . '</td>'
                . '<td>' . $row->Apr . '</td>'
                . '<td>' . $row->May . '</td>'
                . '<td>' . $row->Jun . '</td>'
                . '<td>' . $row->Jul . '</td>'
                . '<td>' . $row->Aug . '</td>'
                . '<td>' . $row->Sep . '</td>'
                . '<td>' . $row->Octo . '</td>'
                . '<td>' . $row->Nov . '</td>'
                . '<td>' . $row->Decb . '</td>';

                echo '</tr>';
            }
            ?>
            <tr>    
                <th>Total</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>                
                <td class="doctorPlannedcount"></td>
                <td class="checkcount"></td>
                <td class="Plannedcount"></td>

            </tr>
        <?php }
        ?>

    </table>
</div>
<script src="<?php echo asset_url(); ?>js/datepicker.js" type="text/javascript"></script>
<script>
    $('document').ready(function () {
        var doctorPlanned = 0;
        var check = 0;
        var Planned = 0;
        
        $(".doctorPlanned").each(function () {
            doctorPlanned = doctorPlanned + parseFloat($(this).html()) || 0;
        });
        $(".check").each(function () {
            check = check + parseFloat($(this).html()) || 0;
        });
        $(".Planned").each(function () {
            Planned = Planned + parseFloat($(this).html()) || 0;
        });


        $(".doctorPlannedcount").html(doctorPlanned);
        $(".checkcount").html(check);
        $(".Plannedcount").html(Planned);

    });
</script>
<script type="text/javascript">
    $('select[name="Zone"]').change(function () {
        getTerritory();
    });
    $('select[name="Division"]').change(function () {
        getTerritory();
         getproduct();
        
        
    });


    function getTerritory() {
        var zone1 = $('select[name="Zone"]').val();
        var division1 = $('select[name="Division"]').val();
        $.ajax({
            url: '<?php echo site_url('Report/get_terr') ?>',
            data: {Division: division1, Zone: zone1},
            type: 'POST',
            success: function (data) {
          
                $('select[name="Territory"]').html(data);
            }
        });
        }
         function getproduct() {
      
        var division1 = $('select[name="Division"]').val();
        $.ajax({
            url: '<?php echo site_url('Report/get_product') ?>',
            data: {Division: division1},
            type: 'POST',
            success: function (data) {
        
                $('select[name="Product"]').html(data);
            }
        });
    }
</script>


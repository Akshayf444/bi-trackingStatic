<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <?php
    $attributes = array('method' => 'get');
    echo form_open('Report/monthlyTrend', $attributes);
    ?>
    <div class="panel panel-default">
        <div class="panel-body ">
            <select name="Zone" <?php echo isset($this->ZoneDropdown) ? $this->ZoneDropdown : ''; ?>>
                <option value="">Select Zone</option>
                <?php echo $zone ?>
            </select>
            <?php echo isset($this->ZoneDropdown) ? '<input type="hidden" name="Zone" value="' . $this->Zone . '">' : ''; ?>
            <select name="Territory">
                <option value="">Select Territory</option>
                <?php echo $Territory ?>
            </select>
            <select name="Division" <?php echo isset($this->DivisionDropdown) ? $this->DivisionDropdown : ''; ?>>
                <option value="">Select Division</option>
                <option value="Thrombi" <?php echo isset($_GET['Division']) && strtolower($_GET['Division']) == 'thrombi' ? 'Selected="Selected"' : '' ?> >Thrombi</option>
                <option value="Diabetes"  <?php echo isset($_GET['Division']) && $_GET['Division'] == 'Diabetes' ? 'Selected' : '' ?>>Diabetes</option>
            </select>
            <?php echo isset($this->DivisionDropdown) ? '<input type="hidden" name="Division" value="' . $this->Division . '">' : ''; ?>
            <select name="Product">
                <option value="">Select Product</option>
                <?php echo $productlist ?>
            </select>
            <input type="submit" value="Fetch" class="btn btn-primary btn-xs">
            <input type="submit" name="Export" value="Export" class="btn btn-success btn-xs" >
        </div>
    </div>
    <div class="panel panel-default">
        <?php
        if (isset($total_pages)) {
            for ($i = 1; $i <= $total_pages; $i++) {
                if (isset($_GET['page']) && $_GET['page'] == $i) {
                    echo '<input type="submit" name="page" class="btn btn-sm btn-primary" value="' . $i . '">';
                } else {
                    echo '<input type="submit" name="page" class="btn btn-sm" value="' . $i . '">';
                }
            }
        }
        ?>
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
<style>
    #fixeddiv {
        position: fixed;
        top: 20em;
        right: 3.5em;
        z-index: 12122;
        background: #0099ffF;
    }
    #fixeddiv2 {
        position: fixed;
        top: 20em;
        right: 78.5em;
        z-index: 12123;
        background: #0099ffF;
    }

</style>
<button id="fixeddiv" ><i class="fa fa-arrow-right"></i></button>
<button id="fixeddiv2" ><i class="fa fa-arrow-left"></i></button>
<div class="col-lg-12 ">
    <table class="table table-bordered panel">
        <tr>
            <th><?php echo $Doctorname; ?> Count </th>
            <th>Total <?php echo $rx; ?> Planned</th>
            <th>Total Activities Planned</th>
            <th>Total Activities completed</th>            
        </tr>
        <tr>            
            <td class="doctorcount"></td>
            <td class="plannedrxcount"></td>
            <td class="plancount"></td>
            <td class="completedcount"></td>            
        </tr>
    </table>
</div>
<div class="col-lg-12 outer_container" style="overflow: scroll; overflow-y: hidden; width:100%;">
    <table class="table table-bordered panel">
        <tr>
            <th>Zone</th>
            <th>Territory</th>
            <th>BDM Name</th>
            <th>BDM Code</th>
            <th><?php echo $Doctorname; ?> Code</th>
            <th><?php echo $Doctorname; ?> name </th>
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
            $fields = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Octo', 'Nov', 'Decb');
            foreach ($fields as $value) {
                ${$value} = 0;
            }
            foreach ($result as $row) {
                foreach ($fields as $value) {
                    ${$value} += $row->{$value};
                }
                //$monthlyRx = $this->User_model->monthwiseData($row->Account_ID, $this->nextYear);
                echo '<tr>'
                . '<td>' . $row->Zone . '</td>'
                . '<td>' . $row->Territory . '</td>'
                . '<td class="emp">' . $row->Full_Name . '</td>'
                . '<td>' . $row->VEEVA_Employee_ID . '</td>'
                . '<td class="doctor">' . $row->Account_ID . '</td>'
                . '<td>' . $row->Account_Name . '</td>';
                if ($this->input->get('Product') && $this->input->get('Product') != '' && $this->input->get('Product') != 'All') {
                    echo '<td>' . $row->Brand_Name . '</td>';
                } else {
                    echo '<td>All</td>';
                }

                echo '<td class="planned">' . $row->No_of_Doctors_planned . '</td>'
                . '<td class="completed">' . $row->checkk . '</td>'
                . '<td class="plannedrx">' . $row->Planned_Rx . '</td>'
                . '<td class="jan">' . $row->Jan . '</td>'
                . '<td class="feb">' . $row->Feb . '</td>'
                . '<td class="mar">' . $row->Mar . '</td>'
                . '<td class="apr">' . $row->Apr . '</td>'
                . '<td class="may">' . $row->May . '</td>'
                . '<td class="jun">' . $row->Jun . '</td>'
                . '<td class="jul">' . $row->Jul . '</td>'
                . '<td class="aug">' . $row->Aug . '</td>'
                . '<td class="sep">' . $row->Sep . '</td>'
                . '<td class="oct">' . $row->Octo . '</td>'
                . '<td class="nov">' . $row->Nov . '</td>'
                . '<td class="dec">' . $row->Decb . '</td>';

                echo '</tr>';
            }
            echo '<tr><th>Total</th>'
            . '<td></td>'
            . '<td></td>'
            . '<td></td>'
            . '<td></td>'
            . '<td></td>'
            . '<td></td>'
            . '<td class="plancount"></td>'
            . '<td class="completedcount"></td>'
            . '<td class="plannedrxcount"></td>';
            foreach ($fields as $value) {
                echo '<td>' . ${$value} . '</td>';
            }
            echo '</tr>';
        }
        ?>
    </table>
</div>
<script src="<?php echo asset_url(); ?>js/DivisionScroll.js" type="text/javascript"></script>
<script>
    $('document').ready(function () {
        var emp = 0;
        var doctor = 0;
        var planned = 0;
        var completed = 0;
        var plannedrx = 0;
        $(".emp").each(function () {
            emp = emp + 1;
        });
        $(".doctor").each(function () {
            doctor = doctor + 1;
        });
        $(".planned").each(function () {
            planned = planned + parseFloat($(this).html()) || 0;
        });
        $(".completed").each(function () {
            completed = completed + parseFloat($(this).html()) || 0;
        });
        $(".plannedrx").each(function () {
            plannedrx = plannedrx + parseFloat($(this).html()) || 0;
        });
        $(".doctorcount").html(doctor);
        $(".plancount").html(planned);
        $(".completedcount").html(completed);
        $(".plannedrxcount").html(plannedrx);

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


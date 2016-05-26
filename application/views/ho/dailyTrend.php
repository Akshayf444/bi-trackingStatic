<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <?php
    $attributes = array('method' => 'get');
    echo form_open('Report/dailyTrend', $attributes);
    ?>
    <div class="panel panel-default">
        <div class="panel-body ">
            <select name="Zone" <?php echo isset($this->ZoneDropdown) ? $this->ZoneDropdown : ''; ?> ">
                <option value="">Select Zone</option>
                <?php echo $zone ?>
            </select>
            <?php echo isset($this->ZoneDropdown) ? '<input type="hidden" name="Zone" value="' . $this->Zone . '">' : ''; ?>
            <select name="Territory" id="terr" >
                <option value="">Select Territory</option>
                <?php echo $Territory ?>
            </select>
            <select name="Division" <?php echo isset($this->DivisionDropdown) ? $this->DivisionDropdown : ''; ?> id="division1">
                <option value="">Select Division</option>
                <option value="Thrombi" <?php echo isset($_GET['Division']) && strtolower($_GET['Division']) == 'thrombi' ? 'Selected="Selected"' : '' ?> >Thrombi</option>
                <option value="Diabetes"  <?php echo isset($_GET['Division']) && $_GET['Division'] == 'Diabetes' ? 'Selected="Selected"' : '' ?>>Diabetes</option>
            </select>
            <?php echo isset($this->DivisionDropdown) ? '<input type="hidden" name="Division" value="' . $this->Division . '">' : ''; ?>
            <select name="Product" id="product1">
                <option value="">Select Product</option>
                <?php echo $productlist; ?>
            </select>
            <input name="Start_date" required="required" autocomplete="off" placeholder="Start Date" value="<?php echo isset($_GET['Start_date']) ? $_GET['Start_date'] : ''; ?>" class="datepicker">
            <input name="End_date" required="required" autocomplete="off" placeholder="End Date" value="<?php echo isset($_GET['End_date']) ? $_GET['End_date'] : ''; ?>" class="datepicker" >
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
<div class="col-lg-12 outer_container" style="overflow: scroll; overflow-y: hidden; width:100%;">
    <table class="table table-bordered panel">
        <tr>
            <th id="zone">Zone</th>
            <th>Territory</th>
            <th>BDM Name</th>
            <th>BDM Code</th>
            <th><?php echo $Doctorname; ?> Code</th>
            <th><?php echo $Doctorname; ?> name </th>
            <th>Product Name</th>
            <th>Number of Activities Planned</th>
            <th>Number of Activities completed</th>
            <th id="plan"><?php echo $rx; ?> Planned for each <?php echo $Doctorname; ?></th>
                <?php
                if (isset($_GET['Start_date'])) {
                    $start_date2 = $_GET['Start_date'];
                    $end_date = $_GET['End_date'];
                    while (strtotime($start_date2) <= strtotime($end_date)) {
                        echo '<th>' . date('d-m-Y', strtotime($start_date2)) . '</th>';
                        $start_date2 = date("Y-m-d", strtotime("+1 day", strtotime($start_date2)));
                    }
                }
                ?>
        </tr>
        <?php
        if (isset($result) && !empty($result)) {
            $totalDateArray = array();
            foreach ($result as $row) {
                $start_date1 = $_GET['Start_date'];
                //$monthlyRx = $this->User_model->daywiseRx($_GET['Start_date'], $_GET['End_date'], $row->Account_ID, $products);
                echo '<tr>'
                . '<td>' . $row->Zone . '</td>'
                . '<td>' . $row->Territory . '</td>'
                . '<td>' . $row->Full_Name . '</td>'
                . '<td>' . $row->VEEVA_Employee_ID . '</td>'
                . '<td>' . $row->Account_ID . '</td>'
                . '<td>' . $row->Account_Name . '</td>';
                if ($this->input->get('Product') && $this->input->get('Product') != '' && $this->input->get('Product') != 'All') {
                    echo '<td>' . $row->Brand_Name . '</td>';
                }
                echo '<td class="planned">' . $row->No_of_Doctors_planned . '</td>'
                . '<td class="completed">' . $row->checkk . '</td>'
                . '<td class="plannedrx">' . $row->Planned_New_Rxn . '</td>';

                while (strtotime($start_date1) <= strtotime($end_date)) {
                    echo '<td class="d' . date('Ymd', strtotime($start_date1)) . '">' . $row->{'d' . date('Ymd', strtotime($start_date1))} . '</td>';
                    $start_date1 = date("Y-m-d", strtotime("+1 day", strtotime($start_date1)));
                }
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
            $start_date1 = $_GET['Start_date'];
            while (strtotime($start_date1) <= strtotime($end_date)) {
                echo '<td class="d' . date('Ymd', strtotime($start_date1)) . 'count"></td>';
                $start_date1 = date("Y-m-d", strtotime("+1 day", strtotime($start_date1)));
            }
            echo '</tr>';
            //var_dump($totalDateArray);
        }
        ?>
    </table>
</div>
<script src="<?php echo asset_url(); ?>js/datepicker.js" type="text/javascript"></script>
<script src="<?php echo asset_url(); ?>js/DivisionScroll.js" type="text/javascript"></script>
<script>
    $('document').ready(function () {
        var emp = 0;
        var doctor = 0;
        var planned = 0;
        var completed = 0;
        var plannedrx = 0;
<?php
$start_date1 = $_GET['Start_date'];
while (strtotime($start_date1) <= strtotime($end_date)) {
    echo 'var d' . date('Ymd', strtotime($start_date1)) . ' = 0;';
    $start_date1 = date("Y-m-d", strtotime("+1 day", strtotime($start_date1)));
}
?>
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

<?php
$start_date1 = $_GET['Start_date'];
while (strtotime($start_date1) <= strtotime($end_date)) {
    echo '$(".d' . date('Ymd', strtotime($start_date1)) . '").each(function () {
            d' . date('Ymd', strtotime($start_date1)) . ' = d' . date('Ymd', strtotime($start_date1)) . ' + parseFloat($(this).html()) || 0;
        });';
    echo '$(".d' . date('Ymd', strtotime($start_date1)) . 'count").html(d' . date('Ymd', strtotime($start_date1)) . ');';
    $start_date1 = date("Y-m-d", strtotime("+1 day", strtotime($start_date1)));
}
?>
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

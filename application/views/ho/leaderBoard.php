<script src="<?php echo asset_url(); ?>js/formValidation.min.js" type="text/javascript"></script>
<script src="<?php echo asset_url(); ?>js/bootstrap.min.js" type="text/javascript"></script>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="panel panel-default">
        <?php
        $attributes = array('method' => 'get', 'id' => 'form1');
        echo form_open('Report/LeaderBoard', $attributes);
        ?>
        <div class="panel-body ">
            <select name="Zone" <?php echo isset($this->ZoneDropdown) ? $this->ZoneDropdown : ''; ?>>
                <option value="">Select Zone</option>
                <?php echo $zone ?>
            </select>
            <select name="Division" <?php echo isset($this->DivisionDropdown) ? $this->DivisionDropdown : ''; ?>>
                <option value="">Select Division</option>
                <option value="Thrombi" <?php echo isset($_GET['Division']) && $_GET['Division'] == 'Thrombi' ? 'Selected' : '' ?> >Thrombi</option>
                <option value="Diabetes"  <?php echo isset($_GET['Division']) && $_GET['Division'] == 'Diabetes' ? 'Selected' : '' ?>>Diabetes</option>
            </select>
            <select name="Product">
                <option value="">Select Product</option>

                <?php echo $productlist ?>
            </select>
            <select name="Parameter">
                <option value="">Select Parameter</option>
                <?php echo $Parameter; ?>
            </select>
            <input name="Start_date" required="required" autocomplete="off" placeholder="Start Date" value="<?php echo isset($_GET['Start_date']) ? $_GET['Start_date'] : ''; ?>" class="datepicker">
            <input name="End_date" required="required" autocomplete="off" placeholder="End Date" value="<?php echo isset($_GET['End_date']) ? $_GET['End_date'] : ''; ?>" class="datepicker" >

            <input type="Submit" value="Fetch" class="btn btn-primary btn-sm">
        </div>
        </form>
    </div>
</div>
<div class="col-lg-12">
    <table class="table table-bordered panel">
        <tr>
            <th>Zone</th>
            <th>Territory</th>
            <th>BDM Name</th>
            <th>Score</th>
        </tr>
        <?php
        if (isset($result) && !empty($result)) {
            foreach ($result as $row) {
                echo '<tr>'
                . '<td>' . $row->Zone . '</td>'
                . '<td>' . $row->Territory . '</td>'
                . '<td>' . $row->Full_Name . '</td>';
                $score = 0;
                if (isset($_GET['Parameter']) && $_GET['Parameter'] == 1) {
                    $score = $row->KPI;
                } elseif (isset($_GET['Parameter']) && $_GET['Parameter'] == 3) {
                    $score = $row->Actual_Rx;
                } elseif (isset($_GET['Parameter']) && $_GET['Parameter'] == 2) {
                    $score = $row->KPI;
                } elseif (isset($_GET['Parameter']) && $_GET['Parameter'] == 5) {
                    $score = $row->Doctor_engaged;
                }
                echo '<td>' . number_format($score, 1, '.', '') . '</td>';
                echo '</tr>';
            }
        }
        ?>
    </table>
</div>
<script src="<?php echo asset_url(); ?>js/datepicker.js" type="text/javascript"></script>
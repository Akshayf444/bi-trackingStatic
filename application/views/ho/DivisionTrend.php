<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <?php
    $attributes = array('method' => 'get');
    echo form_open('Report/diabetesTrend', $attributes);
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
                <?php echo isset($Territory) ? $Territory : '' ?>
            </select>
            <input name="Start_date" required="required" autocomplete="off" placeholder="Start Date" value="<?php echo isset($_GET['Start_date']) ? $_GET['Start_date'] : ''; ?>" class="datepicker">
            <input name="End_date" required="required" autocomplete="off" placeholder="End Date" value="<?php echo isset($_GET['End_date']) ? $_GET['End_date'] : ''; ?>" class="datepicker" >
            <input type="submit" value="Fetch" class="btn btn-primary btn-xs">
            <input type="submit" name="Export" value="Export" class="btn btn-success btn-xs" >
        </div>
    </div>
</form>
</div>
<div class="col-lg-12">
    <table class="table table-bordered panel">
        <tr>
            <th>Total DPP4</th>
            <th>Total SGLT2</th>
            <th>Total Trajenta Planned</th>
            <th>Total Trajenta Achieved</th>
            <th>Total Jardiance Planned</th>
            <th>Total Jardiance Achieved</th>
            <th>Total TrajentaDuo Planned</th>
            <th>Total TrajentaDuo Achieved</th>      
        </tr>
        <tr>            
            <td class="DPP4count"></td>
            <td class="SGLTcount"></td>
            <td class="TrajentaPlannedcount"></td>
            <td class="trajenta_rxcount"></td>            
            <td class="JardiancePlannedcount"></td>            
            <td class="jardiance_rxcount"></td>            
            <td class="TrajentaDuoPlannedcount"></td>            
            <td class="trajentaduo_rxcount"></td>            
        </tr>
    </table>
</div>
<div class="col-lg-12 table-responsive">
    <table class="table table-bordered panel">
        <tr>
            <th>Zone</th>
            <th>Territory</th>
            <th>BDM Name</th>
            <th>BDM Code</th>
            <th>DPP4</th>
            <th>SGLT2</th>
            <th>Trajenta Planned</th>
            <th>Trajenta Achieved</th>
            <th>Jardiance Planned</th>
            <th>Jardiance Achieved</th>
            <th>TrajentaDuo Planned</th>
            <th>TrajentaDuo Achieved</th>
        </tr>
        <?php
        if (isset($result) && !empty($result)) {
            $fields = array('DPP4', 'SGLT', 'TrajentaPlanned', 'trajenta_rx', 'JardiancePlanned', 'jardiance_rx', 'TrajentaDuoPlanned', 'trajentaduo_rx');
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
                . '<td>' . $row->Full_Name . '</td>'
                . '<td>' . $row->VEEVA_Employee_ID . '</td>'
                . '<td class="DPP4">' . $row->DPP4 . '</td>'
                . '<td class="SGLT">' . $row->SGLT . '</td>'
                . '<td class="TrajentaPlanned">' . $row->TrajentaPlanned . '</td>'
                . '<td class="trajenta_rx">' . $row->trajenta_rx . '</td>'
                . '<td class="JardiancePlanned">' . $row->JardiancePlanned . '</td>'
                . '<td class="jardiance_rx">' . $row->jardiance_rx . '</td>'
                . '<td class="TrajentaDuoPlanned">' . $row->TrajentaDuoPlanned . '</td>'
                . '<td class="trajentaduo_rx">' . $row->trajentaduo_rx . '</td>';

                echo '</tr>';
            }
            ?>
            <tr>    
                <td>Total</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="DPP4count"></td>
                <td class="SGLTcount"></td>
                <td class="TrajentaPlannedcount"></td>
                <td class="trajenta_rxcount"></td>            
                <td class="JardiancePlannedcount"></td>            
                <td class="jardiance_rxcount"></td>            
                <td class="TrajentaDuoPlannedcount"></td>            
                <td class="trajentaduo_rxcount"></td>            
            </tr>
            <script>
                $('document').ready(function () {
                    $(".DPP4count").html(<?php echo isset($DPP4) ? $DPP4 : 0; ?>);
                    $(".SGLTcount").html(<?php echo isset($SGLT) ? $SGLT : 0; ?>);
                    $(".TrajentaPlannedcount").html(<?php echo isset($TrajentaPlanned) ? $TrajentaPlanned : 0; ?>);
                    $(".trajenta_rxcount").html(<?php echo isset($trajenta_rx) ? $trajenta_rx : 0; ?>);
                    $(".JardiancePlannedcount").html(<?php echo isset($JardiancePlanned) ? $JardiancePlanned : 0; ?>);
                    $(".jardiance_rxcount").html(<?php echo isset($jardiance_rx) ? $jardiance_rx : 0; ?>);
                    $(".TrajentaDuoPlannedcount").html(<?php echo isset($TrajentaDuoPlanned) ? $TrajentaDuoPlanned : 0; ?>);
                    $(".trajentaduo_rxcount").html(<?php echo isset($trajentaduo_rx) ? $trajentaduo_rx : 0; ?>);
                });
            </script>
            <?php
        }
        ?>
    </table>
</div>
<script src="<?php echo asset_url(); ?>js/datepicker.js" type="text/javascript"></script>

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

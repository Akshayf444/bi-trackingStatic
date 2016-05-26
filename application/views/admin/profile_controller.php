<?php echo form_open('Admin/TabControl'); ?>
<table class="table table-bordered table-hover panel">
    <tr>
        <th>BDM Name</th>
        <th colspan="2">Profiling</th>
        <th colspan="2">Planning</th>
        <th colspan="2">Reporting For Activities</th> 
        <th colspan="2">Reporting For Prescription</th> 
    </tr>
    <tr>
        <th></th>
        <th><input type="radio" id="pf" name="check">Unlock</th><th><input type="radio" id="pfl" name="check">Lock</th>
        <th><input type="radio" id="pl" name="check1">Unlock</th><th><input type="radio" id="pll" name="check1">Lock</th>
        <th><input type="radio" id="ra" name="check2">Unlock</th><th><input type="radio" id="ral" name="check2">Lock</th>
        <th><input type="radio" id="rp" name="check3">Unlock</th><th><input type="radio" id="rpl" name="check3">Lock</th>

    </tr>

    <?php
    if (!empty($List)) {
        foreach ($List as $row) :
            ?><tr>  
                <td><?php echo $row->Full_Name; ?><input type="hidden" name="VEEVA_Employee_ID[]" value="<?php echo $row->VEEVA_Employee_ID ?>"></td>  
                <td><input type="hidden" name="ProfileExist<?php echo $row->VEEVA_Employee_ID ?>" value="<?php echo (isset($row->Tab1) && !is_null($row->Tab1)) ? 1 : 0; ?>">
                    <input type="radio" value="1" <?php echo (isset($row->Tab1) && !is_null($row->Tab1) && $row->Tab1 == 1) ? 'checked' : ''; ?> name="<?php echo $row->VEEVA_Employee_ID . 'Tab1' ?>" class="pf"></td>
                <td><input type="radio" value="0" name="<?php echo $row->VEEVA_Employee_ID . 'Tab1' ?>" <?php echo (isset($row->Tab1) && !is_null($row->Tab1) && $row->Tab1 == 0) ? 'checked' : ''; ?> class="pfl"></td>
                <td><input type="radio" value="1" name="<?php echo $row->VEEVA_Employee_ID . 'Tab2' ?>" <?php echo (isset($row->Tab2) && !is_null($row->Tab2) && $row->Tab2 == 1) ? 'checked' : ''; ?> class="pl"></td>
                <td><input type="radio" value="0" name="<?php echo $row->VEEVA_Employee_ID . 'Tab2' ?>" <?php echo (isset($row->Tab2) && !is_null($row->Tab2) && $row->Tab2 == 0) ? 'checked' : ''; ?> class="pll"></td>
                <td><input type="radio" value="1" name="<?php echo $row->VEEVA_Employee_ID . 'Tab3' ?>" <?php echo (isset($row->Tab3) && !is_null($row->Tab3) && $row->Tab3 == 1) ? 'checked' : ''; ?> class="ra"></td>
                <td><input type="radio" value="0" name="<?php echo $row->VEEVA_Employee_ID . 'Tab3' ?>" <?php echo (isset($row->Tab3) && !is_null($row->Tab3) && $row->Tab3 == 0) ? 'checked' : ''; ?> class="ral"></td>
                <td><input type="radio" value="1" name="<?php echo $row->VEEVA_Employee_ID . 'Tab4' ?>" <?php echo (isset($row->Tab4) && !is_null($row->Tab4) && $row->Tab4 == 1) ? 'checked' : ''; ?> class="rp"></td>
                <td><input type="radio" value="0" name="<?php echo $row->VEEVA_Employee_ID . 'Tab4' ?>" <?php echo (isset($row->Tab4) && !is_null($row->Tab4) && $row->Tab4 == 0) ? 'checked' : ''; ?> class="rpl"></td>

                <?php
            endforeach;
        }
        ?>

</table>
<input type="submit" class="btn btn-primary" value="Save">
</form>
<script>
    $('#pf').click(function (e) {
        $(this).closest('table').find('td .pf').prop('checked', this.checked);
    });

    $('#pfl').click(function (e) {
        $(this).closest('table').find('td .pfl').prop('checked', this.checked);
    });
    $('#pl').click(function (e) {
        $(this).closest('table').find('td .pl').prop('checked', this.checked);
    });
    $('#pll').click(function (e) {
        $(this).closest('table').find('td .pll').prop('checked', this.checked);
    });
    $('#ra').click(function (e) {
        $(this).closest('table').find('td .ra').prop('checked', this.checked);
    });
    $('#ral').click(function (e) {
        $(this).closest('table').find('td .ral').prop('checked', this.checked);
    });
    $('#rp').click(function (e) {
        $(this).closest('table').find('td .rp').prop('checked', this.checked);
    });
    $('#rpl').click(function (e) {
        $(this).closest('table').find('td .rpl').prop('checked', this.checked);
    });
</script>




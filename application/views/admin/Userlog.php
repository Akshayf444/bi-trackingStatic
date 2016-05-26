<div class="panel panel-body">
    <?php echo form_open('Admin/Userlog') ?>

    <select name="Profile" class="btn btn-default">
        <option>Select Profile</option>
        <?php echo isset($Profile) ? $Profile : ''; ?>
    </select>
    <input name="Start_date" autocomplete="off" placeholder="Start Date" value="<?php echo isset($_POST['Start_date']) ? $_POST['Start_date'] : ''; ?>" class="datepicker ">
    <input name="End_date" autocomplete="off" placeholder="End Date" value="<?php echo isset($_POST['End_date']) ? $_POST['End_date'] : ''; ?>" class="datepicker " >

    <input type="submit" value="Fetch" class="btn btn-primary">

    </form>
</div>

    <table class="table table-bordered panel">
        <tr>
            <th>Zone</th>
            <th>Territory</th>
            <th>Name</th>
            <th>Description</th>
            <th>Profile</th>
            <th>Ip Address</th>
            <th>Date</th>
        </tr>
        <?php
        if (isset($logs) & !empty($logs)) {
            foreach ($logs as $log) {
                echo '<tr>'
                . '<td>' . $log->Zone . '</td>'
                . '<td>' . $log->Territory . '</td>'
                . '<td>' . $log->Full_Name . '</td>'
                . '<td>' . $log->description . '</td>'
                . '<td>' . $log->Profile . '</td>'
                . '<td>' . $log->ip_address . '</td>'
                . '<td>' . date('d-m-Y h:i A', strtotime($log->date)) . '</td>'
                . '</tr>';
            }
        }
        ?>
    </table>

<script>
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
</script>
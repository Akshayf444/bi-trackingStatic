<div class="col-lg-12">
    <div class="pull-right">
        <select id="month">
            <option value="-1">Select Month</option>
            <?php
            $month = $this->Master_Model->generateDropdown($this->User_model->getMonthObject(), 'month', 'monthname');
            echo $month;
            ?>
        </select>
        <select id="year">
            <option value="-1">Select Year</option>
            <?php
            $year = $this->Master_Model->generateDropdown($this->User_model->getYearObject(), 'Year', 'Yearname');
            echo $year;
            ?>
        </select>
<!--        <input type="button" id="fetch" class="btn btn-primary" value="Fetch">-->
    </div>
    <ul align="center" class="nav nav-tabs ">

        <li class="active"> <a data-toggle="tab" style="padding: 12px;" onclick="getTabDetails('planning')" href="#planning">Planning Status</a></li>
        <li> <a data-toggle="tab" style="padding: 12px;" onclick="getTabDetails('activityplan')" href="#activityplan">Activity Planning Status</a></li>
        <li> <a data-toggle="tab" style="padding: 12px;" onclick="getTabDetails('rxreport')" href="#rxreport">Rx Report Status</a></li>
        <li> <a data-toggle="tab" style="padding: 12px;" onclick="getTabDetails('activityreport')" href="#activityreport">Activity Reporting Status</a></li>
        <li><a data-toggle="tab" style="padding: 12px;" onclick="getTarget()" href="#target" >Target Assigned</a></li>
        <li id="loader" style="display:none"><img src="<?php echo asset_url(); ?>images/loader.gif" alt=""/></li>
    </ul>
    <div class="tab-content" id="result">
        <div id="planning" class="tab-pane fade in active">

        </div>
    </div>

</div>
<script>

    function getTabDetails(tab) {
        var month = $("#month").val();
        var year = $("#year").val();
        $("#loader").show();
        $.ajax({
            //Send request
            type: 'GET',
            data: {tabName: tab, month: month, year: year},
            url: '<?php
            $Zone = isset($_GET['Zone']) && $_GET['Zone'] != '' && $_GET['Zone'] != '-1' ? $_GET['Zone'] : '';
            $Division = isset($_GET['Division']) && $_GET['Division'] != '' ? $_GET['Division'] : '';
            echo site_url('Report/getSystemStatus?Zone=' . $Zone . "&Division=" . $Division);
            ?>',
            success: function (data) {
                // alert(data);
                $("#loader").hide();
                $("#result").html(data);
            }
        });
    }

    $('#fetch').click(function () {
        getTabDetails('planning')
    });
    function getTarget() {
        var month = $("#month").val();
        var year = $("#year").val();
        $("#loader").show();
        $.ajax({
            //Send request
            type: 'GET',
            data: {month: month, year: year},
            url: '<?php
            $Zone = isset($_GET['Zone']) && $_GET['Zone'] != '' && $_GET['Zone'] != '-1' ? $_GET['Zone'] : '';
            $Division = isset($_GET['Division']) && $_GET['Division'] != '' ? $_GET['Division'] : '';
            echo site_url('Report/TargetAssigned?Zone=' . $Zone . "&Division=" . $Division);
            ?>',
            success: function (data) {
                $("#loader").hide();
                $(".tab-content").html(data);
            }
        });
    }
</script>

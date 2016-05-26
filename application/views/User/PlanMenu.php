<style>

    a{
        color: #000;
    }
    .panel{
        margin-bottom: 10px;
        margin-top: 0px;
    }
    .panel-body{
        border-radius: 9px;
    }
</style>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="panel panel-default" style="border-color: #fff;">
        <div class="panel-body">
            <a style="font-weight: 700;" onclick="window.location = '<?php echo site_url('User/Planning'); ?>'" >
                <?php
                if ($this->Product_Id == '1') {
                    echo "Vials";
                } else {
                    echo "Rx";
                }
                ?> Planning 
            </a>

        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="panel panel-default" style="border-color: #fff;">
        <div class="panel-body">
            <a style="margin: 28px 0px 0px 0px;font-weight: 700;" onclick="window.location = '<?php echo site_url('User/ActivityPlanning'); ?> '" >
                Activity Planning
            </a>
            <span class="pull-right" >Activity Planned : <?php echo isset($activity_planned['activity_planned']) ? $activity_planned['activity_planned'] : 0; ?> </span><br>
            <span class="pull-right" >No Of Prioritize <?php
                if ($this->Product_Id == 1) {
                    echo "Hospital";
                } else {
                    echo "Dr";
                }
                ?>  : <?php echo isset($prio_dr['doctor_id']) ? $prio_dr['doctor_id'] : 0; ?> </span>
        </div>
    </div>
</div>
<!--<div class="card">
    <ul class="table-view">
        <li class="table-view-cell">
            <a class="navigate-right" onclick="window.location = '<?php echo site_url('User/Planning'); ?>';" >
<?php
if ($this->Product_Id == '1') {
    echo "Vials";
} else {
    echo "Rx";
}
?> Planning 
            </a>
        </li>
    </ul>
</div>
<div class="card">
    <ul class="table-view">
        <li class="table-view-cell">
            <a class="navigate-right"  onclick="window.location = '<?php echo site_url('User/ActivityPlanning'); ?>';" >
                Activity Planning
            </a>
            <span class="pull-right" style="    margin-top: -20px;">Activity Planned : <?php echo isset($activity_planned['activity_planned']) ? $activity_planned['activity_planned'] : 0;
?> </span><br>
            <span class="pull-right" style="margin-top: -15px;">No Of Prioritize <?php
if ($this->Product_Id == 1) {
    echo "Hospital";
} else {
    echo "Dr";
}
?>  : <?php echo isset($prio_dr['doctor_id']) ? $prio_dr['doctor_id'] : 0; ?> </span>
        </li>
    </ul>
</div>-->


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">Asm Comment</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th> ASM Comment</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($asm_comment)) {
                            foreach ($asm_comment as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row->created_at ?> </td>
                                    <td><?php echo $row->Comment ?> </td>
                                    <td><?php echo $row->Comment_type ?> </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
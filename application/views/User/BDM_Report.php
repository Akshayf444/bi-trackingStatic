<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table-bordered">
                <tr>
                    <th>Doctor Name</th>
                    <th>Rx Actual</th>
                    <th>Rx Planning</th>
                </tr>
                <?php foreach($detail as $d):?>
                <tr>
                    <td><?php echo $d->doctor_name ;?></td>
                    <td><?php echo $d->rx_actual ;?></td>
                    <td><?php echo $d->rx_planned ;?></td>
                </tr>
                <?php endforeach;?>
            </table>
        </div>
    </div>
</div>

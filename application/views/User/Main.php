<script src="<?php echo asset_url() ?>js/knob.js" type="text/javascript"></script>
<script src="<?php echo asset_url() ?>js/jquery.knob.js" type="text/javascript"></script>
<link href="<?php echo asset_url() ?>css/style.css" rel="stylesheet" type="text/css"/>
<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        border-top: 0px solid #dddddd;
    }
    audio, progress, video {

        margin-left: 208px;
        margin-top: 0px;
        margin-bottom: 0px;
    }
    audio,#kp1, progress, video {
        height: 90px;
        margin-left: 208px;
        margin-top: -12px;
        margin-bottom: -22px;
    }

    a{
        color: #000;
    }

    .panel{
        margin-bottom: 10px;
        margin-top: 0px;
    }
    canvas{
        width: 84px;
        height: 89px;
    }


</style>
<div align="center" class="col-lg-12 col-md-12 col-sm-12">
    <div class="panel panel-default" >
        <div class="panel-body" >
            <div class="col-sm-4 col-md-4 col-lg-4"></div>
            <?php echo form_open('User/dashboard'); ?>
            <div class="col-sm-4 col-md-4 col-lg-4">
                <select name="Product_Id" class="form-control" onchange="this.form.submit()">
                    <option value="-1">Select Product</option>
                    <?php echo $productList ?>
                </select>
            </div>
            <div class="col-sm-4"></div>
            </form>

        </div>
    </div>
</div>

<?php echo isset($tab1) ? $tab1 : ''; ?>

<?php
if ($this->Product_Id == '-1' || $this->Product_Id == '') {
    
} else {
    ?>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default" > 
            <div class="panel-heading"><?php

                if ($this->Product_Id == '1') {
                    echo "<b>Actilyse</b>";
                } elseif ($this->Product_Id == '2') {
                    echo "<b>Pradaxa</b>";
                } elseif ($this->Product_Id == '3') {
                    echo "<b>Metalyse</b>";
                } elseif ($this->Product_Id == '4') {
                    echo "<b>Trajenta Family</b>";
                } elseif ($this->Product_Id == '5') {
                    echo "<b>Jardiance</b>";
                } elseif ($this->Product_Id == '6') {
                    echo "<b>Trajenta Duo</b>";
                }
                ?></div>
            <?php
            $rxlabel = isset($Product_Id) && $Product_Id == 1 ? 'Vials' : 'Rx';
            if ($this->Product_Id == '1') {
                echo "<div class='panel-body' style='background-color: #79B61C;'>";
            } elseif ($this->Product_Id == '2') {
                echo "<div class='panel-body' style='background-color: #4E88BC;'>";
            } elseif ($this->Product_Id == '3') {
                echo "<div class='panel-body' style='background-color: #EFC083;'>";
            } elseif ($this->Product_Id == '4') {
                echo "<div class='panel-body' style='background-color:  #87CEEB'>";
            } elseif ($this->Product_Id == '5') {
                echo "<div class='panel-body' style='background-color: #20B2AA;'>";
            } elseif ($this->Product_Id == '6') {
                echo "<div class='panel-body' style='background-color: #9999FF;'>";
            }
            ?>
            <div class="panel-body">
                <table class=" table" style="margin-left: -87px;">
                    <tr>

                        <th  style="text-align:right">
                            <?php
                            if ($this->Product_Id == '1') {

                                echo "Actilyse";
                            } elseif ($this->Product_Id == '2') {
                                echo "Pradaxa";
                            } elseif ($this->Product_Id == '3') {
                                echo "Metalyse";
                            } elseif ($this->Product_Id == '4') {
                                echo "Trajenta Family";
                            } elseif ($this->Product_Id == '5') {
                                echo "Jardiance";
                            } elseif ($this->Product_Id == '6') {
                                echo "Trajenta duo";
                            }
                            ?>
                        </th>
                        <th  style="text-align:center"><?php echo date('M',strtotime('-4 month'));?></th>
                        <th  style="text-align:center"><?php echo date('M',strtotime('-3 month'));?></th>
                        <th  style="text-align:center"><?php echo date('M',strtotime('-2 month'));?></th>
                        <th><?php echo date('M',strtotime('-1 month'));?></th>
                    </tr>

                    <tr>
                        <th  style="text-align:right">Users</th>
                        <td style="text-align:center"><?php echo $user4['doctor_count'] ?></td>
                        <td style="text-align:center"><?php echo $user3['doctor_count'] ?></td>
                        <td style="text-align:center"><?php echo $user2['doctor_count'] ?></td>
                        <td><?php echo $user1['doctor_count'] ?></td>
                    </tr>
                    <tr>
                        <th  style="text-align:right"><?php
                            if ($this->Product_Id == '1') {
                                echo "Vials";
                            } else {
                                echo "Rx";
                            }
                            ?></th>


                        <td style="text-align:center"><?php echo $month4['Actual_Rx'] ?></td>
                        <td style="text-align:center"><?php echo $month3['Actual_Rx'] ?></td>
                        <td style="text-align:center"><?php echo $month2['Actual_Rx'] ?></td>
                        <td><?php echo $month1['Actual_Rx'] ?></td>
                    </tr>
                </table>


            </div>
        </div>
    </div>
<?php } ?>
<style>
    .progress{
        height: 25px;
    }
    .progress .planning{
        height: 5px;
    }
    .progress .progress-bar{
        font-size: 15px;
        vertical-align: central;
        line-height: 25px;
    }
    .achievement {
        padding: 11px 15px 11px 15px;
    }
    #kp2{
        display: none
    }
    #kp1{
        display: none
    }
</style>
<?php
if ($this->Product_Id == '-1' || $this->Product_Id == '') {
    
} else {
    ?>

    <div class="panel panel-default"> 
        <div class="panel-heading">
            Achievement Of Jan 2016
        </div>

        <div class="panel-body " style="padding-bottom: 31px;">
            <div class="col-lg-1 col-xs-2 col-md-2">
                <select class="form-control" class="cycle" id="cycle">
                   
                    <option>  Cycle <?php echo  $this->Cycle ?></option>
                 
                </select>
            </div>
            <div class="col-lg-5 col-md-5 col-xs-5">

                <div class="demo" >        
                    <input class="knob"  readonly="" id="kp1" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="<?php echo round($kpi1, 2); ?>">
                    <span style="margin-left: -48px;position: absolute;margin-top: 63px;"><?php echo round($kpi1, 2); ?>%</span>
                    <span style="margin-left: -57px;position: absolute;margin-top: 80px;">KPI 1</span>
                    <span style="margin-left: -109px;position: absolute;margin-top: 93px;"><?php echo $rxlabel; ?> Actual / <?php echo $rxlabel; ?> Target</span>


                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-xs-5">
                <div class="demo" >       
                    <input class="knob" id="kp2"  readonly="" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="<?php echo round($kpi2, 2); ?>">
                    <span style="margin-left: -59px;position: absolute;margin-top: 69px;"><?php echo round($kpi2, 2); ?>%</span>
                    <span style="margin-left: -62px;position: absolute;margin-top: 84px;">KPI 2</span>
                    <span style="margin-left: -157px;position: absolute;margin-top: 96px;"><?php

                        if ($this->Product_Id == 1) {
                            echo "Hospital";
                        } else {
                            echo "Doctor";
                        }
                        ?> Engaged in Activity / Planned</span>
                </div>
            </div>
        </div>
    </div>

<?php } ?>

<script>



    $(document).ready(function () {
        var initval = parseInt($('#profile').val(), 10);
        //alert(initval);
        $({value: 0}).animate({value: initval}, {
            duration: 1000,
            easing: 'swing',
            step: function ()
            {
                $('#1').val(initval).trigger('change');
                $('#preval').val(initval);
            }
        });
    });
    $(document).ready(function () {
        var initval = 20;
        $({value: 0}).animate({value: initval}, {
            duration: 1000,
            easing: 'swing',
            step: function ()
            {
                $('#2').val(this.value).trigger('change');
                $('#preval').val(initval);
            }
        });
    });
    $(document).ready(function () {
        var initval = 20;
        $({value: 0}).animate({value: initval}, {
            duration: 1000,
            easing: 'swing',
            step: function ()
            {
                $('#3').val(this.value).trigger('change');
                $('#preval').val(initval);
            }
        });
    });
    $(document).ready(function () {
        var initval = <?php echo round($tot); ?>;
        $({value: 0}).animate({value: initval}, {
            duration: 1000,
            easing: 'swing',
            step: function ()
            {
                $('#4').val(this.value).trigger('change');
                $('#preval').val(initval);

            }
        });
    });
    $(document).ready(function () {
        var initval = <?php echo round($tot1); ?>;
        $({value: 0}).animate({value: initval}, {
            duration: 1000,
            easing: 'swing',
            step: function ()
            {
                $('#5').val(this.value).trigger('change');
                $('#preval').val(initval);
            }
        });
    });
    $(document).ready(function () {
        var initval = <?php echo round($kpi1, 2); ?>;
        $({value: 0}).animate({value: initval}, {
            duration: 1000,
            easing: 'swing',
            step: function ()
            {
                $('#kp1').val(this.value).trigger('change');
                $('#preval').val(initval);
            }
        });
    });
    $(document).ready(function () {
        var initval = <?php echo round($kpi2, 2); ?>;
        $({value: 0}).animate({value: initval}, {
            duration: 1000,
            easing: 'swing',
            step: function ()
            {
                $('#kp2').val(this.value).trigger('change');
                $('#preval').val(initval);
            }
        });
    });


</script>
<script type="text/javascript">
    $('#cycle').change(function () {
        var cycle = $(this).val();
        $.ajax({
            url: '<?php echo site_url('user/ajax_data') ?>',
            data: {cycle: cycle},
            type: 'POST',
            success: function (data) {
                $("#reporting_to").empty();
                $("#reporting_to").append('<option value="-1">Select Reporting To</option>');
                $('#reporting_to').append(data);    //please note this, here we're focusing in that input field
            }
        });
    });

   


</script>
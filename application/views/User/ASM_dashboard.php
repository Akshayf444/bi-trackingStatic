
<link href="<?php echo asset_url() ?>css/owl.carousel.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo asset_url() ?>css/owl.transitions.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo asset_url() ?>css/responsive.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo asset_url() ?>js/knob.js" type="text/javascript"></script>
<script src="<?php echo asset_url() ?>js/jquery.knob.js" type="text/javascript"></script>
<link href="<?php echo asset_url() ?>css/style.css" rel="stylesheet" type="text/css"/>
<style>

    #owl-demo .item img{
        display: block;
        width: 100%;
        height: auto;
    }
    #owl-demo2 .item img{
        display: block;
        width: 100%;
        height: auto;
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        border-top: 0px solid #dddddd;
    }
    audio, canvas, progress, video {
        height: 90px;
        margin-left: 208px;
        margin-top: 2px;
        margin-bottom: 30px;
    }
    audio,#kp1, progress, video {
        height: 90px;
        margin-left: 208px;
        margin-top: -12px;
        margin-bottom: -22px;
    }
    a{
        font-weight: bold;
    }
</style>



<div class="card">
    <ul class="table-view">
        <li class="table-view-cell" style="margin-bottom: -32px;">
            <a class="navigate-right" onclick="window.location = '<?php echo site_url(); ?>';"  style="margin-bottom: -61px;margin-top: 11px;" > hospital Profiling </a>

    <div class="demo pull-right">
                <input type="hidden" id="profile" value="">
                <input class="knob" id="1" style="display: none;" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="">
                <span style="margin-left: 100px;position: absolute;margin-top: -46px;"></span>
            </div>
        </li>
    </ul>
</div>
<div class="card">
    <ul class="table-view">
        <li class="table-view-cell" style="margin-bottom: -32px;">
            <a class="navigate-right" style="    margin-bottom: -61px;margin-top: 11px;"  onclick="window.location = " > hospital Profiling </a>
            <div class="demo pull-right">
                <input type="hidden" id="profile" value="">
                <input class="knob" id="1" style="display: none;" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="">
                <span style="margin-left: 100px;position: absolute;margin-top: -46px;"></span>
            </div>
        </li>
    </ul>
</div>
<div class="card">
    <ul class="table-view">
        <li class="table-view-cell" style="margin-bottom: -32px;">
            <a class="navigate-right" style="    margin-bottom: -61px;margin-top: 11px;"  onclick="window.location = " > hospital Profiling </a>
            <div class="demo pull-right">
                <input type="hidden" id="profile" value="">
                <input class="knob" id="1" style="display: none;" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="">
                <span style="margin-left: 100px;position: absolute;margin-top: -46px;"></span>
            </div>
        </li>
    </ul>
</div>
<div class="card">
    <ul class="table-view">
        <li class="table-view-cell" style="margin-bottom: -32px;">
            <a class="navigate-right" style="    margin-bottom: -61px;margin-top: 11px;"  onclick="window.location = " > hospital Profiling </a>
            <div class="demo pull-right">
                <input type="hidden" id="profile" value="">
                <input class="knob" id="1" style="display: none;" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="">
                <span style="margin-left: 100px;position: absolute;margin-top: -46px;"></span>
            </div>
        </li>
    </ul>
</div>



<?php //echo isset($tab1) ? $tab1 : ''; ?>


<!--<div class="card">
    <ul class="table-view">
        <li class="table-view-cell">
            <a class="navigate-right" onclick="window.location = '<?php echo site_url('User/productSel'); ?>';" >
                View Status
            </a>
        </li>
    </ul>
</div>
<div class="card">
    <ul class="table-view">
        <li class="table-view-cell">
            <a class="navigate-right" onclick="window.location = '<?php echo site_url('User/view_status'); ?>';" >
                View Result
            </a>
        </li>
    </ul>
</div>-->

<div class="item card" style="text-align: center" > 



    <li class="table-view-cell media">
        <table class="slider-table table" style="margin-left: -87px;">
            <tr>

                <th  style="text-align:right"><?php
//        if ($this->Product_Id == '1') {
//
//            echo "Actilyse";
//        } elseif ($this->Product_Id == '2') {
//            echo "Pradaxa";
//        } elseif ($this->Product_Id == '3') {
//            echo "Metalyse";
//        } elseif ($this->Product_Id == '4') {
//            echo "Trajenta Family";
//        } elseif ($this->Product_Id == '5') {
//            echo "Jardiance";
//        } elseif ($this->Product_Id == '6') {
//            echo "Trajenta duo";
//        }
                    ?></th>
                <th  style="text-align:center">Sep</th>
                <th  style="text-align:center">Oct</th>
                <th  style="text-align:center">Nov</th>
                <th>Dec</th>
            </tr>

            <tr>
                <th  style="text-align:right">Users</th>
            </tr>
            <tr>
                <th  style="text-align:right"><?php
//                    if ($this->Product_Id == '1') {
//                        echo "Vials";
//                    } else {
//                        echo "Rx";
//                    }
                    ?></th>
            </tr>
        </table>
    </li>
</ul>
</div>
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
</style>
<ul class="table-view card ">
    <li class="table-view-cell table-view-divider" style="padding: 11px 20px 11px 15px;">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            Achievement Of Jan 2016
        </div>
        <div class="col-lg-4"></div>
    </li>
    <li class="table-view-cell achievement">

        <div class="col-lg-1">
            <select class="form-control">
                <option>Jan 2016</option>
                <option>Cycle 1</option>
            </select>
        </div>
        <div class="col-lg-4">


            <div class="demo" >        
                <input class="knob" id="kp1" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="35">
                <span style="margin-left: 98px;position: absolute;margin-top: -84px;">20%</span>
                <span style="margin-left: 95px;position: absolute;margin-top: -45px;">KPI 1</span>

                <span style="margin-left: 41px;position: absolute;margin-top: -30px;"><?php // echo $rxlabel;  ?> Actual / <?php //echo $rxlabel;  ?> Planned</span>

            </div>
        </div>

        <div class="col-lg-4">

            <div class="demo" >       
                <input class="knob" id="kp2" data-angleOffset=-125 data-angleArc=250 data-fgColor="#66EE66" value="35">
                <span style="margin-left: 98px;position: absolute;margin-top: -84px;">20%</span>
                <span style="margin-left: 95px;position: absolute;margin-top: -45px;">KPI 2</span>
                <span style="margin-left: 41px;position: absolute;margin-top: -30px;">Activity Done / Planned</span>
            </div>
        </div>
    </li>
</ul>
<script src="<?php echo asset_url() ?>js/owl.carousel.min.js" type="text/javascript"></script>
<script>
                                $(document).ready(function () {
                                    $("#owl-demo").owlCarousel({
                                        navigation: true, // Show next and prev buttons
                                        slideSpeed: 300,
                                        paginationSpeed: 400,
                                        singleItem: true,
                                        navigation : false
                                    });
                                    $("#owl-demo2").owlCarousel({
                                        navigation: true, // Show next and prev buttons
                                        slideSpeed: 300,
                                        paginationSpeed: 400,
                                        singleItem: true,
                                        navigation : false
                                    });
                                });


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
                                    var initval = 20;
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
                                    var initval = 20;
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
                                    var initval = 20;
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
                                    var initval = 20;
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
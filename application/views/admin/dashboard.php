<!-- Info boxes -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-user-md"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Doctors </span>
                <span class="info-box-number"><?php echo $Doctor_Count['COUNT'] ?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-medkit"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Target</span>
                <span class="info-box-number"><?php
                    //var_dump($Target_Count);
                    echo $Target_Count['TOTAL'];
                    ?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Achievements</span>
                <span class="info-box-number"><?php echo $Actual_Count['TOTAL'] ?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->
<!--    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">New Conversion</span>
                <span class="info-box-number"><?php echo $Con_Count['TOTAL'] ?></span>
            </div> /.info-box-content 
        </div> /.info-box 
    </div> /.col -->
</div><!-- /.row -->

<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php if (!empty($productlist)) { ?>


            <ul align="center" class="nav nav-tabs ">
                <?php
                if (!empty($productlist)) {
                    $count = 1;
                    foreach ($productlist as $product) {
                        ?>
                        <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a data-toggle="tab" style="    padding: 12px;" onclick="getTabDetails(<?php echo $product->id ?>)" href="#<?php echo $product->id ?>"><?php echo $product->Brand_Name ?></a></li>
                        <?php
                        $count ++;
                    }
                }
                ?>
                <li id="loader" style="display:none"><img src="<?php echo asset_url(); ?>images/loader.gif" alt=""/></li>

            </ul>

            <div class="tab-content">
                <?php
                if (!empty($productlist)) {
                    $count = 1;

                    foreach ($productlist as $product) {
                        $noofdoctors = 0;
                        $target = 0;
                        $planned = 0;

                        $result = $this->admin_model->adminDashboardCount2($product->id, '2', '2016');
                        if (!empty($result)) {
                            foreach ($result as $value) {
                                $target+= $value->Target_New_Rxn_for_the_month;
                                $planned+= $value->Planned_New_Rxn;
                                $noofdoctors+= $value->No_of_Doctors;
                            }
                        }
                        ?>
                        <div id="<?php echo $product->id ?>" class="tab-pane fade <?php echo isset($count) && $count == 1 ? 'in active' : ''; ?>">


                            <div class="row" style="margin-top:5px">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box bg-yellow">
                                        <span class="info-box-icon"><i class="fa fa-user-md"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Doctors </span>
                                            <span class="info-box-number"><?php echo $noofdoctors; ?></span>
                                            <!--                                                <div class="progress">
                                                                                                <div class="progress-bar" style="width: 50%"></div>
                                                                                            </div>
                                                                                            <span class="progress-description">
                                                                                                50% Increase in 30 Days
                                                                                            </span>-->
                                        </div><!-- /.info-box-content -->
                                    </div><!-- /.info-box -->
                                </div><!-- /.col -->
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box bg-green">
                                        <span class="info-box-icon"><i class="fa fa-medkit"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Target</span>
                                            <span class="info-box-number"><?php
                                                echo $target;
                                                ?></span>
                                            <!--                                                <div class="progress">
                                                                                                <div class="progress-bar" style="width: 50%"></div>
                                                                                            </div>
                                                                                            <span class="progress-description">
                                                                                                50% Increase in 30 Days
                                                                                            </span>-->
                                        </div><!-- /.info-box-content -->
                                    </div><!-- /.info-box -->
                                </div><!-- /.col -->

                                <!-- fix for small devices only -->
                                <div class="clearfix visible-sm-block"></div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box bg-red">
                                        <span class="info-box-icon"><i class="ion ion-ios-cart-outline"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Planned</span>
                                            <span class="info-box-number"><?php
                                                echo $planned;
                                                ?></span>
                                            <!--                                                <div class="progress">
                                                                                                <div class="progress-bar" style="width: 50%"></div>
                                                                                            </div>
                                                                                            <span class="progress-description">
                                                                                                50% Increase in 30 Days
                                                                                            </span>-->
                                        </div><!-- /.info-box-content -->
                                    </div><!-- /.info-box -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div>
                        <?php
                        $count ++;
                        break;
                    }
                }
                ?>
            </div>

        <?php } ?>
    </div>
</div>
<script>
    function getTabDetails(Product_id) {
        $("#loader").show();
        $.ajax({
            //Send request
            type: 'POST',
            data: {Product_Id: Product_id},
            url: '<?php echo site_url('Admin/dashboardTab'); ?>',
            success: function (data) {
                $("#loader").hide();
                $(".tab-content").html(data);
            }
        });
    }
</script>
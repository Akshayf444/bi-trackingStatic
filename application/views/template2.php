<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="<?php echo asset_url() ?>css/bootstrap.min.css" rel="stylesheet" type="text/css " >
        <link href="<?php echo asset_url() ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo asset_url() ?>css/jquery-ui.css" rel="stylesheet" type="text/css " >
        <script src="<?php echo asset_url() ?>js/jquery.js" type="text/javascript"></script>
        <script src="<?php echo asset_url() ?>js/bootstrap.min_1.js" type="text/javascript"></script>
        <script src="<?php echo asset_url() ?>js/jquery-ui.js" type="text/javascript"></script>
        <script src='<?php echo asset_url() ?>js/jquery.bootstrap-growl.min.js' type='text/javascript'></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php
        echo $this->session->userdata('message') ? $this->session->userdata('message') : '';
        $this->session->unset_userdata('message');
        ?>
        <style>
            .navbar {
                border-bottom: 1px solid #e7e7e7;
                border-radius: 0px; 
                min-height: 46px;
            }
            .form-control{
                height: 32px;
                padding: 3px 3px;
            }
            input[type=text],input[type=number]{
                height: 32px;
                padding: 0px 4px;
            }
            .panel{
                margin-top: 10px;
            }
            .panel{
                margin-bottom: 10px;
                margin-top: 0px;
            }
            .panel-heading{
                text-align: center;
                font-size: 18px;
                font-weight: bold;
            }
            a{
                cursor: pointer;
            }

            .nav>li>a {
                position: relative;
                display: block;
                padding: 0px 6px;
            }
            .col-md-3{
                padding: 0;
            }
        </style>

        <?php
        if ($this->session->userdata('Product_Id') == 1 && $this->session->userdata('Designation') == 'BDM') {
            ?>
            <style>
                .table-view,.panel-body{
                    background-color: #79B61C;
                }
            </style>
            <?php
        } elseif ($this->session->userdata('Product_Id') == 2 && $this->session->userdata('Designation') == 'BDM') {
            ?>
            <style>
                .table-view,.panel-body{
                    background-color: #4E88BC;
                }
            </style>
            <?php
        } elseif ($this->session->userdata('Product_Id') == 3 && $this->session->userdata('Designation') == 'BDM') {
            ?>
            <style>
                .table-view,.panel-body{
                    background-color: #EFC083;
                }
            </style>
            <?php
        } elseif ($this->session->userdata('Product_Id') == 4 && $this->session->userdata('Designation') == 'BDM') {
            ?>
            <style>
                .table-view,.panel-body{
                    background-color: skyblue;
                }
            </style>
            <?php
        } elseif ($this->session->userdata('Product_Id') == 5 && $this->session->userdata('Designation') == 'BDM') {
            ?>
            <style>
                .table-view,.panel-body{
                    background-color: #20B2AA;
                }
            </style>
            <?php
        } elseif ($this->session->userdata('Product_Id') == 6 && $this->session->userdata('Designation') == 'BDM') {
            ?>
            <style>
                .table-view,.panel-body{
                    background-color: #9999ff;
                }
            </style>
        <?php } ?>
    </head>
    <body style="background: #ECEFF1">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div  class="navbar-header col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0">
                    <?php
                    $updateurl = '';
                    $dashboardurl = '';
                    if ($this->Designation == 'BDM') {
                        $updateurl = site_url('User/BDM_update');
                        $dashboardurl = site_url('User/dashboard');
                    } else {
                        if ($this->Designation == 'ASM') {
                            $updateurl = site_url('ASM/ASM_update');
                            $dashboardurl = site_url('ASM/dashboard');
                        }
                    }
                    ?>
                    <div  class="col-md-1 col-sm-1 col-xs-1 col-lg-1" style="padding: 0">
                        <a class="fa fa-2x fa-arrow-left" onclick="window.location = '<?php echo isset($backUrl) ? site_url($backUrl) : site_url('User/dashboard'); ?>';" style="padding:8px 0px 0px 0px"></a>
                    </div>
                    <div align="middle" class="col-md-8 col-sm-9 col-xs-9 col-lg-9"> 
                        <img  style="" onclick="window.location = '<?php echo $dashboardurl; ?>';" src="<?php echo asset_url() ?>images/Boehringer.png" alt=""/>
                    </div>
                    <div class="col-md-3 col-sm-2 col-xs-3 col-lg-2">
                        <div class="dropdown pull-right" style="top:10px">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i><?php echo isset($this->Full_Name) ? ' ' . $this->Full_Name : ''; ?><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li> <a onclick="window.location = '<?php echo $updateurl; ?>';"><i class="fa fa-fw fa-user"></i> Profile</a></li>
                                <li><a href="#" onclick="window.location = '<?php echo site_url('User/logout'); ?>';"  ><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </nav>
        <?php $this->load->view($content, $view_data); ?>
    </body>
</html>
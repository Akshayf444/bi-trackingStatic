<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo asset_url() ?>dashboard/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo asset_url() ?>dashboard/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo asset_url() ?>dashboard/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo asset_url() ?>js/jquery.js" type="text/javascript"></script>
        <link href="<?php echo asset_url() ?>css/jQuery-ui.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo asset_url() ?>js/jQuery-ui.js"></script>
        <script src="<?php echo asset_url(); ?>js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="<?php echo asset_url() ?>dashboard/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src='<?php echo asset_url() ?>js/jquery.bootstrap-growl.min.js' type='text/javascript'></script>
        <script src="<?php echo asset_url() ?>js/excellentexport.min.js" type="text/javascript"></script>
        <link href="<?php echo asset_url(); ?>css/chosen.min.css" rel="stylesheet" type="text/css"/>
        <script src="<?php echo asset_url(); ?>js/chosen.jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo asset_url(); ?>js/chosen.proto.js" type="text/javascript"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style>
            select{
                height: 25px;
            }
        </style>
    </head>

    <body class="skin-blue">
        <div class="wrapper">

            <!-- Main Header -->
            <header class="main-header">
                <a href="<?php echo ($this->Designation != 'ASM') ? site_url('Report/dashboard') : ''; ?>" class="logo" style="background-color: #fff;"><b><img src="<?php echo asset_url() ?>images/Boehringer.png" ></b></a>
                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav"  style="padding-top: 10px;">
                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <p>
                                    <?php $CI = & get_instance(); ?>
                                    <span ><b><?php echo isset($CI->Full_Name) ? $CI->Full_Name : ''; ?></b>&nbsp;</span>
                                    <a class="text-aqua" href="#" data-toggle="modal" data-target=".logout">
                                        <span style="font-size: 20px" class="fa fa-power-off">  </span>
                                    </a>
                                </p>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->

            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <ul class="sidebar-menu">
                        <?php
                        if (isset($CI->Designation) && strtoupper($CI->Designation) == 'ZSM' || strtoupper($CI->Designation) == 'Marketing' || strtoupper($CI->Designation) == 'NSM' || strtoupper($CI->Designation) == 'HO USER' || strtoupper($CI->Designation) == 'MD' || strtoupper($CI->Designation) == 'ETM' || strtoupper($CI->Designation) == 'ASM') {
                            if (strtoupper($CI->Designation) == 'ZSM' || strtoupper($CI->Designation) == 'ASM') {
                                $Zone = isset($CI->Zone) && $CI->Zone != '' ? 'Zone=' . $CI->Zone : '';
                                $Division = isset($CI->Division) && $CI->Division != '' && $CI->Division != 'Both' ? '&Division=' . $CI->Division : '';
                            } else {
                                $Zone = isset($CI->Zone) && $CI->Zone != '' ? 'Zone=' . $CI->Zone : '';
                                $Division = isset($CI->Division) && $CI->Division != '' && $CI->Division != 'Both' ? '&Division=' . $CI->Division : '';
                            }
                            ?>
                            <?php if (strtoupper($CI->Designation) == 'ZSM') { ?>
                                <li>                                
                                    <a href="<?php echo site_url('Report/ZSMdashboard'); ?>">
                                        <i class="fa fa-dashboard"></i>
                                        <span>KPI Dashboard</span>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (strtoupper($CI->Designation) == 'ASM') { ?>
                                <li>
                                    <a href="<?php echo site_url('ASM/dashboard'); ?>">
                                        <i class="fa fa-dashboard"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ASM/ASM_update'); ?>">
                                        <i class="fa fa-user"></i>
                                        <span>Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ASM/target'); ?>">
                                        <i class="fa fa-bullseye"></i> <span>Assign Target</span> 
                                    </a>
                                </li>
                                <li class="treeview">
                                    <a href="#">
                                        <i class="fa fa-line-chart"></i> <span>Approve Planning</span> <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu treeview" style="display: none;">
                                        <li class=""><a href="<?php echo site_url('ASM/asm_rx_planning'); ?>"><i class="fa fa-circle-o"></i>  Rx Planning</a></li>

                                        <li class=""><a href="<?php echo site_url('ASM/activity_planning'); ?>"><i class="fa fa-circle-o"></i>  Activity Planning</a></li>
                                    </ul>
                                </li>
                                <li class="treeview">
                                    <a href="#">
                                        <i class="fa fa-file-o"></i> <span>Approve Reporting</span> <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu" style="display: none;">

                                        <li class=""><a href="<?php echo site_url('ASM/reporting_rx'); ?>"><i class="fa fa-circle-o"></i>  Rx Reporting</a></li>

                                        <li class=""><a href="<?php echo site_url('ASM/reporting_activity'); ?>"><i class="fa fa-circle-o"></i>  Activity Reporting</a></li>
                                    </ul>
                                </li>
                                <?php if (strtolower($CI->Division) == 'thrombi' && strtoupper($CI->Designation) == 'ASM') { ?>
                                    <li>
                                        <a href="<?php echo site_url('ASM/reporting_info'); ?>">
                                            <i class="fa fa-dashboard"></i> <span>Actilyse Dashboard</span> 
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php
                            }
                            ?>
                            <?php if (strtoupper($CI->Designation) != 'ASM') { ?>
                                <li>                                
                                    <a href="<?php echo site_url('Report/dashboard?' . $Zone . $Division); ?>">
                                        <i class="fa fa-dashboard"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                            <?php }
                            ?>

                            <li>
                                <a href="<?php echo site_url('Report/dailyTrend?' . $Zone . $Division); ?>">
                                    <i class="fa fa-file-text"></i>
                                    <span>Daily Trend</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('Report/monthlyTrend?' . $Zone . $Division); ?>">
                                    <i class="fa fa-file-text"></i>
                                    <span>Monthly Trend</span>
                                </a>
                            </li>
                            <?php
                            if (isset($CI->Division) && strtolower($CI->Division) == 'thrombi' || strtolower($CI->Division) == 'diabetes' || strtolower($CI->Division) == 'both') {

                                if (isset($CI->Division) && strtolower($CI->Division) == 'thrombi' || strtolower($CI->Division) == 'both') {
                                    ?>
                                    <li>
                                        <a href="<?php echo site_url('Report/thrombiTrend?' . $Zone); ?>">
                                            <i class="fa fa-file-text"></i>
                                            <span>Thrombi Report</span>
                                        </a>
                                    </li>                                 
                                    <?php
                                }
                                if (isset($CI->Division) && strtolower($CI->Division) == 'diabetes' || strtolower($CI->Division) == 'both') {
                                    ?>
                                    <li>
                                        <a href="<?php echo site_url('Report/diabetesTrend?' . $Zone); ?>">
                                            <i class="fa fa-file-text"></i>
                                            <span>Diabetes Report</span>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            <?php }
                            ?>
                            <li>
                                <a href="<?php echo site_url('Report/ActivityTrend?' . $Zone . $Division); ?>">
                                    <i class="fa fa-file-text"></i>
                                    <span>Activity Trend</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('Report/SystemStatus?' . $Zone . $Division); ?>">
                                    <i class="fa fa-dashboard"></i>
                                    <span>Status Report</span>
                                </a>
                            </li>

                            <?php
                            if (isset($CI->Division) && strtolower($CI->Division) == 'thrombi' && $CI->Designation != 'ASM' || strtolower($CI->Division) == 'both') {
                                ?>
                                <li>
                                    <a href="<?php echo site_url('Report/actilyse_report?' . $Zone); ?>">
                                        <i class="fa fa-file-text"></i>
                                        <span>Actilyse Dashboard</span>
                                    </a>
                                </li>                                 
                                <?php
                            }
                        }
                        ?>

                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>    
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" style="overflow: scroll">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?php echo isset($page_title) ? $page_title : ''; ?>
                        <small></small>
                    </h1>
                </section>
                <?php
                echo $this->session->userdata('message') ? $this->session->userdata('message') : '';
                $this->session->unset_userdata('message');
                ?>
                <!-- Main content -->
                <section class="content" >
                    <?php $this->load->view($content, $view_data); ?>
                </section>
                <div class="modal fade logout" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg largeModal">
                        <div class="col-lg-8 col-lg-push-2">
                            <div class="modal-content logoutModal">
                                <div class="modal-header huge" ><i class="fa fa-sign-out"></i> Log Out ?</div>
                                <div class=" modal-body"><p>Are you sure you want to log out?</p>
                                    Press <span class="text-danger">No</span> if you want to continue work. Press <span class="text-success">Yes</span> to logout current user.</div>
                                <div class="modal-footer">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <a href="<?php echo site_url('User/logout'); ?>" class="btn btn-success">Yes</a>
                                            <a href="#" class="btn btn-danger" data-dismiss="modal" aria-label="Close">No</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- ./wrapper -->  
            <script type="text/javascript">
                var config = {
                    '.chosen-select': {},
                    '.chosen-select-deselect': {allow_single_deselect: true},
                    '.chosen-select-no-single': {disable_search_threshold: 10},
                    '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
                    '.chosen-select-width': {width: "95%"}
                }
                for (var selector in config) {
                    $(selector).chosen(config[selector]);
                }
            </script>
            <script src="<?php echo asset_url() ?>dashboard/dist/js/app.min.js" type="text/javascript"></script>
    </body>
</html>
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
        <!-- Morris chart -->
<!--        <link href="<?php echo asset_url() ?>dashboard/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
         jvectormap 
        <link href="<?php echo asset_url() ?>dashboard/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />-->

        <!-- Theme style -->
        <link href="<?php echo asset_url() ?>dashboard/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="<?php echo asset_url() ?>dashboard/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo asset_url() ?>dashboard/plugins/jQuery/jQuery-2.1.3.min.js"></script>
        <link href="<?php echo asset_url() ?>css/jQuery-ui.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo asset_url() ?>js/jQuery-ui.js"></script>
        <script src="<?php echo asset_url(); ?>js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="<?php echo asset_url() ?>js/excellentexport.min.js" type="text/javascript"></script>
        <link href="<?php echo asset_url(); ?>css/chosen.min.css" rel="stylesheet" type="text/css"/>
        <script src="<?php echo asset_url(); ?>js/chosen.jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo asset_url(); ?>js/chosen.proto.js" type="text/javascript"></script>
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
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
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
                //padding: 0;
            }
        </style>

    </head>

    <body class="skin-blue sidebar-collapse">
        <div class="wrapper">

            <!-- Main Header -->
            <header class="main-header">
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
                <a href="<?php echo $dashboardurl; ?>" class="logo" style="background-color: #fff;"><b><img src="<?php echo asset_url() ?>images/Boehringer.png" ></b></a>
                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav" style="padding-top: 10px;">
                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <p>
                                    <?php $CI = & get_instance(); ?>
                                    <span ><b><?php echo isset($CI->Full_Name) ? $CI->Full_Name : ''; ?></b>&nbsp;</span>
                                    <a class="text-aqua" href="<?php echo site_url('User/logout'); ?>">
                                        <span style="font-size: 20px" class="fa fa-power-off">  </span>
                                    </a>
                                </p>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <ul class="sidebar-menu">
                        <li>
                            <input type="text" class="form-control" placeholder="Search">
                        </li>
                        <li>
                            <a href="<?php echo site_url('User/dashboard'); ?>">
                                <i class="ion ion-ios-people-outline"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Customer</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu" style="display: none;">

                                <li class=""><a href="<?php echo site_url('User/Doctorlist'); ?>"><i class="fa fa-circle-o"></i>  Doctors</a></li>

                                <li class=""><a href="<?php //echo site_url('User/view_doctor');       ?>"><i class="fa fa-circle-o"></i>  Hospital</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Profiling</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu" style="display: none;">

                                <li class=""><a href="<?php echo site_url('User/Doctorlist2'); ?>"><i class="fa fa-circle-o"></i>  Doctors</a></li>

                                <li class=""><a href="<?php //echo site_url('User/view_doctor');       ?>"><i class="fa fa-circle-o"></i>  Hospital</a></li>
                            </ul>
                        </li>

                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Planning</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu treeview" style="display: none;">
                                <li class=""><a href="<?php echo site_url('User/PlanningMenu'); ?>"><i class="fa fa-circle-o"></i>  Rx Planning</a></li>

                                <li class=""><a href="<?php echo site_url('User/PriorityMenu'); ?>"><i class="fa fa-circle-o"></i>  Priotize</a></li>

                                <li class=""><a href="<?php echo site_url('User/ActivityMenu'); ?>"><i class="fa fa-circle-o"></i>  Activity Planning</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Reporting</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu" style="display: none;">

                                <li class=""><a href="<?php echo site_url('User/ReportingMenu'); ?>"><i class="fa fa-circle-o"></i>  Rx Reporting</a></li>

                                <li class=""><a href="<?php echo site_url('User/ActivityReportingMenu'); ?>"><i class="fa fa-circle-o"></i>  Activity Reporting</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Achievement</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu" style="display: none;">

                                <li class=""><a href="<?php //echo site_url('User/view_doctor');       ?>"><i class="fa fa-circle-o"></i>  Rx </a></li>

                                <li class=""><a href="<?php //echo site_url('User/view_doctor');       ?>"><i class="fa fa-circle-o"></i>  Activity </a></li>
                            </ul>
                        </li>

                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>   

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?php echo isset($page_title) ? $page_title : ''; ?>
                        <small></small>
                    </h1>
                </section>
                <?php
//        echo $this->session->userdata('message') ? $this->session->userdata('message') : '';
//        $this->session->unset_userdata('message');
                ?>

                <!-- Main content -->
                <section class="content" style="overflow: scroll;">
                    <?php
                    echo $this->session->userdata('message') ? $this->session->userdata('message') : '';
                    $this->session->unset_userdata('message');
                    ?>
                    <?php $this->load->view($content, $view_data); ?>
                </section>
            </div><!-- Bootstrap 3.3.2 JS -->
            <script src="<?php echo asset_url() ?>dashboard/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <script src='<?php echo asset_url() ?>js/jquery.bootstrap-growl.min.js' type='text/javascript'></script>
            <!-- AdminLTE App -->
            <script src="<?php echo asset_url() ?>dashboard/dist/js/app.min.js" type="text/javascript"></script>
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
    </body>
</html>
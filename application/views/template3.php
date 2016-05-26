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

    </head>

    <body class="skin-blue">
        <div class="wrapper">

            <!-- Main Header -->
            <header class="main-header">
                <a href="<?php echo site_url('Admin/emp_view'); ?>" class="logo" style="background-color: #fff;"><b><img src="<?php echo asset_url() ?>images/Boehringer.png" ></b></a>
                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?php $CI = & get_instance(); ?>
                                    <span class="hidden-xs"><?php echo isset($CI->Full_Name) ? $CI->Full_Name : ''; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php //echo $CI->user_name; ?>                                   
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-right">
                                            <a href="<?php echo site_url('User/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
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
                            <a href="<?php echo site_url('Admin/emp_view'); ?>">
                                <i class="ion ion-ios-people-outline"></i>
                                <span>Employee Master</span>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('Admin/doc_view'); ?>">
                                <i class="fa fa-user-md"></i> <span>Doctor Master</span> 
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('Admin/TabControl'); ?>">
                                <i class="fa fa-folder"></i> <span>Tab Control</span> 
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('Admin/Userlog'); ?>">
                                <i class="fa fa-file-text"></i> <span>User Log</span> 
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('Admin/login_history'); ?>">
                                <i class="fa fa-eye-slash"></i> <span>Login History</span> 
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('Admin/UnlockUser'); ?>">
                                <i class="fa fa-unlock"></i> <span>Unlock User</span> 
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('Admin/emp_docmaster'); ?>">
                                <i class="fa fa-user-md"></i> <span>Employee Doctor</span> 
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('Admin/territory_view'); ?>">
                                <i class="fa fa-file-archive-o"></i> <span>Territory Master</span> 
                            </a>
                        </li>
                        <!--                        
                                                  <li>
                                                    <a href="<?php echo site_url('Admin/asm_target'); ?>">
                                                        <i class="fa fa-file-archive-o"></i> <span>Target</span> 
                                                    </a>
                                                </li>-->

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
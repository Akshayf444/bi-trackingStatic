<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid" >
        <div class="row col-sm-offset-1">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" id="homeLogo"><img class="img-responsive" src="<?php echo asset_url() ?>/images/logo.jpg" alt=""/></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (!empty($Navbar)) {
                        foreach ($Navbar as $menu => $link) {
                            ?>
                            <li>
                                <a href="<?php echo site_url($link) ?>"><?php echo $menu ?></a>
                            </li>
                        <?php
                        }
                    }
                    ?>

                    <li>
                        <a href="#"></a>
                    </li>
                </ul>
            </div>

        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
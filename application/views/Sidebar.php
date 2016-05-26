<div class="row">
    <div class="col-lg-12">
        <?php
        //var_dump($Sidebar);
        if (!empty($Sidebar)) {
            foreach ($Sidebar as $menu => $submenu) {
                echo '<h5 class="page-header">' . $menu . '</h5>';
                echo '<ul class="nav sidebar">';
                foreach ($submenu as $name => $link) {
                    echo '<li><a href="' . site_url($link) . '">' . $name . '</a></li>';
                }
                echo '</ul>';
            }
        }
        ?>
    </div>
</div>


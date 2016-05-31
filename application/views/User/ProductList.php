<div class="row" style="padding-bottom: 10px">
    <div class="col-xs-12">
        <ul class="nav nav-tabs">
            <?php
            if (!empty($this->Product_List)) {
                $count = 1;
                foreach ($this->Product_List as $product) {

                    if (isset($_GET['Product_Id']) && $_GET['Product_Id'] > 0) {
                        ?>
                        <li class="<?php echo isset($_GET['Product_Id']) && $_GET['Product_Id'] == $product->id ? 'active' : ''; ?>"><a style="padding: 12px;" href="<?php echo site_url($site_url .'?Product_Id=' . $product->id); ?>" ><?php echo $product->Brand_Name ?></a></li>

                    <?php } else { ?>
                        <li class="<?php echo isset($count) && $count == 1 ? 'active' : ''; ?>"><a style="padding: 12px;" href="<?php echo site_url($site_url . '?Product_Id=' . $product->id); ?>" ><?php echo $product->Brand_Name ?></a></li>

                        <?php
                    }

                    $count ++;
                }
            }
            ?>
        </ul>
    </div>
</div>
<style>
    .btn-big{
        padding: 22px;
        font-size: 30px;
    }
</style>
<div class="row" style="padding-top: 50px;">
    <?php
    if (!empty($productlist)) {
        foreach ($productlist as $product) {
            echo '<div class=" col-xs-4">
        <a href="' . site_url($site_url . '?Product_Id=' . $product->id) . '"  class="btn btn-success btn-big btn-block" >' . $product->Brand_Name . '</a>
    </div>';
        }
    }
    ?>
</div>
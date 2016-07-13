<style>
    .thumbnail{
        border-radius: 0px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-lg-offset-7 col-md-12 col-xs-12" id="mini-notification" align="right">
            Helpline No : <span class="helpline" style = 'color:red;font-weight:bold'>022-65657701</span><br>From 10 am - 6 pm
            <p>Mail Us: <a href="mailto:bisupport@instacom.in">bisupport@instacom.in</p></a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12" align="center">
            <h3>User Guide<small onclick="window.history.back()" class="btn-link pull-left badge"><< Go back</small></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <?php
            $video_array = array(
                'Assign Target' => '1.webm',
                'Rx - Vials Planning' => '2.webm',
                'Actilyse Dashboard (For Thrombi)' => '3.webm',
                'Profiling' => '4.webm',
            );
            ?>
            <div class="row" align="center">
                <?php foreach ($video_array as $key => $value) { ?>
                    <a href="<?php echo asset_url(); ?>video/<?php echo $value; ?>" data-name="<?php echo $key; ?>" data-toggle="lightbox" data-gallery="youtubevideos" class="col-sm-3 thumbnail">
                        <h4><?php echo $key; ?></h4>
                    </a>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="modal fade logout" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg largeModal" style="width: 95%">
            <div class="modal-content logoutModal">
                <div class="modal-header">
                    <span id="name"></span>
                    <a href="#" class="pull-right" data-dismiss="modal" aria-label="Close">X</a>
                    
                </div>
                <div class=" modal-body">
                    <video id="video1" width="100%" height="100%" controls>
                        <source src="movie.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ($) {
        // delegate calls to data-toggle="lightbox"
        $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            var name = $(this).attr('data-name');
            $("#video1").attr('src', url);
            $("#name").html(name);
            $('.logout').modal('show');
        });

    });
</script>
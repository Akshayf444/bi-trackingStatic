<div class="container" style="margin-top: 2em;">
    <?php
    if (isset($message)) {
        echo '<div class="alert alert-danger" style="text-align:center">' . $message . '</div>';
    }
    ?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-8 col-lg-offset-3 col-md-offset-3 col-sm-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align: center">
                    <img  src="<?php echo asset_url() ?>images/travels.png" width="45%" >
                </div>
                <div class="panel-body">
                    <?php echo form_open('User/index') ?>

                    <div class="form-group">
                        <input type="text" class="form-control uname" placeholder="Username" name="username"/>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="password" class="form-control pword" placeholder="Password" id="pwd" aria-describedby="basic-addon2" name="password">
                            <span class="input-group-addon" id="eye"><i class="fa fa-eye"></i></span>
                        </div>
                    </div>
                    <!--                        <div class="col-lg-8">
                                                <input type="password" id="pwd" class="form-control pword" placeholder="Password" name="password" /></div><div class="col-lg-4">  <button type="button" id="eye">Show</button></div>
                                        </div>-->
                    <input class="btn btn-success btn-block" type="submit" value="Sign In" >

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div onclick="window.location = '<?php echo site_url('User/forget_pass') ?>';" class="col-lg-3 col-md-3 col-lg-offset-3 col-md-offset-3">
            <a>Forget Password</a>
        </div>

        <div class="col-lg-3 col-md-3" id="mini-notification" align="right">
            Helpline No : <span class="helpline" style = 'color:red;font-weight:bold'>022-65657701</span><br>From 10 am - 6 pm
            <p>Mail Us: <a href="mailto:bisupport@instacom.in">bisupport@instacom.in</p></a>
        </div>
    </div>
</div>

<script>
    $(function () {

        function blinker() {
            $('.helpline').fadeOut(500);
            $('.helpline').fadeIn(500);
        }

        setInterval(blinker, 1000);
    });
</script>

<script>

    $(function () {


        $('#button').click(function () {

            $('#password').password('toggle');

        });

    });

</script>

<script>

    $(function () {
        function show() {
            var p = document.getElementById('pwd');
            p.setAttribute('type', 'text');
        }

        function hide() {
            var p = document.getElementById('pwd');
            p.setAttribute('type', 'password');
        }

        var pwShown = 0;

        document.getElementById("eye").addEventListener("click", function () {
            if (pwShown == 0) {
                pwShown = 1;
                show();
            } else {
                pwShown = 0;
                hide();
            }
        }, false);
    });

</script>

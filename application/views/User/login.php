<div class="container" >
    <?php
    if (isset($message)) {
        echo '<div class="alert alert-danger" style="text-align:center">' . $message . '</div>';
    }
    ?>
    <div class="row">
        <div class="col-lg-3 col-lg-offset-7 col-md-12 col-xs-12" id="mini-notification" align="right">
            Helpline No : <span class="helpline" style = 'color:red;font-weight:bold'>022-65657701</span><br>From 10 am - 6 pm
            <p>Mail Us: <a href="mailto:bisupport@instacom.in">bisupport@instacom.in</p></a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12" align="center">
            <h3>Welcome To BI Tracker</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-sm-offset-2 col-xs-6 col-xs-offset-3 col-md-offset-1 col-lg-offset-1" style="padding: 15px">            
            <img  src="<?php echo asset_url() ?>images/travels.png" width="100%"  >
            <br/>
            <br/>
            <br/>
            <br/>
            <a href="<?php echo site_url('User/video');?>" class="badge label-info">Click Here To View User Guide</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1   ">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align: center;border-bottom: dashed 2px #118E11">
                    <h3>Sign In</h3>
                    Login To Access Your Account
                </div>
                <div class="panel-body" style="padding: 30px">
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
                    <input class="btn btn-success btn-block" style="background: #2A5567" type="submit" value="Sign In" >
                    <br/>
                    <div onclick="window.location = '<?php echo site_url('User/forget_pass') ?>';" >
                        <a>Forget Password</a>
                    </div>
                    </form>
                </div>
            </div>
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

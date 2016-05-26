
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                  
                    <div class="pull-right">
                        <a href="<?php echo site_url() ?>/User/index" class="btn btn-primary btn-link">Login </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="">  Change Password</h1>
                </div>
            </div>
            
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <form method="post"   name="form" class="form"   enctype="multipart/form-data" role="form" action="#">
                    <div class="col-lg-9 col-sm-9 col-md-9 col-xs-9">

                        <div class=" form-group">
New Password:
  <input name="password" id="password" type="password" />

                        </div>
                            <div class=" form-group">
confirm Password:
 <input type="password" name="confirm_password" id="confirm_password" /> <span id='message'></span>



                        </div>
                        <div class="pull-right" style="margin-top: 5px">
                            <input type="submit"  name="submit" value="Set Password" class="btn btn-primary">

                        </div>

                </form>

                
            </div>

        </div>

    </div>
<script>
   $('#confirm_password').on('keyup', function () {
    if ($(this).val() == $('#password').val()) {
        $('#message').html('matching').css('color', 'green');
    } else $('#message').html('not matching').css('color', 'red');
});
</script>

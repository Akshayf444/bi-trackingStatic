
<div class="container" style="margin-top: 2em;">
    <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3">
        <?php echo form_open('user/sendMail2'); ?>
        <div class="panel">
            <div class="panel-heading" style="text-align: center">
            </div>
            <div class="panel-body">

                <div class=" form-group">
                    <label>Please Enter Your Email-Id / Username</label>
                    <input type="email" required class="form-control" name="email" autofocus="" value="" placeholder="Email"  >
                </div>
            </div>
            <div class="panel-footer" >
                <input type="submit"  name="submit"  value="Send" class="btn btn-primary">
                <a href="<?php echo site_url('User/index') ?>" class="btn btn-success">Go Back</a>
            </div>
        </div>

        </form>

    </div>

</div>



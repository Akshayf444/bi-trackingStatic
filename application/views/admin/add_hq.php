
<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
    Add HQ
</button>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Add HQ</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="">

                    <div class="form-group">
                        <input type="text" class="form-control uname" placeholder="Enter Zone" name="zone"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control pword" placeholder=" Enter Name" name="name" />
                    </div>
                    <button class="btn btn-positive btn-block" type="submit" name="submit" >Sign In</button>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>




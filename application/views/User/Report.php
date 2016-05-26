<style>
    .table-view .table-view-cell {
        background-position: 0px 100%;
    }
    .col-xs-9, .col-xs-3{
        padding: 0px;
    }
    .table-view-cell {
        padding: 11px 12px 11px 15px;
    }
</style>
<style>
    label{
        margin-bottom: 0px;
    }

    .toggle {
        margin:4px;
        background-color:#EFEFEF;
        border-radius:20px;
        border:1px solid #EFEFEF;
        overflow:auto;
        float:left;

    }

    .toggle label {
        float:left;
        //width:2.0em;

    }

    .toggle label span {
        text-align:center;
        padding: 3px 12px 3px 9px;
        display:block;
        cursor: pointer;

        // margin-top: -25px;
    }

    .toggle label input {
        visibility: hidden;
        position:absolute;
        top:-20px;
    }

    .toggle .input-checked {
        background-color:#000;
        color:red;
        -webkit-transition: 0.15s ease-out;
        -moz-transition: 0.15s ease-out;
        -o-transition: 0.15s ease-out;
        transition: 0.15s ease-out;
    }
</style>
<form class="card">
    <ul class="table-view " >
        <li class="table-view-cell table-view-divider">Reporting</li>
        <li class="table-view-cell">
            Select Product
            <select class="form-control">
                <option>Please Select</option>
                <option>Actilyse</option>
                <option>Pradaxa</option>
                <option>Trajenta Family</option>              
            </select>

        </li>
        <li class="table-view-cell">
            Select Doctor
            <select class="form-control">
                <option>Please Select</option>
                <option>Yogesh kanse</option>       
            </select>
        </li>
        <li class="table-view-cell">
            Enter Rx For Jan
            <input type="text" >
        </li>
        <li class="table-view-cell">
            <div class="col-xs-4">Activity 1</div>
            <div class="col-xs-8">
                <div class="toggle">
                    <label><input type="radio" name="<?php echo 1 . 'radio'; ?>" value="Yes"><span id="<?php echo 1 . "-1"; ?>">Yes</span></label>    
                </div>
                <div class="toggle">
                    <label><input type="radio" name="<?php echo 1 . 'radio'; ?>" value="No"><span id="<?php echo 1 . "-2"; ?>" >No</span></label>
                </div>
            </div>
            <div id="<?php echo "heading" . 1; ?>" class="custom-collapse " style="display: none">
                <div class="row row-margin-top">
                    <div class="col-xs-12 col-lg-12"><textarea class="form-control" name="post_call_planning[]" placeholder="Activity Details"></textarea> </div> 
                </div> 
            </div>
            <div id="<?php echo "reason" . 1; ?>" class="custom-collapse " style="display: none">
                <div class="row row-margin-top">
                    <div class="col-xs-12 col-lg-12"><textarea class="form-control" name="reason[]" placeholder="Reason"></textarea> </div> 
                </div> 
            </div>
        </li>
        <li class="table-view-cell">
            <div class="col-xs-4">Activity 2</div>
            <div class="col-xs-8">
                <div class="toggle">
                    <label><input type="radio" name="<?php echo 2 . 'radio'; ?>" value="Yes"><span id="<?php echo 2 . "-1"; ?>">Yes</span></label>    
                </div>
                <div class="toggle">
                    <label><input type="radio" name="<?php echo 2 . 'radio'; ?>" value="No"><span id="<?php echo 2 . "-2"; ?>" >No</span></label>
                </div>
            </div>
            <div id="<?php echo "heading" . 2; ?>" class="custom-collapse " style="display: none">
                <div class="row row-margin-top">
                    <div class="col-xs-12 col-lg-12"><textarea class="form-control" name="post_call_planning[]" placeholder="Activity Details"></textarea> </div> 
                </div> 
            </div>
            <div id="<?php echo "reason" . 2; ?>" class="custom-collapse " style="display: none">
                <div class="row row-margin-top">
                    <div class="col-xs-12 col-lg-12"><textarea class="form-control" name="reason[]" placeholder="Reason"></textarea> </div> 
                </div> 
            </div>
        </li>
        <li class="table-view-cell">
            <br/>
            <button class="btn btn-lg btn-positive">Submit</button>
            <br/>
        </li>
    </ul>
</form>
<script>
    $('label').click(function () {
        $(this).children('span').addClass('input-checked');
        $(this).parent('.toggle').siblings('.toggle').children('label').children('span').removeClass('input-checked');

        var id = $(this).children('span').attr('id').split("-");
        id = id[0];

        if ($(this).children('span').text() === 'Yes') {
            $("#heading" + id).show();
            $("#reason" + id).hide();
        } else if ($(this).children('span').text() === 'No') {
            $("#heading" + id).hide();
            $("#reason" + id).show();
        }
    });
</script>
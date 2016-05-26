  <?php
    if (!empty($rows)) {
   ?>
<div class="row">
    <?php echo form_open('admin/update_terr?id='.$rows['id']); ?>
  
       
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <input type="hidden" class="form-control" value="<?php echo $rows['id']; ?>" name="terrid" />
              <?php
             $territory = $rows['Territory'];
         
              $terr =  explode("-",$territory);
              foreach ($terr as $terrt) {
            for($i=0;$i<=$terrt;$i++)
            {              ?>
          
            
             <div class="col-lg-3">    <input type="text" class="form-control" value="<?php  echo  $terrt ; ?>" name="territory[]" placeholder="Enter Territory"/> </div>
<!--             <div class="col-lg-3">    <input type="text" class="form-control" value="<?php // echo  $terr[1]  ; ?>" name="territory[]" placeholder="Enter Territory"/> </div>
           <div class="col-lg-3">      <input type="text" class="form-control" value="<?php // echo  $terr[2]  ; ?>" name="territory[]" placeholder="Enter Territory"/> </div>
            <div class="col-lg-3">     <input type="text" class="form-control" value="<?php // echo  $terr [3] ; ?>" name="territory[]" placeholder="Enter Territory"/> </div>-->

    <?php } } }?> 
        </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">

            <button type="submit" class="btn btn-success pull-right">Submit</button>
        </div>
    </div>

</form>
</div>
    </div>
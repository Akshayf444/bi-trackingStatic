<div class="card">
    <ul class="table-view">
        <li class="table-view-cell">
            <div class="col-sm-4"></div>

            <?php echo form_open('ASM/asm_rx_planning'); ?>
            <div class="col-sm-3"  >
                <select name="rx_id" class="form-control">
                    <option value="-1">Select BDM </option>
                    <?php echo $bdm; ?>
                </select>
            </div>
            <div class="col-sm-3"  >
                <select name="product_id" class="form-control">
                    <option value="-1">Select Product</option>
                    <?php echo $product; ?>
                </select>
            </div>
            <div class="col-sm-2"  >
                <button class="btn btn-primary" >FETCH</button>
            </div>
            </form>
        </li>
    </ul>
</div>

<div class="row">
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="table-responsive">
    <table class="table table-bordered table-hover ">
      <tr>
      <tr style="background-color: #428BCA">
             <?php
                    if (!empty($show)) {?>
                    <th>Doctor Name</th>
                    <th>Planned RX</th>

                    
                    <th><input type="checkbox" id="check-all"></th>
                </tr>
                <tr>
                 <?php
                        foreach ($show as $row) :
                            ?><tr>  
                            <td><?php echo $row->Account_Name; ?></td>  
                            <td><?php echo $row->Planned_RX; ?>  
                            <td><input type="checkbox" id="check-all">></td>  
                           
                                <?php
                            endforeach;
                        }
                        ?>
                </tr>

       
        </table>
    </div>
</div>
</div>

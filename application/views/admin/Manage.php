<html>
    <div>
        <ul class="table-view " >
            <li class="table-view-cell">
                <?php echo form_open('admin/manage') ?>
                <div class="col-lg-4">
                    <select class="form-control" name="team">
                        <option>Please Select</option>
                        <option value='asm'<?php
                        if(isset($team1))
                        {
                            echo 'selected';
                        }
                        ?>>Asm</option>
                        <option value="zsm"<?php
                        if(isset($team2))
                        {
                            echo 'selected';
                        }
                        ?>>Zsm</option>
                        <option value="bdm"<?php
                        if(isset($team3))
                        {
                            echo 'selected';
                        }
                        ?>>Bdm</option>              
                    </select>
                </div>
                <div class="col-lg-2">
                    <input type="submit" value="Search" class="btn btn-success"/>
                </div>
                </form>
            </li>
        </ul><ul class="table-view-cell">
            <li class="table-view-cell">
                <table class="table table-borderedb">
                    <?php
                    if (isset($team1)) {
                        ?>
                        <tr>
                            <th>Name</th>
                            <th>mobile</th>
                            <th>view</th>
                        </tr>
                        <?php
                        foreach ($team1 as $t):
                            ?>
                            <tr>
                                <td><?php echo $t->asm_name ?></td>
                                <td><?php echo $t->mobile ?></td>
                                <td><a onclick="window.location='<?php echo site_url() ?>/admin/edit?id=<?php echo $t->asm_id;?>&name=<?php echo "asm";?>'">Edit</a></td>
                            </tr>
                            <?php
                        endforeach;
                    }
                    elseif (isset($team2)) {
                        ?>
                        <tr>
                            <th>Name</th>
                            <th>mobile</th>
                            <th>view</th>
                        </tr>
                        <?php
                        foreach ($team2 as $t):
                            ?>
                            <tr>
                                <td><?php echo $t->zsm_name ?></td>
                                <td><?php echo $t->mobile ?></td>
                                <td><a onclick="window.location='<?php echo site_url() ?>/admin/edit?id=<?php echo $t->zsm_id;?>&name=<?php echo "zsm";?>'">Edit</a></td>
                            </tr>
                            <?php
                        endforeach;
                    }
                    elseif (isset($team3)) {
                        ?>
                        <tr>
                            <th>Name</th>
                            <th>mobile</th>
                            <th>view</th>
                        </tr>
                        <?php
                        foreach ($team3 as $t):
                            ?>
                            <tr>
                                <td><?php echo $t->bdm_name ?></td>
                                <td><?php echo $t->mobile ?></td>
                                <td><a onclick="window.location='<?php echo site_url() ?>/admin/edit?id=<?php echo $t->bdm_id;?>&name=<?php echo "bdm";?>'">Edit</a></td>
                            </tr>
                            <?php
                        endforeach;
                    }
                    ?>
                </table>
            </li>
        </ul>
    </div>
</html>
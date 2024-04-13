<!--<div>
    <?php
    /*
    foreach ($sedeid_tblsedes as $sede) {
        foreach ($sedeid_tblpvorders as $pvorderidsede){
    ?>
        <span><?= ($pvorderidsede->sede_id == $sede->sede_id) ? 'Es igual' : $sede->sede_id ?></span>
    <?php
        }
    }
    */
    ?>
</div>-->
<select name="sede_id"  id="sede_id" class="form-control select_box" style="width: 100%" required>
  <option value="">Selecciona</option>
    <?php
    /*
    foreach ($sedeid_tblsedes as $sede) {
        foreach ($sedeid_tblpvorders as $pvorderidsede){
            if($pvorderidsede->sede_id != $sede->sede_id){
    ?>
                <option value="<?= $sede->sede_id ?>" <?php echo ( $sede_id == $sede->sede_id ) ? 'selected' : ''; ?> ><?= $sede->sede ?></option>
    <?php
            }
    
        }
    }
    */
    ?>
  
    <?php
    if (!empty($sedeid_tblsedes)) {
        foreach ($sedeid_tblsedes as $sede) {
        ?>
          <option value="<?= $sede->sede_id ?>" <?php echo ( $sede_id == $sede->sede_id ) ? 'selected' : ''; ?> ><?= $sede->sede ?></option>
        <?php
        }
    }
    ?>
</select>
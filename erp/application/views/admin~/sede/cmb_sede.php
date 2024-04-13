<select name="sede_id"  id="sede_id" class="form-control select_box" style="width: 100%" required>
  <option value="">Selecciona</option>
  <?php
  if (!empty($all_sedes)) {
    foreach ($all_sedes as $sede) {
  ?>
      <option value="<?= $sede->sede_id ?>" <?php echo ( $sede_id == $sede->sede_id ) ? 'selected' : ''; ?> ><?= $sede->sede ?></option>
  <?php
    }
  }
  ?>
</select>
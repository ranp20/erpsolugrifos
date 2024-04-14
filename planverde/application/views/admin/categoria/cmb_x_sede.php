<select name="categoria_id" class="form-control select_box" style="width: 100%" required>
  <option value="">Seleccionar</option>
  <?php
  if (!empty($all_categories)) {
    foreach ($all_categories as $cate) {
  ?>
    <optgroup label="<?php echo $cate->nombre_categoria; ?>">
      <?php
      $all_subcategories = $this->db->get_where('tbl_subcategoria', ['categoria_id' => $cate->categoria_id])->result_object();
      foreach ($all_subcategories as $key => $subcat) {
        if( in_array( $subcat->subcategoria_id, $all_permissions ) ) :
          echo count($subcat->subcategoria_id);
      ?>
        <option value="<?= $subcat->subcategoria_id ?>"><?= $subcat->nombre_subcategoria ?></option>
      <?php
      else :
      ?>
        <!-- <option value="0">No hay categorías para la sede</option> -->
      <?php
      endif;
    } ?>
    </optgroup>
  <?php
    }
  }
  ?>
</select>
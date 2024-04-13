<div class="row">
    <div class="col-xs-12">
        <div class="cTitle-h">
            <a class="btn btn-success cTitle-h__cLink" title="Retornar" href="<?php echo base_url() . 'client/categoria/list/'.$_SESSION['sede']; ?>">
                <em class="fa fa-arrow-left"></em>
                <span>&nbsp;&nbsp;Volver</span>
            </a>
            <div class="cTitle-h__cTitle">
                <h4 class="title text-center">
                    <span><?php echo $categoria;?></span>
                </h4>
            </div>
        </div>
  </div>
</div>
<div class="row maincSubcategories">
  <div class="cFolderItm">
    <ul class="cFolderItm__m">
      <?php if (!empty($all_subcategories)){
        foreach ($all_subcategories as $subcat){
          $subcat = (object) $subcat;
          $subcat_nombre = strlen($subcat->nombre_subcategoria) > 42 ? substr($subcat->nombre_subcategoria, 0, 42 - 3) . '...' : $subcat->nombre_subcategoria;
      ?>
          <div class="col-lg-2 col-sm-4 box-folder cFolderItm__m__cItm">
            <a class="cFolderItm__m__cItm__link link-folder" href="<?php echo base_url() . 'client/subcategoria/anio/' . $subcat->subcategoria_id; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $subcat->nombre_subcategoria;?>">
              <span class="cFolderItm__m__cItm__link__cIcon">
                <i class="mdi mdi-folder"></i>
              </span>
              <span class="cFolderItm__m__cItm__link__cTitle">
                <span class="title-folder" ><?php echo $subcat_nombre;?></span>
              </span>
            </a>
          </div>
      <?php
        }
      }
      ?>
    </ul>
  </div>
</div>
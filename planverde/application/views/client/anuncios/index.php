<?php
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
// $url =  $actual_link . "/";
$url =  $actual_link . "/erpsolugrifos/";
?>
<script type="text/javascript" src="<?php echo $url; ?>node_modules/@fancyapps/ui/dist/fancybox/fancybox.umd.js"></script>
<link rel="stylesheet" href="<?php echo $url; ?>node_modules/@fancyapps/ui/dist/fancybox/fancybox.css"/>
<div class="row maincAds">
  <div class="col-md-12 col-xs-12 p-0">
      <?php 
      function cambiaf_mysql($date){
        $originalDate = $date;
        $newDate = date("d/m/Y", strtotime($originalDate));
        return $newDate;
      }
      $count = 0;
      $sectionGroup = [];
      if(isset($all_anuncios) && count($all_anuncios) > 0) :
        foreach($all_anuncios as $ads){
          $groupName = $ads->seccion;
          if(!isset($sectionGroup[$groupName])){
            $sectionGroup[$groupName] = [];
          }
          $sectionGroup[$groupName][] = $ads;
        }
        // Count the total elements within each index
        $groupCounts = array_map('count', $sectionGroup);
        foreach ($sectionGroup as $key => $anuncio) : 
          // $count++;
          echo "<header class='pnlHeading__c'>
            <div class='pnlHeading__c__cTitle'>
              <h3>{$key} ({$groupCounts[$key]})</h3>
            </div>
          </header>";
          echo "<div class='col-md-12 col-xs-12 mb-3 p-0'>
              <div class='cntAds thmAdsCards-2'>";
          foreach ($anuncio as $ads) :
      ?>
        <div class="cntAds--i">
          <div class="cntAds--i__c">
            <div class="cntAds--i__c__Title">
              <!-- <span class="cntAds--i__c__Title--cDate"><?php //echo cambiaf_mysql($ads->created_at);?></span> -->
            </div>
            <div class="media cntAds--i__c__itm">
              <?php if( !empty($ads->foto )): ?>
              <?php 
                $imagen = getimagesize(base_url().'uploads/anuncios/fotos/'. $ads->foto);
                $ancho = $imagen[0];
                $alto = $imagen[1];
              ?>
              <div class="cntAds--i__c__itm--cInfo">
                <figure class="ads_dashboard" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                  <a class="ads_dashboard--link" href="<?php echo base_url().'uploads/anuncios/fotos/'. $ads->foto;?>"  data-size="<?= $ancho.'x'.$alto ?>" data-index="0" data-fancybox="gallery" data-caption="ANUNCIO #<?php echo $count;?>" title="<?php echo "ANUNCIO - ".$ads->titulo;?>">
                    <div class="col-md-12 col-xs-12 cntAds--i__c__itm--cInfo--cImg">
                      <img src="<?php echo base_url().'uploads/anuncios/fotos/'. $ads->foto;?>" class="img-fluid media-object cntAds--i__c__itm--cInfo--cImg__img" width="100" height="100" decoding="sync">
                    </div>
                  </a>
                </figure>
                <?php endif; ?>
                <div class="col-md-12 col-xs-12 cntAds--i__c__itm--cInfo--cDesc">
                  <h4 class="media-heading cntAds--i__c__itm--cInfo--cDesc__Title"><?php echo $ads->titulo; ?></h4>
                  <p class="media-body cntAds--i__c__itm--cInfo--cDesc__desc"><?php echo $ads->descripcion; ?></p>
                  <div class="media-footer"></div>
                </div>
              </div>
              <?php if(!empty($ads->adjunto) || $ads->adjunto != "") :?>
              <div class="cntAds--i__c__itm--cAdj">
                <a href="<?php echo base_url().'uploads/anuncios/fotos/'. $ads->adjunto;?>" class="cntAds--i__c__itm--cAdj--link" target="_blank">Ver archivo adjunto >></a>
              </div>
              <?php endif;?>
            </div>
          </div>
        </div>
      <?php endforeach;
        echo "</div>
        </div>";
      ?>
      <?php endforeach;
      else : ?>
      <h4>No hay anuncios Por el momento!!</h4>
      <?php endif; ?>    
  </div>
</div>
<script type="text/javascript">    
  Fancybox.bind('[data-fancybox="gallery"]', {
    Toolbar: {
      display: {
        left: ["infobar"],
        middle: [
          "zoomIn",
          "zoomOut",
          "toggle1to1",
          // "toggleZoom",
          "panLeft",
          "panRight",
          "panUp",
          "panDown",
          "rotateCCW",
          "rotateCW",
          "flipX",
          "flipY",
          "fitX",
          "fitY",
          "reset",
          "toggleFS"
        ],
        right: ["slideshow", "fullscreen", "thumbs", "close"],
      },
    },
  });

  $(".cntAds--i__c__itm--cInfo figure").on( "mouseenter", function(){
    $(this).parent().parent().addClass("hoverInFigure");
    // console.log($(this).parent().parent());
  }).on( "mouseleave", function(){
    $(this).parent().parent().removeClass("hoverInFigure");
  });
</script>
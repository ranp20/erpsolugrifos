<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong><?php echo strtoupper($title); ?></strong>
  </header>
  <?php
  /* echo "<pre>";
  print_r($constancia_detail);
  echo "</pre>"; */
  ?>
  <div class="tab-content mt" style="border: 0;padding:0;">
    <div class="tab-pane active" id="task_details" style="position: relative;">
      <div class="panel panel-custom">
        <div class="panel-heading">
          <div class="panel-title">
            <?php echo $data_cotizacion->nombre; ?>

          </div>
        </div>
        <div class="panel-body form-horizontal ">
          <?php


          // $data_cotizacion = $this->db->where('visita_tecnica_id', $constancia_detail->visita_tecnica_id)->get('tbl_visita_tecnica')->row();

          if (!empty($cliente)) {
            $name = $cliente;
          } else {
            $name = '-';
          }

          if (!empty($sede)) {
            $name_sede = $sede;
          } else {
            $name_sede = '-';
          }
          ?>
          <?php $visita_details_view = 2;
          if (!empty($visita_details_view) && $visita_details_view == '2') {
          ?>
            <div class="row">
              <div class="col-md-12 br">
                <p class="lead bb"></p>
                <form class="form-horizontal p-20">
                  
                  <!-- <div class="form-group">
                    <div class="col-sm-4"><strong>Servicio :</strong></div>
                    <div class="col-sm-8">
                      <?php
                      if (!empty($data_cotizacion->nombre)) {
                        echo $data_cotizacion->nombre;
                      }
                      ?>
                    </div>
                  </div> -->
                  <div class="form-group">
                    <div class="col-sm-4"><strong>Cliente :</strong></div>
                    <div class="col-sm-8">
                      <strong><?php echo $name; ?></strong>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-4"><strong>Sede :</strong></div>
                    <div class="col-sm-8">
                      <strong><?php echo $name_sede; ?></strong>
                    </div>
                  </div>
                  

                  <div class="form-group">
                    <div class="col-sm-4"><strong>Documento :</strong></div>
                    <div class="col-sm-8">
                      <span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE CONFORMIDAD DE SERVICIO">
                        <a target="_blank" class="btn btn-success btn-lg" href="<?php echo base_url() ?>uploads/cotizaciones/conformidad_servicio/<?php echo $data_cs->ruta ?>"><span class="fa fa-download"> DESCARGAR</span></a>
                      </span>
                    </div>
                  </div>

                </form>
              </div>
            <?php } ?>
            </div>
        </div>
      </div>
    </div>
  </div>
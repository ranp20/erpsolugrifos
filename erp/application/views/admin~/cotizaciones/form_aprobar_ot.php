<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong> APROBACION DE ORDEN DE TRABAJO </strong>
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
                <?php echo form_open(base_url('admin/cotizacion/save_forms/' . $form . '/' . $id), array('id' => 'cotizacion_ot', 'class' => 'form-horizontal p-20', "enctype" => "multipart/form-data")); ?>

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

                <?php
                $text = '';

                if ($data_ot->start_date > date('Y-m-d')) {
                  $text = 'text-danger';
                } else {
                  $text = 'text-success';
                }
                ?>
                <div class="form-group">
                  <div class="col-sm-4"><strong>Fecha Inicio :</strong></div>
                  <div class="col-sm-8 <?= $text ?>">
                    <strong><?= strftime('%d, %B, %Y', strtotime($data_ot->start_date)) ?>
                    </strong>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-4"><strong>Fecha Fin :</strong></div>
                  <div class="col-sm-8 <?= $text ?>">
                    <strong><?= strftime('%d, %B, %Y', strtotime($data_ot->end_date)) ?>
                    </strong>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-3 control-label" for="aprobar">Aprobar OT</label>
                  <div class="col-lg-5 checkbox">
                    <input type="hidden" name="ot_id" id="" value="<?php echo $data_ot->cotizacion_ot_id; ?>">
                    <input type="hidden" name="designation_id" id="designation_id" value="<?php echo $data_ot->area_asignada; ?>">
                    <input data-id="" data-toggle="toggle" name="aprobar" id="aprobar" value="1" data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox" checked>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Observaciones</label>
                  <div class="col-sm-5">
                    <textarea name="observaciones" id="observaciones" cols="30" rows="5" class="form-control"></textarea>
                  </div>
                </div>
                <div class="form-group mt">
                  <label class="col-lg-3"></label>
                  <div class="col-lg-5">
                    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
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
  <script>
    $('#myModal').on('loaded.bs.modal', function() {
      console.log('ok')

      // $("#active-achivo").bootstrapToggle();
      $("input[type=checkbox]").bootstrapToggle();


    })
  </script>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css">
<style type="text/css">
  .datepicker {
    z-index: 1151 !important;
  }

  .mt-sm {
    font-size: 14px;
  }
</style>
<?php
echo message_box('success');
echo message_box('error');
$curency = $this->admin_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
$leave_info = $this->db->where('attendance_status', '3')->get('tbl_attendance')->result();
?>
<div class="dashboard row">

  <div class="full-calender">
    <div class="clearfix visible-sm-block "></div>

    <div class="col-sm-12 mt-lg">
      <div class="panel panel-custom ">
        <div class="panel-heading mb0 pt-sm pb-sm">
          <div class="panel-title ">
            <h4><?php echo $title; ?>
              <?php if (in_array($this->session->userdata('designations_id'), [5])) : ?>
                <a class="btn btn-success" href="<?= base_url() ?>admin/pv_order/send_activities"><i class="fa fa-send"></i> Enviar ordenes de trabajo Actividades </a>

                <div class="pull-right">
                  <a class="btn btn-info" href="<?= base_url() ?>admin/pv_order/"><i class="fa fa-arrow-left"></i> Retornar a Ordenes </a>

                </div>

              <?php endif; ?>


              <?php if (in_array($this->session->userdata('designations_id'), [6, 7])) : ?>

                <div class="pull-right">
                  <a class="btn btn-info" href="<?= base_url() ?>admin/pv_actividades_realizar/"><i class="fa fa-arrow-left"></i> Retornar a Ordenes </a>
                </div>
              <?php endif; ?>
            </h4>

          </div>
        </div>
        <?php if (in_array($this->session->userdata('designations_id'), [5])) : ?>
          <?php echo form_open(base_url('admin/pv_order/activities_calendar/'), array('id' => 'activities_calendar', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>

          <div class="form-row">
            <div class="col-md-4 mb-3">
              <label for="validationCustom01">Cliente</label>
              <select name="cliente_id" id="cliente_id" class=" form-control select_box " style="width: 100%">
                <option value="">Seleccionar</option>
                <?php foreach ($clientes as $key => $cliente) : ?>
                  <option value="<?php echo $cliente->cliente_id; ?>" <?php echo (isset($cliente_id) && $cliente_id == $cliente->cliente_id) ? 'selected' : ''; ?>><?php echo $cliente->ruc . '-' . $cliente->razon_social; ?></option>
                <?php endforeach; ?>
              </select>

            </div>
            <div class="col-md-4 mb-3">
              <label for="validationCustom02">Areas</label>
              <select name="designation_id" id="designation_id" class=" form-control select_box " style="width: 100%">
                <option value="">Seleccionar</option>
                <?php foreach ($designations as $key => $designation) : ?>
                  <option value="<?php echo $designation->designations_id; ?>" <?php echo (isset($designation_id) && $designation_id == $designation->designations_id) ? 'selected' : ''; ?>><?php echo $designation->designations; ?></option>
                <?php endforeach; ?>
              </select>

            </div>
            <div class="col-md-4 mb-3">
              <button type="submit" class="btn btn-success">Filtrar</button>
            </div>
          </div>
          </form>
        <?php endif; ?>
          <div class="clearfix"></div>
        <div class="">
          <div class="mt-lg" id="calendar"></div>
        </div>
      </div>
    </div>

  </div>
  <?php
  $gcal_api_key = config_item('gcal_api_key');
  $gcal_id = config_item('gcal_id');
  ?>
  <!--Calendar-->
  <script type="text/javascript">
    $(document).ready(function() {
      if ($('#calendar').length) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var calendar = $('#calendar').fullCalendar({
          googleCalendarApiKey: '<?= $gcal_api_key ?>',
          eventAfterRender: function(event, element, view) {
            if (event.type == 'fo') {
              $(element).attr('data-toggle', 'ajaxModal').addClass('ajaxModal');
            }
          },
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          //                    buttonText: {
          //                        prev: '<i class="fa fa-angle-left"><i>',
          //                        next: '<i class="fa fa-angle-right"><i>'
          //                    },
          //                     firstDay: 1,
          selectable: true,
          selectHelper: true,
          eventLimit: true,
          select: function(start, end, allDay) {
            var endtime = $.fullCalendar.formatDate(end, 'h:mm tt');
            var starttime = $.fullCalendar.formatDate(start, 'yyyy/MM/dd');
            var mywhen = starttime + ' - ' + endtime;
            $('#event_modal #apptStartTime').val(starttime);
            $('#event_modal #apptEndTime').val(starttime);
            $('#event_modal #apptAllDay').val(allDay);
            $('#event_modal #when').text(mywhen);
            $('#event_modal').modal('show');
          },

          eventSources: [{
            events: [
              <?php
              if ($activities) :
                foreach ($activities as $key => $activitie) :
              ?> {
                    title: '<?php echo $activitie->activitie . ' - ' . $activitie->razon_social . '-' . $activitie->direccion ?>',
                    description: '<?php echo $activitie->pv_order_activitie_id . '-' . $activitie->status_activitie ?>',
                    start: '<?= date('Y-m-d', strtotime($activitie->start_date)) ?>',
                    end: '<?= date('Y-m-d', strtotime($activitie->end_date . '+1 day')) ?>',
                    color: '<?php echo ($activitie->status_activitie == 1) ? "#3A87AD" : (($activitie->status_activitie == 2) ? "#DFCC0E" : '#0B9700'); ?>'
                  },
              <?php
                endforeach;
              endif;
              ?>
            ]
          }, ],

          <?php if ($this->session->userdata('designations_id') ==  5) : ?>
            eventClick: function(calEvent, jsEvent, view) {
              let description = calEvent.description.split('-')
              console.log(description)
              if (description[1] == 1) {
                $('#event-title').text(calEvent.title);
                $.ajax({
                  type: "GET",
                  url: base_url + 'admin/pv_order/update_order_activitie/' + description[0],
                  dataType: "html",
                  success: function(response) {
                    console.log(response)
                    $('#event-description').html(response);
                    $('#myModal_calendar').modal();
                  }
                });


              }
            }
          <?php endif; ?>
        });
      }
    });
  </script>
  <?php if ($this->session->userdata('designations_id') ==  5) : ?>
    <div class="modal fade" id="myModal_calendar" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div id="event-description"></div>
      </div>
    </div>
</div>
<?php endif; ?>
<script src='<?= base_url() ?>assets/plugins/fullcalendar/moment.min.js'></script>
<script src='<?= base_url() ?>assets/plugins/fullcalendar/fullcalendar.min.js'></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/fullcalendar/gcal.min.js"></script>

<script>
  $('#myModal_calendar').on('loaded.bs.modal', function() {
    $("input[type=checkbox]").bootstrapToggle();
    $('.start_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd',
      todayBtn: "linked"
      // update "toDate" defaults whenever "fromDate" changes
    }).on('changeDate', function() {
      // set the "toDate" start to not be later than "fromDate" ends:
      $('.end_date').datepicker('setStartDate', new Date($(this).val()));
    });

    $('.end_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd',
      todayBtn: "linked"
      // update "fromDate" defaults whenever "toDate" changes
    }).on('changeDate', function() {
      // set the "fromDate" end to not be later than "toDate" starts:
      $('.start_date').datepicker('setEndDate', new Date($(this).val()));
    });
  })
</script>
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
                        <h4>Calendario
                        </h4>

                    </div>
                </div>
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
                    locale: 'es',
                    googleCalendarApiKey: '<?= $gcal_api_key ?>',
                    eventAfterRender: function(event, element, view) {
                        if (event.type == 'fo') {
                            $(element).attr('data-toggle', 'ajaxModal').addClass('ajaxModal');
                        }
                    },
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay,listMonth'
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
                    eventSources: [
                        {
                                            events: [
                                                <?php 
                                                $data_OV = $this->db->get( 'tbl_visita_tecnica_orden' )->result();
                                                foreach($data_OV as $key => $ov):
                                                ?>
                                                {
                                                title: '<?php echo clear_textarea_breaks('Visita Tecnica '.$ov->visita_tecnica_id) ?>',
                                                        start: '<?= date('Y-m-d', strtotime($ov->fecha_visita)) ?>',
                                                        end: '<?= date('Y-m-d', strtotime($ov->fecha_visita)) ?>',
                                                        color: 'green'
                                                },
                                                <?php
                                                endforeach;
                                                    ?>
                                            ]
                                        },
                                        {
                                            events: [
                                                <?php 
                                                $data_OT = $this->db->get( 'tbl_cotizacion_ot' )->result();
                                                foreach($data_OT as $key => $ot):
                                                ?>
                                                {
                                                title: '<?php echo clear_textarea_breaks('OT '.$ot->cotizacion_ot_id) ?>',
                                                        start: '<?= date('Y-m-d', strtotime($ot->start_date)) ?>',
                                                        end: '<?= date('Y-m-d', strtotime($ot->end_date)) ?>',
                                                        color: 'blue'
                                                },
                                                <?php
                                                endforeach;
                                                    ?>
                                            ]
                                        },
                        <?php if (!empty($gcal_id)) { ?> {
                                googleCalendarId: '<?= $gcal_id ?>'
                            }
                        <?php } ?>
                    ]
                });
            }
        });
    </script>
    <script src='<?= base_url() ?>assets/plugins/fullcalendar/moment.min.js'></script>
    <script src='<?= base_url() ?>assets/plugins/fullcalendar/fullcalendar.min.js'></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/plugins/fullcalendar/gcal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/locales-all.js"></script>
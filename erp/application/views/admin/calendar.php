<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/html2pdf/dist/html2pdf.bundle.min.js"></script>
<style type="text/css">
    .cont-erpsl_loader-admin{display:none;visibility:hidden;opacity:0;pointer-events:none;margin-left:-0.5rem;}
    .cont-erpsl_loader-admin.show{display:block;visibility:visible;opacity:1;pointer-events:auto;margin-left:1rem;}
    .datepicker {z-index: 1151 !important;}
    .mt-sm {font-size: 14px;}
    .d-ibclock{display:inline-block !important;}
    .c-honlytwoelem{display:flex;align-items:center;justify-content:space-between;flex-flow:wrap row;}
    .cnt-btngenPDF_calendar{margin-left:auto;text-align:right;display:inline-block;}
    .btnlink-exppdf-calendar_admin{padding-top:.25rem;padding-bottom:.45rem;-webkit-appearance: none;-moz-appearance: none;appearance: none;}
    .btnlink-exppdf-calendar_admin svg{color:#fff;fill:#fff;width: 1.5rem;vertical-align: middle;}
    .btnlink-exppdf-calendar_admin span{margin-left:.4rem;vertical-align: middle;}
    .dcontent_calendar-admin{position:relative;margin: 2rem auto 2rem auto;width: 100%;padding: 0 3rem;}
    .dcontent_calendar-admin #calendar{float:unset !important;margin:0 auto;padding:0;}
    @media (min-width:991px){
        .cnt-btngenPDF_calendar{float:right;}
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
                    <div class="panel-title">
                        <div class="c-honlytwoelem">
                            <h4>Calendario - Visita técnica</h4>
                            <div class="cnt-btngenPDF_calendar">
                                <a href="javascript:void(0);" class="btn btn-danger btnlink-exppdf-calendar_admin" id="btn-genpdf-activities_calendar_admin">
                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M184 208c0-4.406-3.594-8-8-8S168 203.6 168 208c0 2.062 .2969 23.31 9.141 50.25C179.1 249.6 184 226.2 184 208zM256 0v128h128L256 0zM80 422.4c0 9.656 10.47 11.97 14.38 6.375C99.27 421.9 108.8 408 120.1 388.6c-14.22 7.969-27.25 17.31-38.02 28.31C80.75 418.3 80 420.3 80 422.4zM224 128L224 0H48C21.49 0 0 21.49 0 48v416C0 490.5 21.49 512 48 512h288c26.51 0 48-21.49 48-48V160h-127.1C238.3 160 224 145.7 224 128zM292 312c24.26 0 44 19.74 44 44c0 24.67-18.94 44-43.13 44c-5.994 0-11.81-.9531-17.22-2.805c-20.06-6.758-38.38-15.96-54.55-27.39c-23.88 5.109-45.46 11.52-64.31 19.1c-14.43 26.31-27.63 46.15-36.37 58.41C112.1 457.8 100.8 464 87.94 464C65.92 464 48 446.1 48 424.1c0-11.92 3.74-21.82 11.18-29.51c16.18-16.52 37.37-30.99 63.02-43.05c11.75-22.83 21.94-46.04 30.33-69.14C136.2 242.4 136 208.4 136 208c0-22.05 17.95-40 40-40c22.06 0 40 17.95 40 40c0 24.1-7.227 55.75-8.938 62.63c-1.006 3.273-2.035 6.516-3.082 9.723c7.83 14.46 17.7 27.21 29.44 38.05C263.1 313.4 284.3 312.1 287.6 312H292zM156.5 354.6c17.98-6.5 36.13-11.44 52.92-15.19c-12.42-12.06-22.17-25.12-29.8-38.16C172.3 320.6 164.4 338.5 156.5 354.6zM292.9 368C299 368 304 363 304 356.9C304 349.4 298.6 344 292 344H288c-.3438 .0313-16.83 .9687-40.95 4.75c11.27 7 24.12 13.19 38.84 18.12C288 367.6 290.5 368 292.9 368z"/></svg>
                                      <span>DESCARGAR</span>
                                  </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7 col-sx-12 dcontent_calendar-admin">
                        <div class="col-md-8 col-sx-12" id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
    $gcal_api_key = config_item('gcal_api_key');
    $gcal_id = config_item('gcal_id');
    ?>
<div id="loader-wrapper" class="cont-erpsl_loader-admin">
    <div id="loader"></div>
</div>
<!--Calendar-->
<script type="text/javascript">
$(document).ready(function() {
    if ($('#calendar').length) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var calendar = $('#calendar').fullCalendar({
            firstDay: 0,
            monthNames: ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
            monthNamesShort: ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SET", "OCT", "NOV", "DIC"],
            dayNames: ["DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO"],
            dayNamesShort: ["DOM", "LUN", "MAR", "MIE", "JUE", "VIE", "SAB"],
            buttonText: {
                prev: "Ant",
                next: "Sig",
                prevYear: "Año Ant",
                nextYear: "Año Sig",
                today: 'HOY',
                month: 'MES',
                week: 'SEMANA',
                day: 'DIA',
                list: 'Agenda',
            },
            weekText: 'Sm',
            allDayText: 'Todo el día',
            moreLinkText: 'más',
            noEventsText: 'No hay eventos para mostrar',
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
            /*
            buttonText: {
                prev: '<i class="fa fa-angle-left"><i>',
                next: '<i class="fa fa-angle-right"><i>'
            },
            firstDay: 1,
            */
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
<!-- NUEVO CONTENIDO (INICIO) -->
<script type="text/javascript">
window.onload = function(){
    let cloadingloader = document.querySelector(".cont-erpsl_loader-admin");
    let c_calendarToPDF = document.querySelector(".dcontent_calendar-admin");
    const btncalendarToPDF = document.querySelector("#btn-genpdf-activities_calendar_admin");
    var calendartoPDF = document.getElementById("calendar");
    btncalendarToPDF.addEventListener("click", function(){
        cloadingloader.classList.add("show");
        c_calendarToPDF.style.opacity = '0';
        //calendartoPDF.classList.add('col-md-7');
        //calendartoPDF.classList.add('col-sx-12');
        calendartoPDF.style.margin = '5.48rem 4.96rem 4.96rem 4.96rem'; // A4 => 2.1cm
        html2pdf().set(
            {
                margin: [0.5, 0.5], // [Vertical, Horizontal]
                filename: 'calendarioPDF.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 3, //Entre más alto el valor mayor el peso
                    letterRendering: true,
                    //width: 100,
                    //height: 70,
                    windowWidth: 50,
                    //windowHeight: 60
                    x: 0,
                    y: 0
                },
                jsPDF: {
                    unit: 'mm', // "mm", "cm", "m", "in" or "px".
                    format: 'a4',
                    orientation: 'landscape', //landscape o portrait
                    //w: 100,
                    //h: 50
                }
            },
        ).from(calendartoPDF).toPdf().get('pdf').save().catch(err => console.log(err)).finally().then(function(){
            c_calendarToPDF.style.opacity = '1';
            //calendartoPDF.classList.remove('col-md-7');
            //calendartoPDF.classList.remove('col-sx-12');
            calendartoPDF.style.margin = '0 auto';
            cloadingloader.classList.remove("show");
        });
    });
}
</script>
<!-- NUEVO CONTENIDO (FIN) -->
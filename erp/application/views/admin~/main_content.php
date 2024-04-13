<style type="text/css">
    .datepicker {
        z-index: 1151 !important;
    }

    .mt-sm {
        font-size: 14px;
    }

    .close-btn {
        font-weight: 100;
        position: absolute;
        right: 10px;
        top: -10px;
        display: none;
    }

    .close-btn i {
        font-weight: 100;
        color: #89a59e;
    }

    .report:hover .close-btn {
        display: block;
    }

    .mt-lg:hover .close-btn {
        display: block;
    }
</style>
<?php
echo message_box('success');
echo message_box('error');
$curency = $this->admin_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
if (empty($curency)) {
    $curency = $this->admin_model->check_by(array('code' => 'AUD'), 'tbl_currencies');
}
$all_report = $this->db->where('report', 1)->order_by('order_no', 'ASC')->get('tbl_dashboard')->result();
if ($this->session->userdata('user_type') == 1) {
    $where = array('report' => 0, 'status' => 1);
} else {
    $where = array('report' => 0, 'status' => 1, 'for_staff' => 1);
}
$all_order_data = $this->db->where($where)->order_by('order_no', 'ASC')->get('tbl_dashboard')->result();;
?>
<div class="dashboard">

    <!--        ******** transactions ************** -->
    <?php if ($this->session->userdata('user_type') == 1) { ?>
        <div id="report_menu" class="row">
        <?php 
/**
 * ADICIONANDO CALENDARIO AL DASHBOARD 
 */
$this->load->view('admin/calendar');
?>
        </div>
    <?php } ?>
    <div class="clearfix visible-sm-block "></div>
    <?php
    $all_project = $this->admin_model->get_permission('tbl_project', array('project_status !=' => 'completed'));
    $project_overdue = 0;
    if (!empty($all_project)) {
        foreach ($all_project as $v_project) {
            $progress = $this->items_model->get_project_progress($v_project->project_id);
            if (strtotime(date('Y-m-d')) > strtotime($v_project->end_date) && $progress < 100) {
                $project_overdue += count($v_project->project_id);
            }
        }
    }
    $task_all_info = $this->admin_model->get_permission('tbl_task', array('task_status !=' => 'completed'));

    $task_overdue = 0;

    if (!empty($task_all_info)) :
        foreach ($task_all_info as $v_task_info) :
            $due_date = $v_task_info->due_date;
            $due_time = strtotime($due_date);
            if (strtotime(date('Y-m-d')) > $due_time && $v_task_info->task_progress < 100) {
                $task_overdue += count($v_task_info->task_id);
            }
        endforeach;
    endif;
    $all_invoices_info = $this->admin_model->get_permission('tbl_invoices');
    $invoice_overdue = 0;
    $total_invoice_amount = 0;
    if (!empty($all_invoices_info)) {
        foreach ($all_invoices_info as $v_invoices) {
            $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
            if (strtotime($v_invoices->due_date) < strtotime(date('Y-m-d')) && $payment_status != lang('fully_paid')) {
                $invoice_overdue += count($v_invoices->invoices_id);
            }
            $total_invoice_amount += $this->invoice_model->calculate_to('total', $v_invoices->invoices_id);
        }
    }
    $all_estimates_info = $this->admin_model->get_permission('tbl_estimates');
    $estimate_overdue = 0;
    $total_estimate_amount = 0;
    if (!empty($all_estimates_info)) {
        foreach ($all_estimates_info as $v_estimates) {
            if (strtotime($v_estimates->due_date) < strtotime(date('Y-m-d')) && $v_estimates->status == 'Pending') {
                $estimate_overdue += count($v_estimates->estimates_id);
            }
            $total_estimate_amount += $this->estimates_model->estimate_calculation('total', $v_estimates->estimates_id);
        }
    }
    $all_bugs_info = $this->admin_model->get_permission('tbl_bug');
    $bug_unconfirmed = 0;
    if (!empty($all_bugs_info)) : foreach ($all_bugs_info as $key => $v_bugs) :
            if ($v_bugs->bug_status == 'unconfirmed') {
                $bug_unconfirmed += count($v_bugs->bug_id);
            }
        endforeach;
    endif;
    $all_opportunity = $this->admin_model->get_permission('tbl_opportunities');
    $opportunity_overdue = 0;
    if (!empty($all_opportunity)) {
        foreach ($all_opportunity as $v_opportunity) {
            if (strtotime(date('Y-m-d')) > strtotime($v_opportunity->close_date) && $v_opportunity->probability < 100) {
                $opportunity_overdue += count($v_opportunity->opportunities_id);
            }
        }
    } ?>

</div>
<?php

if ($this->session->userdata('user_type') == 1) {
    $where = array('status' => 1);
} else {
    $t_where = array('for_staff' => 1);
    $where = $where + $t_where;
}
$income_report_order = get_row('tbl_dashboard', array('name' => 'income_report') + $where);
$expense_report_order = get_row('tbl_dashboard', array('name' => 'expense_report') + $where);
$income_expense_order = get_row('tbl_dashboard', array('name' => 'income_expense') + $where);
$payments_report_order = get_row('tbl_dashboard', array('name' => 'payments_report') + $where);
$finance_overview_order = get_row('tbl_dashboard', array('name' => 'finance_overview') + $where);
$goal_report_order = get_row('tbl_dashboard', array('name' => 'goal_report') + $where);
?>
<!-- Morris.js charts -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/raphael/raphael.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/morris/morris.min.js"></script>
<!-- / Chart.js Script -->
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/chart.min.js" type="text/javascript"></script>
<?php /*Comment in my JavaScript*/ ?>



<script type="text/javascript">
    $(document).ready(function() {
        $('.complete-todo input[type="checkbox"]').change(function() {
            var todo_id = $(this).data().id;
            var todo_complete = $(this).is(":checked");

            var formData = {
                'todo_id': todo_id,
                'status': '3'
            };
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>admin/dashboard/completed_todo/' + todo_id,
                data: formData,
                dataType: 'json',
                encode: true,
                success: function(res) {
                    if (res) {
                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });

    });
</script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/jquery-ui/jquery-u.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("#report_menu").sortable({
            connectWith: ".report_menu",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function(event, ui) {
                var id = JSON.stringify(
                    $("#report_menu").sortable(
                        'toArray', {
                            attribute: 'id'
                        }
                    )
                );
                var formData = {
                    'report_menu': id
                };
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url() ?>admin/settings/save_dashboard/',
                    data: formData,
                    dataType: 'json',
                    encode: true,
                    success: function(res) {
                        if (res) {} else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })

            }
        });
        $(".report_menu").disableSelection();

        $("#menu").sortable({
            connectWith: ".menu",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function(event, ui) {
                var mid = JSON.stringify(
                    $("#menu").sortable(
                        'toArray', {
                            attribute: 'id'
                        }
                    )
                );
                var formData = {
                    'menu': mid
                };
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url() ?>admin/settings/save_dashboard/',
                    data: formData,
                    dataType: 'json',
                    encode: true,
                    success: function(res) {
                        if (res) {} else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })
            }
        });
        $(".menu").disableSelection();
    });
</script>
<?php /*Comment in my JavaScript*/ ?>
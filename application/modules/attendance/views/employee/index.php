<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-check-square-o"></i><small> <?php echo $this->lang->line('employee_attendance'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                <?php $this->load->view('quick-link'); ?>
            </div>

            <div class="x_content">
                <?php echo form_open_multipart(site_url('attendance/employee/index'), array('name' => 'employee', 'id' => 'employee', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row">
                    <?php $this->load->view('layout/school_list_filter'); ?>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group">
                            <div><?php echo $this->lang->line('start_date'); ?> <span class="required">*</span></div>
                            <input class="form-control col-md-7 col-xs-12" name="start_date" id="start_date" value="<?php if (isset($start_date)) {
                                                                                                                        echo $start_date;
                                                                                                                    } ?>" placeholder="<?php echo $this->lang->line('start_date'); ?>" required="required" type="text" autocomplete="off">
                            <div class="help-block"><?php echo form_error('start_date'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group">
                            <div><?php echo $this->lang->line('to_date'); ?> <span class="required">*</span></div>
                            <input class="form-control col-md-7 col-xs-12" name="to_date" id="to_date" value="<?php if (isset($to_date)) {
                                                                                                                    echo $to_date;
                                                                                                                } ?>" placeholder="<?php echo $this->lang->line('to_date'); ?>" required="required" type="text" autocomplete="off">
                            <div class="help-block"><?php echo form_error('to_date'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group"><br />
                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>

            <?php if (isset($employees) && !empty($employees)) { ?>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-4  col-sm-offset-4 layout-box">
                            <p>
                            <h4><?php echo $this->lang->line('employee_attendance'); ?></h4>
                            <?php echo $this->lang->line('day'); ?> : <?php echo date('l', strtotime($start_date)) . ' - ' . date('l', strtotime($to_date)); ?><br />
                            <?php echo $this->lang->line('date'); ?> : <?php echo date('jS F Y', strtotime($start_date)) . ' - ' . date('jS F Y', strtotime($to_date)); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                            <th><?php echo $this->lang->line('photo'); ?></th>
                            <th><?php echo $this->lang->line('name'); ?></th>
                            <th><?php echo $this->lang->line('designation'); ?></th>
                            <th><?php echo $this->lang->line('phone'); ?></th>
                            <th><?php echo $this->lang->line('email'); ?></th>
                            <th><input type="checkbox" value="P" name="present" id="fn_present" class="fn_all_attendnce" /> <?php echo $this->lang->line('present_all'); ?></th>
                            <th><input type="checkbox" value="L" name="late" id="fn_late" class="fn_all_attendnce" /> <?php echo $this->lang->line('late_all'); ?></th>
                            <th><input type="checkbox" value="A" name="absent" id="fn_absent" class="fn_all_attendnce" /> <?php echo $this->lang->line('absent_all'); ?></th>
                            <th><input type="checkbox" value="H" name="holiday" id="fn_holiday" class="fn_all_attendnce" /> H & L</th>
                        </tr>
                    </thead>
                    <tbody id="fn_attendance">
                        <?php
                        $count = 1;
                        if (isset($employees) && !empty($employees)) {
                        ?>
                            <?php foreach ($employees as $obj) { ?>
                                <?php $attendance = get_employee_attendance($obj->id, $school_id, $academic_year_id, $year, $month, $day); ?>
                                <tr class="checklisttr">
                                    <td><?php echo $count++;  ?></td>
                                    <td>
                                        <?php if ($obj->photo != '') { ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/employee-photo/<?php echo $obj->photo; ?>" alt="" width="60" />
                                        <?php } else { ?>
                                            <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="60" />
                                        <?php } ?>
                                    </td>
                                    <td><?php echo ucfirst($obj->name); ?></td>
                                    <td><?php echo $obj->designtion; ?></td>
                                    <td><?php echo $obj->phone; ?></td>
                                    <td><?php echo $obj->email; ?></td>
                                    <td><input type="radio" value="P" itemid="<?php echo $obj->id; ?>" name="employee_<?php echo $obj->id; ?>" class="present fn_single_attendnce" <?php if ($attendance == 'P') {
                                                                                                                                                                                        echo 'checked="checked"';
                                                                                                                                                                                    } ?> /></td>
                                    <td><input type="radio" value="L" itemid="<?php echo $obj->id; ?>" name="employee_<?php echo $obj->id; ?>" class="late fn_single_attendnce" <?php if ($attendance == 'L') {
                                                                                                                                                                                    echo 'checked="checked"';
                                                                                                                                                                                } ?> /></td>
                                    <td><input type="radio" value="A" itemid="<?php echo $obj->id; ?>" name="employee_<?php echo $obj->id; ?>" class="absent fn_single_attendnce" <?php if ($attendance == 'A') {
                                                                                                                                                                                        echo 'checked="checked"';
                                                                                                                                                                                    } ?> /></td>
                                    <td><input type="radio" value="H" itemid="<?php echo $obj->id; ?>" name="employee_<?php echo $obj->id; ?>" class="holiday fn_single_attendnce" <?php if ($attendance == 'H') {
                                                                                                                                                                                        echo 'checked="checked"';
                                                                                                                                                                                    } ?> /></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="9" align="center"><?php echo $this->lang->line('no_data_found'); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button id="all_attendence" class="btn btn-info">submit</button>
                <button id="all_clear" class="btn btn-info">Clear</button>
            </div>

        </div>
    </div>
</div>


<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
<script type="text/javascript">
    $('#start_date').datepicker();
    $('#to_date').datepicker();

    $(document).ready(function() {

        $('#fn_present').click(function() {

            if ($(this).prop('checked')) {
                $('input:checkbox').removeAttr('checked');
                $(this).prop('checked', true);
                $('.present').prop('checked', true);
            } else {
                $('.present').prop('checked', false);
            }
        });


        $('#fn_late').click(function() {

            if ($(this).prop('checked')) {
                $('input:checkbox').removeAttr('checked');
                $(this).prop('checked', true);
                $('.late').prop('checked', true);
            } else {
                $('.late').prop('checked', false);
            }
        });

        $('#fn_absent').click(function() {

            if ($(this).prop('checked')) {
                $('input:checkbox').removeAttr('checked');
                $(this).prop('checked', true);
                $('.absent').prop('checked', true);
            } else {
                $('.absent').prop('checked', false);
            }
        });

        $('#fn_holiday').click(function() {

            if ($(this).prop('checked')) {
                $('input:checkbox').removeAttr('checked');
                $(this).prop('checked', true);
                $('.holiday').prop('checked', true);
            } else {
                $('.holiday').prop('checked', false);
            }
        });

        // $('.fn_single_attendnce').click(function() {

        //     var status = $(this).prop('checked') ? $(this).val() : '';
        //     var employee_id = $(this).prop('checked') ? $(this).attr('itemid') : '';
        //     var school_id = $('#school_id').val();
        //     var class_id = $('#class_id').val();
        //     var section_id = $('#section_id').val();
        //     var date = $('#date').val();
        //     var obj = $(this);
        //     $.ajax({
        //         type: "POST",
        //         url: "<?php echo site_url('attendance/employee/update_single_attendance'); ?>",
        //         data: {
        //             school_id: school_id,
        //             status: status,
        //             employee_id: employee_id,
        //             class_id: class_id,
        //             section_id: section_id,
        //             date: date
        //         },
        //         async: false,
        //         success: function(response) {

        //             if (response == 'ay') {
        //                 toastr.error('<?php echo $this->lang->line('set_academic_year_for_school'); ?>');
        //                 $(obj).prop('checked', false);

        //             } else if (response == 1) {

        //                 toastr.success('<?php echo $this->lang->line('update_success'); ?>');

        //             } else {

        //                 toastr.error('<?php echo $this->lang->line('update_failed'); ?>');
        //                 $(obj).prop('checked', false);
        //             }
        //         }
        //     });

        // });

        // $('.fn_all_attendnce').click(function() {

        //     var status = $(this).prop('checked') ? $(this).val() : '';
        //     var school_id = $('#school_id').val();
        //     var class_id = $('#class_id').val();
        //     var section_id = $('#section_id').val();
        //     var date = $('#date').val();
        //     var obj = $(this);

        //     $.ajax({
        //         type: "POST",
        //         url: "<?php echo site_url('attendance/employee/update_all_attendance'); ?>",
        //         data: {
        //             school_id: school_id,
        //             status: status,
        //             class_id: class_id,
        //             section_id: section_id,
        //             date: date
        //         },
        //         async: false,
        //         success: function(response) {
        //             if (response == 'ay') {

        //                 toastr.error('<?php echo $this->lang->line('set_academic_year_for_school'); ?>');
        //                 $('.fn_single_attendnce').prop('checked', false);
        //                 $(obj).prop('checked', false);

        //             } else if (response == 1) {
        //                 toastr.success('<?php echo $this->lang->line('update_success'); ?>');
        //             } else {
        //                 toastr.error('<?php echo $this->lang->line('update_failed'); ?>');
        //                 $('.fn_single_attendnce').prop('checked', false);
        //                 $(obj).prop('checked', false);
        //             }
        //         }
        //     });

        // });
        $('#all_attendence').click(function() {
            $('tr.checklisttr > td > input:radio:checked').each(function() {
                var status = $(this).val();
                var employee_id = $(this).attr('itemid');
                statusupdate(status, employee_id);
            });
            toastr.success('<?php echo $this->lang->line('update_success'); ?>');
        });

        $('#all_clear').click(function() {
            $('.fn_single_attendnce').prop('checked', false);
            var status = '';
            var school_id = $('#school_id').val();
            var start_date = $('#start_date').val();
            var to_date = $('#to_date').val();
            var obj = $(this);

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('attendance/employee/update_all_attendance'); ?>",
                data: {
                    status: status,
                    school_id: school_id,
                    start_date: start_date,
                    to_date: to_date
                },
                async: false,
                success: function(response) {
                    if (response == 'ay') {

                        toastr.error('<?php echo $this->lang->line('set_academic_year_for_school'); ?>');
                        $('.fn_single_attendnce').prop('checked', false);
                        $(obj).prop('checked', false);

                    } else if (response == 1) {
                        toastr.success('<?php echo $this->lang->line('update_success'); ?>');
                    } else {
                        toastr.error('<?php echo $this->lang->line('update_failed'); ?>');
                        $('.fn_single_attendnce').prop('checked', false);
                        $(obj).prop('checked', false);
                    }
                }
            });

        });
    });
    $("#employee").validate();

    function statusupdate(status, employee_id) {
        var school_id = $('#school_id').val();
        var start_date = $('#start_date').val();
        var to_date = $('#to_date').val();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('attendance/employee/update_single_attendance'); ?>",
            data: {
                school_id: school_id,
                status: status,
                employee_id: employee_id,
                start_date: start_date,
                to_date: to_date
            },
            async: false,
            success: function(response) {}
        });
    }
</script>
<style>
    h1 {
        font-size: 35px;
        text-align: center;
        line-height: 1;
        color: #333;
    }

    .details {
        border-radius: 4px;
        background: #fff;
        border: 1px solid #ebecee;
        padding: 10px;
        border-left: 5px solid var(--theme-bg);
        height: 100%;
    }

    .sticky-header {
        height: var(--sticky-height);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--body-bg);
        z-index: 7;
        position: sticky;
        top: 0;
        font-weight: 700;
        overflow: hidden;
    }

    .sticky-header span {
        opacity: 0;
        transform: translateY(-100%);
        transition: 0.4s;
    }

    .reveal .sticky-header span {
        opacity: 1;
        transform: none;
    }

    .table {
        position: relative;
        border: solid var(--bd-color);
        border-width: 0 1px 0 0;
        overscroll-behavior: contain;
        border-bottom: 1px solid var(--bd-color);
    }

    .headers {
        top: var(--sticky-height);
        position: -webkit-sticky;
        position: sticky;
        display: flex;
        justify-content: flex-end;
        z-index: 1;
    }

    .tracks,
    .scroller {
        display: flex;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
    }

    .scroller {
        overflow-x: hidden;
        flex: 1;
    }

    .tracks {
        overflow: auto;
    }

    .tracks::-webkit-scrollbar,
    .scroller::-webkit-scrollbar {
        display: none;
    }

    .track {
        flex: 1 0 12%;
    }

    .track+.track {
        margin-left: -1px;
    }

    .time {
        flex: 0 0 var(--time-width);
        position: -webkit-sticky;
        position: sticky;
        left: 0;
    }

    .headers .time {
        z-index: 5;
    }

    time {
        font-weight: 600;
        font-size: 12px;
        letter-spacing: 0.03em;
    }

    time {
        color: hsl(210, 5%, 70%);
        text-align: right;
    }

    .time .heading {
        justify-content: flex-end;
        padding-right: 1em;
        font-weight: 500;
        background: #f9f9f9;
    }

    .heading {
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        border: solid var(--bd-color);
        border-width: 1px;
        color: hsla(210, 5%, 40%, 1);
        z-index: 1;
        background: var(--thead-bg);
        font-weight: 700;
    }

    .entry {
        border: 1px solid #ebebeb;
        border-top: 0;
        background: var(--body-bg);
        height: 9em;
        /* padding: 1em; */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .entry:not(:last-of-type) {
        border-bottom-style: dashed;
    }

    .track:last-of-type>div {
        border-right: 0;
    }

    .time .entry,
    .time .heading {
        position: relative;
        border: 1px solid #ebebeb;
        /* justify-content: center; */
        align-items: center;
        width: 85px;
    }

    .time .entry:after,
    .time .heading:after {
        content: "";
        position: absolute;
        bottom: -1px;
        right: -1px;
        width: 50%;
        height: 1px;
        z-index: 3;
        background: linear-gradient(to left, var(--bd-color), var(--body-bg));
    }

    .double {
        height: 18em;
    }

    .treble {
        height: 27em;
    }

    p {
        font-size: 12px;
        color: #333;
        font-weight: 500;
        margin: 0;
    }

    .yellow {
        --theme-color: hsl(40, 50%, 30%);
        --theme-bg: #a40808dd;
    }

    p+p {
        color: #858585;
        margin-top: 5px;
        font-weight: 400;
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        position: absolute;
        z-index: 6;
        height: 100%;
        padding: 1px 0;
        width: calc(100% - var(--time-width));
    }

    button {
        border-radius: 0;
        border: 0;
        padding: 5px;
        font: inherit;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        outline: none !important;
        cursor: pointer;
        background: var(--thead-bg);
    }

    button>svg {
        line-height: 0;
        width: 30px;
        height: 30px;
        fill: var(--thead-color);
        pointer-events: none;
    }

    .btn-left {
        transform: scaleX(-1);
    }

    #top-of-site-pixel-anchor {
        position: absolute;
        width: 1px;
        height: 1px;
        top: 15em;
        left: 0;
    }

    @media (max-width: 767px) {
        .track:not(.time) {
            flex: 1 0 calc(50% + 7px);
        }
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title no-print">
                <h3 class="head-title"><i class="fa fa-clock-o"></i><small> <?php echo $this->lang->line('manage_routine'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link no-print">
                <?php $this->load->view('quick-link'); ?>
            </div>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    <ul class="nav nav-tabs bordered  no-print">
                        <li class="<?php if (isset($list)) {
                                        echo 'active';
                                    } ?>"><a href="#tab_routine_list" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if (has_permission(ADD, 'academic', 'routine')) { ?>
                            <?php if (isset($edit)) { ?>
                                <li class="<?php if (isset($add)) {
                                                echo 'active';
                                            } ?>"><a href="<?php echo site_url('academic/routine/add'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>
                            <?php } else { ?>
                                <li class="<?php if (isset($add)) {
                                                echo 'active';
                                            } ?>"><a href="#tab_add_routine" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if (isset($edit)) { ?>
                            <li class="active"><a href="#tab_edit_routine" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>
                        <?php } ?>


                        <!-- <li class="li-class-list">
                            <?php if ($this->session->userdata('role_id') != SUPER_ADMIN) {  ?>
                                <select class="form-control col-md-7 col-xs-12" onchange="get_subject_by_class(this.value);">
                                    <?php if ($this->session->userdata('role_id') != STUDENT) { ?>
                                        <option value="<?php echo site_url('academic/routine/index'); ?>">--<?php echo $this->lang->line('select'); ?>--</option>
                                    <?php } ?>

                                    <?php $guardian_class_data = get_guardian_access_data('class'); ?>
                                    <?php foreach ($class_list as $obj) { ?>
                                        <?php if ($this->session->userdata('role_id') == STUDENT) { ?>
                                            <?php if ($obj->id != $this->session->userdata('class_id')) {
                                                continue;
                                            } ?>
                                            <option value="<?php echo site_url('academic/routine/index/' . $obj->id); ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                                                                echo 'selected="selected"';
                                                                                                                            } ?>><?php echo $obj->name; ?></option>
                                        <?php } elseif ($this->session->userdata('role_id') == GUARDIAN) { ?>
                                            <?php if (!in_array($obj->id, $guardian_class_data)) {
                                                continue;
                                            } ?>
                                            <option value="<?php echo site_url('academic/routine/index/' . $obj->id); ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                                                                echo 'selected="selected"';
                                                                                                                            } ?>><?php echo $obj->name; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo site_url('academic/routine/index/' . $obj->id); ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                                                                echo 'selected="selected"';
                                                                                                                            } ?>><?php echo $obj->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            <?php } else { ?>

                                <?php echo form_open(site_url('academic/routine/index'), array('name' => 'filter', 'id' => 'filter', 'class' => 'form-horizontal form-label-left'), ''); ?>
                                <select class="form-control col-md-7 col-xs-12" style="width:auto;" name="school_id" onchange="get_class_by_school(this.value, '');">
                                    <option value="">--<?php echo $this->lang->line('select_school'); ?>--</option>
                                    <?php foreach ($schools as $obj) { ?>
                                        <option value="<?php echo $obj->id; ?>" <?php if (isset($filter_school_id) && $filter_school_id == $obj->id) {
                                                                                    echo 'selected="selected"';
                                                                                } ?>> <?php echo $obj->school_name; ?></option>
                                    <?php } ?>
                                </select>
                                <select class="form-control col-md-7 col-xs-12" id="filter_class_id" name="class_id" style="width:auto;" onchange="this.form.submit();">
                                    <option value="">--<?php echo $this->lang->line('class'); ?>--</option>
                                    <?php if (isset($class_list) && !empty($class_list)) { ?>
                                        <?php foreach ($class_list as $obj) { ?>
                                            <option value="<?php echo $obj->id; ?>"><?php echo $obj->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <?php echo form_close(); ?>

                            <?php } ?>
                        </li> -->
                    </ul>
                    <br />

                    <?php if (isset($time_table) && !empty($time_table)) { ?>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6  col-sm-offset-3 col-xs-offset-3 layout-box">
                                    <h4>Time Table <?php echo  date('M d Y', strtotime($time_table->start_date)) . " - " . date('M d Y', strtotime($time_table->end_date)) ?></h4>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="tab-content">
                        <div class="tab-pane fade in <?php if (isset($list)) {
                                                            echo 'active';
                                                        } ?>" id="tab_routine_list">
                            <div class="x_content">


                                <div class="table">
                                    <div class="headers">
                                        <div class="buttons">
                                            <button class="btn-left">
                                                <svg>
                                                    <use xlink:href="#icon-arrow"></use>
                                                </svg>
                                            </button>
                                            <button class="btn-right">
                                                <svg>
                                                    <use xlink:href="#icon-arrow"></use>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="scroller syncscroll" name="myElements">
                                            <div class="track time">
                                                <div class="heading">Time</div>
                                            </div>
                                            <?php
                                            $days = get_week_days();
                                            ?>
                                            <?php foreach ($days as $daykey => $day) { ?>
                                                <div class="track">
                                                    <div class="heading"><?php echo $day; ?></div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tracks syncscroll" name="myElements">
                                        <div class="track time">
                                            <?php
                                            $s = strtotime($time_table->start_time);
                                            $e = strtotime('+60 minutes', strtotime($time_table->end_time));
                                            $start = strtotime($time_table->start_time);
                                            $end = strtotime('+60 minutes', strtotime($time_table->end_time));
                                             while ($s != $e) { ?>
                                                <div class="entry">
                                                    <time><?= date('h:i A', $s) ?></time>
                                                </div>
                                            <?php $s = strtotime('+60 minutes', $s);
                                            } ?>
                                        </div>

                                        <?php foreach ($days as $daykey => $day) {
                                            $daykeystart = strtotime($time_table->start_time);
                                            $daykeyend = strtotime('+60 minutes', strtotime($time_table->end_time)); ?>
                                            <div class="track yellow" style="height:72em;">
                                                <?php while ($daykeystart != $daykeyend) {
                                                    $routin = get_routines_by_day_time($time_table->id, $day, date('H:i', $daykeystart));
                                                    // print_r($routin);
                                                ?>
                                                    <?php
                                                    $height = 9;
                                                    if (!empty($routin)) {
                                                        $height = round($routin->diff / 6.6);
                                                    } ?>
                                                    <div class="entry " style="height: <?php echo $height ?>em;">
                                                        <?php if (!empty($routin)) { ?>
                                                            <div class="details">
                                                                <?php // echo $routin->diff ?>
                                                                <?php echo (!empty($routin)) ? $routin->subject_name . ' - ' . $routin->teacher_name : ""; ?><br />
                                                                <?php echo (!empty($routin)) ? $routin->start_time . ' - ' . $routin->end_time : ""; ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php $daykeystart = strtotime('+60 minutes', $daykeystart);
                                                }
                                                ?>
                                            </div>
                                        <?php } ?>
                                        <!-- <div class="track">
                                            <div class="entry"></div>
                                            <div class="entry"></div>
                                            <div class="entry"></div>
                                            <div class="entry"></div>
                                            <div class="entry"></div>
                                            <div class="entry"></div>
                                            <div class="entry"></div>
                                            <div class="entry"></div>
                                            <div class="entry"></div>
                                        </div> -->
                                    </div>
                                </div>

                                <table style="display: none;" id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <tbody>
                                        <?php $days = get_week_days(); ?>
                                        <tr>
                                            <td width="100">Time/Day</td>
                                            <?php foreach ($days as $daykey => $day) { ?>
                                                <td width="100"><?php echo $day; ?></td>
                                            <?php } ?>
                                        </tr>

                                        <?php
                                        $s = strtotime($time_table->start_time);
                                        $e = strtotime('+60 minutes', strtotime($time_table->end_time));
                                        do { ?>
                                            <tr>
                                                <td><?= date('h:i A', $s) ?></td>
                                                <?php foreach ($days as $daykey => $day) {
                                                    $routin = get_routines_by_day_time($time_table->id, $day, date('H:i', $s));
                                                ?>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-default dropdown-toggle routine-text" data-toggle="dropdown">
                                                                <?php echo (!empty($routin)) ? $routin->subject_name . ' - ' . $routin->teacher_name : ""; ?><br />
                                                                <?php echo (!empty($routin)) ? $routin->start_time . ' - ' . $routin->end_time : ""; ?>
                                                                <span class="caret"></span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php $s = strtotime('+60 minutes', $s);
                                        } while ($s != $e); ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- <div class="row no-print">
                                <div class="col-xs-12 text-right">
                                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                                </div>
                            </div> -->
                        </div>

                        <div class="tab-pane fade in <?php if (isset($add)) {
                                                            echo 'active';
                                                        } ?>" id="tab_add_routine">
                            <div class="x_content">
                                <?php echo form_open(site_url('academic/time_table/add'), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                <?php $this->load->view('layout/school_list_form'); ?>
                                <input type="hidden" name="time_table_id" value="<?= $time_table->id ?>">
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="years"><?php echo $this->lang->line('lacture_type'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_break" id="add_years" required="required" onchange="is_break_time(this.value);">
                                            <option value="1" <?php echo isset($post['is_break']) && $post['is_break'] == '1' ?  'selected="selected"' : ''; ?> selected>Break</option>
                                            <option value="0" <?php echo isset($post['is_break']) && $post['is_break'] == '0' ?  'selected="selected"' : ''; ?>> Lecture</option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_break'); ?></div>
                                    </div>
                                </div>
                                <div id="lecture_detail">
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="subject_id" id="add_subject_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_id"><?php echo $this->lang->line('teacher'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="teacher_id" id="add_teacher_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach ($teachers as $obj) { ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php echo isset($post['teacher_id']) && $post['teacher_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('teacher_id'); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('start_time'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="start_time" id="add_start_time" value="<?php echo isset($post['start_time']) ?  $post['start_time'] : ''; ?>" placeholder="<?php echo $this->lang->line('start_time'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_time'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('end_time'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="end_time" id="add_end_time" value="<?php echo isset($post['end_time']) ?  $post['end_time'] : ''; ?>" placeholder="<?php echo $this->lang->line('end_time'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('end_time'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="day"><?php echo $this->lang->line('day'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12 basic-multiple" name="day[]" id="day" required="required" multiple="multiple">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php $types = get_week_days(); ?>
                                            <?php foreach ($types as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>" <?php echo isset($post['class_id']) && $post['class_id'] == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('day'); ?></div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('academic/time_table/index/' . $time_table->id); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div> <!-- End first tab -->
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .btn-group .btn {
        padding: 2px 6px;
    }

    span.select2.select2-container.select2-container--default {
        width: 100% !important;
    }
</style>
<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>timepicker/timepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>timepicker/timepicker.js"></script>
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Super admin js START  -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.basic-multiple').select2();
    });
    var edit = false;
    <?php if (isset($edit)) { ?>
        edit = true;
    <?php } ?>

    $("document").ready(function() {
        <?php if (isset($routine) && !empty($routine)) { ?>
            $("#add_school_id").trigger('change');
        <?php } ?>
    });

    $('.fn_school_id').on('change', function() {

        var school_id = $(this).val();
        var class_id = '';
        var teacher_id = '';

        <?php if (isset($routine) && !empty($routine)) { ?>
            class_id = '<?php echo $routine->class_id; ?>';
            teacher_id = '<?php echo $routine->teacher_id; ?>';
        <?php } ?>

        // if (!school_id) {
        //     toastr.error('<?php echo $this->lang->line("select_school"); ?>');
        //     return false;
        // }

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (edit) {
                        $('#edit_class_id').html(response);
                    } else {
                        $('#add_class_id').html(response);
                    }

                    get_teacher_by_school(school_id, teacher_id);
                }
            }
        });
    });


    function get_teacher_by_school(school_id, teacher_id) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_teacher_by_school'); ?>",
            data: {
                school_id: school_id,
                teacher_id: teacher_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (edit) {
                        $('#edit_teacher_id').html(response);
                    } else {
                        $('#add_teacher_id').html(response);
                    }
                }
            }
        });
    }
</script>
<!-- Super admin js end -->



<script type="text/javascript">
    $('#add_start_time').timepicker();
    $('#add_end_time').timepicker();
    $('#edit_start_time').timepicker();
    $('#edit_end_time').timepicker();

    $('#add_start_date').datepicker();
    $('#add_end_date').datepicker();
    $('#edit_start_date').datepicker();
    $('#edit_end_date').datepicker();


    <?php if (isset($edit)) { ?>
        get_section_subject_by_class('<?php echo $routine->class_id; ?>', '<?php echo $routine->section_id; ?>', '<?php echo $routine->subject_id; ?>');
        get_department_by_teacher('<?php echo $routine->teacher_id ?>', '<?php echo $routine->department_id ?>');
    <?php } else { ?>
        get_section_subject_by_class('<?php echo $time_table->class_id; ?>', '<?php echo $time_table->section_id; ?>', '');
    <?php } ?>


    function get_section_subject_by_class(class_id, section_id, subject_id) {

        if (edit) {
            var school_id = $('#edit_school_id').val();
        } else {
            var school_id = $('#add_school_id').val();
        }
        // if (!school_id) {
        //     toastr.error('<?php echo $this->lang->line("select_school"); ?>');
        //     return false;
        // }

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_section_by_class'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id,
                section_id: section_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (edit) {
                        $('#edit_section_id').html(response);
                    } else {
                        $('#add_section_id').html(response);
                    }
                }
            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id,
                subject_id: subject_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (edit) {
                        $('#edit_subject_id').html(response);
                    } else {
                        $('#add_subject_id').html(response);
                    }
                }
            }
        });

    }

    function get_subject_by_class(url) {
        if (url) {
            window.location.href = url;
        }
    }
    $("#add").validate();
    $("#edit").validate();


    <?php if (isset($filter_class_id)) { ?>
        get_class_by_school('<?php echo $filter_school_id; ?>', '<?php echo $filter_class_id; ?>');
    <?php } ?>

    function get_class_by_school(school_id, class_id) {


        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_class_by_school'); ?>",
            data: {
                school_id: school_id,
                class_id: class_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    $('#filter_class_id').html(response);
                }
            }
        });
    }

    function get_department_by_teacher(teacher_id, department_id) {


        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_department_by_teacher'); ?>",
            data: {
                teacher_id: teacher_id,
                department_id: department_id
            },
            async: false,
            success: function(response) {
                if (response) {
                    if (edit) {
                        console.log(response);
                        $('#edit_department_id').html(response);
                    } else {
                        $('#add_department_id').html(response);
                    }
                }
            }
        });
    }

    $('#lecture_detail').hide();

    function is_break_time(is_break) {
        if (is_break == '1') {
            $('#lecture_detail').hide();
        } else {
            $('#lecture_detail').show();

        }
    }
</script>
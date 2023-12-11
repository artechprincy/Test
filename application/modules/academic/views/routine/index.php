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

                    <!-- <?php if (isset($sections) && !empty($sections)) { ?>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6  col-sm-offset-3 col-xs-offset-3 layout-box">

                                    <div><img class="logo-identifier" src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $school->logo; ?>" alt="" width="70" /></div>
                                    <h4><?php echo $school->school_name; ?></h4>
                                    <p><?php echo $school->address; ?></p>
                                    <h4><?php echo $this->lang->line('class_routine'); ?></h4>
                                    <?php echo $this->lang->line('class'); ?> - <?php echo $single_class->name; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?> -->


                    <div class="tab-content">
                        <div class="tab-pane fade in <?php if (isset($list)) {
                                                            echo 'active';
                                                        } ?>" id="tab_routine_list">
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <th><?php echo $this->lang->line('time_table_name'); ?></th>
                                            <th><?php echo $this->lang->line('session_year'); ?></th>
                                            <th><?php echo $this->lang->line('created_at'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1;
                                        if (isset($time_tables) && !empty($time_tables)) { ?>
                                            <?php foreach ($time_tables as $obj) { ?>
                                                <tr>
                                                    <td><?php echo $count++; ?></td>
                                                    <td><?php echo $obj->class_name . " " . $obj->sections_name . " Time Table " . date('M d Y', strtotime($obj->start_date)) . " - " . date('M d Y', strtotime($obj->end_date)) ?></td>
                                                    <td><?php echo $obj->session_year ?></td>
                                                    <td><?php echo date('Y-m-d h:i A', strtotime($obj->created_at)) ?></td>
                                                    <td><?php echo ($obj->status == 1) ? "Active" : "Inactive"; ?></td>
                                                    <td>
                                                        <?php if (has_permission(EDIT, 'academic', 'routine')) { ?>
                                                            <a href="<?php echo site_url('academic/routine/edit/' . $obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(VIEW, 'academic', 'routine')) { ?>
                                                            <a href="<?php echo site_url('academic/time_table/index/' . $obj->id); ?>" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(DELETE, 'academic', 'routine')) { ?>
                                                            <a href="<?php echo site_url('academic/routine/delete/' . $obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End first tab -->



                        <div class="tab-pane fade in <?php if (isset($add)) {
                                                            echo 'active';
                                                        } ?>" id="tab_add_routine">
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> <?php echo $this->lang->line('add_routine_instruction'); ?></div>
                                </div>
                                <?php echo form_open(site_url('academic/routine/add'), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                <?php $this->load->view('layout/school_list_form'); ?>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="years"><?php echo $this->lang->line('academic_year'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="academic_year_id" id="add_years" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php foreach ($years as $obj) { ?>
                                                <?php
                                                if ($this->session->userdata('role_id') == TEACHER) {
                                                    if (!in_array($obj->id, $teacher_access_data)) {
                                                        continue;
                                                    }
                                                }
                                                ?>
                                                <option value="<?php echo $obj->id; ?>" <?php echo isset($post['academic_year_id']) && $post['academic_year_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->session_year; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('academic_year_id'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="class_id" id="add_class_id" required="required" onchange="get_section_subject_by_class(this.value, '','');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php foreach ($classes as $obj) { ?>
                                                <option value="<?php echo $obj->id; ?>" <?php echo isset($post['class_id']) && $post['class_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="section_id" id="add_section_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('start_date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="start_date" id="add_start_date" value="<?php echo isset($post['start_date']) ?  $post['start_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('start_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_date'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('end_date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="end_date" id="add_end_date" value="<?php echo isset($post['end_date']) ?  $post['end_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('end_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('end_date'); ?></div>
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
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('academic/routine/index/' . $class_id); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>

                            </div>
                        </div>

                        <?php if (isset($edit)) { ?>
                            <div class="tab-pane fade in active" id="tab_edit_routine">
                                <div class="x_content">
                                    <?php echo form_open(site_url('academic/routine/edit/' . $routine->id), array('name' => 'edit', 'id' => 'edit', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                    <?php $this->load->view('layout/school_list_edit_form'); ?>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_years"><?php echo $this->lang->line('academic_year'); ?><span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="academic_year_id" id="edit_years" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach ($years as $obj) {
                                                    print_r($years); ?>
                                                    <?php
                                                    if ($this->session->userdata('role_id') == TEACHER) {
                                                        if (!in_array($obj->id, $teacher_access_data)) {
                                                            continue;
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php echo $routine->academic_year_id == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->session_year; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('academic_year_id'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="class_id" id="edit_class_id" required="required" onchange="get_section_subject_by_class(this.value, '','');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach ($classes as $obj) { ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php echo $routine->class_id == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="section_id" id="edit_section_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('start_date'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="start_date" id="edit_start_date" value="<?php echo $routine->start_date; ?>" placeholder="<?php echo $this->lang->line('start_date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('start_date'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('end_date'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="end_date" id="edit_end_date" value="<?php echo $routine->end_date ?>" placeholder="<?php echo $this->lang->line('end_date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('end_date'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('start_time'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="start_time" id="edit_start_time" value="<?php echo $routine->start_time; ?>" placeholder="<?php echo $this->lang->line('start_time'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('start_time'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('end_time'); ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="end_time" id="edit_end_time" value="<?php echo $routine->end_time; ?>" placeholder="<?php echo $this->lang->line('end_time'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('end_time'); ?></div>
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" value="<?php echo isset($routine) ? $routine->id : $id; ?>" name="id" />
                                            <a href="<?php echo site_url('academic/routine/index/' . $class_id); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .btn-group .btn {
        padding: 2px 6px;
    }
</style>
<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>timepicker/timepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>timepicker/timepicker.js"></script>
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>


<!-- Super admin js START  -->
<script type="text/javascript">
    var edit = false;
    <?php if (isset($edit)) { ?>
        edit = true;
    <?php } ?>

    $("document").ready(function() {
        <?php if (isset($routine) && !empty($routine)) { ?>
            $("#add_school_id").trigger('change');
        <?php } ?>
    });

    // $('.fn_school_id').on('change', function() {

    //     var school_id = $(this).val();
    //     var class_id = '';
    //     var teacher_id = '';

    //     <?php if (isset($routine) && !empty($routine)) { ?>
    //         class_id = '<?php echo $routine->class_id; ?>';
    //         teacher_id = '<?php // echo $routine->teacher_id; 
                                ?>';
    //     <?php } ?>

    //     if (!school_id) {
    //         toastr.error('<?php echo $this->lang->line("select_school"); ?>');
    //         return false;
    //     }

    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo site_url('ajax/get_class_by_school'); ?>",
    //         data: {
    //             school_id: school_id,
    //             class_id: class_id
    //         },
    //         async: false,
    //         success: function(response) {
    //             if (response) {
    //                 if (edit) {
    //                     $('#edit_class_id').html(response);
    //                 } else {
    //                     $('#add_class_id').html(response);
    //                 }

    //                 get_teacher_by_school(school_id, teacher_id);
    //             }
    //         }
    //     });
    // });


    // function get_teacher_by_school(school_id, teacher_id) {

    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo site_url('ajax/get_teacher_by_school'); ?>",
    //         data: {
    //             school_id: school_id,
    //             teacher_id: teacher_id
    //         },
    //         async: false,
    //         success: function(response) {
    //             if (response) {
    //                 if (edit) {
    //                     $('#edit_teacher_id').html(response);
    //                 } else {
    //                     $('#add_teacher_id').html(response);
    //                 }
    //             }
    //         }
    //     });
    // }
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
        get_section_subject_by_class('<?php echo $routine->class_id; ?>', '<?php echo $routine->section_id; ?>');
        // get_department_by_teacher('<?php // echo $routine->teacher_id 
                                        ?>', '<?php // echo $routine->department_id 
                                                ?>');
    <?php } ?>


    function get_section_subject_by_class(class_id, section_id) {

        if (edit) {
            var school_id = $('#edit_school_id').val();
        } else {
            var school_id = $('#add_school_id').val();
        }
        if (!school_id) {
            toastr.error('<?php echo $this->lang->line("select_school"); ?>');
            return false;
        }

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

        // $.ajax({
        //     type: "POST",
        //     url: "<?php echo site_url('ajax/get_subject_by_class'); ?>",
        //     data: {
        //         school_id: school_id,
        //         class_id: class_id,
        //         subject_id: subject_id
        //     },
        //     async: false,
        //     success: function(response) {
        //         if (response) {
        //             if (edit) {
        //                 $('#edit_subject_id').html(response);
        //             } else {
        //                 $('#add_subject_id').html(response);
        //             }
        //         }
        //     }
        // });

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

    // function get_department_by_teacher(teacher_id, department_id) {


    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo site_url('ajax/get_department_by_teacher'); ?>",
    //         data: {
    //             teacher_id: teacher_id,
    //             department_id: department_id
    //         },
    //         async: false,
    //         success: function(response) {
    //             if (response) {
    //                 if (edit) {
    //                     console.log(response);
    //                     $('#edit_department_id').html(response);
    //                 } else {
    //                     $('#add_department_id').html(response);
    //                 }
    //             }
    //         }
    //     });
    // }
</script>
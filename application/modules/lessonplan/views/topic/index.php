<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-bars"></i><small> <?php echo $this->lang->line('manage_topic'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                <?php $this->load->view('quick-link'); ?>
            </div>

            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">

                    <ul class="nav nav-tabs  nav-tab-find  bordered">
                        <li class="<?php if (isset($list)) {
                                        echo 'active';
                                    } ?>"><a href="#tab_topic_list" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if (has_permission(ADD, 'lessonplan', 'topic')) { ?>
                            <?php if (isset($edit)) { ?>
                                <li class="<?php if (isset($add)) {
                                                echo 'active';
                                            } ?>"><a href="<?php echo site_url('lessonplan/topic/add'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> </a> </li>
                            <?php } else { ?>
                                <li class="<?php if (isset($add)) {
                                                echo 'active';
                                            } ?>"><a href="#tab_add_topic" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> </a> </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if (isset($edit)) { ?>
                            <li class="active"><a href="#tab_edit_topic" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>
                        <?php } ?>


                        <li class="li-class-list">

                            <?php $guardian_class_data = get_guardian_access_data('class'); ?>
                            <?php $teacher_access_data = get_teacher_access_data(); ?>

                            <?php echo form_open(site_url('lessonplan/topic/index'), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left'), ''); ?>

                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN) {  ?>

                                <select class="form-control col-md-7 col-xs-12" style="width:auto;" name="school_id" id="school_id" onchange="get_class_by_school(this.value, '', '');">
                                    <option value="">--<?php echo $this->lang->line('select_school'); ?>--</option>
                                    <?php foreach ($schools as $obj) { ?>
                                        <option value="<?php echo $obj->id; ?>" <?php if (isset($school_id) && $school_id == $obj->id) {
                                                                                    echo 'selected="selected"';
                                                                                } ?>> <?php echo $obj->school_name; ?></option>
                                    <?php } ?>
                                </select>

                                <select class="form-control col-md-7 col-xs-12" id="class_id" name="class_id" onchange="get_subject_by_class(this.value, '', '');" style="width:auto;">
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                </select>

                                <select class="form-control col-md-7 col-xs-12 gsms-nice-select_" name="subject_id" id="subject_id" style="width: auto;">
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                </select>

                            <?php } else {  ?>

                                <select class="form-control col-md-7 col-xs-12 gsms-nice-select_" name="class_id" id="class_id" onchange="get_subject_by_class(this.value, '', '');" style="width: auto;">
                                    <?php if ($this->session->userdata('role_id') != STUDENT) { ?>
                                        <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                    <?php } ?>
                                    <?php foreach ($classes as $obj) { ?>
                                        <?php if ($this->session->userdata('role_id') == STUDENT) { ?>
                                            <?php if ($obj->id != $this->session->userdata('class_id')) {
                                                continue;
                                            } ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                        echo 'selected="selected"';
                                                                                    } ?>><?php echo $obj->name; ?></option>
                                        <?php } elseif ($this->session->userdata('role_id') == GUARDIAN) { ?>
                                            <?php if (!in_array($obj->id, $guardian_class_data)) {
                                                continue;
                                            } ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                        echo 'selected="selected"';
                                                                                    } ?>><?php echo $obj->name; ?></option>
                                        <?php } elseif ($this->session->userdata('role_id') == TEACHER) { ?>
                                            <?php if (!in_array($obj->id, $teacher_access_data)) {
                                                continue;
                                            } ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                        echo 'selected="selected"';
                                                                                    } ?>><?php echo $obj->name; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                        echo 'selected="selected"';
                                                                                    } ?>><?php echo $obj->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <select class="form-control col-md-7 col-xs-12 gsms-nice-select_" name="subject_id" id="subject_id" style="width: auto;">
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                    <?php if ($this->session->userdata('role_id') == STUDENT) { ?>
                                        <?php foreach ($subjects as $obj) { ?>
                                            <option value="<?php $obj->id; ?>" <?php if (isset($subject_id) && $subject_id == $obj->id) {
                                                                                    echo 'selected="selected"';
                                                                                } ?>><?php echo $obj->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>

                            <?php } ?>
                            <input type="submit" name="find" value="<?php echo $this->lang->line('find'); ?>" class="btn btn-success btn-sm" />
                            <?php echo form_close(); ?>
                        </li>

                    </ul>
                    <br />

                    <div class="tab-content">

                        <div class="tab-pane fade in <?php if (isset($list)) {
                                                            echo 'active';
                                                        } ?>" id="tab_topic_list">
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN) { ?>
                                                <th><?php echo $this->lang->line('school'); ?></th>
                                            <?php } ?>
                                            <th><?php echo $this->lang->line('academic_year'); ?></th>
                                            <th> <?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('subject'); ?> </th>
                                            <th>Today lession </th>
                                            <th>Today key concept </th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php $count = 1;
                                        if (isset($topics) && !empty($topics)) { ?>
                                            <?php foreach ($topics as $obj) { ?>
                                                <?php
                                                if ($this->session->userdata('role_id') == GUARDIAN) {
                                                    if (!in_array($obj->class_id, $guardian_class_data)) {
                                                        continue;
                                                    }
                                                } elseif ($this->session->userdata('role_id') == STUDENT) {
                                                    if ($obj->class_id != $this->session->userdata('class_id')) {
                                                        continue;
                                                    }
                                                } elseif ($this->session->userdata('role_id') == TEACHER) {
                                                    if ($obj->teacher_id != $this->session->userdata('profile_id')) {
                                                        continue;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo $count++; ?></td>
                                                    <?php if ($this->session->userdata('role_id') == SUPER_ADMIN) { ?>
                                                        <td><?php echo $obj->school_name; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $obj->session_year; ?></td>
                                                    <td><?php echo $obj->class_name; ?></td>
                                                    <td><?php echo $obj->subject; ?></td>
                                                    <td><?php echo $obj->title; ?></td>
                                                    <td>
                                                        <?php echo $obj->today_key_concept; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (has_permission(EDIT, 'lessonplan', 'topic')) { ?>
                                                            <a href="<?php echo site_url('lessonplan/topic/edit/' . $obj->id); ?>" title="<?php echo $this->lang->line('edit'); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(VIEW, 'lessonplan', 'topic')) { ?>
                                                            <a onclick="get_topic_modal(<?php echo $obj->id; ?>);" data-toggle="modal" data-target=".bs-topic-modal-lg" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(DELETE, 'lessonplan', 'topic')) { ?>
                                                            <a href="<?php echo site_url('lessonplan/topic/delete/' . $obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade in <?php if (isset($add)) {
                                                            echo 'active';
                                                        } ?>" id="tab_add_topic">
                            <div class="x_content">
                                <?php echo form_open_multipart(site_url('lessonplan/topic/add'), array('name' => 'add', 'id' => 'add_topic', 'class' => 'form-horizontal form-label-left'), ''); ?>
                                <?php $this->load->view('layout/school_list_form'); ?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12 gsms-nice-select" name="class_id" id="add_class_id" required="required" onchange="get_subject_by_class(this.value, '', 'add_');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php if (isset($classes) && !empty($classes)) { ?>
                                                <?php foreach ($classes as $obj) { ?>
                                                    <?php
                                                    if ($this->session->userdata('role_id') == TEACHER) {
                                                        if (!in_array($obj->id, $teacher_access_data)) {
                                                            continue;
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php echo isset($post['class_id']) && $post['class_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-12 col-xs-12 gsms-nice-select_" name="subject_id" id="add_subject_id" required="required" onchange="get_lesson_by_subject_today(this.value, '', 'add_'); get_lesson_by_subject_next(this.value, '', 'add_');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="today_topic">Today lession<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12 gsms-nice-select" name="today_topic" id="add_today_topic" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('today_topic'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Today key concept</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-12 col-xs-12" type="text" name="today_key_concept" placeholder="Today key concept" autocomplete="off" />
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="next_topic">Next lession</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12 gsms-nice-select" name="next_topic" id="add_next_topic">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('next_topic'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Next key concept</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-12 col-xs-12" type="text" name="next_key_concept" placeholder="Next key concept" autocomplete="off" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="attachment"><?php echo $this->lang->line('attachment'); ?></span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <!-- <div class="btn btn-default btn-file">
                                        <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('attachment'); ?> -->
                                        <input class="form-control col-md-12 col-xs-12" name="attachment" id="add_attachment" type="file">
                                        <!-- </div> -->
                                    </div>
                                    <div class="help-block"><?php echo form_error('attachment'); ?></div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea class="form-control" name="note" id="add_note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['note']) ?  $post['note'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="help-block">
                                            <a href="javascript:void(0);" class="btn btn-primary btn-xs" id="toggle_homework">+ Add Homework</a>
                                        </div>
                                    </div>
                                </div>

                                <div id="homework">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="add_homework_title"><?php echo $this->lang->line('title'); ?></span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" name="homework_title" id="add_homework_title" class="form-control" placeholder="<?php echo $this->lang->line('title'); ?>" value="<?php echo isset($post['add_homework_title']) ?  $post['add_homework_title'] : ''; ?>">
                                            <div class="help-block"><?php echo form_error('add_homework_title'); ?></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="add_homework_intruction">Instruction</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" name="homework_intruction" id="add_homework_intruction" class="form-control" placeholder="Instruction" value="<?php echo isset($post['add_homework_intruction']) ?  $post['add_homework_intruction'] : ''; ?>">
                                            <div class="help-block"><?php echo form_error('add_homework_intruction'); ?></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="add_homework_due_date"><?php echo $this->lang->line('due_date'); ?></span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" name="homework_due_date" id="add_homework_due_date" class="form-control" placeholder="<?php echo $this->lang->line('due_date'); ?>" value="<?php echo isset($post['add_homework_due_date']) ?  $post['add_homework_due_date'] : ''; ?>">
                                            <div class="help-block"><?php echo form_error('add_homework_due_date'); ?></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="homework_notes"><?php echo $this->lang->line('note'); ?></span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="homework_notes" id="add_homework_notes" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['homework_notes']) ?  $post['homework_notes'] : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('homework_notes'); ?></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="homework_attachment"><?php echo $this->lang->line('attachment'); ?></span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <!-- <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('attachment'); ?> -->
                                            <input class="form-control col-md-12 col-xs-12" name="homework_attachment" id="add_homework_attachment" type="file">
                                            <!-- </div> -->
                                        </div>
                                        <div class="help-block"><?php echo form_error('homework_attachment'); ?></div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('lessonplan/topic/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>

                                <?php echo form_close(); ?>
                            </div>
                        </div>

                        <?php if (isset($edit)) { ?>
                            <div class="tab-pane fade in <?php if (isset($edit)) {
                                                                echo 'active';
                                                            } ?>" id="tab_edit_topic">
                                <div class="x_content">
                                    <?php echo form_open_multipart(site_url('lessonplan/topic/edit/' . $topic->id), array('name' => 'edit', 'id' => 'edit', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                    <?php $this->load->view('layout/school_list_edit_form'); ?>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12 gsms-nice-select" name="class_id" id="edit_class_id" required="required" onchange="get_subject_by_class(this.value, '', 'edit_');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach ($classes as $obj) { ?>
                                                    <?php
                                                    if ($this->session->userdata('role_id') == TEACHER) {
                                                        if (!in_array($obj->id, $teacher_access_data)) {
                                                            continue;
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if ($obj->id == $topic->class_id) {
                                                                                                echo 'selected="selected"';
                                                                                            } ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-12 col-xs-12 gsms-nice-select_" name="subject_id" id="edit_subject_id" required="required" onchange="get_lesson_by_subject_today(this.value, '', 'edit_'); get_lesson_by_subject_next(this.value, '', 'edit_');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_today_topic">Today lession <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12 gsms-nice-select" name="today_topic" id="edit_today_topic" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('today_topic'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Today key concept</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-12 col-xs-12" type="text" value="<?php echo $topic->today_key_concept ?>" name="today_key_concept" placeholder="Today key concept" autocomplete="off" />
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_next_topic">Next lession</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12 gsms-nice-select" name="next_topic" id="edit_next_topic">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('next_topic'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Next key concept</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-12 col-xs-12" type="text" name="next_key_concept" placeholder="Next key concept" value="<?php echo $topic->next_key_concept ?>" autocomplete="off" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_attachment"><?php echo $this->lang->line('attachment'); ?></span></label>
                                        <input type="hidden" name="prev_attachment" id="prev_attachment" value="<?php echo $topic->attachment; ?>" />
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <!-- <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('attachment'); ?> -->
                                            <input class="form-control col-md-12 col-xs-12" name="attachment" id="edit_attachment" type="file">
                                            <!-- </div> -->
                                        </div>
                                        <div class="help-block"><?php echo form_error('attachment'); ?></div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_note"><?php echo $this->lang->line('note'); ?></span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="note" id="edit_note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo $topic->note; ?></textarea>
                                            <div class="help-block"><?php echo form_error('note'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="help-block">
                                                <a href="javascript:void(0);" class="btn btn-primary btn-xs" id="edit_toggle_homework">- Remove Homework</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="edithomework">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_homework_title"><?php echo $this->lang->line('title'); ?></span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" name="homework_title" id="edit_homework_title" class="form-control" placeholder="<?php echo $this->lang->line('title'); ?>" value="<?php echo $topic->homework_title; ?>">
                                                <div class="help-block"><?php echo form_error('ahomework_title'); ?></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_homework_intruction">Instruction</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" name="homework_intruction" id="edit_homework_intruction" class="form-control" placeholder="Instruction" value="<?php echo $topic->homework_intruction; ?>">
                                                <div class="help-block"><?php echo form_error('homework_intruction'); ?></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_homework_due_date"><?php echo $this->lang->line('due_date'); ?></span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" name="homework_due_date" id="edit_homework_due_date" class="form-control" placeholder="<?php echo $this->lang->line('due_date'); ?>" value="<?php echo $topic->homework_due_date; ?>">
                                                <div class="help-block"><?php echo form_error('homework_due_date'); ?></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_homework_notes"><?php echo $this->lang->line('note'); ?></span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea class="form-control" name="homework_notes" id="edit_homework_notes" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo $topic->homework_notes; ?></textarea>
                                                <div class="help-block"><?php echo form_error('homework_notes'); ?></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="edit_homework_attachment"><?php echo $this->lang->line('attachment'); ?></span></label>
                                            <input type="hidden" name="prev_homework_attachment" id="prev_homework_attachment" value="<?php echo $topic->homework_attachment; ?>" />
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <!-- <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('attachment'); ?> -->
                                                <input class="form-control col-md-12 col-xs-12" name="homework_attachment" id="edit_homework_attachment" type="file">
                                                <!-- </div> -->
                                            </div>
                                            <div class="help-block"><?php echo form_error('homework_attachment'); ?></div>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" value="<?php echo isset($topic) ? $topic->id : $id; ?>" id="id" name="id" />
                                            <a href="<?php echo site_url('lessonplan/topic/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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


<div class="modal fade bs-topic-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
            </div>
            <div class="modal-body fn_topic_data">

            </div>
        </div>
    </div>
</div>

<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>

<script type="text/javascript">
    $('#add_homework_due_date').datepicker();
    $('#edit_homework_due_date').datepicker();

    function get_topic_modal(topic_id) {

        $('.fn_topic_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('lessonplan/topic/get_single_topic'); ?>",
            data: {
                topic_id: topic_id
            },
            success: function(response) {
                if (response) {
                    $('.fn_topic_data').html(response);
                }
            }
        });
    }
</script>



<script type="text/javascript">
    $('#homework').hide();
    $('#edithomework').show();

    $(document).on('click', '#toggle_homework', function() {
        $(this).text(($(this).text() == '- Remove Homework') ? '+ Add Homework' : '- Remove Homework');
        $('#homework').toggle();
    });

    $(document).on('click', '#edit_toggle_homework', function() {
        $(this).text(($(this).text() == '- Remove Homework') ? '+ Add Homework' : '- Remove Homework');
        $('#edithomework').toggle();
    });

    function remove(obj, topic_detail_id) {

        if (topic_detail_id) {
            if (confirm('<?php echo $this->lang->line('confirm_alert'); ?>')) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('lessonplan/topic/remove'); ?>",
                    data: {
                        topic_detail_id: topic_detail_id
                    },
                    async: false,
                    success: function(response) {
                        if (response) {
                            $(obj).parent().parent('tr').remove();
                        }
                    }
                });
            }
        } else {

            $(obj).parent().parent('tr').remove();
        }
    }
</script>


<!-- Super admin js START  -->
<script type="text/javascript">
    $("document").ready(function() {
        <?php if (isset($edit) && !empty($edit)) { ?>
            $("#edit_school_id").trigger('change');
        <?php } ?>
    });

    $('.fn_school_id').on('change', function() {

        var school_id = $(this).val();
        var class_id = '';

        <?php if (isset($edit) && !empty($edit)) { ?>
            class_id = '<?php echo $topic->class_id; ?>';
        <?php } ?>

        if (!school_id) {
            toastr.error('<?php echo $this->lang->line("select_school"); ?>');
            return false;
        }

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
                    if (class_id) {
                        $('#edit_class_id').html(response);
                    } else {
                        $('#add_class_id').html(response);
                    }
                }
            }
        });
    });
</script>
<!-- Super admin js end -->


<script type="text/javascript">
    <?php if (isset($post) && $post['school_id'] != '') { ?>
        get_class_by_school('<?php echo $post['school_id']; ?>', '<?php echo $post['class_id']; ?>', 'add_');
    <?php } ?>

    <?php if (isset($topic) && !empty($topic)) { ?>
        get_class_by_school('<?php echo $topic->school_id; ?>', '<?php echo $topic->class_id; ?>', 'edit_');
    <?php } ?>

    <?php if (isset($school_id) && $school_id != '' && isset($class_id)) { ?>
        get_class_by_school('<?php echo $school_id; ?>', '<?php echo $class_id; ?>', '');
    <?php } ?>

    function get_class_by_school(school_id, class_id, form) {


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
                    $('#' + form + 'class_id').html(response);
                }
            }
        });
    }


    <?php if (isset($post) && $post['class_id'] != '') { ?>
        get_subject_by_class('<?php echo $post['class_id']; ?>', '<?php echo $post['subject_id']; ?>', 'add_');
    <?php } ?>
    <?php if (isset($topic) && !empty($topic)) {  ?>
        get_subject_by_class('<?php echo $topic->class_id; ?>', '<?php echo $topic->subject_id; ?>', 'edit_');
    <?php } ?>
    <?php if (isset($class_id) && $class_id != '' && isset($subject_id)) { ?>
        get_subject_by_class('<?php echo $class_id; ?>', '<?php echo $subject_id; ?>', '');
    <?php } ?>

    function get_subject_by_class(class_id, subject_id, form) {

        var school_id = $('#' + form + 'school_id').val();
        if (!school_id) {
            school_id = '<?php echo $school_id; ?>';
        }

        if (!school_id) {
            toastr.error('<?php echo $this->lang->line("select_school"); ?>');
            return false;
        }

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
                    $('#' + form + 'subject_id').html(response);
                }
            }
        });
    }



    <?php if (isset($topic)) { ?>
        get_lesson_by_subject_today('<?php echo $topic->subject_id; ?>', '<?php echo $topic->today_topic; ?>', 'edit_');
        get_lesson_by_subject_next('<?php echo $topic->subject_id; ?>', '<?php echo $topic->next_topic; ?>', 'edit_');
    <?php } ?>

    function get_lesson_by_subject_today(subject_id, today_topic, form) {

        var school_id = $('#' + form + 'school_id').val();

        if (!school_id) {
            toastr.error('<?php echo $this->lang->line("select_school"); ?>');
            return false;
        }


        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_lesson_by_subject'); ?>",
            data: {
                school_id: school_id,
                subject_id: subject_id,
                today_topic: today_topic
            },
            async: false,
            success: function(response) {
                if (response) {

                    $('#' + form + 'today_topic').html(response);

                }
            }
        });
    }

    function get_lesson_by_subject_next(subject_id, today_topic, form) {

        var school_id = $('#' + form + 'school_id').val();

        if (!school_id) {
            toastr.error('<?php echo $this->lang->line("select_school"); ?>');
            return false;
        }


        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ajax/get_lesson_by_subject'); ?>",
            data: {
                school_id: school_id,
                subject_id: subject_id,
                today_topic: today_topic
            },
            async: false,
            success: function(response) {
                if (response) {
                    $('#' + form + 'next_topic').html(response);

                }
            }
        });
    }



    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            dom: 'Bfrtip',
            iDisplayLength: 15,
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'pageLength'
            ],
            search: true,
            responsive: true
        });
    });

    $("#add_topic").validate();
    $("#edit").validate();
</script>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-thumbs-o-up"></i><small> Manage Packages </small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content quick-link">
                <?php // $this->load->view('quick-link'); 
                ?>
            </div>

            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">

                    <ul class="nav nav-tabs bordered">
                        <li class="<?php if (isset($list)) {
                                        echo 'active';
                                    } ?>"><a href="#tab_plan_list" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if (has_permission(ADD, 'subscription', 'plan')) { ?>
                            <li class="<?php if (isset($add)) {
                                            echo 'active';
                                        } ?>"><a href="#tab_add_plan" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>
                        <?php } ?>
                        <?php if (isset($edit)) { ?>
                            <li class="active"><a href="#tab_edit_plan" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>
                        <?php } ?>

                    </ul>
                    <br />

                    <div class="tab-content">
                        <div class="tab-pane fade in <?php if (isset($list)) {
                                                            echo 'active';
                                                        } ?>" id="tab_plan_list">
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <th><?php echo $this->lang->line('plan_name'); ?></th>
                                            <th><?php echo $this->lang->line('price'); ?></th>
                                            <th><?php echo $this->lang->line('student_limit'); ?></th>
                                            <th><?php echo $this->lang->line('guardian_limit'); ?></th>
                                            <th><?php echo $this->lang->line('teacher_limit'); ?></th>
                                            <th><?php echo $this->lang->line('employee_limit'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1;
                                        if (isset($plans) && !empty($plans)) { ?>
                                            <?php foreach ($plans as $obj) { ?>
                                                <tr>
                                                    <td><?php echo $count++; ?></td>
                                                    <td><?php echo $obj->plan_name; ?></td>
                                                    <td><?php echo $obj->plan_price; ?></td>
                                                    <td><?php echo $obj->student_limit; ?></td>
                                                    <td><?php echo $obj->guardian_limit ?></td>
                                                    <td><?php echo $obj->teacher_limit ?></td>
                                                    <td><?php echo $obj->employee_limit ?></td>
                                                    <td><?php echo $obj->status ? $this->lang->line('active') : $this->lang->line('in_active') ?></td>
                                                    <td>
                                                        <?php if (has_permission(VIEW, 'subscription', 'plan')) { ?>
                                                            <a onclick="get_plan_modal(<?php echo $obj->id; ?>);" data-toggle="modal" data-target=".bs-plan-modal-lg" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                        <?php  } ?>
                                                        <?php if (has_permission(EDIT, 'subscription', 'plan')) { ?>
                                                            <a href="<?php echo site_url('subscription/plan/edit/' . $obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(DELETE, 'subscription', 'plan')) { ?>
                                                            <a href="<?php echo site_url('subscription/plan/delete/' . $obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
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
                                                        } ?>" id="tab_add_plan">
                            <div class="x_content">

                                <?php echo form_open(site_url('subscription/plan/add'), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left'), ''); ?>


                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plan_name"><?php echo $this->lang->line('plan_name'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="plan_name" id="add_plan_name" class="form-control col-md-7 col-xs-12" required="required" placeholder="<?php echo $this->lang->line('plan_name'); ?>">
                                        <!-- <select  class="form-control col-md-7 col-xs-12" name="plan_name" id="add_plan_name" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $sub_plans = get_subscription_plans(); ?>
                                                <?php foreach ($sub_plans as $key => $value) { ?>
                                                    <option value="<?php echo $key; ?>" <?php echo isset($post['plan_name']) && $post['plan_name'] == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select> -->
                                        <div class="help-block"><?php echo form_error('plan_name'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plan_price"><?php echo $this->lang->line('price'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="plan_price" id="add_plan_price" value="<?php echo isset($post['plan_price']) ?  $post['plan_price'] : ''; ?>" placeholder="<?php echo $this->lang->line('price'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <div class="help-block"><?php echo form_error('plan_price'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_limit"><?php echo $this->lang->line('student_limit'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="student_limit" id="add_student_limit" value="<?php echo isset($post['student_limit']) ?  $post['student_limit'] : ''; ?>" placeholder="<?php echo $this->lang->line('student_limit'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <div class="help-block"><?php echo form_error('student_limit'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="guardian_limit"><?php echo $this->lang->line('guardian_limit'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="guardian_limit" id="add_guardian_limit" value="<?php echo isset($post['guardian_limit']) ?  $post['guardian_limit'] : ''; ?>" placeholder="<?php echo $this->lang->line('guardian_limit'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <div class="help-block"><?php echo form_error('guardian_limit'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_limit"><?php echo $this->lang->line('teacher_limit'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="teacher_limit" id="add_teacher_limit" value="<?php echo isset($post['teacher_limit']) ?  $post['teacher_limit'] : ''; ?>" placeholder="<?php echo $this->lang->line('teacher_limit'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <div class="help-block"><?php echo form_error('teacher_limit'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_limit"><?php echo $this->lang->line('employee_limit'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="employee_limit" id="add_employee_limit" value="<?php echo isset($post['employee_limit']) ?  $post['employee_limit'] : ''; ?>" placeholder="<?php echo $this->lang->line('employee_limit'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <div class="help-block"><?php echo form_error('employee_limit'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_manage_student">Is Enable <?php echo $this->lang->line('manage_student'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_manage_student" id="add_is_enable_manage_student" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_manage_student']) && $post['is_enable_manage_student'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_manage_student']) && $post['is_enable_manage_student'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_manage_student'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_setting">Is Enable <?php echo $this->lang->line('setting'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_setting" id="add_is_enable_setting" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_setting']) && $post['is_enable_setting'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_setting']) && $post['is_enable_setting'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_setting'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_administrator">Is Enable <?php echo $this->lang->line('administrator'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_administrator" id="add_is_enable_administrator" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_administrator']) && $post['is_enable_administrator'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_administrator']) && $post['is_enable_administrator'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_administrator'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_template">Is Enable <?php echo $this->lang->line('template'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_template" id="add_is_enable_template" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_template']) && $post['is_enable_template'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_template']) && $post['is_enable_template'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_template'); ?></div>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_human_resource">Is Enable <?php echo $this->lang->line('human_resource'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_human_resource" id="add_is_enable_human_resource" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_human_resource']) && $post['is_enable_human_resource'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_human_resource']) && $post['is_enable_human_resource'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_human_resource'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_teacher">Is Enable <?php echo $this->lang->line('teacher'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_teacher" id="add_is_enable_teacher" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_teacher']) && $post['is_enable_teacher'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_teacher']) && $post['is_enable_teacher'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_teacher'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_manage_leave">Is Enable <?php echo $this->lang->line('manage_leave'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_manage_leave" id="add_is_enable_manage_leave" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_manage_leave']) && $post['is_enable_manage_leave'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_manage_leave']) && $post['is_enable_manage_leave'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_manage_leave'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_academic">Is Enable <?php echo $this->lang->line('academic'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_academic" id="add_is_enable_academic" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_academic']) && $post['is_enable_academic'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_academic']) && $post['is_enable_academic'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_academic'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_class_routine">Is Enable <?php echo $this->lang->line('class_routine'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_class_routine" id="add_is_enable_class_routine" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_class_routine']) && $post['is_enable_class_routine'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_class_routine']) && $post['is_enable_class_routine'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_class_routine'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_manage_exam">Is Enable <?php echo $this->lang->line('manage_exam'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_manage_exam" id="add_is_enable_manage_exam" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_manage_exam']) && $post['is_enable_manage_exam'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_manage_exam']) && $post['is_enable_manage_exam'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_manage_exam'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_certificate">Is Enable <?php echo $this->lang->line('certificate'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_certificate" id="add_is_enable_certificate" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_certificate']) && $post['is_enable_certificate'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_certificate']) && $post['is_enable_certificate'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_certificate'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_message">Is Enable <?php echo $this->lang->line('message'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_message" id="add_is_enable_message" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_message']) && $post['is_enable_message'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_message']) && $post['is_enable_message'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_message'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_complain">Is Enable <?php echo $this->lang->line('complain'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_complain" id="add_is_enable_complain" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_complain']) && $post['is_enable_complain'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_complain']) && $post['is_enable_complain'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_complain'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_announcement">Is Enable <?php echo $this->lang->line('announcement'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_announcement" id="add_is_enable_announcement" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_announcement']) && $post['is_enable_announcement'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_announcement']) && $post['is_enable_announcement'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_announcement'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_event">Is Enable <?php echo $this->lang->line('event'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_event" id="add_is_enable_event" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_event']) && $post['is_enable_event'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_event']) && $post['is_enable_event'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_event'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_payroll">Is Enable <?php echo $this->lang->line('payroll'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_payroll" id="add_is_enable_payroll" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_payroll']) && $post['is_enable_payroll'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_payroll']) && $post['is_enable_payroll'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_payroll'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_accounting"><?php echo $this->lang->line('is_enable_accounting'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_accounting" id="add_is_enable_accounting" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_accounting']) && $post['is_enable_accounting'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_accounting']) && $post['is_enable_accounting'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_accounting'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_report"><?php echo $this->lang->line('is_enable_report'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_report" id="add_is_enable_report" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_report']) && $post['is_enable_report'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_report']) && $post['is_enable_report'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_report'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_media_gallery">Is Enable <?php echo $this->lang->line('media_gallery'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_media_gallery" id="add_is_enable_media_gallery" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_media_gallery']) && $post['is_enable_media_gallery'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_media_gallery']) && $post['is_enable_media_gallery'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_media_gallery'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_inventory"><?php echo $this->lang->line('is_enable_inventory'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_inventory" id="add_is_enable_inventory" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_inventory']) && $post['is_enable_inventory'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_inventory']) && $post['is_enable_inventory'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_inventory'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_attendance"><?php echo $this->lang->line('is_enable_attendance'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_attendance" id="add_is_enable_attendance" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_attendance']) && $post['is_enable_attendance'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_attendance']) && $post['is_enable_attendance'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_attendance'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_exam_mark"><?php echo $this->lang->line('is_enable_exam_mark'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_exam_mark" id="add_is_enable_exam_mark" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_exam_mark']) && $post['is_enable_exam_mark'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_exam_mark']) && $post['is_enable_exam_mark'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_exam_mark'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_payroll"><?php echo $this->lang->line('is_enable_payroll'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_payroll" id="add_is_enable_payroll" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_payroll']) && $post['is_enable_payroll'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_payroll']) && $post['is_enable_payroll'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_payroll'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_asset_management"><?php echo $this->lang->line('is_enable_asset_management'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_asset_management" id="add_is_enable_asset_management" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_asset_management']) && $post['is_enable_asset_management'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_asset_management']) && $post['is_enable_asset_management'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_asset_management'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_promotion"><?php echo $this->lang->line('is_enable_promotion'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_promotion" id="add_is_enable_promotion" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_promotion']) && $post['is_enable_promotion'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_promotion']) && $post['is_enable_promotion'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_promotion'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_front_office">Is Enable <?php echo $this->lang->line('front_office'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_front_office" id="add_is_enable_front_office" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_front_office']) && $post['is_enable_front_office'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_front_office']) && $post['is_enable_front_office'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_front_office'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_lesson_plan"><?php echo $this->lang->line('is_enable_lesson_plan'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_lesson_plan" id="add_is_enable_lesson_plan" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_lesson_plan']) && $post['is_enable_lesson_plan'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_lesson_plan']) && $post['is_enable_lesson_plan'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_lesson_plan'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_guardian">Is Enable <?php echo $this->lang->line('guardian'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_guardian" id="add_is_enable_guardian" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_guardian']) && $post['is_enable_guardian'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_guardian']) && $post['is_enable_guardian'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_guardian'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_generate_card">Is Enable <?php echo $this->lang->line('generate_card'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_generate_card" id="add_is_enable_generate_card" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_generate_card']) && $post['is_enable_generate_card'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_generate_card']) && $post['is_enable_generate_card'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_generate_card'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_online_exam">Is Enable <?php echo $this->lang->line('online_exam'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_online_exam" id="add_is_enable_online_exam" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_online_exam']) && $post['is_enable_online_exam'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_online_exam']) && $post['is_enable_online_exam'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_online_exam'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_library">Is Enable <?php echo $this->lang->line('library'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_library" id="add_is_enable_library" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_library']) && $post['is_enable_library'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_library']) && $post['is_enable_library'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_library'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_transport">Is Enable <?php echo $this->lang->line('transport'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_transport" id="add_is_enable_transport" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_transport']) && $post['is_enable_transport'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_transport']) && $post['is_enable_transport'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_transport'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_hostel">Is Enable <?php echo $this->lang->line('hostel'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_hostel" id="add_is_enable_hostel" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_hostel']) && $post['is_enable_hostel'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_hostel']) && $post['is_enable_hostel'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_hostel'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_scholarship">Is Enable <?php echo $this->lang->line('scholarship'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_scholarship" id="add_is_enable_scholarship" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_scholarship']) && $post['is_enable_scholarship'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_scholarship']) && $post['is_enable_scholarship'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_scholarship'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_frontend">Is Enable <?php echo $this->lang->line('manage_frontend'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_frontend" id="add_is_enable_frontend" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_frontend']) && $post['is_enable_frontend'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_frontend']) && $post['is_enable_frontend'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_frontend'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_miscellaneous">Is Enable <?php echo $this->lang->line('miscellaneous'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="is_enable_miscellaneous" id="add_is_enable_miscellaneous" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="1" <?php if (isset($post['is_enable_miscellaneous']) && $post['is_enable_miscellaneous'] == '1') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php if (isset($post['is_enable_miscellaneous']) && $post['is_enable_miscellaneous'] == '0') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_enable_miscellaneous'); ?></div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('subscription/plan/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>

                        <?php if (isset($edit)) { ?>
                            <div class="tab-pane fade in active" id="tab_edit_plan">
                                <div class="x_content">

                                    <?php echo form_open(site_url('subscription/plan/edit/' . $plan->id), array('name' => 'edit', 'id' => 'edit', 'class' => 'form-horizontal form-label-left'), ''); ?>


                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plan_name"><?php echo $this->lang->line('plan_name'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" name="plan_name" id="add_plan_name" class="form-control col-md-7 col-xs-12" required="required" placeholder="<?php echo $this->lang->line('plan_name'); ?>" value="<?= (isset($plan->plan_name)) ? $plan->plan_name : ''; ?>">
                                            <!-- <select  class="form-control col-md-7 col-xs-12" name="plan_name" id="add_plan_name" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $sub_plans = get_subscription_plans(); ?>
                                                <?php foreach ($sub_plans as $key => $value) { ?>
                                                    <option value="<?php echo $key; ?>" <?php echo isset($post['plan_name']) && $post['plan_name'] == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select> -->
                                            <div class="help-block"><?php echo form_error('plan_name'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plan_price"><?php echo $this->lang->line('price'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="plan_price" id="add_plan_price" value="<?= (isset($plan->plan_price)) ? $plan->plan_price : ''; ?>" placeholder="<?php echo $this->lang->line('price'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            <div class="help-block"><?php echo form_error('plan_price'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_limit"><?php echo $this->lang->line('student_limit'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="student_limit" id="add_student_limit" value="<?= (isset($plan->student_limit)) ? $plan->student_limit : ''; ?>" placeholder="<?php echo $this->lang->line('student_limit'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            <div class="help-block"><?php echo form_error('student_limit'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="guardian_limit"><?php echo $this->lang->line('guardian_limit'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="guardian_limit" id="add_guardian_limit" value="<?= (isset($plan->guardian_limit)) ? $plan->guardian_limit : ''; ?>" placeholder="<?php echo $this->lang->line('guardian_limit'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            <div class="help-block"><?php echo form_error('guardian_limit'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="teacher_limit"><?php echo $this->lang->line('teacher_limit'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="teacher_limit" id="add_teacher_limit" value="<?= (isset($plan->teacher_limit)) ? $plan->teacher_limit : ''; ?>" placeholder="<?php echo $this->lang->line('teacher_limit'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            <div class="help-block"><?php echo form_error('teacher_limit'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_limit"><?php echo $this->lang->line('employee_limit'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="employee_limit" id="add_employee_limit" value="<?= (isset($plan->employee_limit)) ? $plan->employee_limit : ''; ?>" placeholder="<?php echo $this->lang->line('employee_limit'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            <div class="help-block"><?php echo form_error('employee_limit'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_manage_student">Is Enable <?php echo $this->lang->line('manage_student'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_manage_student" id="add_is_enable_manage_student" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_manage_student) && $plan->is_enable_manage_student == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_manage_student) && $plan->is_enable_manage_student == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_manage_student'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_setting">Is Enable <?php echo $this->lang->line('setting'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_setting" id="add_is_enable_setting" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_setting) && $plan->is_enable_setting == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_setting) && $plan->is_enable_setting == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_setting'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_administrator">Is Enable <?php echo $this->lang->line('administrator'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_administrator" id="add_is_enable_administrator" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_administrator) && $plan->is_enable_administrator == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_administrator) && $plan->is_enable_administrator == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_administrator'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_template">Is Enable <?php echo $this->lang->line('template'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_template" id="add_is_enable_template" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_template) && $plan->is_enable_template == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_template) && $plan->is_enable_template == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_template'); ?></div>
                                        </div>
                                    </div>


                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_human_resource">Is Enable <?php echo $this->lang->line('human_resource'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_human_resource" id="add_is_enable_human_resource" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_human_resource) && $plan->is_enable_human_resource == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_human_resource) && $plan->is_enable_human_resource == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_human_resource'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_teacher">Is Enable <?php echo $this->lang->line('teacher'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_teacher" id="add_is_enable_teacher" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_teacher) && $plan->is_enable_teacher == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_teacher) && $plan->is_enable_teacher == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_teacher'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_manage_leave">Is Enable <?php echo $this->lang->line('manage_leave'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_manage_leave" id="add_is_enable_manage_leave" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_manage_leave) && $plan->is_enable_manage_leave == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_manage_leave) && $plan->is_enable_manage_leave == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_manage_leave'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_academic">Is Enable <?php echo $this->lang->line('academic'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_academic" id="add_is_enable_academic" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_academic) && $plan->is_enable_academic == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_academic) && $plan->is_enable_academic == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_academic'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_class_routine">Is Enable <?php echo $this->lang->line('class_routine'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_class_routine" id="add_is_enable_class_routine" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_class_routine) && $plan->is_enable_class_routine == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_class_routine) && $plan->is_enable_class_routine == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_class_routine'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_manage_exam">Is Enable <?php echo $this->lang->line('manage_exam'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_manage_exam" id="add_is_enable_manage_exam" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_manage_exam) && $plan->is_enable_manage_exam == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_manage_exam) && $plan->is_enable_manage_exam == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_manage_exam'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_certificate">Is Enable <?php echo $this->lang->line('certificate'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_certificate" id="add_is_enable_certificate" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_certificate) && $plan->is_enable_certificate == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_certificate) && $plan->is_enable_certificate == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_certificate'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_message">Is Enable <?php echo $this->lang->line('message'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_message" id="add_is_enable_message" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_message) && $plan->is_enable_message == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_message) && $plan->is_enable_message == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_message'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_complain">Is Enable <?php echo $this->lang->line('complain'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_complain" id="add_is_enable_complain" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_complain) && $plan->is_enable_complain == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_complain) && $plan->is_enable_complain == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_complain'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_announcement">Is Enable <?php echo $this->lang->line('announcement'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_announcement" id="add_is_enable_announcement" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_announcement) && $plan->is_enable_announcement == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_announcement) && $plan->is_enable_announcement == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_announcement'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_event">Is Enable <?php echo $this->lang->line('event'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_event" id="add_is_enable_event" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_event) && $plan->is_enable_event == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_event) && $plan->is_enable_event == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_event'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_payroll">Is Enable <?php echo $this->lang->line('payroll'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_payroll" id="add_is_enable_payroll" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_payroll) && $plan->is_enable_payroll == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_payroll) && $plan->is_enable_payroll == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_payroll'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_accounting"><?php echo $this->lang->line('is_enable_accounting'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_accounting" id="add_is_enable_accounting" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_accounting) && $plan->is_enable_accounting == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_accounting) && $plan->is_enable_accounting == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_accounting'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_report"><?php echo $this->lang->line('is_enable_report'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_report" id="add_is_enable_report" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_report) && $plan->is_enable_report == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_report) && $plan->is_enable_report == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_report'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_media_gallery">Is Enable <?php echo $this->lang->line('media_gallery'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_media_gallery" id="add_is_enable_media_gallery" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_media_gallery) && $plan->is_enable_media_gallery == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_media_gallery) && $plan->is_enable_media_gallery == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_media_gallery'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_inventory"><?php echo $this->lang->line('is_enable_inventory'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_inventory" id="add_is_enable_inventory" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_inventory) && $plan->is_enable_inventory == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_inventory) && $plan->is_enable_inventory == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_inventory'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_attendance"><?php echo $this->lang->line('is_enable_attendance'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_attendance" id="add_is_enable_attendance" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_attendance) && $plan->is_enable_attendance == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_attendance) && $plan->is_enable_attendance == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_attendance'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_exam_mark"><?php echo $this->lang->line('is_enable_exam_mark'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_exam_mark" id="add_is_enable_exam_mark" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_exam_mark) && $plan->is_enable_exam_mark == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_exam_mark) && $plan->is_enable_exam_mark == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>

                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_exam_mark'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_payroll"><?php echo $this->lang->line('is_enable_payroll'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_payroll" id="add_is_enable_payroll" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_payroll) && $plan->is_enable_payroll == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_payroll) && $plan->is_enable_payroll == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_payroll'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_asset_management"><?php echo $this->lang->line('is_enable_asset_management'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_asset_management" id="add_is_enable_asset_management" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_asset_management) && $plan->is_enable_asset_management == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_asset_management) && $plan->is_enable_asset_management == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_asset_management'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_promotion"><?php echo $this->lang->line('is_enable_promotion'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_promotion" id="add_is_enable_promotion" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_promotion) && $plan->is_enable_promotion == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_promotion) && $plan->is_enable_promotion == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_promotion'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_front_office"> Is Enable <?php echo $this->lang->line('front_office'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_front_office" id="front_office" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_front_office) && $plan->is_enable_front_office == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_front_office) && $plan->is_enable_front_office == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_front_office'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_lesson_plan"><?php echo $this->lang->line('is_enable_lesson_plan'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_lesson_plan" id="add_is_enable_lesson_plan" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_lesson_plan) && $plan->is_enable_lesson_plan == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_lesson_plan) && $plan->is_enable_lesson_plan == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_lesson_plan'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_guardian">Is Enable <?php echo $this->lang->line('guardian'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_guardian" id="add_is_enable_guardian" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_guardian) && $plan->is_enable_guardian == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_guardian) && $plan->is_enable_guardian == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_guardian'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_generate_card">Is Enable <?php echo $this->lang->line('generate_card'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_generate_card" id="add_is_enable_generate_card" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_generate_card) && $plan->is_enable_generate_card == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_generate_card) && $plan->is_enable_generate_card == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_generate_card'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_online_exam">Is Enable <?php echo $this->lang->line('online_exam'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_online_exam" id="add_is_enable_online_exam" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_online_exam) && $plan->is_enable_online_exam == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_online_exam) && $plan->is_enable_online_exam == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_online_exam'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_library">Is Enable <?php echo $this->lang->line('library'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_library" id="add_is_enable_library" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_library) && $plan->is_enable_library == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_library) && $plan->is_enable_library == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_library'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_transport">Is Enable <?php echo $this->lang->line('transport'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_transport" id="add_is_enable_transport" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_transport) && $plan->is_enable_transport == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_transport) && $plan->is_enable_transport == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_transport'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_hostel">
                                            Is Enable <?php echo $this->lang->line('hostel'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_hostel" id="add_is_enable_hostel" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_hostel) && $plan->is_enable_hostel == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_hostel) && $plan->is_enable_hostel == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_hostel'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_scholarship">Is Enable <?php echo $this->lang->line('scholarship'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_scholarship" id="add_is_enable_scholarship" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_scholarship) && $plan->is_enable_scholarship == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_scholarship) && $plan->is_enable_scholarship == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_scholarship'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_frontend">Is Enable <?php echo $this->lang->line('manage_frontend'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_frontend" id="add_is_enable_frontend" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_frontend) && $plan->is_enable_frontend == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_frontend) && $plan->is_enable_frontend == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_frontend'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_enable_miscellaneous">Is Enable <?php echo $this->lang->line('miscellaneous'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="is_enable_miscellaneous" id="add_is_enable_miscellaneous" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->is_enable_miscellaneous) && $plan->is_enable_miscellaneous == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('yes'); ?></option>
                                                <option value="0" <?php if (isset($plan->is_enable_miscellaneous) && $plan->is_enable_miscellaneous == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('no'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('is_enable_miscellaneous'); ?></div>
                                        </div>
                                    </div>


                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status"><?php echo $this->lang->line('status'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="status" id="edit_status" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="1" <?php if (isset($plan->status) && $plan->status == '1') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('active'); ?></option>
                                                <option value="0" <?php if (isset($plan->status) && $plan->status == '0') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('in_active'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('status'); ?></div>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" value="<?php echo isset($plan) ? $plan->id : $id; ?>" id="id" name="id" />
                                            <a href="<?php echo site_url('subscription/plan/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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



<div class="modal fade bs-plan-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
            </div>
            <div class="modal-body fn_plan_data"> </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function get_plan_modal(plan_id) {

        $('.fn_plan_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('subscription/plan/get_single_plan'); ?>",
            data: {
                plan_id: plan_id
            },
            success: function(response) {
                if (response) {
                    $('.fn_plan_data').html(response);
                }
            }
        });
    }
</script>

<!-- bootstrap-datetimepicker -->

<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            dom: 'Bfrtip',
            iDisplayLength: 15,
            buttons: [{
                    extend: 'copyHtml5',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'excelHtml5',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'csvHtml5',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                // 'csvHtml5',
                // 'pdfHtml5',
                'pageLength'
            ],
            search: true
        });
    });
    $("#add").validate();
    $("#edit").validate();
</script>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-thumbs-o-up"></i><small> Manage Trials <?php // echo $this->lang->line('manage_subscription'); 
                                                                                                ?></small></h3>
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
                                            <th><?php echo $this->lang->line('school_name'); ?></th>
                                            <th><?php echo $this->lang->line('plan_name'); ?></th>
                                            <th><?php echo $this->lang->line('price'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('email'); ?></th>
                                            <th><?php echo $this->lang->line('phone'); ?></th>
                                            <th><?php echo $this->lang->line('trial'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1;
                                        if (isset($subscriptions) && !empty($subscriptions)) { ?>
                                            <?php foreach ($subscriptions as $obj) { ?>
                                                <tr>
                                                    <td><?php echo $count++; ?></td>
                                                    <td><?php echo $obj->school_name ? $obj->school_name : $obj->school; ?></td>
                                                    <td><?php echo $obj->plan_name; ?></td>
                                                    <td><?php echo $obj->plan_price; ?></td>
                                                    <td><?php echo $obj->name; ?></td>
                                                    <td><?php echo $obj->phone ?></td>
                                                    <td><?php echo $obj->email ?></td>
                                                    <td><?php echo $obj->trial_day ?></td>
                                                    <td><?php echo $this->lang->line($obj->subscription_status); ?></td>
                                                    <td>
                                                        <?php if (has_permission(VIEW, 'subscription', 'subscription')) { ?>
                                                            <a onclick="get_subscription_modal(<?php echo $obj->id; ?>);" data-toggle="modal" data-target=".bs-subscription-modal-lg" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                        <?php  } ?>
                                                        <?php if (has_permission(EDIT, 'subscription', 'subscription')) { ?>
                                                            <a href="<?php echo site_url('subscription/trial_edit/' . $obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                        <?php } ?>
                                                        <?php if (has_permission(DELETE, 'subscription', 'subscription')) { ?>
                                                            <a href="<?php echo site_url('subscription/delete/' . $obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
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

                                <?php echo form_open(site_url('subscription/trial_add'), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left'), ''); ?>


                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subscription_plan_id"><?php echo $this->lang->line('plan_name'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="subscription_plan_id" id="edit_subscription_plan_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php foreach ($plans as $obj) { ?>
                                                <option value="<?php echo $obj->id; ?>" <?php echo isset($post) && $post['subscription_plan_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->plan_name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('subscription_plan_id'); ?></div>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_name"><?php echo $this->lang->line('school_name'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12 school_name" name="school_name" id="edit_school_name" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php foreach ($schools as $obj) { ?>
                                                <option value="<?php echo $obj->school_name; ?>" <?php echo isset($post) && $post['school_name'] == $obj->school_name ?  'selected="selected"' : ''; ?>><?php echo $obj->school_name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <!-- <input class="form-control col-md-7 col-xs-12" name="school_name" id="edit_school_name" value="<?php echo isset($post) ?  $post['school_name'] : ''; ?>" placeholder="<?php echo $this->lang->line('school_name'); ?>" required="required" type="text"> -->
                                        <div class="help-block"><?php echo form_error('school_name'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12 name" name="name" id="edit_name" value="<?php echo isset($post) ?  $post['name'] : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text">
                                        <div class="help-block"><?php echo form_error('name'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?php echo $this->lang->line('email'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12 email" name="email" id="edit_email" value="<?php echo isset($post) ?  $post['email'] : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?>" required="required" type="email">
                                        <div class="help-block"><?php echo form_error('email'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12 phone" name="phone" id="add_phone" value="<?php echo isset($post) ?  $post['phone'] : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text" maxlength="12" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <div class="help-block"><?php echo form_error('phone'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address"><?php echo $this->lang->line('address'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12 address" name="address" id="edit_address" value="<?php echo isset($post) ?  $post['address'] : ''; ?>" placeholder="<?php echo $this->lang->line('address'); ?>" required="required" type="text">
                                        <div class="help-block"><?php echo form_error('address'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_date"><?php echo $this->lang->line('start_date'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="start_date" id="add_start_date" value="<?php echo isset($post['start_date']) ?  date('d-m-Y', strtotime($post['start_date']))  : ''; ?>" placeholder="<?php echo $this->lang->line('start_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_date'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_date"><?php echo $this->lang->line('end_date'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="end_date" id="add_end_date" value="<?php echo isset($post['end_date']) ?  date('d-m-Y', strtotime($post['end_date']))  : ''; ?>" placeholder="<?php echo $this->lang->line('end_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('end_date'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="trial_day">Trial Days <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="trial_day" id="add_trial_day" value="<?php echo isset($post['trial_day']) ?  $post['trial_day']  : ''; ?>" placeholder="Trial Days" required="required" type="text" autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <div class="help-block"><?php echo form_error('trial_day'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subscription_status"><?php echo $this->lang->line('status'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="subscription_status" id="edit_subscription_status" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <option value="pending" <?php if (isset($post['subscription_status']) && $post['subscription_status'] == 'pending') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('pending'); ?></option>
                                            <option value="approved" <?php if (isset($post['subscription_status']) && $post['subscription_status'] == 'approved') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?php echo $this->lang->line('approved'); ?></option>
                                            <option value="suspend" <?php if (isset($post['subscription_status']) && $post['subscription_status'] == 'suspend') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('suspend'); ?></option>
                                            <option value="expired" <?php if (isset($post['subscription_status']) && $post['subscription_status'] == 'expired') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $this->lang->line('expired'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('status'); ?></div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('subscription/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>

                        <?php if (isset($edit)) { ?>
                            <div class="tab-pane fade in active" id="tab_edit_plan">
                                <div class="x_content">

                                    <?php echo form_open(site_url('subscription/edit/' . $subscription->id), array('name' => 'edit', 'id' => 'edit', 'class' => 'form-horizontal form-label-left'), ''); ?>


                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subscription_plan_id"><?php echo $this->lang->line('plan_name'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="subscription_plan_id" id="edit_subscription_plan_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach ($plans as $obj) { ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php echo isset($obj->id) && $obj->id == $subscription->subscription_plan_id ?  'selected="selected"' : ''; ?>><?php echo $obj->plan_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('subscription_plan_id'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plan_price"><?php echo $this->lang->line('price'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="plan_price" id="edit__plan_price" value="<?php echo isset($subscription) ?  $subscription->plan_price : ''; ?>" placeholder="<?php echo $this->lang->line('price'); ?>" required="required" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            <div class="help-block"><?php echo form_error('plan_price'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="school_name"><?php echo $this->lang->line('school_name'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12 school_name" name="school_name" id="edit_school_name" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach ($schools as $obj) {

                                                ?>
                                                    <option value="<?php echo $obj->school_name; ?>" <?php echo isset($subscription) ? (($subscription->school == $obj->school_name) ?  'selected="selected"' : '') : ""; ?>><?php echo $obj->school_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <!-- <input class="form-control col-md-7 col-xs-12" name="school_name" id="edit_school_name" value="<?php echo isset($subscription) ?  $subscription->school_name ? $subscription->school_name : $subscription->school : ''; ?>" placeholder="<?php echo $this->lang->line('school_name'); ?>" required="required" type="text"> -->
                                            <div class="help-block"><?php echo form_error('school_name'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12 name" name="name" id="edit_name" value="<?php echo isset($subscription) ?  $subscription->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text">
                                            <div class="help-block"><?php echo form_error('name'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?php echo $this->lang->line('email'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12 email" name="email" id="edit_email" value="<?php echo isset($subscription) ?  $subscription->email : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?>" required="required" type="email">
                                            <div class="help-block"><?php echo form_error('email'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12 phone" name="phone" id="add_phone" value="<?php echo isset($subscription) ?  $subscription->phone : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text" maxlength="12" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            <div class="help-block"><?php echo form_error('phone'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address"><?php echo $this->lang->line('address'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12 address" name="address" id="edit_address" value="<?php echo isset($subscription) ?  $subscription->address : ''; ?>" placeholder="<?php echo $this->lang->line('address'); ?>" required="required" type="text">
                                            <div class="help-block"><?php echo form_error('address'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_date"><?php echo $this->lang->line('start_date'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="start_date" id="edit_start_date" value="<?php echo isset($subscription->start_date) ?  date('d-m-Y', strtotime($subscription->start_date))  : ''; ?>" placeholder="<?php echo $this->lang->line('start_date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('start_date'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_date"><?php echo $this->lang->line('end_date'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="end_date" id="edit_end_date" value="<?php echo isset($subscription->end_date) ?  date('d-m-Y', strtotime($subscription->end_date))  : ''; ?>" placeholder="<?php echo $this->lang->line('end_date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('end_date'); ?></div>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="trial_day">Trial Days <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input class="form-control col-md-7 col-xs-12" name="trial_day" id="edit_trial_day" value="<?php echo isset($subscription->trial_day) ?  $subscription->trial_day  : ''; ?>" placeholder="Trial Days" required="required" type="text" autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            <div class="help-block"><?php echo form_error('trial_day'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subscription_status"><?php echo $this->lang->line('status'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" name="subscription_status" id="edit_subscription_status" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <option value="pending" <?php if (isset($subscription->subscription_status) && $subscription->subscription_status == 'pending') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?php echo $this->lang->line('pending'); ?></option>
                                                <option value="approved" <?php if (isset($subscription->subscription_status) && $subscription->subscription_status == 'approved') {
                                                                                echo 'selected="selected"';
                                                                            } ?>><?php echo $this->lang->line('approved'); ?></option>
                                                <option value="suspend" <?php if (isset($subscription->subscription_status) && $subscription->subscription_status == 'suspend') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?php echo $this->lang->line('suspend'); ?></option>
                                                <option value="expired" <?php if (isset($subscription->subscription_status) && $subscription->subscription_status == 'expired') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?php echo $this->lang->line('expired'); ?></option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('status'); ?></div>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" value="<?php echo isset($subscription) ? $subscription->saas_id : ''; ?>" id="id" name="id" />
                                            <a href="<?php echo site_url('subscription/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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



<div class="modal fade bs-subscription-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
            </div>
            <div class="modal-body fn_subscription_data"> </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function get_subscription_modal(subscription_id) {

        $('.fn_plan_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('subscription/get_single_subscription'); ?>",
            data: {
                subscription_id: subscription_id
            },
            success: function(response) {
                if (response) {
                    $('.fn_subscription_data').html(response);
                }
            }
        });
    }
</script>

<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>

<script type="text/javascript">
    $('#add_start_date').datepicker();
    $('#edit_start_date').datepicker();
    $('#add_end_date').datepicker();
    $('#edit_end_date').datepicker();


    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            dom: 'Bfrtip',
            iDisplayLength: 15,
            buttons: [{
                    extend: 'copyHtml5',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'excelHtml5',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'csvHtml5',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
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
    $(document).on('change', '.school_name', function() {
        var school_name = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('subscription/getSchoolDetail'); ?>",
            data: {
                school_name: school_name
            },
            async: false,
            success: function(response) {
                $('.name').val(response.school_name);
                $('.email').val(response.email);
                $('.phone').val(response.phone);
                $('.address').val(response.address);
            }
        });
    });
</script>
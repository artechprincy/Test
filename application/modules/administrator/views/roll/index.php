<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-file-word-o"></i><small> Manage Roll No.</small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
            </div>

            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">

                    <ul class="nav nav-tabs bordered">
                        <li class="<?php if (isset($list)) {
                                        echo 'active';
                                    } ?>"><a href="#tab_roll_list" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if (has_permission(ADD, 'academic', 'assignment')) { ?>

                            <?php if (isset($edit)) { ?>
                                <li class="<?php if (isset($add)) {
                                                echo 'active';
                                            } ?>"><a href="<?php echo site_url('administrator/roll_no/add'); ?>" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>
                            <?php } else { ?>
                                <li class="<?php if (isset($add)) {
                                                echo 'active';
                                            } ?>"><a href="#tab_add_roll" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>
                            <?php } ?>
                        <?php } ?>

                        <?php if (isset($edit)) { ?>
                            <li class="active"><a href="#tab_edit_roll" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>
                        <?php } ?>

                        <li class="li-class-list">

                            <?php $teacher_access_data = get_teacher_access_data(); ?>
                            <?php $guardian_access_data = get_guardian_access_data('class'); ?>

                            <?php if ($this->session->userdata('role_id') == SUPER_ADMIN) {  ?>

                                <?php echo form_open(site_url('administrator/roll_no/index'), array('name' => 'filter', 'id' => 'filter', 'class' => 'form-horizontal form-label-left'), ''); ?>
                                <select class="form-control col-md-7 col-xs-12" style="width:auto;" name="school_id" onchange="get_class_by_school(this.value, '');">
                                    <option value="">--<?php echo $this->lang->line('select_school'); ?>--</option>
                                    <?php foreach ($schools as $obj) { ?>
                                        <option value="<?php echo $obj->id; ?>" <?php if (isset($filter_school_id) && $filter_school_id == $obj->id) {
                                                                                    echo 'selected="selected"';
                                                                                } ?>> <?php echo $obj->school_name; ?></option>
                                    <?php } ?>
                                </select>
                                <select class="form-control col-md-7 col-xs-12" id="filter_class_id" name="class_id" style="width:auto;" onchange="this.form.submit();">
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                </select>
                                <?php echo form_close(); ?>

                            <?php } else {  ?>

                                <select class="form-control col-md-7 col-xs-12" onchange="get_assignment_by_class(this.value);">
                                    <?php if ($this->session->userdata('role_id') != STUDENT) { ?>
                                        <option value="<?php echo site_url('administrator/roll_no/index'); ?>">--<?php echo $this->lang->line('select'); ?>--</option>
                                    <?php } ?>
                                    <?php foreach ($class_list as $obj) { ?>
                                        <?php if ($this->session->userdata('role_id') == STUDENT) { ?>
                                            <?php if ($obj->id != $this->session->userdata('class_id')) {
                                                continue;
                                            } ?>
                                            <option value="<?php echo site_url('administrator/roll_no/index/' . $obj->id); ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                                                                    echo 'selected="selected"';
                                                                                                                                } ?>><?php echo $obj->name; ?></option>
                                        <?php } elseif ($this->session->userdata('role_id') == GUARDIAN) { ?>
                                            <?php if (!in_array($obj->id, $guardian_access_data)) {
                                                continue;
                                            } ?>
                                            <option value="<?php echo site_url('administrator/roll_no/index/' . $obj->id); ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                                                                    echo 'selected="selected"';
                                                                                                                                } ?>><?php echo $obj->name; ?></option>
                                        <?php } elseif ($this->session->userdata('role_id') == TEACHER) { ?>
                                            <?php if (!in_array($obj->id, $teacher_access_data)) {
                                                continue;
                                            } ?>
                                            <option value="<?php echo site_url('administrator/roll_no/index/' . $obj->id); ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                                                                    echo 'selected="selected"';
                                                                                                                                } ?>><?php echo $obj->name; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo site_url('administrator/roll_no/index/' . $obj->id); ?>" <?php if (isset($class_id) && $class_id == $obj->id) {
                                                                                                                                    echo 'selected="selected"';
                                                                                                                                } ?>><?php echo $obj->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </li>
                    </ul>
                    <br />

                    <div class="tab-content">
                        <div class="tab-pane fade in <?php if (isset($list)) {
                                                            echo 'active';
                                                        } ?>" id="tab_roll_list">
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <th>Roll No. Prefix</th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('section'); ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1;
                                        if (isset($rolls) && !empty($rolls)) { ?>
                                            <?php foreach ($rolls as $obj) { ?>
                                                <?php
                                                if ($this->session->userdata('role_id') == GUARDIAN) {
                                                    if (!in_array($obj->class_id, $guardian_access_data)) {
                                                        continue;
                                                    }
                                                } elseif ($this->session->userdata('role_id') == TEACHER) {
                                                    if (!in_array($obj->class_id, $teacher_access_data)) {
                                                        continue;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo $count++; ?></td>
                                                    <td><?php echo $obj->start_roll; ?></td>
                                                    <td><?php echo $obj->class_name; ?></td>
                                                    <td><?php echo $obj->section; ?></td>
                                                    <td>
                                                        <?php
                                                        // echo check_enrollments($obj->class_id, $obj->section_id);
                                                        if (check_enrollments($obj->class_id, $obj->section_id) == 0) { ?>
                                                            <a href="<?php echo site_url('administrator/roll_no/edit/' . $obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
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
                                                        } ?>" id="tab_add_roll">
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> Please add Class & Section before add Roll No.</div>
                                </div>
                                <?php echo form_open_multipart(site_url('administrator/roll_no/add'), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                <?php $this->load->view('layout/school_list_form'); ?>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="class_id" id="add_class_id" required="required" onchange="get_section_by_class(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php if (isset($classes) && !empty($classes)) { ?>
                                                <?php foreach ($classes as $obj) { ?>
                                                    <?php
                                                    if ($this->session->userdata('role_id') == TEACHER) {
                                                        if (!in_array($obj->id, $teacher_access_data)) {
                                                            continue;
                                                        }
                                                    } else if ($this->session->userdata('role_id') == GUARDIAN) {
                                                        if (!in_array($obj->id, $guardian_access_data)) {
                                                            continue;
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $obj->id; ?>"><?php echo $obj->name; ?></option>
                                                <?php } ?>
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
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_roll"><?php echo $this->lang->line('roll_prefix'); ?><span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="start_roll" id="start_roll" value="<?php echo isset($post['start_roll']) ?  $post['start_roll'] : ''; ?>" placeholder="<?php echo $this->lang->line('roll_prefix'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_roll'); ?></div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('administrator/roll_no/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <div class="tab-pane fade in <?php if (isset($edit)) {
                                                            echo 'active';
                                                        } ?>" id="tab_edit_roll">
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> Please add Class & Section before add Roll No.</div>
                                </div>
                                <?php echo form_open_multipart(site_url('administrator/roll_no/edit/' . $roll->id), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left'), ''); ?>

                                <?php $this->load->view('layout/school_list_form'); ?>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="class_id" id="edit_class_id" required="required" onchange="get_section_by_class(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            <?php if (isset($classes) && !empty($classes)) { ?>
                                                <?php foreach ($classes as $obj) { ?>
                                                    <?php
                                                    if ($this->session->userdata('role_id') == TEACHER) {
                                                        if (!in_array($obj->id, $teacher_access_data)) {
                                                            continue;
                                                        }
                                                    } else if ($this->session->userdata('role_id') == GUARDIAN) {
                                                        if (!in_array($obj->id, $guardian_access_data)) {
                                                            continue;
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php echo $roll->class_id == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-7 col-xs-12" name="section_id" id="edit_section_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_roll"><?php echo $this->lang->line('roll_prefix'); ?><span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="start_roll" id="start_roll" value="<?php echo isset($roll->start_roll) ?  $roll->start_roll : ''; ?>" placeholder="<?php echo $this->lang->line('roll_prefix'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_roll'); ?></div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" id="edit_school_id" value="<?php echo $this->session->userdata('school_id') ?>">
                                        <input type="hidden" value="<?php echo isset($roll) ? $roll->id : $id; ?>" name="id" />
                                        <a href="<?php echo site_url('administrator/roll_no/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bs-assignment-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
            </div>
            <div class="modal-body fn_assignment_data">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function get_assignment_modal(assignment_id) {

        $('.fn_assignment_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('administrator/roll_no/get_single_assignment'); ?>",
            data: {
                assignment_id: assignment_id
            },
            success: function(response) {
                if (response) {
                    $('.fn_assignment_data').html(response);
                }
            }
        });
    }
</script>


<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
<!-- Super admin js end -->

<script type="text/javascript">
    $('#add_assigment_date').datepicker();
    $('#edit_assigment_date').datepicker();
    $('#add_submission_date').datepicker();
    $('#edit_submission_date').datepicker();


    var edit = false;
    <?php if (isset($edit)) { ?>
        edit = true;
        get_section_by_class('<?php echo $roll->class_id; ?>', '<?php echo $roll->section_id; ?>');
    <?php } ?>

    function get_section_by_class(class_id, section_id) {

        var school_id = '<?= $this->session->userdata('school_id') ?>';

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

    }



    /* Menu Filter Start */
    function get_assignment_by_class(url) {
        if (url) {
            window.location.href = url;
        }
    }

    function get_assignment_by_class_sa(class_id) {

        var school_id = $('#school_id_filter').val();
        if (!school_id) {

            toastr.error('<?php echo $this->lang->line("select_school"); ?>');
            return false;
        }
        if (!class_id) {
            return false;
        }
        window.location.href = '<?php echo site_url('administrator/roll_no/index/'); ?>' + class_id + '/' + school_id;

    }
</script>
<script type="text/javascript">
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

    $("#add").validate();
    $("#edit").validate();
</script>
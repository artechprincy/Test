<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-gear"></i><small> <?php echo $this->lang->line('admission_no'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">

                    <ul class="nav nav-tabs bordered">
                        <li class="active"><a href="#tab_admission_no" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-gear"></i> <?php echo $this->lang->line('admission_no'); ?></a></li>
                    </ul>
                    <br />
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab_admission_no">
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> Please be careful. You should not edit.</div>
                                </div>
                                <?php echo form_open(site_url('administrator/admission_no/add'), array('name' => 'add', 'id' => 'add', 'class' => 'form-horizontal form-label-left', 'onsubmit' => "return confirm('Please be careful. You should not edit.');"), ''); ?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admission_no"><?php echo $this->lang->line('admission_no'); ?> Prefix<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="admission_no" id="admission_no" <?php echo !empty($admission_no) ?  'readonly' : ''; ?> value="<?php echo isset($admission_no) ?  $admission_no : ''; ?>" placeholder="<?php echo $this->lang->line('admission_no'); ?>" required="required" type="text" autocomplete="off" style="text-transform: uppercase;">
                                        <div class="help-block"><?php echo form_error('admission_no'); ?></div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('dashboard/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success" <?php echo !empty($admission_no) ?  'disabled' : ''; ?>><?php echo $this->lang->line('submit'); ?></button>
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
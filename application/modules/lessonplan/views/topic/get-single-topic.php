<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('school_name'); ?> </th>
            <td><?php echo $topic->school_name; ?></td>
            <th width="20%"> <?php echo $this->lang->line('academic_year'); ?> </th>
            <td><?php echo $topic->session_year; ?></td>
        </tr>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('class'); ?> </th>
            <td><?php echo $topic->class_name; ?></td>
            <th><?php echo $this->lang->line('subject'); ?></th>
            <td><?php echo $topic->subject; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('today_lesson'); ?></th>
            <td><?php echo $topic->today_topic; ?></td>
            <th><?php echo $this->lang->line('today_key_concept'); ?></th>
            <td><?php echo $topic->today_key_concept; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('next_lesson'); ?></th>
            <td><?php echo $topic->next_topic; ?></td>
            <th><?php echo $this->lang->line('next_key_concept'); ?></th>
            <td><?php echo $topic->next_key_concept; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('attachment'); ?></th>
            <td>
                <?php if ($topic->attachment != '') { ?>
                    <img src="<?php echo UPLOAD_PATH; ?>/attachment/<?php echo $topic->attachment; ?>" alt="" width="40" />
                <?php } else { ?>
                    <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="60" />
                <?php } ?>
            </td>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $topic->note; ?></td>
        </tr>
        <tr>
            <th colspan="4"></th>
        </tr>
        <tr>
            <th colspan="4"><strong>Homework</strong></th>
        </tr>

        <tr>
            <th><?php echo $this->lang->line('title'); ?></th>
            <td><?php echo $topic->homework_title; ?></td>
            <th><?php echo $this->lang->line('intruction'); ?></th>
            <td><?php echo $topic->homework_intruction; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('due_date'); ?></th>
            <td><?php echo $topic->homework_due_date; ?></td>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $topic->homework_notes; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('attachment'); ?></th>
            <td>
                <?php if ($topic->homework_attachment != '') { ?>
                    <img src="<?php echo UPLOAD_PATH; ?>/homework-attachment/<?php echo $topic->homework_attachment; ?>" alt="" width="40" />
                <?php } else { ?>
                    <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="60" />
                <?php } ?>
            </td>
            <th></th>
            <td></td>
        </tr>
    </tbody>
</table>
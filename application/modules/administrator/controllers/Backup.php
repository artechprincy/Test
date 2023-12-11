<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Administrator_Model', 'administrator', true);
         
        // if($this->session->userdata('role_id') != SUPER_ADMIN){ 
        //   error($this->lang->line('permission_denied'));
        //   redirect('dashboard/index');
        // }
    }

    public function index() {
        
        check_permission(VIEW);
        
        if ($_POST) {             
            if (IS_LIVE == TRUE) {
              
                $this->load->dbutil();
                $conf = array(
                    'format' => 'zip',
                    'filename' => 'database-backup.sql'
                );
                $backup = $this->dbutil->backup($conf);
                $this->load->helper('download');
                force_download('database-backup.zip', $backup);
                
                create_log('Has been taken database backup');
                redirect('administrator/backup/index');
            } else {
                error($this->lang->line('in_demo_db_backup'));
                redirect('administrator/backup/index');
            }
        } else {
            $this->layout->title($this->lang->line('backup_database'). ' | ' . SMS);
            $this->layout->view('backup/index', $this->data);  
        }
    }
    
    
}

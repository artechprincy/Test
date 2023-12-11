<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
    public $global_setting = array();
    public function index() {
              
        if (logged_in_user_id()) {
            redirect('dashboard/index');
        }
                        
        $this->global_setting = $this->db->get_where('global_setting', array('status'=>1))->row();
        
        if(!empty($this->global_setting) && $this->global_setting->language){             
            $this->lang->load($this->global_setting->language);             
        }else{
           $this->lang->load('english');
        }
        
        $this->load->view('login');
    }

}

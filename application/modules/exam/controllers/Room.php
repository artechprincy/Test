<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Room_Model', 'room', true);        
    }

    
       
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Hostel Room List" user interface                 
    *                      
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index($school_id = null) {

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }        
        $this->data['rooms'] = $this->room->get_room_list($school_id);
        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_room') . ' | ' . SMS);
        $this->layout->view('room/index', $this->data);
    }

    
    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Hostel Room" user interface                 
    *                    and process to store "Hostel Room" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {
        if ($_POST) {
            $this->_prepare_room_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_room_data();

                $insert_id = $this->room->insert('school_rooms', $data);
                if ($insert_id) {
                    create_log('Has been added a room no : '.$data['room_no'] );
                    
                    success($this->lang->line('insert_success'));
                    redirect('exam/room/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('exam/room/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }

         $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }        
        $this->data['rooms'] = $this->room->get_room_list();
        $this->data['schools'] = $this->schools;
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('room/index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Hostel Room" user interface                 
    *                    with populate "Hostel Room" value 
    *                    and process to update "Hostel Room" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        if(!is_numeric($id)){
          error($this->lang->line('unexpected_error'));
          redirect('exam/room/index');
        }
        
        if ($_POST) {
            $this->_prepare_room_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_room_data();
                $updated = $this->room->update('school_rooms', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    create_log('Has been updated a room no : '.$data['room_no']);
                    
                    success($this->lang->line('update_success'));
                    redirect('exam/room/index/'.$data['school_id']);
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('exam/room/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['room'] = $this->room->get_single('rooms', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['room'] = $this->room->get_single('school_rooms', array('id' => $id));

            if (!$this->data['room']) {
                redirect('exam/room/index');
            }
        }

        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
        }        
        $this->data['rooms'] = $this->room->get_room_list($this->data['room']->school_id);
        
         $this->data['school_id'] = $this->data['room']->school_id;
        $this->data['filter_school_id'] = $this->data['room']->school_id;
        $this->data['schools'] = $this->schools;
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('room/index', $this->data);
    }

    
        
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : view
    * @description     : Load user interface with specific Hostel Room data                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function view($id = null) {

        check_permission(VIEW);
        
        if(!is_numeric($id)){
          error($this->lang->line('unexpected_error'));
          redirect('hostel/room/index');
        }
        
        $condition = array();
        $condition['status'] = 1;        
        if($this->session->userdata('role_id') != SUPER_ADMIN){            
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['hostels'] = $this->room->get_list('hostels', $condition, '', '', '', 'id', 'ASC');
        }        
        $this->data['rooms'] = $this->room->get_room_list();        
        $this->data['room'] = $this->room->get_single_room($id);
        
        $this->data['detail'] = TRUE;
        $this->layout->title($this->lang->line('view') . ' ' . $this->lang->line('room') . ' | ' . SMS);
        $this->layout->view('room/index', $this->data);
    }

                   
    /*****************Function get_single_room**********************************
     * @type            : Function
     * @function name   : get_single_room
     * @description     : "Load single room information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_room(){
        
        $room_id = $this->input->post('room_id');
       
         $this->data['room'] = $this->room->get_single_room($room_id);
        echo $this->load->view('room/get-single-room', $this->data);
    }
        
    /*****************Function _prepare_room_validation**********************************
    * @type            : Function
    * @function name   : _prepare_room_validation
    * @description     : Process "HOstel Room" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_room_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('school_id', $this->lang->line('school_name'), 'trim|required');
        $this->form_validation->set_rules('total_seat', $this->lang->line('seat_total'), 'trim|required');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
        $this->form_validation->set_rules('room_no', $this->lang->line('room_no'), 'required|trim|callback_room_no');
    }

    
        
    
    /*****************Function room_no**********************************
    * @type            : Function
    * @function name   : room_no
    * @description     : Unique check for "Hostel Room No" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function room_no() {
        if ($this->input->post('id') == '') {
            $room = $this->room->duplicate_check($this->input->post('school_id'), $this->input->post('room_no'));
            if ($room) {
                $this->form_validation->set_message('room_no', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $room = $this->room->duplicate_check($this->input->post('school_id'), $this->input->post('room_no'), $this->input->post('id'));
            if ($room) {
                $this->form_validation->set_message('room_no', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    
       
    /*****************Function _get_posted_room_data**********************************
    * @type            : Function
    * @function name   : _get_posted_room_data
    * @description     : Prepare "Hostel Room No" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_room_data() {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'room_no';
        $items[] = 'total_seat';
        $items[] = 'note';
        $data = elements($items, $_POST);

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }

        return $data;
    }

         
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Hostel Room" data from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);

        if(!is_numeric($id)){
          error($this->lang->line('unexpected_error'));
          redirect('exam/room/index');
        }
        
        $room = $this->room->get_single('rooms', array('id' => $id));
        
        if ($this->room->delete('rooms', array('id' => $id))) {
          
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
         redirect('exam/room/index/'.$room->school_id);
    }
}

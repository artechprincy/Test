<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends MY_Controller
{

    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('Teacher_Model', 'teacher', true);
        $this->data['roles'] = $this->teacher->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
    }


    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Teacher List" user interface                 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function index($school_id = null)
    {

        check_permission(VIEW);
        $this->data['teachers'] = $this->teacher->get_teacher_list($school_id);

        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            $condition = array();
            $condition['status'] = 1;
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['departments'] = $this->teacher->get_list('departments', $condition, '', '', '', 'id', 'ASC');
            $this->data['grades'] = $this->teacher->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }

        $this->data['filter_school_id'] = $school_id;
        $this->data['schools'] = $this->schools;

        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_teacher') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }


    /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Add new Teacer" user interface                 
     *                    and process to store "Teacer" into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function add()
    {

        check_permission(ADD);

        if ($_POST) {

            // need to check school subscription status
            // if($this->session->userdata('role_id') != SUPER_ADMIN){                 
            //     if(!check_saas_status($this->session->userdata('school_id'), 'teacher')){                        
            //       redirect('dashboard/index');
            //     }
            // }


            $this->_prepare_teacher_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_teacher_data();

                $insert_id = $this->teacher->insert('teachers', $data);
                if ($insert_id) {
                    success($this->lang->line('insert_success'));
                    redirect('teacher/index/' . $data['school_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('teacher/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }

        $this->data['teachers'] = $this->teacher->get_teacher_list();
        $this->data['roles'] = $this->teacher->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');

        if ($this->session->userdata('role_id') != SUPER_ADMIN) {

            $condition = array();
            $condition['status'] = 1;
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['departments'] = $this->teacher->get_list('departments', $condition, '', '', '', 'id', 'ASC');
            $this->data['grades'] = $this->teacher->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }

        $this->data['schools'] = $this->schools;

        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }


    /*****************Function edit**********************************
     * @type            : Function
     * @function name   : edit
     * @description     : Load Update "Teacer" user interface                 
     *                    with populate "Teacher" data/value 
     *                    and process to update "Teacher" into database    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null)
    {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_teacher_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_teacher_data();
                $updated = $this->teacher->update('teachers', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    success($this->lang->line('update_success'));
                    redirect('teacher/index/' . $data['school_id']);
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('teacher/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['teacher'] = $this->teacher->get_single_teacher($this->input->post('id'));
            }
        }

        if ($id) {
            $this->data['teacher'] = $this->teacher->get_single_teacher($id);

            if (!$this->data['teacher']) {
                redirect('teacher/index');
            }
        }

        $this->data['teachers'] = $this->teacher->get_teacher_list($this->data['teacher']->school_id);
        $this->data['roles'] = $this->teacher->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');

        if ($this->session->userdata('role_id') != SUPER_ADMIN) {

            $condition = array();
            $condition['status'] = 1;
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['departments'] = $this->teacher->get_list('departments', $condition, '', '', '', 'id', 'ASC');
            $this->data['grades'] = $this->teacher->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }

        $this->data['school_id'] = $this->data['teacher']->school_id;
        $this->data['filter_school_id'] = $this->data['teacher']->school_id;
        $this->data['schools'] = $this->schools;

        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }


    /*****************Function get_single_teacher**********************************
     * @type            : Function
     * @function name   : get_single_teacher
     * @description     : "Load single teacher information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_teacher()
    {

        $teacher_id = $this->input->post('teacher_id');

        $this->data['teacher'] = $this->teacher->get_single_teacher($teacher_id);
        echo $this->load->view('teacher/get-single-teacher', $this->data);
    }


    /*****************Function _prepare_teacher_validation**********************************
     * @type            : Function
     * @function name   : _prepare_teacher_validation
     * @description     : Process "Teacher" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_teacher_validation()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        if (!$this->input->post('id')) {
            $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|callback_username');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|min_length[5]|max_length[30]');
        }

        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email');
        $this->form_validation->set_rules('role_id', $this->lang->line('role'), 'trim|required');
        $this->form_validation->set_rules('school_id', $this->lang->line('school'), 'trim|required');

        // $this->form_validation->set_rules('department_id', $this->lang->line('department'), 'trim|required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required');
        $this->form_validation->set_rules('present_address', $this->lang->line('present_address'), 'trim');
        $this->form_validation->set_rules('permanent_address', $this->lang->line('permanent_address'), 'trim');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required');
        $this->form_validation->set_rules('blood_group', $this->lang->line('blood_group'), 'trim');
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim');
        $this->form_validation->set_rules('dob', $this->lang->line('birth_date'), 'trim|required');
        $this->form_validation->set_rules('joining_date', $this->lang->line('join_date'), 'trim|required');
        // $this->form_validation->set_rules('salary_grade_id', $this->lang->line('salary_grade'), 'trim|required');
        // $this->form_validation->set_rules('salary_type', $this->lang->line('salary_type'), 'trim|required');
        $this->form_validation->set_rules('facebook_url', $this->lang->line('facebook_url'), 'trim');
        $this->form_validation->set_rules('linkedin_url', $this->lang->line('linkedin_url'), 'trim');
        $this->form_validation->set_rules('instagram_url', $this->lang->line('instagram_url'), 'trim');
        $this->form_validation->set_rules('pinterest_url', $this->lang->line('pinterest_url'), 'trim');
        $this->form_validation->set_rules('twitter_url', $this->lang->line('twitter_url'), 'trim');
        $this->form_validation->set_rules('youtube_url', $this->lang->line('youtube_url'), 'trim');
        $this->form_validation->set_rules('other_info', $this->lang->line('other_info'), 'trim');
        $this->form_validation->set_rules('pan_id', $this->lang->line('pan_id'), 'trim|required');
        $this->form_validation->set_rules('national_id', $this->lang->line('national_id'), 'trim|required');

        $this->form_validation->set_rules('resume', $this->lang->line('resume'), 'trim|callback_resume');
        $this->form_validation->set_rules('photo', $this->lang->line('photo'), 'trim|callback_photo');
        $this->form_validation->set_rules('signature', $this->lang->line('signature'), 'trim|callback_signature');
    }



    /*****************Function username**********************************
     * @type            : Function
     * @function name   : username
     * @description     : Unique check for "Teacher username" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */
    public function username()
    {
        if ($this->input->post('id') == '') {
            $username = $this->teacher->duplicate_check($this->input->post('username'));
            if ($username) {
                $this->form_validation->set_message('username', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $username = $this->teacher->duplicate_check($this->input->post('username'), $this->input->post('id'));
            if ($username) {
                $this->form_validation->set_message('username', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }


    /*****************Function resume**********************************
     * @type            : Function
     * @function name   : resume
     * @description     : validate resume                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */
    public function resume()
    {
        if ($_FILES['resume']['name']) {
            $name = $_FILES['resume']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'ppt' || $ext == 'pptx' || $ext == 'txt') {
                return TRUE;
            } else {
                $this->form_validation->set_message('resume', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }

    /*****************Function photo**********************************
     * @type            : Function
     * @function name   : photo
     * @description     : validate photo                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */
    public function photo()
    {
        if ($_FILES['photo']['name']) {

            // list($width, $height) = getimagesize($_FILES['photo']['tmp_name']);
            $file_size = $_FILES["photo"]["size"];
            if ((!empty($file_size)) && $file_size >= 4097152) {
                $this->form_validation->set_message('photo', "File too large. File must be less than 4 MB.");
                return FALSE;
            }

            $name = $_FILES['photo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('photo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }
    /*****************Function signature**********************************
     * @type            : Function
     * @function name   : signature
     * @description     : validate signature                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */
    public function signature()
    {
        if ($_FILES['signature']['name']) {

            list($width, $height) = getimagesize($_FILES['signature']['tmp_name']);
            $file_size = $_FILES["signature"]["size"];
            if ((!empty($file_size)) && $file_size >= 4097152) {
                $this->form_validation->set_message('signature', "File too large. File must be less than 4 MB.");
                return FALSE;
            }

            $name = $_FILES['signature']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('signature', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }


    /*****************Function _get_posted_teacher_data**********************************
     * @type            : Function
     * @function name   : _get_posted_teacher_data
     * @description     : Prepare "Teacher" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_teacher_data()
    {

        $items = array();
        $items[] = 'school_id';
        $items[] = 'name';
        $items[] = 'email';
        $items[] = 'national_id';
        $items[] = 'pan_id';
        // $items[] = 'department_id';
        $items[] = 'phone';
        $items[] = 'present_address';
        $items[] = 'permanent_address';
        $items[] = 'gender';
        $items[] = 'blood_group';
        $items[] = 'religion';
        $items[] = 'other_info';
        $items[] = 'salary_grade_id';
        $items[] = 'salary_type';
        $items[] = 'facebook_url';
        $items[] = 'linkedin_url';
        $items[] = 'instagram_url';
        $items[] = 'pinterest_url';
        $items[] = 'twitter_url';
        $items[] = 'youtube_url';
        $items[] = 'is_view_on_web';

        $data = elements($items, $_POST);

        $data['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));
        $data['joining_date'] = date('Y-m-d', strtotime($this->input->post('joining_date')));

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
            // create user 
            $data['user_id'] = $this->teacher->create_user();
        }

        $data['department_id'] = implode(',', $this->input->post('department_id'));

        if ($_FILES['photo']['name']) {
            $data['photo'] = $this->_upload_photo();
        }
        if ($_FILES['signature']['name']) {
            $data['signature'] = $this->_upload_signature();
        }
        if ($_FILES['resume']['name']) {
            $data['resume'] = $this->_upload_resume();
        }

        return $data;
    }


    /*****************Function _upload_photo**********************************
     * @type            : Function
     * @function name   : _upload_photo
     * @description     : process to upload teacher profile photo in the server                  
     *                     and return photo file name  
     * @param           : null
     * @return          : $return_photo string value 
     * ********************************************************** */
    private function _upload_photo()
    {

        $prev_photo = $this->input->post('prev_photo');
        $photo = $_FILES['photo']['name'];
        $photo_type = $_FILES['photo']['type'];
        $return_photo = '';
        if ($photo != "") {
            if (
                $photo_type == 'image/jpeg' || $photo_type == 'image/pjpeg' ||
                $photo_type == 'image/jpg' || $photo_type == 'image/png' ||
                $photo_type == 'image/x-png' || $photo_type == 'image/gif'
            ) {

                $destination = 'assets/uploads/teacher-photo/';

                $file_type = explode(".", $photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $photo_path);

                // need to unlink previous photo
                if ($prev_photo != "") {
                    if (file_exists($destination . $prev_photo)) {
                        @unlink($destination . $prev_photo);
                    }
                }

                $return_photo = $photo_path;
            }
        } else {
            $return_photo = $prev_photo;
        }

        return $return_photo;
    }
    
    /*****************Function _upload_signature**********************************
     * @type            : Function
     * @function name   : _upload_signature
     * @description     : process to upload teacher profile signature in the server                  
     *                     and return signature file name  
     * @param           : null
     * @return          : $return_signature string value 
     * ********************************************************** */
    private function _upload_signature()
    {

        $prev_signature = $this->input->post('prev_signature');
        $signature = $_FILES['signature']['name'];
        $signature_type = $_FILES['signature']['type'];
        $return_signature = '';
        if ($signature != "") {
            if (
                $signature_type == 'image/jpeg' || $signature_type == 'image/pjpeg' ||
                $signature_type == 'image/jpg' || $signature_type == 'image/png' ||
                $signature_type == 'image/x-png' || $signature_type == 'image/gif'
            ) {

                $destination = 'assets/uploads/teacher-signature/';

                $file_type = explode(".", $signature);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $signature_path = 'signature-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['signature']['tmp_name'], $destination . $signature_path);

                // need to unlink previous signature
                if ($prev_signature != "") {
                    if (file_exists($destination . $prev_signature)) {
                        @unlink($destination . $prev_signature);
                    }
                }

                $return_signature = $signature_path;
            }
        } else {
            $return_signature = $prev_signature;
        }

        return $return_signature;
    }


    /*****************Function _upload_resume**********************************
     * @type            : Function
     * @function name   : _upload_resume
     * @description     : process to upload teacher profile resume in the server                  
     *                     and return resume file name  
     * @param           : null
     * @return          : $return_resume string value 
     * ********************************************************** */
    private function _upload_resume()
    {
        $prev_resume = $this->input->post('prev_resume');
        $resume = $_FILES['resume']['name'];
        $resume_type = $_FILES['resume']['type'];
        $return_resume = '';

        if ($resume != "") {
            if (
                $resume_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                $resume_type == 'application/msword' || $resume_type == 'text/plain' ||
                $resume_type == 'application/vnd.ms-office' || $resume_type == 'application/pdf'
            ) {

                $destination = 'assets/uploads/teacher-resume/';

                $file_type = explode(".", $resume);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $resume_path = 'resume-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['resume']['tmp_name'], $destination . $resume_path);

                // need to unlink previous photo
                if ($prev_resume != "") {
                    if (file_exists($destination . $prev_resume)) {
                        @unlink($destination . $prev_resume);
                    }
                }

                $return_resume = $resume_path;
            }
        } else {
            $return_resume = $prev_resume;
        }

        return $return_resume;
    }




    /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Teacher" data from database                  
     *                    also unlink teacher profile photo & resume from server   
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null)
    {

        check_permission(DELETE);

        if (!is_numeric($id)) {
            error($this->lang->line('unexpected_error'));
            redirect('teacher/index');
        }

        $teacher = $this->teacher->get_single('teachers', array('id' => $id));
        if (!empty($teacher)) {

            // delete teacher data
            $this->teacher->delete('teachers', array('id' => $id));
            // delete teacher login data
            $this->teacher->delete('users', array('id' => $teacher->user_id));

            // delete  teacher_attendances data
            $this->teacher->delete('teacher_attendances', array('teacher_id' => $id));

            // delete teacher resume and photo
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/teacher-resume/' . $teacher->resume)) {
                @unlink($destination . '/teacher-resume/' . $teacher->resume);
            }
            if (file_exists($destination . '/teacher-photo/' . $teacher->photo)) {
                @unlink($destination . '/teacher-photo/' . $teacher->photo);
            }

            success($this->lang->line('delete_success'));
            redirect('teacher/index/' . $teacher->school_id);
        } else {
            error($this->lang->line('delete_failed'));
        }

        redirect('teacher/index');
    }


    public function update_display_order()
    {


        $school_id = $this->input->post('school_id');
        $ids       = rtrim($this->input->post('ids'), ',');
        $orders    = rtrim($this->input->post('orders'), ',');

        if (!$ids || !$school_id) {
            echo FALSE;
            die();
        }

        $id_arr = explode(',', $ids);
        $order_arr = explode(',', $orders);

        if (is_array($id_arr)) {

            foreach ($id_arr as $key => $val) {
                $this->teacher->update('teachers', array('display_order' => $order_arr[$key], 'modified_at' => date('Y-m-d H:i:s')), array('id' => $val));
            }
            echo TRUE;
        }

        echo FALSE;
    }



    /*****************Function view**********************************
     * @type            : Function
     * @function name   : view
     * @description     : Load "Teacher view" user interface                 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function view($id = null)
    {

        check_permission(VIEW);

        $this->data['teacher'] = $this->teacher->get_single_teacher($id);
        $this->data['teachers'] = $this->teacher->get_teacher_list($this->data['teacher']->school_id);
        $this->data['roles'] = $this->teacher->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');

        if ($this->session->userdata('role_id') != SUPER_ADMIN) {

            $condition = array();
            $condition['status'] = 1;
            $condition['school_id'] = $this->session->userdata('school_id');
            $this->data['grades'] = $this->teacher->get_list('salary_grades', $condition, '', '', '', 'id', 'ASC');
        }

        $this->data['filter_school_id'] = $this->data['teacher']->school_id;
        $this->data['schools'] = $this->schools;

        $this->data['detail'] = TRUE;
        $this->layout->title($this->lang->line('manage_teacher') . ' | ' . SMS);
        $this->layout->view('teacher/index', $this->data);
    }


    /*****************Function get_supplier_by_school**********************************
     * @type            : Function
     * @function name   : get_supplier_by_school
     * @description     : Load "Supplier Listing" by ajax call                
     *                    and populate user listing
     * @param           : null
     * @return          : null 
     * ********************************************************** */

    public function get_department_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $department_id  = explode(',', $this->input->post('department_id'));

        $departments = $this->teacher->get_list('departments', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($departments)) {
            foreach ($departments as $obj) {
                $selected = in_array($obj->id, $department_id) ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
            }
        }

        echo $str;
    }
}

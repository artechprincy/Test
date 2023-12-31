<?php
defined('BASEPATH') or exit('No direct script access allowed');

class School extends MY_Controller
{

    public $data = array();


    function __construct()
    {
        parent::__construct();
        $this->load->model('School_Model', 'school', true);
        if ($this->session->userdata('role_id') != SUPER_ADMIN) {
            error($this->lang->line('permission_denied'));
            redirect('dashboard/index');
        }

        $this->data['fields'] = $this->school->get_table_fields('languages');
        $this->data['currencies'] = $this->db->get('currencies')->result_array();
        $this->data['themes'] = $this->school->get_list('themes', array(), '', '', '', 'id', 'ASC');
        $this->data['subscriptions'] = $this->school->get_subscription_list();
        $this->data['schools'] = $this->school->get_school_list();
    }


    public function index()
    {

        check_permission(VIEW);

        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_school') . ' | ' . SMS);
        $this->layout->view('school/index', $this->data);
    }

    public function add()
    {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_school_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_school_data();

                $insert_id = $this->school->insert('schools', $data);
                if ($insert_id) {
                    create_log('Has been created a school : ' . $data['school_name']);

                    success($this->lang->line('insert_success'));
                    redirect('administrator/school/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/school/add');
                }
            } else {
                error($this->lang->line('insert_failed'));
                $this->data['post'] = $_POST;
            }
        }

        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('school/index', $this->data);
    }

    public function edit($id = null)
    {

        check_permission(EDIT);

        if ($_POST) {
            $this->_prepare_school_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_school_data();
                $updated = $this->school->update('schools', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    create_log('Has been updated a school : ' . $data['school_name']);
                    success($this->lang->line('update_success'));
                    redirect('administrator/school/index');
                } else {

                    error($this->lang->line('update_failed'));
                    redirect('administrator/school/edit/' . $this->input->post('id'));
                }
            } else {
                error($this->lang->line('update_failed'));
                $this->data['school'] = $this->school->get_single('schools', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['school'] = $this->school->get_single('schools', array('id' => $id));
                $this->data['users'] = $this->school->get_single('users', array('school_id' => $id));

                if (!$this->data['school']) {
                    redirect('administrator/school/index');
                }
            }
        }

        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('school/index', $this->data);
    }

    public function get_single_school()
    {

        $school_id = $this->input->post('school_id');
        $this->data['school'] = $this->school->get_single_school($school_id);
        // print_r($this->data);
        echo $this->load->view('school/get-single-school', $this->data);
    }

    private function _prepare_school_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('school_name', $this->lang->line('school_name'), 'trim|required|callback_school_name');
        $this->form_validation->set_rules('school_url', $this->lang->line('school_url'), 'trim|required');
        $this->form_validation->set_rules('address', $this->lang->line('address'), 'trim|required');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|min_length[10]|max_length[12]');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');
        $this->form_validation->set_rules('currency', $this->lang->line('currency'), 'trim');
        $this->form_validation->set_rules('currency_symbol', $this->lang->line('currency_symbol'), 'trim|required');
        $this->form_validation->set_rules('language', $this->lang->line('language'), 'trim|required');
        $this->form_validation->set_rules('theme_name', $this->lang->line('theme'), 'trim|required');
        $this->form_validation->set_rules('footer', $this->lang->line('footer'), 'trim');
        $this->form_validation->set_rules('logo', $this->lang->line('admin_logo'), 'trim|callback_logo');
        $this->form_validation->set_rules('frontend_logo', $this->lang->line('frontend_logo'), 'trim|callback_frontend_logo');
        $this->form_validation->set_message('min_length[10]', 'Please Enter valid phone No.');
        $this->form_validation->set_message('max_length[12]', 'Please Enter valid phone No.');
    }

    public function school_name()
    {
        if ($this->input->post('id') == '') {
            $school = $this->school->duplicate_check($this->input->post('school_name'));
            if ($school) {
                $this->form_validation->set_message('school_name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $school = $this->school->duplicate_check($this->input->post('school_name'), $this->input->post('id'));
            if ($school) {
                $this->form_validation->set_message('school_name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    public function logo()
    {
        if ($_FILES['logo']['name']) {

            list($width, $height) = getimagesize($_FILES['logo']['tmp_name']);
            if ((!empty($width)) && $width > 100 || $height > 110) {
                $this->form_validation->set_message('logo', $this->lang->line('please_check_image_dimension'));
                return FALSE;
            }

            $name = $_FILES['logo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('logo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }

    public function frontend_logo()
    {
        if ($_FILES['frontend_logo']['name']) {


            list($width, $height) = getimagesize($_FILES['frontend_logo']['tmp_name']);
            if ((!empty($width)) && $width > 150 || $height > 90) {
                $this->form_validation->set_message('frontend_logo', $this->lang->line('please_check_image_dimension'));
                return FALSE;
            }

            $name = $_FILES['frontend_logo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('frontend_logo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }

    private function _get_posted_school_data()
    {

        $items = array();

        $items[] = 'school_url';
        $items[] = 'school_code';
        $items[] = 'school_name';
        $items[] = 'address';
        $items[] = 'phone';
        $items[] = 'email';
        $items[] = 'currency';
        $items[] = 'currency_symbol';
        $items[] = 'school_fax';
        $items[] = 'zoom_api_key';
        $items[] = 'zoom_secret';
        $items[] = 'enable_frontend';
        $items[] = 'final_result_type';
        $items[] = 'registration_date';
        $items[] = 'footer';
        $items[] = 'google_map';
        $items[] = 'theme_name';
        $items[] = 'language';
        $items[] = 'enable_online_admission';
        $items[] = 'enable_rtl';
        $items[] = 'facebook_url';
        $items[] = 'twitter_url';
        $items[] = 'linkedin_url';
        $items[] = 'youtube_url';
        $items[] = 'instagram_url';
        $items[] = 'pinterest_url';

        $data = elements($items, $_POST);

        if ($this->input->post('id')) {
            $data['status'] = $this->input->post('status');
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {

            $data['about_text'] = 'Lorem ipsum dolor sit amet, consecte- tur adipisicing elit, We create Premium WordPress themes & plugins for more than three years. ';
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }

        if ($_FILES['logo']['name']) {
            $data['logo'] = $this->_upload_logo();
        }
        if ($_FILES['frontend_logo']['name']) {
            $data['frontend_logo'] = $this->_upload_frontend_logo();
        }

        return $data;
    }
    private function _upload_logo()
    {

        $prevoius_logo = @$_POST['logo_prev'];
        $logo_name = $_FILES['logo']['name'];
        $logo_type = $_FILES['logo']['type'];
        $logo = '';


        if ($logo_name != "") {
            if (
                $logo_type == 'image/jpeg' || $logo_type == 'image/pjpeg' ||
                $logo_type == 'image/jpg' || $logo_type == 'image/png' ||
                $logo_type == 'image/x-png' || $logo_type == 'image/gif'
            ) {

                $destination = 'assets/uploads/logo/';

                $file_type = explode(".", $logo_name);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $logo_path = time() . '-school-admin-logo.' . $extension;

                copy($_FILES['logo']['tmp_name'], $destination . $logo_path);

                if ($prevoius_logo != "") {
                    // need to unlink previous image
                    if (file_exists($destination . $prevoius_logo)) {
                        @unlink($destination . $prevoius_logo);
                    }
                }

                $logo = $logo_path;
            }
        } else {
            $logo = $prevoius_logo;
        }

        return $logo;
    }

    private function _upload_frontend_logo()
    {

        $prevoius_logo = @$_POST['frontend_logo_prev'];
        $logo_name = $_FILES['frontend_logo']['name'];
        $logo_type = $_FILES['frontend_logo']['type'];
        $logo = '';


        if ($logo_name != "") {
            if (
                $logo_type == 'image/jpeg' || $logo_type == 'image/pjpeg' ||
                $logo_type == 'image/jpg' || $logo_type == 'image/png' ||
                $logo_type == 'image/x-png' || $logo_type == 'image/gif'
            ) {

                $destination = 'assets/uploads/logo/';

                $file_type = explode(".", $logo_name);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $logo_path = time() . '-school-front-logo.' . $extension;

                copy($_FILES['frontend_logo']['tmp_name'], $destination . $logo_path);

                if ($prevoius_logo != "") {
                    // need to unlink previous image
                    if (file_exists($destination . $prevoius_logo)) {
                        @unlink($destination . $prevoius_logo);
                    }
                }

                $logo = $logo_path;
            }
        } else {
            $logo = $prevoius_logo;
        }

        return $logo;
    }

    public function update_subscription_status()
    {

        $school_id = $this->input->post('school_id');
        $subscription_id     = $this->input->post('subscription_id');

        $exist = $this->school->get_single('schools', array('subscription_id' => $subscription_id));

        if (empty($exist)) {
            echo $this->school->update('schools', array('modified_at' => date('Y-m-d H:i:s'), 'subscription_id' => $subscription_id), array('id' => $school_id));
        } else {
            echo FALSE;
        }
    }

    public function delete($id = null)
    {

        check_permission(DELETE);
        if (!is_numeric($id)) {
            error($this->lang->line('unexpected_error'));
            redirect('administrator/school/index');
        }

        // need to find all child data from database 
        $skips = array(
            'global_setting', 'gmsms_sessions', 'languages', 'modules', 'operations', 'privileges', 'purchase',
            'roles', 'schools', 'system_admin', 'themes', 'replies',
            'saas_faqs', 'saas_plans', 'saas_settings', 'saas_sliders', 'saas_subscriptions', 'currencies'
        );
        $tables = $this->db->list_tables();

        foreach ($tables as $table) {

            if (in_array($table, $skips)) {
                continue;
            }

            // $child_exist =$this->school->get_list($table, array('school_id'=>$id), '','', '', 'id', 'ASC');
            $child_exist = $this->school->delete($table, array('school_id' => $id));
            /*if(!empty($child_exist)){
                 error($this->lang->line('pls_remove_child_data'));
                 redirect('administrator/school/index');
            }*/
        }


        $school = $this->school->get_single('schools', array('id' => $id));

        if ($this->school->delete('schools', array('id' => $id))) {

            // delete syllabus file
            $destination = 'assets/uploads/logo/';
            if (file_exists($destination . $school->frontend_logo)) {
                @unlink($destination . $school->frontend_logo);
            }
            if (file_exists($destination . $school->logo)) {
                @unlink($destination . $school->logo);
            }

            create_log('Has been deleted a school : ' . $school->school_name);
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('administrator/school/index');
    }

    public function currency_symbol()
    {
        $currency = $this->input->post('currency');
        $currency_symbol = $this->db->get_where('currencies', ['code' => $currency]);
        echo $currency_symbol->row()->symbol;
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ajax extends My_Controller
{

    function __construct()
    {

        parent::__construct();
        $this->load->model('Ajax_Model', 'ajax', true);
    }
    public function get_user_by_role()
    {

        $role_id = $this->input->post('role_id');
        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $user_id = $this->input->post('user_id');
        $message = $this->input->post('message');

        $school = $this->ajax->get_school_by_id($school_id);

        $users = array();
        if ($role_id == SUPER_ADMIN) {
            $users = $this->ajax->get_list('system_admin', array('status' => 1), '', '', '', 'id', 'ASC');
        } elseif ($role_id == TEACHER) {
            $users = $this->ajax->get_list('teachers', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');
        } elseif ($role_id == GUARDIAN) {
            $users = $this->ajax->get_list('guardians', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');
        } elseif ($role_id == STUDENT) {

            if ($class_id) {
                $users = $this->ajax->get_student_list($class_id, $school_id, $school->academic_year_id);
            } else {
                $users = $this->ajax->get_list('students', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');
            }
        } else {

            $this->db->select('E.*');
            $this->db->from('employees AS E');
            $this->db->join('users AS U', 'U.id = E.user_id', 'left');
            $this->db->where('U.role_id', $role_id);
            $this->db->where('E.school_id', $school_id);
            $users = $this->db->get()->result();
        }

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if (!$message && !empty($users)) {
            $str .= '<option value="0">' . $this->lang->line('all') . '</option>';
        }

        $select = 'selected="selected"';
        if (!empty($users)) {
            foreach ($users as $obj) {

                //if(logged_in_user_id() == $obj->user_id){continue;}

                $selected = $user_id == $obj->user_id ? $select : '';
                $str .= '<option value="' . $obj->user_id . '" ' . $selected . '>' . $obj->name . '(' . $obj->id . ')</option>';
            }
        }

        echo $str;
    }
    public function get_tag_by_role()
    {

        $role_id = $this->input->post('role_id');
        $tags = get_template_tags($role_id);
        $str = '';
        foreach ($tags as $value) {
            $str .= $value . ' ';
        }

        echo $str;
    }
    public function update_user_status()
    {

        $user_id = $this->input->post('user_id');
        $status = $this->input->post('status');
        if ($this->ajax->update('users', array('status' => $status), array('id' => $user_id))) {
            echo TRUE;
        } else {
            echo FALSE;
        }
    }
    public function get_student_by_class()
    {

        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $student_id = $this->input->post('student_id');
        $is_bulk = $this->input->post('is_bulk');

        $school = $this->ajax->get_school_by_id($school_id);
        $students = $this->ajax->get_student_list($class_id, $school_id, $school->academic_year_id);

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if ($is_bulk) {
            $str .= '<option value="all">' . $this->lang->line('all') . '</option>';
        }

        $select = 'selected="selected"';
        if (!empty($students)) {
            foreach ($students as $obj) {
                $selected = $student_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . ' [' . $obj->roll_no . ']</option>';
            }
        }

        echo $str;
    }
    public function get_section_by_class()
    {

        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        $sections = $this->ajax->get_list('sections', array('status' => 1, 'school_id' => $school_id, 'class_id' => $class_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';

        $guardian_section_data = get_guardian_access_data('section');

        $select = 'selected="selected"';
        if (!empty($sections)) {
            foreach ($sections as $obj) {

                if ($this->session->userdata('role_id') == GUARDIAN && !in_array($obj->id, $guardian_section_data)) {
                    continue;
                } elseif ($this->session->userdata('role_id') == TEACHER && $obj->teacher_id != $this->session->userdata('profile_id')) {
                    continue;
                }

                $selected = $section_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
            }
        }

        echo $str;
    }

    public function get_teacher_by_subject()
    {
        $subject_id = $this->input->post('subject_id');

        $subjects = $this->ajax->get_single('subjects', array('id' => $subject_id));
        echo $subjects->teacher_id;
    }

    public function get_department_by_teacher()
    {

        $teacher_id = $this->input->post('teacher_id');
        $department_id =  $this->input->post('department_id');

        $teachers = $this->ajax->get_single('teachers', array('id' => $teacher_id));
        $departments_id = explode(',', $teachers->department_id);
        $departments = $this->db->where_in('id', $departments_id)->get('departments')->result();

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';

        $select = 'selected="selected"';
        if (!empty($departments)) {
            foreach ($departments as $obj) {
                $selected = $department_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
            }
        }

        echo $str;
    }
    public function get_student_by_section()
    {

        $student_id = $this->input->post('student_id');
        $section_id = $this->input->post('section_id');
        $school_id = $this->input->post('school_id');
        $is_all = $this->input->post('is_all');

        $students = $this->ajax->get_student_list_by_section($school_id, $section_id, 'regular');

        if ($is_all) {
            $str = '<option value="0">' . $this->lang->line('all_student') . '</option>';
        } else {
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        }

        $select = 'selected="selected"';
        if (!empty($students)) {
            foreach ($students as $obj) {
                $selected = $student_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . ' [' . $obj->roll_no . ']</option>';
            }
        }

        echo $str;
    }
    public function get_subject_by_class()
    {

        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $subject_id = $this->input->post('subject_id');

        if ($this->session->userdata('role_id') == TEACHER) {
            $subjects = $this->ajax->get_list('subjects', array('status' => 1, 'class_id' => $class_id, 'school_id' => $school_id,  'teacher_id' => $this->session->userdata('profile_id')), '', '', '', 'id', 'ASC');
        } else {
            $subjects = $this->ajax->get_list('subjects', array('status' => 1, 'class_id' => $class_id, 'school_id' => $school_id), '', '', '', 'id', 'ASC');
        }

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';

        $select = 'selected="selected"';
        if (!empty($subjects)) {
            foreach ($subjects as $obj) {
                $selected = $subject_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
            }
        }

        echo $str;
    }
    public function get_assignment_by_subject()
    {

        $subject_id = $this->input->post('subject_id');
        echo $assignment_id = $this->input->post('assignment_id');

        $assignments = $this->ajax->get_list('assignments', array('status' => 1, 'subject_id' => $subject_id, 'academic_year_id' => $this->academic_year_id), '', '', '', 'id', 'ASC');
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($assignments)) {
            foreach ($assignments as $obj) {
                $selected = $assignment_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
            }
        }

        echo $str;
    }

    public function get_guardian_by_id()
    {

        header('Content-Type: application/json');
        $guardian_id = $this->input->post('guardian_id');

        $guardian = $this->ajax->get_single('guardians', array('id' => $guardian_id));
        echo json_encode($guardian);
        die();
    }

    public function get_room_by_hostel()
    {

        $hostel_id = $this->input->post('hostel_id');

        $hostels = $this->ajax->get_list('rooms', array('status' => 1, 'hostel_id' => $hostel_id), '', '', '', 'id', 'ASC');
        $str = '<option value="">--.' . $this->lang->line('select_room_no') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($hostels)) {
            foreach ($hostels as $obj) {
                $selected = $subject_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->room_no . ' [' . $this->lang->line($obj->room_type) . '] [ ' . $obj->cost . ' ]</option>';
            }
        }

        echo $str;
    }

    public function get_user_list_by_type()
    {

        $school_id  = $this->input->post('school_id');
        $payment_to  = $this->input->post('payment_to');
        $user_id  = $this->input->post('user_id');

        $users = $this->ajax->get_user_list($school_id, $payment_to);

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($users)) {
            foreach ($users as $obj) {
                $selected = $user_id == $obj->user_id ? $select : '';
                $str .= '<option value="' . $obj->user_id . '" ' . $selected . '>' . $obj->name . ' [ ' . $obj->designation . ' ]</option>';
            }
        }

        echo $str;
    }

    public function get_designation_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $designation_id  = $this->input->post('designation_id');

        $designations = $this->ajax->get_list('designations', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($designations)) {
            foreach ($designations as $obj) {

                $selected = $designation_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . ' </option>';
            }
        }

        echo $str;
    }

    public function get_salary_grade_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $salary_grade_id  = $this->input->post('salary_grade_id');

        $salary_grades = $this->ajax->get_list('salary_grades', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($salary_grades)) {
            foreach ($salary_grades as $obj) {

                $selected = $salary_grade_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->grade_name . ' </option>';
            }
        }

        echo $str;
    }

    public function get_teacher_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $teacher_id  = $this->input->post('teacher_id');
        $is_all  = $this->input->post('is_all');

        $teachers = $this->ajax->get_list('teachers', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        if ($is_all) {
            $str = '<option value="0">' . $this->lang->line('all_teacher') . '</option>';
        } else {
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        }

        $select = 'selected="selected"';
        if (!empty($teachers)) {
            foreach ($teachers as $obj) {

                $selected = $teacher_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
            }
        }

        echo $str;
    }

    public function get_teacher_by_department()
    {

        $department_id  = $this->input->post('department_id');
        $teacher_id  = $this->input->post('teacher_id');
        $is_all  = $this->input->post('is_all');

        $teachers = $this->ajax->get_list('teachers', array('status' => 1), '', '', '', 'id', 'ASC');

        if ($is_all) {
            $str = '<option value="0">' . $this->lang->line('all_teacher') . '</option>';
        } else {
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        }

        $select = 'selected="selected"';
        if (!empty($teachers)) {
            foreach ($teachers as $obj) {
                $department = explode(',', $obj->department_id);
                if (in_array($department_id, $department)) {
                    $selected = $teacher_id == $obj->id ? $select : '';
                    $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
                }
            }
        }

        echo $str;
    }

    public function get_employee_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $employee_id  = $this->input->post('employee_id');
        $is_all  = $this->input->post('is_all');

        $employees = $this->ajax->get_list('employees', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        if ($is_all) {
            $str = '<option value="0">' . $this->lang->line('all_employee') . '</option>';
        } else {
            $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        }

        $select = 'selected="selected"';
        if (!empty($employees)) {
            foreach ($employees as $obj) {

                $selected = $employee_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
            }
        }

        echo $str;
    }

    public function get_guardian_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $guardian_id  = $this->input->post('guardian_id');

        $guardinas = $this->ajax->get_list('guardians', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($guardinas)) {
            foreach ($guardinas as $obj) {

                $selected = $guardian_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
            }
        }

        echo $str;
    }

    public function get_discount_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $discount_id  = $this->input->post('discount_id');

        $discounts = $this->ajax->get_list('discounts', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($discounts)) {
            foreach ($discounts as $obj) {

                $selected = $discount_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
            }
        }

        echo $str;
    }


    public function get_student_type_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $type_id  = $this->input->post('type_id');

        $types = $this->ajax->get_list('student_types', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($types)) {
            foreach ($types as $obj) {

                $selected = $type_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->type . '</option>';
            }
        }

        echo $str;
    }


    public function get_class_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $class_id  = $this->input->post('class_id');

        $classes = $this->ajax->get_list('classes', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($classes)) {
            foreach ($classes as $obj) {

                $selected = $class_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
            }
        }

        echo $str;
    }

    public function get_exam_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $exam_id  = $this->input->post('exam_id');

        $exams = $this->ajax->get_list('exams', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($exams)) {
            foreach ($exams as $obj) {

                $selected = $exam_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
            }
        }

        echo $str;
    }

    public function get_certificate_type_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $certificate_id  = $this->input->post('certificate_id');

        $certificates = $this->ajax->get_list('certificates', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($certificates)) {
            foreach ($certificates as $obj) {

                $selected = $certificate_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . '</option>';
            }
        }

        echo $str;
    }

    public function get_gallery_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $gallery_id  = $this->input->post('gallery_id');

        $galleries = $this->ajax->get_list('galleries', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($galleries)) {
            foreach ($galleries as $obj) {

                $selected = $gallery_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
            }
        }

        echo $str;
    }

    public function get_leave_type_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $role_id  = $this->input->post('role_id');
        $type_id  = $this->input->post('type_id');

        $types = $this->ajax->get_list('leave_types', array('status' => 1, 'school_id' => $school_id, 'role_id' => $role_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($types)) {
            foreach ($types as $obj) {

                $selected = $type_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->type . '</option>';
            }
        }

        echo $str;
    }

    public function get_leave_total_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $role_id  = $this->input->post('role_id');
        $class_id  = $this->input->post('class_id');
        $user_id  = $this->input->post('user_id');
        $type_id  = $this->input->post('type_id');
        $school = $this->ajax->get_school_by_id($school_id);
        $academic_year_id = $school->academic_year_id;

        // $types = $this->ajax->get_list('leave_applications', array('status' => 1, 'leave_status' => 2, 'school_id' => $school_id, 'role_id' => $role_id, 'academic_year_id' => $academic_year_id, 'user_id' => $user_id, 'class_id' => $class_id, 'type_id' => $type_id), '', '', '', 'id', 'ASC');
        $school = $this->ajax->get_school_by_id($this->input->post('school_id'));
        $used = get_total_used_leave($school->academic_year_id, $this->input->post('role_id'), $this->input->post('type_id'), $this->input->post('user_id'));
// echo $this->db->last_query();die;
        if ($used) {
            echo $used;
        } else {
            echo '0';
            // $leave = 0;
            // foreach ($types as $row) {
            //     $leave += $row->leave_day;
            // }
            // print_r($leave);
            // echo $types['leave_day'];
        }
    }


    public function get_visitor_purpose_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $purpose_id  = $this->input->post('purpose_id');

        $purposes = $this->ajax->get_list('visitor_purposes', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($purposes)) {
            foreach ($purposes as $obj) {

                $selected = $purpose_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->purpose . '</option>';
            }
        }

        echo $str;
    }


    public function get_complain_type_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $type_id  = $this->input->post('type_id');

        $types = $this->ajax->get_list('complain_types', array('status' => 1, 'school_id' => $school_id), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($types)) {
            foreach ($types as $obj) {

                $selected = $type_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->type . '</option>';
            }
        }

        echo $str;
    }

    public function get_user_single_payment()
    {

        $payment_to  = $this->input->post('payment_to');
        $user_id  = $this->input->post('user_id');
        $salary_month  = $this->input->post('salary_month');

        $exist = $this->ajax->get_single('salary_payments', array('user_id' => $user_id, 'salary_month' => $salary_month, 'payment_to' => $payment_to));

        if ($exist) {
            echo 1;
        } else {
            echo 2;
        }
    }

    public function get_school_info_by_id()
    {

        $school_id  = $this->input->post('school_id');

        $school = $this->ajax->get_single('schools', array('id' => $school_id));
        echo $school->final_result_type;
    }

    public function get_sms_gateways()
    {

        $school_id  = $this->input->post('school_id');

        $gateways = get_sms_gateways($school_id);

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if (!empty($gateways)) {
            foreach ($gateways as $key => $value) {

                $str .= '<option value="' . $key . '" >' . $value . '</option>';
            }
        }

        echo $str;
    }


    public function get_academic_year_by_school()
    {

        $school_id  = $this->input->post('school_id');
        $academic_year_id  = $this->input->post('academic_year_id');

        $academic_years = $this->ajax->get_list('academic_years', array('school_id' => $school_id, 'status' => 1), '', '', '', 'id', 'ASC');

        $str = '<option value="">--' . $this->lang->line('session_year') . '--</option>';
        $select = 'selected="selected"';
        $running = '';
        if (!empty($academic_years)) {
            foreach ($academic_years as $obj) {
                $running = $obj->is_running ? ' [' . $this->lang->line('running_year') . ']' : '';
                $selected = $academic_year_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->session_year . $running . '</option>';
            }
        }

        echo $str;
    }

    public function get_email_template_by_role()
    {

        $role_id = $this->input->post('role_id');
        $school_id = $this->input->post('school_id');

        $templates = $this->ajax->get_list('email_templates', array('status' => 1, 'role_id' => $role_id, 'school_id' => $school_id), '', '', '', 'id', 'ASC');
        $str = '<option value="">-- ' . $this->lang->line('template') . ' --</option>';
        if (!empty($templates)) {
            foreach ($templates as $obj) {
                $str .= '<option itemid="' . $obj->id . '" value="' . $obj->id . '">' . $obj->title . '</option>';
            }
        }

        echo $str;
    }

    public function get_email_template_by_id()
    {

        $template_id = $this->input->post('template_id');
        $school_id = $this->input->post('school_id');

        $template = $this->ajax->get_single('email_templates', array('status' => 1, 'id' => $template_id, 'school_id' => $school_id), '', '', '', 'id', 'ASC');
        if (!empty($template)) {
            echo $template->template;
        } else {
            echo FALSE;
        }
    }

    public function get_sms_template_by_role()
    {

        $role_id = $this->input->post('role_id');
        $school_id = $this->input->post('school_id');

        $templates = $this->ajax->get_list('sms_templates', array('status' => 1, 'role_id' => $role_id, 'school_id' => $school_id), '', '', '', 'id', 'ASC');
        $str = '<option value="">-- ' . $this->lang->line('template') . ' --</option>';
        if (!empty($templates)) {
            foreach ($templates as $obj) {
                $str .= '<option itemid="' . $obj->id . '" value="' . $obj->template . '">' . $obj->title . '</option>';
            }
        }

        echo $str;
    }

    public function get_current_session_by_school()
    {

        $current_session_id = $this->input->post('current_session_id');
        $school_id = $this->input->post('school_id');

        $school = $this->ajax->get_school_by_id($school_id);

        $curr_session = $this->ajax->get_list('academic_years', array('id' => $school->academic_year_id, 'school_id' => $school_id));
        $str = '<option value="">-- ' . $this->lang->line('select') . ' --</option>';
        $select = 'selected="selected"';

        if (!empty($curr_session)) {
            foreach ($curr_session as $obj) {
                $selected = $current_session_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->session_year . '</option>';
            }
        }

        echo $str;
    }

    public function get_next_session_by_school()
    {

        $academic_year_id = $this->input->post('academic_year_id');
        $school_id = $this->input->post('school_id');
        $school = $this->ajax->get_school_by_id($school_id);

        $next_session = $this->ajax->get_list('academic_years', array('id !=' => $school->academic_year_id, 'school_id' => $school_id));
        $str = '<option value="">-- ' . $this->lang->line('select') . ' --</option>';
        $select = 'selected="selected"';

        if (!empty($next_session)) {
            foreach ($next_session as $obj) {

                $selected = $academic_year_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->session_year . '</option>';
            }
        }

        echo $str;
    }

    public function get_lesson_by_subject()
    {

        $school_id = $this->input->post('school_id');
        $subject_id  = $this->input->post('subject_id');
        $today_topic  = $this->input->post('today_topic');

        $school = $this->ajax->get_school_by_id($school_id);
        $lessons = $this->ajax->get_lesson_by_subject($subject_id, @$school->academic_year_id);

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        $select = 'selected="selected"';
        if (!empty($lessons)) {
            foreach ($lessons as $obj) {

                $selected = $today_topic == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->title . '</option>';
            }
        }

        echo $str;
    }

    public function update_student_status_type()
    {

        $student_id = $this->input->post('student_id');
        $status     = $this->input->post('status');

        echo $this->ajax->update('students', array('modified_at' => date('Y-m-d H:i:s'), 'status_type' => $status), array('id' => $student_id));
    }

    public function get_roll()
    {
        $school_id = $this->session->userdata('school_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $group_id = $this->input->post('group_id');
        $query = $this->db->where(['school_id' => $school_id, 'class_id' => $class_id, 'section_id' => $section_id])->get('student_roll');
        // $last_students = $this->db->order_by('id', "desc")->where('school_id', $school_id)->limit(1)->get('students')->row();
        if ($query->num_rows() > 0) {
            $last_students = $this->db->where(['school_id' => $school_id, 'class_id' => $class_id, 'section_id' => $section_id])->order_by('id', "desc")->limit(1)->get('enrollments')->row();
            if (!empty($last_students)) {
                $last_id = sprintf('%04d', trim($last_students->roll_no, $query->row()->start_roll) + 1);
            } else {
                $last_id = '1';
            }
            echo $query->row()->start_roll . $last_id;
        } else {
            echo "error";
        }
    }

    public function get_leave_days_by_school()
    {
        $school_id = $this->input->post('school_id');
        $leave_from = date('Y-m-d', strtotime($this->input->post('start')));
        $leave_to = date('Y-m-d', strtotime($this->input->post('end')));

        $query = $this->db->where(['school_id' => $school_id])->get('holidays')->result();
        $holidays = [];
        foreach ($query as $obj) {
            $row['holiday_from'] = $obj->date_from;
            $row['holiday_to'] = $obj->date_to;
            $holidays[] = $row;
        }
        // _d($holidays);
        $leave_from_date = strtotime($leave_from);
        $leave_to_date = strtotime($leave_to);

        // Count the number of days between leave start and end date, excluding Sundays and holidays
        $days = 0;
        while ($leave_from_date <= $leave_to_date) {
            $dayOfWeek = date("w", $leave_from_date);
            if ($dayOfWeek != 0) { // 0 = Sunday
                $is_holiday = false;
                foreach ($holidays as $holiday) {
                    $holiday_from_date = strtotime($holiday['holiday_from']);
                    $holiday_to_date = strtotime($holiday['holiday_to']);
                    if (($leave_from_date >= $holiday_from_date) && ($leave_from_date <= $holiday_to_date)) {
                        $dayOfWeekHoliday = date("w", $holiday_from_date);
                        if ($dayOfWeekHoliday != 0) { // 0 = Sunday
                            $is_holiday = true;
                            break;
                        }
                    }
                }
                if (!$is_holiday) {
                    $days++;
                }
            }
            $leave_from_date = strtotime("+1 day", $leave_from_date);
        }
        echo $days;
    }
}

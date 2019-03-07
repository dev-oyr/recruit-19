<?php 
if(!preg_match("/".$_SERVER['HTTP_HOST']."/i",$_SERVER['HTTP_REFERER'])) exit('No direct access allowed');  

header('Content-Type: application/json');

class Dbctr extends CI_Controller {
    public function index() { echo "You can't direct access!"; exit; }

    public function main() { exit; }

    #모든 사용자 호출(options: {0: 승인되지 않음, 1: 승인됨, 2: 전체})
    public function get_all_users($options) {
        $this->load->database();

        $opt = intval($options);
        $where = $opt === 2 ? 1 : "approved = $opt";

        $query = $this->db->query("SELECT * FROM users WHERE $where;");
        $this->db->close();

        echo json_encode($query->result());
        return;
    }

    #지원서 제출
    public function send_apply() {
        $this->load->database();

        $form_data = array(
            'apply_name' => $_POST['apply-name'],
            'apply_birthday' => $_POST['apply-birth-year']."-".$_POST['apply-birth-month']."-".$_POST['apply-birth-day'],
            'apply_gender' => $_POST['apply-gender'],
            'phone_no' => str_replace('-', '', $_POST['apply-phone-no']),
            'apply_email' => $_POST['apply-email'],
            'apply_military' => $_POST['apply-military'],
            'apply_major' => $_POST['apply-major'],
            'apply_grade' => $_POST['apply-grade'],
            'apply_known' => $_POST['apply-known-data'],
            'apply_motivation' => $_POST['apply-motivation'],
            'proj_exp' => $_POST['apply-project-exp'],
            'proj_wish' => $_POST['apply-project-wish'],
            'my_promise' => $_POST['apply-my-promise']
        );

        $this->db->insert('applications', $form_data);
        $result = ($this->db->affected_rows() != 1) ? false : true;

        echo json_encode($result);
        return $result;
    }

    #지원서 중복 확인
    public function check_duplicate() {
        $this->load->database();

        $apply_name = $_POST['apply-name'];
        $apply_birthday = $_POST['apply-birth-year']."-".$_POST['apply-birth-month']."-".$_POST['apply-birth-day'];
        $apply_gender = $_POST['apply-gender'];
        $phone_no = str_replace('-', '', $_POST['apply-phone-no']);

        $query = $this->db->query("SELECT count(*) FROM applications WHERE apply_name = ? AND apply_birthday = ? AND apply_gender = ? AND phone_no = ?;", array($apply_name, $apply_birthday, (int) $apply_gender, $phone_no));
        $list_count = $query->row_array();

        echo json_encode($list_count['count(*)']);
        return $list_count['count(*)'];
    }

    #관리자 등록
    public function register_admin() {
        $this->load->database();

        $salt = "";

        $pw_raw = $_POST['admin-pw'];

        // Encrypt password
        $pw = password_hash($pw_raw, PASSWORD_BCRYPT);

        $register_data = array(
            'admin_id' => $_POST['admin-id'],
            'admin_pw' => $pw,
            'admin_salt' => $salt
        );

        $this->db->insert('admin', $register_data);
        $result = ($this->db->affected_rows() != 1) ? false : true;

        echo json_encode($result);
        return $result;
    }

    #관리자 중복 확인
    public function check_admin_duplication() {
        $this->load->database();

        $admin_id = $_POST['admin-id'];

        $query = $this->db->query("SELECT count(*) FROM admin WHERE admin_id = ?;", array($admin_id));
        $list_count = $query->row_array();

        echo json_encode($list_count['count(*)']);
        return $list_count['count(*)'];
    }

    #관리자 로그인
    public function login_admin() {
        $this->load->database();

        $id = $_POST['admin-id'];
        $pw_raw = $_POST['admin-pw'];

        $query = $this->db->query("SELECT * FROM admin WHERE admin_id = ?;", array($id));
        $result = $query->row();

        if($result !== NULL && password_verify($pw_raw, $result->admin_pw) && $id === $result->admin_id && intval($result->admin_approved)) {
            $this->session->set_userdata('authorized', true);
            echo json_encode(true);
        } else {
            $this->session->set_userdata('authorized', false);
            $this->session->set_flashdata('message', '관리자 로그인에 실패함.');
            echo json_encode(false);
        }

        return true;
    }

    #지원자 정보(options: {0: 시간순, 1: 이름순}, sorting: {0: 오름차순, 1: 내림차순})
    public function get_applications($options, $sorting) {
        $this->load->database();

        $order = $options === '0' ? 'apply_date' : 'apply_name';
        $direction = $sorting === '0' ? 'ASC' : 'DESC';

        $query = $this->db->query("SELECT * FROM applications WHERE 1 ORDER BY $order $direction");
        $result = $query->result();
        
        echo json_encode($result);
        return $result;
    }

    #선발된 지원자인지 확인
    public function check_applicant_selected($apply_name, $apply_major, $phone_no) {
        $this->load->database();

        $name = urldecode($apply_name);
        $major = urldecode($apply_major);
        $phone = urldecode($phone_no);

        $query = $this->db->query("SELECT selected FROM applications WHERE apply_name = ? AND apply_major = ? AND phone_no = ?;", array($name, $major, $phone));
        $list_count = $query->row_array();

        echo json_encode($list_count['selected']);
        return $list_count['selected'];
    }

    #지원자 선발하기
    public function select_applicant($apply_name, $apply_birthday, $phone_no, $apply_gender, $apply_major) {
        $this->load->database();

        $name = urldecode($apply_name);
        $birthday = urldecode($apply_birthday);
        $phone = urldecode($phone_no);
        $gender = urldecode($apply_gender);
        $major = urldecode($apply_major);

        $this->db->query("UPDATE applications SET selected=? WHERE apply_name=? AND apply_birthday=? AND phone_no=? AND apply_gender=? AND apply_major=?", array((int) 1, $name, $birthday, $phone, $gender, $major));
        
        $result = ($this->db->affected_rows() != 1) ? false : true;

        echo json_encode($result);
        return $result;
    }

    #지원자 선발 취소하기
    public function unselect_applicant($apply_name, $apply_birthday, $phone_no, $apply_gender, $apply_major) {
        $this->load->database();

        $name = urldecode($apply_name);
        $birthday = urldecode($apply_birthday);
        $phone = urldecode($phone_no);
        $gender = urldecode($apply_gender);
        $major = urldecode($apply_major);

        $this->db->query("UPDATE applications SET selected=? WHERE apply_name=? AND apply_birthday=? AND phone_no=? AND apply_gender=? AND apply_major=?", array((int) 0, $name, $birthday, $phone, $gender, $major));

        $result = ($this->db->affected_rows() != 1) ? false : true;

        echo json_encode($result);
        return $result;
    }
}
?>
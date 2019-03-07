<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Description: PHP4 Generated Data" );

class Excel extends CI_Controller {
    public function index() { echo "You can't direct access!"; exit; }

    public function main() { exit; }

    public function download($name, $major, $date) {
        //header( "Content-type: text/html" ); 
        
        # 세션 검증
        if(!$this->session->userdata('authorized')){
            echo '세션이 만료되었습니다. 다시 로그인 해주세요.';
            exit;
        }

        $this->load->database();

        $query = $this->db->query("SELECT * FROM applications WHERE apply_name = ? AND apply_major = ? AND apply_date = ?", array(urldecode($name), urldecode($major), urldecode($date)));
        $result = $query->row();

        $gender = $result->apply_gender === '0' ? '남' : '여';
        $military = $result->apply_military === '0' ? '미필' : '군필(면제 포함) 또는 해당없음';

        $_EXCEL = "
        <table>
            <tr>
                <td colspan='4' rowspan='4' style='text-align:center;font-size:16pt;'>지원서</td>
            </tr>
        </table>
        <table border='1' bordercolor='#bebebe'>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;'>이름</td>
                <td name='apply_name' colspan='3'>$result->apply_name</td>
            </tr>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;'>생년월일</td>
                <td name='apply_birthday' style='mso-number-format:\@'>$result->apply_birthday</td>
                <td style='font-weight: 600;background: #dbdbdb;'>성별</td>
                <td name='apply_gender'>$gender</td>
            </tr>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;'>연락처</td>
                <td name='phone_no' style='mso-number-format:\@'>$result->phone_no</td>
                <td style='font-weight: 600;background: #dbdbdb;'>이메일</td>
                <td name='apply_email'>$result->apply_email</td>
            </tr>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;'>군필여부</td>
                <td name='apply_military' colspan='3'>$military</td>
            </tr>
        </table>
        <br>
        <table table border='1' bordercolor='#bebebe'>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;' rowspan='2'>학사정보</td>
                <td style='font-weight: 600;background: #dbdbdb;' colspan='2'>학과</td>
                <td style='font-weight: 600;background: #dbdbdb;'>현재 학년</td>
            </tr>
            <tr style='font-size:11pt'>
                <td name='apply_major' colspan='2'>$result->apply_major</td>
                <td name='apply_grade'>$result->apply_grade</td>
            </tr>
        </table>
        <br>
        <table table border='1' bordercolor='#bebebe'>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;'>유입경로</td>
                <td name='apply_known' colspan='3'>$result->apply_known</td>
            </tr>
        </table>
        <br>
        <table table border='1' bordercolor='#bebebe'>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;'>지원동기</td>
                <td name='apply_motivation' colspan='3'>$result->apply_motivation</td>
            </tr>
        </table>
        <br>
        <table table border='1' bordercolor='#bebebe'>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;'>프로젝트 경험</td>
                <td name='proj_exp' colspan='3'>$result->proj_exp</td>
            </tr>
        </table>
        <br>
        <table table border='1' bordercolor='#bebebe'>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;'>제작하고 싶은 웹서비스</td>
                <td name='proj_wish' colspan='3'>$result->proj_wish</td>
            </tr>
        </table>
        <br>
        <table table border='1' bordercolor='#bebebe'>
            <tr style='font-size:11pt'>
                <td style='font-weight: 600;background: #dbdbdb;'>앞으로의 각오</td>
                <td name='my_promise'colspan='3'>$result->my_promise</td>
            </tr>
        </table>
        ";

        header("Content-Disposition: attachment; filename = ".urldecode($name)."_".urldecode($major)."_".urldecode($date).".xls"); 
        // echo "<meta http-equiv='Content-Type' content='text/html; charset=euc-kr'> ";
        echo "<meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\">";  
        echo $_EXCEL;
    }
}
?>
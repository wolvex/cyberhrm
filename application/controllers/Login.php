<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Login extends MY_Controller {  
        
        public function index() {
            if (isset($this->session->userdata['profile'])) {
                header("Location: ".base_url('dashboard'));
                die();
            } else {
                //$this->load->view('login');
                layout('login', array());
            }
        }  
        
        public function process() {
            //$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');  
            
            if (!isset($_POST['username']) || !isset($_POST['password']))
                json_error('Username/password tidak terkirim');

            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $hashpass = hash("sha256", $username."-".$password);

            $sql = "select a.*,b.module,b.action from users a,permissions b WHERE a.role = b.role and 
                a.id = ? and a.password = ?";
            $query = $this->db->query($sql, array($username, $hashpass));
            
            $profile = array();
            $modules = array();
                    
            $rows = 0;
            foreach ($query->result_array() as $rec) {
                if ($rows == 0) {
                    $profile['user_id']   = $rec["ID"];
                    $profile['user_name'] = $rec["NAME"];
                    $profile['user_role'] = $rec["ROLE"];
                }
                $modules[$rec["MODULE"]] = $rec["ACTION"];
                $rows++;
            }

            if ($rows == 0) json_error('Username/password salah atau tidak terdaftar');

            $this->session->set_userdata('profile', $profile);
            $this->session->set_userdata('modules', $modules);

            json_success();
        }

        public function logout() {  
            //removing session  
            $this->session->unset_userdata('profile');
            session_destroy();

            json_success();
        }  
      
    }  
?>
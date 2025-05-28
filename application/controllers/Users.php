<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Users extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('users', array());
        }  
		
		public function query() {
			//if (!isGranted()) json_forbidden();
			$users = array();
			if (isAdminMode()) {
				$query = $this->db->query("select * from users where password <> '***REVOKED***' order by id");
				$users = $query->result_array();
			} else {
				$query = $this->db->query("select * from users where password <> '***REVOKED***' and id = ?", $_SESSION['profile']['user_id']);
				$users = $query->result_array();
			}
			
			$data = array(); $i = 0;
			foreach ($users as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editUsers('."'".$rec['ID']."'".')"></i>';
				$rec['PASSWORD'] = "dummy";
				$data[] = $rec; $i++;
			}
			
			$output = array(
				"page" => 1,
                "total" => $i,
				"records" => $i,
				"rows" => $data
			);
			echo json_encode($output);
		}
		
		public function get($id) {
			if (!isAdminMode()) $id = $_SESSION['profile']['user_id'];

			$query = $this->db->query("select * from users a where id = ?", $id);
			$rec = $query->row_array();
			if (!isset($rec)) json_notfound();

			$rec['PASSWORD'] = "";
			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['ID'] == '') 		json_error('ID user harus diisi');
			if ($_POST['NAME'] == '') 		json_error('Nama user harus diisi');
			if ($_POST['ROLE'] == '') 		json_error('Role user harus dipilih');
			//if ($_POST['PASSWORD'] == '') 	json_error('Password user harus diisi');
			if ($_POST['EMAIL'] == '') 		json_error('Email user harus diisi');
		}
		
		public function create() {
			if (!isAdminMode()) json_forbidden();

			$this->validate();
			
			$_POST['ID'] = strtolower($_POST['ID']);
			$_POST['PASSWORD'] = hash("sha256", $_POST['ID']."-".$_POST['PASSWORD']);

			insertRecord('USERS', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			$id = $_SESSION['profile']['user_id'];

			if ($_POST['ID'] !=  $id && !isAdminMode()) json_forbidden();
			
			if (array_nvl($_POST, 'PASSWORD', '') != '') {
				$_POST['PASSWORD'] = hash("sha256", $_POST['ID']."-".$_POST['PASSWORD']);
			} else {
				unset($_POST['PASSWORD']);
			}
			
			updateRecord('USERS', $_POST, array('ID'));

			json_success();
        }
	  
		public function reset() {
			if (!isAdminMode()) json_forbidden();
			
			$query = $this->db->query("select nvl(email,'-') email from users where id = ?", $_POST['ID']);
			if ($query->num_rows() == 0) json_notfound();

			$user = $query->row_array();
			if ($user['EMAIL'] == '-') json_error("Pengguna tidak memiliki email yang terdaftar");
			
			$password = uniqid();
			$_POST['PASSWORD'] = hash("sha256", $_POST['ID']."-".$password);
			updateRecord('USERS', $_POST, array('ID'));

			$content = "Password Anda di ".base_url()." sudah di-reset menjadi:\r\n".
				"User ID: ".$_POST['ID']."\r\n".
				"Password: ".$password."\r\n";
			
			$this->sendMail($user['EMAIL'], 'Reset Password', $content);

			json_success();
		}

        public function delete() {  
			if (!isAdminMode()) json_forbidden();

			$_POST['PASSWORD'] = '***REVOKED***';
			updateRecord('USERS', $_POST, array('ID'));

			json_success();
		}
    }  
?>
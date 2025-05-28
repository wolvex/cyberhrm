<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Employee extends MY_Controller {  
          
        public function index() {  
			validateSession();

			show_error('Page Not Found', 404);
        }
		
		public function view($id) {
			if (!isGranted()) {
				show_error('Forbidden', 403);
				return;
			}
			layout('employee', array("ID" => $id));
		}

		public function get($id) {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select t.*,
				to_char(joined_at,'YYYY-MM-DD') joined_at,
				to_char(borned_at,'YYYY-MM-DD') borned_at,
				to_char(resigned_at,'YYYY-MM-DD') resigned_at
				from employee t where id = ".$id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['CODE'] == '') json_error('Kode pegawai harus diisi');
			if ($_POST['NAME'] == '') json_error('Nama pegawai harus diisi');
		}

		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			$keys = insertRecord('EMPLOYEE', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => $keys['ID']));
			json_success($keys);
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('EMPLOYEE', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => '0'));
			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('EMPLOYEE', $_POST, array('ID'));

			json_success();
        }
    }  
?>
<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Department extends MY_Controller {  
          
        public function index() {  
            validateSession();
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query('select * from dept where type = 1');
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editDept('."'".$rec['ID']."'".')"></i>';
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
			if (!isGranted()) json_forbidden();

			$query = $this->db->query('select * from dept where id = '.$id);
			$rec = $query->row_array();
			if (!isset($rec)) json_notfound();

			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['CODE'] == '') 	json_error('Kode harus diisi');
			if ($_POST['NAME'] == '') 	json_error('Nama harus diisi');
			if ($_POST['PFX'] == '') 	json_error('Kode dokumen harus diisi');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();

			$_POST['TYPE'] = '1';
			insertRecord('DEPT', $_POST, array('ID'));
			
			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('DEPT', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('DEPT', $_POST, array('ID'));

			json_success();
        }
    }  
?>
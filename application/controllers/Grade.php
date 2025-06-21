<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Grade extends MY_Controller {  
          
        public function index() {  
            validateSession();
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query('select * from grade');
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editGrade('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query('select * from grade where id = '.$id);
			$rec = $query->row_array();
			if (!isset($rec)) json_notfound();

			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['CODE'] == '') json_error('Kode harus diisi');
			if ($_POST['NAME'] == '') json_error('Nama harus diisi');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('GRADE',$_POST,array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('GRADE',$_POST,array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('GRADE',$_POST,array('ID'));

			json_success();
        }

		/**
		public function benefit($id = 0) {
			if (!isGranted()) json_forbidden();

			if ($id == 0) {

				$query = $this->db->query('select * from grade');
				$data = array(); $i = 0;
				foreach ($query->result_array() as $rec) {
					$rec['CMD'] = '<i class="edit icon" onclick="editGrade('."'".$rec['ID']."'".')"></i>';
					$data[] = $rec; $i++;
				}
				
				$output = array(
					"page" => 1,
					"total" => $i,
					"records" => $i,
					"rows" => $data
				);
				echo json_encode($output);

			} else {

				$query = $this->db->query('select * from grade where id = ?', $id);
				$rec = $query->row_array();
				$data = array();
				if (isset($rec)) {
					$data['status'] = TRUE;
					$data['grade'] = $rec;
				} else {
					$data['status'] = FALSE;
					$data['error'] = "Data tidak ditemukan";
				}
				echo json_encode($data);

			}
		}*/
	}  
?>  

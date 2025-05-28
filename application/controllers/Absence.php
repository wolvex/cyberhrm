<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Absence extends MY_Controller {  
          
        public function index() {  
            validateSession();
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select id,code,description,
				decode(type,0,'Ijin/Cuti','Lembur') type from absence order by type,code");
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editAbsence('."'".$rec['ID']."'".')"></i>';
				$data[] = $rec; $i++;
			}
			
			echo json_encode(array(
				"page" => 1,
                "total" => $i,
				"records" => $i,
				"rows" => $data
			));
		}
		
		public function get($id) {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select * from absence a where id = ?", $id);
			$rec = $query->row_array();
			if (!isset($rec)) json_notfound();

			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['CODE'] == '') 			json_error('Kode harus diisi');
			if ($_POST['DESCRIPTION'] == '') 	json_error('Deskripsi harus diisi');
			if ($_POST['TYPE'] == '') 			json_error('Jenis harus dipilih');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('ABSENCE', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('ABSENCE', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('ABSENCE', $_POST, array('ID'));

			json_success();
		}
		

    }  
?>
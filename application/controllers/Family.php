<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Family extends MY_Controller {  
          
        public function index() {  
			validateSession();
		}
		
		public function query($id) {
			if (!isGranted()) json_forbidden();

			$sql = "select a.*,decode(relation_type,'S','Pasangan','Anak') relation_name,
				to_char(a.borned_at,'DD-Mon-YYYY') borned_at,
				decode(status,'A','Ya','Tidak') status from family a
				where a.emp_id = ? order by a.borned_at";
			
			$query = $this->db->query($sql, $id);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editFamily('."'".$rec['ID']."'".')"></i>';
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
			
			$sql = "select a.*,to_char(a.borned_at,'YYYY-MM-DD') borned_at 
				from family a where a.id = ?";
			
			$query = $this->db->query($sql, $id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['EMP_ID'] == '') 		json_error('Pilih pegawai ybs');
			if ($_POST['NAME'] == '') 			json_error('Masukkan nama');
			if ($_POST['BORNED_AT'] == '') 		json_error('Masukkan tanggal lahir');
			if ($_POST['RELATION_TYPE'] == '') 	json_error('Pilih jenis hubungan keluarga');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();

			if ($_POST['STATUS'] == '') $_POST['STATUS'] = 'A';
			
			$keys = insertRecord('FAMILY', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => $keys['ID']));
			json_success($keys);
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('FAMILY', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => $_POST['ID']));
			json_success(array('ID' => $_POST['ID']));
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('FAMILY', $_POST, array('ID'));

			json_success();
        }
    }  
?>
<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Study extends MY_Controller {  
          
        public function index() {  
			validateSession();
		}
		
		public function query($id) {
			if (!isGranted()) json_forbidden();

			$sql = "select a.*,b.description,
				decode(a.study_type,'F','Formal','Non-Formal') type_name,
				to_char(a.enrolled_at,'DD-Mon-YYYY') enrolled_at,to_char(a.graduated_at,'DD-Mon-YYYY') graduated_at
				from study a,certificate b
				where a.certificate_id = b.id and a.emp_id = ?
				order by a.graduated_at desc";
			
			$query = $this->db->query($sql, $id);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editStudy('."'".$rec['ID']."'".')"></i>';
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
			
			$sql = "select a.*,b.description,
				to_char(a.enrolled_at,'YYYY-MM-DD') enrolled_at,to_char(a.graduated_at,'YYYY-MM-DD') graduated_at
				from study a,certificate b
				where a.certificate_id = b.id and a.id = ?";
			
			$query = $this->db->query($sql, $id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['EMP_ID'] == '')  		json_error('Pilih pegawai ybs');
			if ($_POST['CERTIFICATE_ID'] == '') json_error('Pilih jenis sertifikasi');
			if ($_POST['INSTITUTE'] == '') 		json_error('Masukkan nama institusi');
			if ($_POST['GRADUATED_AT'] == '') 	json_error('Masukkan tanggal kelulusan');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			$keys = insertRecord('STUDY', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => $keys['ID']));
			json_success($keys);
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('STUDY', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => $_POST['ID']));
			json_success(array('ID' => $_POST['ID']));
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('STUDY', $_POST, array('ID'));

			json_success();
        }
    }  
?>
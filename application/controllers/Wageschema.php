<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Wageschema extends MY_Controller {  
          
        public function index() {  
			validateSession();
		}
		
		public function query($id) {
			if (!isGranted()) json_forbidden();
			
			$query = $this->db->query("select a.*,to_char(a.effective_at,'DD-Mon-YYYY') effective_at,
				b.name schema_name from empschema a,schema b 
				where b.status <> 'I' and a.schema_id = b.id and a.emp_id = ? 
				order by a.effective_at desc, a.id desc", $id);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editSchema('."'".$rec['ID']."'".')"></i>';
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
			
			$query = $this->db->query("select a.*,c.name||' ('||c.code||')' emp_name,
				to_char(a.effective_at,'YYYY-MM-DD') effective_at,
				b.name schema_name from empschema a,schema b,employee c
				where b.status <> 'I' and a.schema_id = b.id and a.emp_id = c.id and a.id = ?", $id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}

		private function validate() {
			if ($_POST['SCHEMA_ID'] == '')		json_error('Pilih skema tunjangan');
			if ($_POST['GROSS_WAGE'] == '')		json_error('Masukkan gaji pokok');
			if ($_POST['EFFECTIVE_AT'] == '')	json_error('Tentukan tanggal mulai efektif');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			$keys = insertRecord('EMPSCHEMA', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => $keys['ID']));
			json_success($keys);
		}

		public function delete() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select * from payslip where empschema_id = ? and rownum = 1", $_POST['ID']);
			if ($query->num_rows() > 0)
				json_error('Maaf, skema ini tidak bisa dihapus, karena sudah digunakan dalam proses payroll');

			$keys = deleteRecord('EMPSCHEMA', $_POST, array('ID'));

			json_success();
		}
    }  
?>
<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Reimburse extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('reimburse', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			if (!isset($_GET['month'])) $_GET['month'] = date('Y-m');

			$query = $this->db->query("select a.*,decode(a.code,
					'meal','Uang Makan',
					'medical','Biaya Kesehatan'
				) code, b.name emp_name,
				to_char(a.adjust_at,'DD-Mon-YYYY') adjust_at,
				to_char(a.trx_at,'DD-Mon-YYYY') trx_at
				from adjustment a, employee b
				where a.amount > 0 and a.emp_id = b.id and to_char(a.adjust_at,'YYYY-MM') = ?
				order by a.adjust_at,b.name", $_GET['month']);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editAdjustment('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select a.*,b.name emp_name
				from adjustment a,employee b where a.emp_id = b.id and a.id = ?", $id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['ADJUST_AT'] == '') 	json_error('Tanggal mulai harus diisi');
			if ($_POST['CODE'] == '') 		json_error('Jenis pemotongan harus dipilih');
			if ($_POST['EMP_ID'] == '') 	json_error('Pegawai harus dipilih');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('ADJUSTMENT', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, data tidak bisa dihapus, karena sudah masuk proses payroll');
			
			updateRecord('ADJUSTMENT', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, data tidak bisa dihapus, karena sudah masuk proses payroll');
			
			deleteRecord('ADJUSTMENT', $_POST, array('ID'));

			json_success();
		}
		
		private function alreadyCommitted($id) {
			$query = $this->db->query("select * from adjustment where id = ? and payslip_id is not null", $_POST['ID']);
			return ($query->num_rows() > 0);
		}
    } 
?>
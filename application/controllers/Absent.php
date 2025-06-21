<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Absent extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('absent', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$find = strtolower('%'.array_nvl($_GET, 'find', '').'%');
			$month = array_nvl($_GET, 'month', date('Y-m'));

			$query = $this->db->query("select a.*, b.name emp_name, b.code emp_code, d.name dept_name,
				to_char(a.clock_in,'DD-Mon-YYYY HH24:MI') clock_in, to_char(a.clock_out,'DD-Mon-YYYY HH24:MI') clock_out
				from absent a, employee b, v_carier c, dept d
				where a.emp_id = b.id and b.id = c.emp_id(+) and c.dept_id = d.id(+) and 
				(to_char(a.clock_in,'YYYY-MM') = ? or to_char(a.clock_out,'YYYY-MM') = ?) and
				lower(b.code||';'||b.name||';'||d.name) like ?
				order by a.clock_in,b.name", array($month, $month, $find));
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editAbsent('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select a.*,b.name||' ('||b.code||')' emp_name,
				to_char(a.clock_in,'DD-Mon-YYYY HH24:MI') clock_in, to_char(a.clock_out,'DD-Mon-YYYY HH24:MI') clock_out				
				from absent a,employee b where a.emp_id = b.id and a.id = ?", $id);
			$rec = $query->row_array();
			if (!isset($rec)) json_notfound();

			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['CLOCK_IN'] == '') 	json_error('Jam masuk harus diisi');
			if ($_POST['CLOCK_OUT'] == '') 	json_error('Jam pulang harus diisi');
			if ($_POST['REMARKS'] == '') 	json_error('Keterangan harus diisi');
			if ($_POST['EMP_ID'] == '') 	json_error('Pegawai harus dipilih');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('ABSENT', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error("Maaf, data absen ini tidak dapat diubah karena sudah digunakan dalam proses payroll");

			if (!$this->isManMade($_POST['ID']))
				json_error("Maaf, data absen ini tidak dapat dihapus karena dihasilkan dari mesin absen");
			
			updateRecord('ABSENT', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();
			
			if ($this->alreadyCommitted($_POST['ID']))
				json_error("Maaf, data absen ini tidak dapat dihapus karena sudah digunakan dalam proses payroll");
			
			if (!$this->isManMade($_POST['ID'])) 
				json_error("Maaf, data absen ini tidak dapat dihapus karena dihasilkan dari mesin absen");

			deleteRecord('ABSENT', $_POST, array('ID'));

			json_success();
		}

		private function alreadyCommitted($id) {
			$query = $this->db->query("select absent_id from timesheet where absent_id = ? and payslip_id is not null
				union all select absent_id from overtime where absent_id = ? and payslip_id is not null", array($id, $id));
			return ($query->num_rows() > 0);
		}

		private function isManMade($id) {
			$query = $this->db->query("select modified_by from absent where modified_by in ('system','x100c') and id = ?", array($id));
			return ($query->num_rows() == 0);
		}
    }  
?>
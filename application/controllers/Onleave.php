<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Onleave extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('onleave', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$month = array_nvl($_GET, 'month', date('Y-m'));
			$find = strtolower('%'.array_nvl($_GET, 'find', '').'%');

			$query = $this->db->query("select a.*,
				b.description||' ('||b.code||')' absence_code,c.name emp_name,c.code emp_code,e.name dept_name,
				to_char(a.started_at,'DD-Mon-YYYY') started_at, to_char(a.ended_at,'DD-Mon-YYYY') ended_at
				from onleave a,absence b, employee c, v_carier d, dept e
				where a.absence_id = b.id and a.emp_id = c.id and c.id = d.emp_id(+) and d.dept_id = e.id(+) and
				(to_char(a.started_at,'YYYY-MM') = ? or to_char(a.ended_at,'YYYY-MM') = ?) and
				lower(c.name||';'||c.code||';'||e.name||';'||b.description||';'||b.code) like ?
				order by a.started_at,c.name", array($month, $month, $find));
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editOnleave('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select a.*,b.name||' ('||b.code||')' emp_name 
				from onleave a,employee b where a.emp_id = b.id and a.id = ?", $id);
			$rec = $query->row_array();
			if (!isset($rec)) json_notfound();

			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['STARTED_AT'] == '') 	json_error('Tanggal mulai harus diisi');
			if ($_POST['ENDED_AT'] == '') 		json_error('Sampai dengan harus diisi');
			if ($_POST['ABSENCE_ID'] == '') 	json_error('Jenis ijin/cuti harus dipilih');
			if ($_POST['EMP_ID'] == '') 		json_error('Pegawai harus dipilih');
			
			$dte = date_create($_POST['ENDED_AT']);				
			$query = $this->db->query("select a.* from bucket a,absence b where a.code = b.code and 
				a.emp_id = ? and b.id = ? and to_date(?, 'YYYY-MM-DD') between effective_at and expire_at",
				array($_POST['EMP_ID'], $_POST['ABSENCE_ID'], date_format($dte, 'Y-m-d')));
			if ($row = $query->row_array()) {
				if ($row['BALANCE'] < $_POST['QUOTA_TAKEN'])
					json_error('Durasi ijin/cuti tidak boleh melebihi quota yang tersedia');
			} else {
				json_error('Pegawai yang bersangkutan tidak memiliki quota aktif');
			}
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('ONLEAVE', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, ijin/cuti ini tidak bisa diubah karena sudah masuk dalam proses payroll');

			updateRecord('ONLEAVE', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, ijin/cuti ini tidak bisa dihapus karena sudah masuk dalam proses payroll');

			deleteRecord('ONLEAVE', $_POST, array('ID'));

			json_success();
		}

		private function alreadyCommitted($id) {
			$query = $this->db->query("select * from timesheet where onleave_id = ? and payslip_id is not null", $id);
			return ($query->num_rows() > 0);
		}
		
		public function quota($empId, $date, $code) {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select a.* from bucket a,absence b where a.code = b.code and 
				a.emp_id = ? and b.id = ? and to_date(?, 'YYYY-MM-DD') between effective_at and expire_at", 
				array($empId, $code, $date));
			$rec = $query->row_array();
			if ($query->num_rows() <= 0) json_notfound();

			json_success($rec);
		}
    }  
?>
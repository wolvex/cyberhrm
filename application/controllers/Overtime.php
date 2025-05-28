<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Overtime extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('overtime', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$month = array_nvl($_GET, 'month', date('Y-m'));
			$find = strtolower('%'.array_nvl($_GET, 'find', '').'%');

			$query = $this->db->query("select a.*,d.description overtime_code,f.name dept_name,
				b.name emp_name, to_char(a.start_clock,'DD-Mon-YYYY HH24:MI') start_clock,
				to_char(a.end_clock,'DD-Mon-YYYY HH24:MI') end_clock,c.description overtime_cat,
				round(work_minute/60,2) work_hour
				from overtime a, employee b, ovrcat c, absence d, v_carier e, dept f
				where a.absence_id = d.id and a.emp_id = b.id and a.overtime_cat = c.id and 
				b.id = e.emp_id(+) and e.dept_id = f.id(+) and to_char(a.start_clock,'YYYY-MM') = ? and 
				lower(b.code||';'||b.name||';'||f.name||';'||d.description) like ?
				order by a.start_clock,b.name", array($month, $find));
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editOvertime('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select a.*,b.name emp_name,c.dept_id department,
				to_char(a.start_clock,'Month DD, YYYY HH24:MI') start_clock, 
				to_char(a.end_clock,'Month DD, YYYY HH24:MI') end_clock,
				to_char(d.clock_in,'Month DD, YYYY HH24:MI') clock_in, 
				to_char(d.clock_out,'Month DD, YYYY HH24:MI') clock_out,
				round(a.work_minute/60,2) work_hour,round(a.duration/60,2) duration
				from overtime a,employee b,v_carier c,absent d
				where a.emp_id = b.id and b.id = c.emp_id and a.absent_id = d.id(+) and a.id = ?", $id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['START_CLOCK'] == '')  	json_error('Jam masuk harus diisi');
			if ($_POST['END_CLOCK'] == '') 		json_error('Jam pulang harus diisi');
			if ($_POST['ABSENCE_ID'] == '')		json_error('Jenis lembur harus dipilih');
			if ($_POST['EMP_ID'] == '')			json_error('Pegawai harus dipilih');

			$diff = date_diff(date_create($_POST['START_CLOCK']), date_create($_POST['END_CLOCK']));
			if (($diff->h == 8 && $diff->m > 0) || $diff->h > 8)
				json_error('Durasi tidak boleh lebih dari 8 jam');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('OVERTIME', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, lembur ini tidak bisa diubah karena sudah masuk dalam proses payroll');

			updateRecord('OVERTIME', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, lembur ini tidak bisa dihapus karena sudah masuk dalam proses payroll');

			deleteRecord('OVERTIME', $_POST, array('ID'));

			json_success();
		}
		
		private function alreadyCommitted($id) {
			$query = $this->db->query("select * from overtime where id = ? and payslip_id is not null", $id);
			return ($query->num_rows() > 0);
		}
    }  
?>
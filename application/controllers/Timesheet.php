<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Timesheet extends MY_Controller {  
          
        public function index() {  
			validateSession();
			
			layout('timesheet', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$find = strtolower("%".array_nvl($_GET, 'find', '')."%");
			$month = array_nvl($_GET, 'month', date('Y-m'));

			$query = $this->db->query("select a.emp_id,b.code,b.name,nvl(e.name,'-') dept_name,
				max(decode(to_char(work_at,'DD'),'01',c.code,null)) D01,
				max(decode(to_char(work_at,'DD'),'02',c.code,null)) D02,
				max(decode(to_char(work_at,'DD'),'03',c.code,null)) D03,
				max(decode(to_char(work_at,'DD'),'04',c.code,null)) D04,
				max(decode(to_char(work_at,'DD'),'05',c.code,null)) D05,
				max(decode(to_char(work_at,'DD'),'06',c.code,null)) D06,
				max(decode(to_char(work_at,'DD'),'07',c.code,null)) D07,
				max(decode(to_char(work_at,'DD'),'08',c.code,null)) D08,
				max(decode(to_char(work_at,'DD'),'09',c.code,null)) D09,
				max(decode(to_char(work_at,'DD'),'10',c.code,null)) D10,
				max(decode(to_char(work_at,'DD'),'11',c.code,null)) D11,
				max(decode(to_char(work_at,'DD'),'12',c.code,null)) D12,
				max(decode(to_char(work_at,'DD'),'13',c.code,null)) D13,
				max(decode(to_char(work_at,'DD'),'14',c.code,null)) D14,
				max(decode(to_char(work_at,'DD'),'15',c.code,null)) D15,
				max(decode(to_char(work_at,'DD'),'16',c.code,null)) D16,
				max(decode(to_char(work_at,'DD'),'17',c.code,null)) D17,
				max(decode(to_char(work_at,'DD'),'18',c.code,null)) D18,
				max(decode(to_char(work_at,'DD'),'19',c.code,null)) D19,
				max(decode(to_char(work_at,'DD'),'20',c.code,null)) D20,
				max(decode(to_char(work_at,'DD'),'21',c.code,null)) D21,
				max(decode(to_char(work_at,'DD'),'22',c.code,null)) D22,
				max(decode(to_char(work_at,'DD'),'23',c.code,null)) D23,
				max(decode(to_char(work_at,'DD'),'24',c.code,null)) D24,
				max(decode(to_char(work_at,'DD'),'25',c.code,null)) D25,
				max(decode(to_char(work_at,'DD'),'26',c.code,null)) D26,
				max(decode(to_char(work_at,'DD'),'27',c.code,null)) D27,
				max(decode(to_char(work_at,'DD'),'28',c.code,null)) D28,
				max(decode(to_char(work_at,'DD'),'29',c.code,null)) D29,
				max(decode(to_char(work_at,'DD'),'30',c.code,null)) D30,
				max(decode(to_char(work_at,'DD'),'31',c.code,null)) D31
			from timesheet a,employee b,shift c,v_carier d,dept e 
			where a.emp_id = b.id and a.shift_id = c.id and a.emp_id = d.emp_id(+) and d.dept_id = e.id(+) and
			to_char(work_at,'YYYY-MM') = ? and lower(b.code||';'||b.name||';'||e.name) like ?
			group by a.emp_id,b.code,b.name,e.name
			order by b.name", array($month, $find));

			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editTimesheet('."'".$rec['EMP_ID']."'".')"></i>';
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

			$query = $this->db->query("select a.*,b.*,c.*
				from timesheet a,onleave b,overtime c
				where a.onleave_id = b.id(+) and a.overtime_id = c.id(+) and a.id = ?", $id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['WORK_AT'] == '')		json_error('Tanggal harus diisi');
			if ($_POST['EMPLOYEE_ID'] == '')	json_error('Pegawai harus diisi');
			if ($_POST['SHIFT_ID'] == '') 		json_error('Shift harus dipilih');
		}

		public function upload() {
			if (!isGranted()) json_forbidden();

			$this->load->library('Uploader');

			$upload_dir = 'upload/timesheet/';
			$uploader = new Uploader('FILENAME');

			// Handle the upload
			$result = $uploader->handleUpload($upload_dir);
			if (!$result) {
  				json_error($uploader->getErrorMsg());
			} else {
				//$this->registerCron('timesheet', $uploader->getSavedFile());
			}
			
			json_success();
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			
			$this->db->insert('TIMESHEET', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, jadwal ini tidak bisa diubah karena sudah masuk dalam proses payroll atau dalam periode ijin/cuti');

			$this->db->updateRecord('TIMESHEET', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, jadwal ini tidak bisa dihapus karena sudah masuk dalam proses payroll atau dalam periode ijin/cuti');

			$this->db->deleteRecord('TIMESHEET', $_POST, array('ID'));

			json_success();
		}

		private function alreadyCommitted($id) {
			$query = $this->db->query("select * from timesheet 
				where id = ? and (onleave_id is not null or absent_id is not null or payslip_id is not null)", $id);
			return ($query->num_rows() > 0);
		}

		public function check($empId, $date) {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select b.code||' ('||to_char(b.start_time,'HH24:MI')||' - '||to_char(b.end_time,'HH24:MI')||')' shift_code,
				to_char(a.clock_in,'DD-Mon-YYYY HH24:MI:SS') shift_in,to_char(a.clock_out,'DD-Mon-YYYY HH24:MI:SS') shift_out
				from timesheet a,shift b
				where a.shift_id = b.id and a.emp_id = ? and to_char(a.work_at,'YYYY-MM-DD') = ?",
				array($empId, $date));
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
	
    }  
?>
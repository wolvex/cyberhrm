<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Attendance extends MY_Controller {  
          
        public function index() {  
			validateSession();

			if (!isGranted()) json_forbidden();

			if (!isset($_GET['id']) || !isset($_GET['period'])) {
				header("Location: ../timesheet");
			}

			$id = $_GET['id']; $period = $_GET['period'].'-01';
			$query = $this->db->query("select t.* from employee t where id = ?", $id);
			if ($data = $query->row_array()) {
				$date = date_create_from_format('Y-M-d', $period);
				print_r($date);
				//$period = date_format($date,'F Y');
				$data['PERIOD'] = $period;
				layout('attendance', $data);
			} else {
				header("Location: ../timesheet");
			}
        }  
		
		public function query($id, $period) {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select a.*,to_char(a.work_at,'DD-Mon-YYYY') work_at,b.code shift_code,
				to_char(b.start_time,'HH24:MI') start_time, to_char(b.end_time,'HH24:MI') end_time,
				nvl(e.code,to_char(c.clock_in,'HH24:MI')) clock_in,nvl(e.code,to_char(c.clock_out,'HH24:MI')) clock_out,
				round(a.work_minute/60,2) work_hour, g.code overtime_code, f.work_minute overtime_min,
				decode(sign(trunc(sysdate)-trunc(a.work_at)),1,
					decode(b.code,'OF','N',decode(sign(late_minute),1,'Y',decode(sign(early_minute),1,'Y','N'))),
				'N') is_late,
				decode(sign(trunc(sysdate)-trunc(a.work_at)),1,
					decode(b.code,'OF','N',decode(late_minute,0,'Y',decode(early_minute,0,'Y','N'))),
				'N') is_incomplete
				from timesheet a,shift b,absent c,onleave d,absence e,overtime f,absence g
				where a.shift_id = b.id and a.absent_id = c.id(+) and a.onleave_id = d.id(+) and 
				d.absence_id = e.id(+) and a.absent_id = f.absent_id(+) and f.absence_id = g.id(+) and
				a.emp_id = ? and to_char(a.work_at,'YYYY-MM') = ?
				order by a.work_at", array($id, $period));
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editTimesheet('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select a.* from timesheet a where id = ?", $id);
			$rec = $query->row_array();
			
			if (!isset($rec)) json_notfound();

			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['WORK_AT'] == '') json_error('Tanggal harus diisi');
			if ($_POST['SHIFT_ID'] == '') json_error('Shift harus dipilih');			
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();

			$query = $this->db->query("select * from timesheet where emp_id = ? and to_char(work_at, 'MONTH DD, YYYY') = ?",
				array($_POST['EMP_ID'], $_POST['WORK_AT']));
			if ($query->num_rows() > 0) json_error('Maaf, jadwal ini sudah ada');

			insertRecord('TIMESHEET', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();

			if ($this->alreadyCommitted($_POST['EMP_ID'], $_POST['WORK_AT']))
				json_error('Maaf, jadwal ini sudah tervalidasi dan tidak dapat diubah');

			updateRecord('TIMESHEET', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			if ($this->alreadyCommitted($_POST['EMP_ID'], $_POST['WORK_AT']))
				json_error('Maaf, jadwal ini sudah tervalidasi dan tidak dapat dihapus');

			deleteRecord('TIMESHEET', $_POST, array('ID'));

			json_success();
        }
		
		private function alreadyCommitted($empId, $workAt) {
			$query = $this->db->query("select sum(nvl(work_minute,0)) work_minute from timesheet t 
				where emp_id = ? and to_char(work_at, 'MONTH DD, YYYY') = ?", array($empId, $workAt));
			return ($query->row_array()['WORK_MINUTE'] > 0);
		}
    }  
?>
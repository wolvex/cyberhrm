<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Schema extends MY_Controller {  
          
        public function index() {  
			validateSession();
			
			layout('schema', array("status" => FALSE));
        }
		
		public function view($id) {
			if (!isGranted()) {
				show_error('Forbidden', 403);
				return;
			}
			layout('schema', array("ID" => $id));
		}

		public function get($id) {
			if (!isGranted()) {
				show_error('Forbidden', 403);
				return;
			}

			$query = $this->db->query("select t.*, to_char(effective_at,'YYYY-MM-DD') effective_at
				from schema t where status <> 'I' and id = ?", $id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['NAME'] == '') 			json_error('Nama skema harus diisi');
			if ($_POST['DESCRIPTION'] == '') 	json_error('Deskripsi harus diisi');
		}

		public function checkUsage($schemaId) {
			$query = $this->db->query("select count(*) total from payslip a,empschema b 
				where a.empschema_id = b.id and b.schema_id = ?", $schemaId);
			$rec = $query->row_array();
			return $rec['TOTAL'];
		}

		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			$keys = insertRecord('SCHEMA', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => $keys['ID']));
			json_success($keys);
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			if ($this->checkUsage($_POST['ID']) > 0) 
				json_error('Maaf, skema ini tidak bisa diubah, karena sudah digunakan dalam proses payroll');

			$this->validate();
			updateRecord('SCHEMA', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => '0'));
			json_success(array('ID' => '0'));
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			if ($this->checkUsage($_POST['ID']) > 0)
				json_error('Maaf, skema ini tidak bisa diubah, karena sudah digunakan dalam proses payroll');

			$_POST['STATUS'] = 'I';
			updateRecord('SCHEMA', $_POST, array('ID'));

			json_success();
		}

		public function approve() {  
			if (!isGranted()) json_forbidden();

			$this->db->query("update schema set status = 'A' where id = ? and status = 'D'", $_POST['ID']);
			if ($this->db->affected_rows() == 0) json_error('Status skema sudah disetujui');

			json_success();
		}

		public function setDefault() {  
			if (!isGranted()) json_forbidden();

			$this->db->query("update schema set status = 'S' where id = ? and status in ('S', 'A')", $_POST['ID']);
			if ($this->db->affected_rows() == 0) json_error('Pastikan status skema sudah disetujui');
			
			$this->db->query("update schema set status = 'A' where id <> ? and status = 'S'", $_POST['ID']);

			json_success();
		}

		public function apply() {  
			if (!isGranted()) json_forbidden();

			if ($_POST['STATUS'] == 'D') json_error('Status skema belum disetujui');

			$uid = $_SESSION['profile']['user_id'];

			$query = $this->db->query("select a.*,b.*,nvl(c.schema_id,0) schema_id,
				d.min_wage,to_char(d.effective_at,'MONTH DD, YYYY')  starts_at,a.id EMPLOYEE_ID
				from employee a,v_carier b, v_empschema c, schema d
				where a.id = b.emp_id and b.emp_status = ? and a.id = c.emp_id(+) and d.status in ('S','A') and d.id = ?",
				array($_POST['EMPSTATUS'], $_POST['ID']));

			$rows = 0;
			foreach ($query->result_array() as $rec) {
				if ($rec['SCHEMA_ID'] == $_POST['ID']) continue;

				$rows++;

				$this->db->query("update empschema set expire_at = to_date(?,'MONTH DD, YYYY')-1 
					where emp_id = ? and status = 'A' and expire_at is null", 
					array($rec['STARTS_AT'], $rec['EMPLOYEE_ID']));

				$this->db->query("insert into empschema (id,emp_id,schema_id,gross_wage,effective_at,modified_at,
					modified_by,status,created_at,created_by) values (seqempschema.nextval,?,?,?,
					to_date(?,'MONTH DD, YYYY'),sysdate,?,'A',sysdate,?)", 
					array($rec['EMPLOYEE_ID'], $_POST['ID'],
						($rec['SCHEMA_ID'] == '0' ? $rec['MIN_WAGE'] : $rec['GROSS_WAGE']),
						$rec['STARTS_AT'], $uid, $uid));
			}

			if ($rows == 0) {
				//echo json_encode(array('status' => false, 'error' => 'Pastikan schema sudah disetujui'));
				json_error('Pastikan skema sudah disetujui');
			} else {
				//echo json_encode(array('status' => TRUE, 'rows' => $rows));
				json_success(array('rows' => $rows));
			}
		}

		public function copy() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select * from schema where id = ?", $_POST['ID']);
			$rec = $query->row_array();
			$rec['ID'] = 0;	$rec['STATUS'] = 'D'; $rec['NAME'] .= ' (Copy)';
			$rec['EFFECTIVE_AT'] = date('F d, Y'); $rec['EXPIRE_AT'] = '';
			$rec = insertRecord('SCHEMA', $rec, array('ID'));

			$query = $this->db->query("select * from schema_grade where schema_id = ?", $_POST['ID']);
			$rows = $query->result_array();
			foreach ($rows as $rst) {
				$rst['SCHEMA_ID'] = $rec['ID'];
				insertRecord('SCHEMA_GRADE', $rst, array('SCHEMA_ID', 'GRADE_ID'));
			}

			$query = $this->db->query("select * from schema_shift where schema_id = ?", $_POST['ID']);
			$rows = $query->result_array();
			foreach ($rows as $rst) {
				$rst['SCHEMA_ID'] = $rec['ID'];
				insertRecord('SCHEMA_SHIFT', $rst, array('SCHEMA_ID', 'SHIFT_ID'));
			}

			$query = $this->db->query("select * from schema_absence where schema_id = ?", $_POST['ID']);
			$rows = $query->result_array();
			foreach ($rows as $rst) {
				$rst['SCHEMA_ID'] = $rec['ID'];
				insertRecord('SCHEMA_ABSENCE', $rst, array('SCHEMA_ID', 'ABSENCE_ID'));
			}

			//echo json_encode(array('status' => TRUE, 'id' => $rec['ID']));
			json_success($rec);
		}
		
		/** Updating shift benefits */
		public function shift($schemaId = 0, $shiftId = 0) {
			if (!isGranted()) json_forbidden();

			if ($schemaId == 0) return;

			if ($shiftId == 0) {
				$query = $this->db->query("select a.*,b.*,a.CODE||' - '||a.DESCRIPTION NAME,
					nvl(b.schema_id,".$schemaId.") schema_id,nvl(b.shift_id,a.id) shift_id,
					to_char(a.start_time,'HH24:MI') clock_in,to_char(a.end_time,'HH24:MI') clock_out,
					nvl(b.premi_attendance,0) premi_attendance,nvl(b.premi_shift,0) premi_shift,nvl(b.allowance,0) allowance
					from shift a,(select * from schema_shift where schema_id = ".$schemaId.") b where a.id = b.shift_id(+)
					order by a.code");
				$data = array(); $i = 0;
				foreach ($query->result_array() as $rec) {
					$rec['CMD'] = '<i class="edit icon" onclick="editShift('."'".$rec['ID']."'".')"></i>';
					$data[] = $rec; $i++;
				}
				
				$output = array(
					"page" => 1,
					"total" => $i,
					"records" => $i,
					"rows" => $data
				);
				echo json_encode($output);
			
			} else {
			
				$query = $this->db->query("select a.*,b.*,a.CODE||' - '||a.DESCRIPTION NAME,
					nvl(b.schema_id,".$schemaId.") schema_id,nvl(b.shift_id,a.id) shift_id,
					to_char(a.start_time,'HH24:MI') clock_in,to_char(a.end_time,'HH24:MI') clock_out,
					nvl(b.premi_attendance,0) premi_attendance,nvl(b.premi_shift,0) premi_shift,nvl(b.allowance,0) allowance
					from shift a,(select * from schema_shift where schema_id = ".$schemaId.") b 
					where a.id = b.shift_id(+) and a.id = ".$shiftId);
				if ($query->num_rows() <= 0) json_notfound();

				json_success($query->row_array());
			}
		}

		public function updateShift() {  
			if (!isGranted()) json_forbidden();

			if ($this->checkUsage($_POST['SCHEMA_ID']) > 0) 
				json_error('Maaf, skema ini tidak bisa diubah, karena sudah digunakan dalam proses payroll');

			//$this->validate();
			$affected = updateRecord('SCHEMA_SHIFT', $_POST, array('SCHEMA_ID','SHIFT_ID'));
			if ($affected == 0) {
				insertRecord('SCHEMA_SHIFT', $_POST, array('SCHEMA_ID','SHIFT_ID'));
			}

			echo json_encode(array('status' => TRUE, 'id' => '0'));
		}
		
		/** Updating grade benefits */
		public function grade($schemaId = 0, $gradeId = 0) {
			if (!isGranted()) json_forbidden();
			if ($schemaId == 0) return;

			if ($gradeId == 0) {
				$query = $this->db->query("select a.*,b.*,a.CODE||' - '||a.NAME DESCRIPTION,
					nvl(b.schema_id,".$schemaId.") schema_id,nvl(b.grade_id,a.id) grade_id,nvl(b.allowance,0) allowance
					from grade a,(select * from schema_grade where schema_id = ".$schemaId.") b where a.id = b.grade_id(+)
					order by a.id");
				$data = array(); $i = 0;
				foreach ($query->result_array() as $rec) {
					$rec['CMD'] = '<i class="edit icon" onclick="editGrade('."'".$rec['ID']."'".')"></i>';
					$data[] = $rec; $i++;
				}
				
				$output = array(
					"page" => 1,
					"total" => $i,
					"records" => $i,
					"rows" => $data
				);
				echo json_encode($output);

			} else {

				$query = $this->db->query("select a.*,b.*,a.CODE||' - '||a.NAME DESCRIPTION,
					nvl(b.schema_id,".$schemaId.") schema_id,nvl(b.grade_id,a.id) grade_id,nvl(b.allowance,0) allowance
					from grade a,(select * from schema_grade where schema_id = ".$schemaId.") b 
					where a.id = b.grade_id(+) and a.id = ".$gradeId);
				if ($query->num_rows() <= 0) json_notfound();

				json_success($query->row_array());
			}
		}

		public function updateGrade() {  
			if (!isGranted()) json_forbidden();

			if ($this->checkUsage($_POST['SCHEMA_ID']) > 0) 
				json_error('Maaf, skema ini tidak bisa diubah, karena sudah digunakan dalam proses payroll');

			//$this->validate();
			$affected = updateRecord('SCHEMA_GRADE', $_POST, array('SCHEMA_ID','GRADE_ID'));
			if ($affected == 0) {
				insertRecord('SCHEMA_GRADE', $_POST, array('SCHEMA_ID','GRADE_ID'));
			}

			//echo json_encode(array('status' => TRUE, 'id' => '0'));
			json_success(array('ID' => '0'));
		}
		
		/** Updating quota absence */
		public function absence($schemaId = 0, $absenceId = 0) {
			if (!isGranted()) json_forbidden();
			if ($schemaId == 0) return;

			if ($absenceId == 0) {
				$query = $this->db->query("select a.*,b.*,a.CODE||' - '||a.DESCRIPTION NAME,
					nvl(b.schema_id,".$schemaId.") schema_id,nvl(b.absence_id,a.id) absence_id,
					nvl(b.annual_quota,0) annual_quota,nvl(b.carry_over,0) carry_over
					from absence a,(select * from schema_absence where schema_id = ".$schemaId.") b 
					where a.type = 0 and a.id = b.absence_id(+) order by a.code");
				$data = array(); $i = 0;
				foreach ($query->result_array() as $rec) {
					$rec['CMD'] = '<i class="edit icon" onclick="editAbsence('."'".$rec['ID']."'".')"></i>';
					$data[] = $rec; $i++;
				}
				
				$output = array(
					"page" => 1,
					"total" => $i,
					"records" => $i,
					"rows" => $data
				);
				echo json_encode($output);

			} else {

				$query = $this->db->query("select a.*,b.*,a.CODE||' - '||a.DESCRIPTION NAME,
					nvl(b.schema_id,".$schemaId.") schema_id,nvl(b.absence_id,a.id) absence_id,
					nvl(b.annual_quota,0) annual_quota,nvl(b.carry_over,0) carry_over
					from absence a,(select * from schema_absence where schema_id = ".$schemaId.") b 
					where a.id = b.absence_id(+) and a.id = ".$absenceId);
				if ($query->num_rows() <= 0) json_notfound();

				json_success($query->row_array());
			}
		}

		public function updateAbsence() {  
			if (!isGranted()) json_forbidden();

			if ($this->checkUsage($_POST['SCHEMA_ID']) > 0) 
				json_error('Maaf, skema ini tidak bisa diubah, karena sudah digunakan dalam proses payroll');

			//$this->validate();
			$affected = updateRecord('SCHEMA_ABSENCE', $_POST, array('SCHEMA_ID','ABSENCE_ID'));
			if ($affected == 0) {
				insertRecord('SCHEMA_ABSENCE', $_POST, array('SCHEMA_ID','ABSENCE_ID'));
			}

			//echo json_encode(array('status' => TRUE, 'id' => '0'));
			json_success(array('ID' => '0'));
		}
		
		/** Updating lembur */
		public function overtime($schemaId = 0, $absenceId = 0) {
			if (!isGranted()) json_forbidden();
			if ($schemaId == 0) return;

			if ($absenceId == 0) {
				$query = $this->db->query("select a.*,b.*,a.CODE||' - '||a.DESCRIPTION NAME,
					nvl(b.schema_id,".$schemaId.") schema_id,nvl(b.absence_id,a.id) absence_id,
					nvl(b.hour1,0) hour1,nvl(b.factor1,0) factor1,
					nvl(b.hour2,0) hour2,nvl(b.factor2,0) factor2,
					nvl(b.hour3,0) hour3,nvl(b.factor3,0) factor3
					from absence a,(select * from schema_overtime where schema_id = ".$schemaId.") b 
					where a.type = 1 and a.id = b.absence_id(+) order by a.id");
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

			} else {

				$query = $this->db->query("select a.*,b.*,a.CODE||' - '||a.DESCRIPTION NAME,
					nvl(b.schema_id,".$schemaId.") schema_id,nvl(b.absence_id,a.id) absence_id,
					nvl(b.hour1,0) hour1,nvl(b.factor1,0) factor1,
					nvl(b.hour2,0) hour2,nvl(b.factor2,0) factor2,
					nvl(b.hour3,0) hour3,nvl(b.factor3,0) factor3
					from absence a,(select * from schema_overtime where schema_id = ".$schemaId.") b 
					where a.id = b.absence_id(+) and a.id = ".$absenceId);
				if ($query->num_rows() <= 0) json_notfound();

				json_success($query->row_array());
			}
		}

		public function updateOvertime() {  
			if (!isGranted()) json_forbidden();

			if ($this->checkUsage($_POST['SCHEMA_ID']) > 0) 
				json_error('Maaf, skema ini tidak bisa diubah, karena sudah digunakan dalam proses payroll');

			//$this->validate();
			$affected = updateRecord('SCHEMA_OVERTIME', $_POST, array('SCHEMA_ID','ABSENCE_ID'));
			if ($affected == 0) {
				insertRecord('SCHEMA_OVERTIME', $_POST, array('SCHEMA_ID','ABSENCE_ID'));
			}

			//echo json_encode(array('status' => TRUE, 'id' => '0'));
			json_success(array('ID' => '0'));
        }
    }  
?>
<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Carier extends MY_Controller {  
          
        public function index() {  
			validateSession();
		}
		
		public function query($id) {
			if (!isGranted()) json_forbidden();

			$sql = "select a.*,b.title job_title,c.pfx dept_pfx,c.name dept_name,d.name grade_name,
				decode(emp_status,'P','Permanen','K','Kontrak','Harian') status_name,
				decode(e.NAME,null,'',e.NAME||' ('||e.CODE||')') spv_name,
				decode(f.NAME,null,'',f.NAME||' ('||f.CODE||')') mgr_name,
				to_char(a.effective_at,'DD-Mon-YYYY') effective_at,to_char(a.expire_at,'DD-Mon-YYYY') expire_at
				from carier a,job b,dept c,grade d,employee e,employee f
				where a.job_id = b.id and a.dept_id = c.id and a.grade_id = d.id and 
				a.approver1 = e.id(+) and a.approver2 = f.id(+) and a.emp_id = ?
				order by a.effective_at desc";
			
			$query = $this->db->query($sql, $id);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editCarier('."'".$rec['ID']."'".')"></i>';
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
			
			$sql = "select a.*,b.title job_title,c.pfx dept_pfx,c.name dept_name,d.name grade_name,
				c.id||';'||b.id dept_job,
				decode(e.NAME,null,'',e.NAME||' ('||e.CODE||')') spv_name,
				decode(f.NAME,null,'',f.NAME||' ('||f.CODE||')') mgr_name,
				to_char(effective_at,'YYYY-MM-DD') effective_at,
				to_char(expire_at,'YYYY-MM-DD') expire_at
				from carier a,job b,dept c,grade d,employee e,employee f
				where a.job_id = b.id and a.dept_id = c.id and a.grade_id = d.id and 
				a.approver1 = e.id(+) and a.approver2 = f.id(+) and a.id = ?";
			
			$query = $this->db->query($sql, $id);
			if ($query->num_rows() == 0) json_notfound();
			
			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['EMP_ID'] == '') 		json_error('Pilih pegawai ybs');
			if ($_POST['JOB_ID'] == '') 		json_error('Pilih penugasan jabatan');
			if ($_POST['DEPT_ID'] == '') 		json_error('Pilih penugasan departemen');
			if ($_POST['GRADE_ID'] == '') 		json_error('Pilih grade pegawai');
			if ($_POST['EMP_STATUS'] == '') 	json_error('Pilih status pegawai');
			if ($_POST['EFFECTIVE_AT'] == '') 	json_error('Tentukan tanggal mulai efektif');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			$keys = insertRecord('CARIER', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => $keys['ID']));
			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('CARIER', $_POST, array('ID'));

			//echo json_encode(array('status' => TRUE, 'id' => $_POST['ID']));
			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('CARIER', $_POST, array('ID'));

			json_success();
        }
    }  
?>
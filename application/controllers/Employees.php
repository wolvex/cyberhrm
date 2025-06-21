<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Employees extends MY_Controller {  
          
        public function index() {  
			validateSession();
			
			layout('employees', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$find = strtolower('%'.array_nvl($_GET, 'find', '').'%');
			$status = array_nvl($_GET, 'emp_status', 'PHK');

			$query = $this->db->query("select a.*,decode(gender,'P','Pria','Wanita') as gender_name,
				nvl(c.name,'-') dept_name, nvl(d.name,'-') grade_name 
				from employee a,v_carier b,dept c,grade d 
				where a.id = b.emp_id(+) and b.dept_id = c.id(+) and b.grade_id = d.id(+) and
				lower(a.code||';'||a.name||';'||a.citizen_id||';'||c.name||';'||d.name) like ? and instr(?, emp_status) > 0 
				order by a.code, a.name", array($find, $status));

			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editEmployee('."'".$rec['ID']."'".')"></i>';
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

		public function search() {
			if (!isGranted()) json_forbidden();

			$status = array_nvl($_GET, 'emp_status', 'PHK');

			$sql = "select a.*,b.dept_id,c.pfx dept_code
				from employee a,v_carier b,dept c
				where a.id = b.emp_id and b.dept_id = c.id and instr('".$status."', emp_status) > 0 and
				LOWER(a.code||';'||a.name||';'||a.citizen_id) like '%".strtolower($_GET['find'])."%' 
				order by a.name";

			$query = $this->db->query($sql);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$item = array(
					"title" => $rec['ID'],
					"description" => $rec['NAME'].' ('.$rec['CODE'].')',
					"department" => $rec['DEPT_ID']
				);
				$data[] = $item; $i++;
			}
			echo json_encode(array(
				"results" => $data
			));
		}
    }  
?>
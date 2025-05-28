<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Wages extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('wages', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$sql = "1 = 1";
			if (isset($_GET['find']))
				$sql = "LOWER(a.code||';'||a.name||';'||a.citizen_id) like '%".strtolower($_GET['find'])."%'";

			$sql = "select a.*,c.name schema_name from employee a,v_empschema b,schema c,v_carier d 
				where a.id = b.emp_id(+) and b.schema_id = c.id(+) and a.id = d.emp_id(+) and 
				instr('".$_GET['emp_status']."', emp_status) > 0 and ".$sql." order by a.code, a.name";
			
			$query = $this->db->query($sql);
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
    }  
?>
<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Schemas extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('schemas', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$sql = "1 = 1";
			if (isset($_GET['find']))
				$sql = "LOWER(name||';'||description) like '%".strtolower($_GET['find'])."%'";

			$sql = "select a.*,decode(status,'D','Draft','A','Disetujui','Default') status,
				nvl(b.empcount,0) total_employee
				from schema a,(select schema_id,count(*) empcount from empschema group by schema_id) b
				where a.status <> 'I' and a.id = b.schema_id(+) and ".$sql." order by id desc,name";
			
			$query = $this->db->query($sql);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editSchema('."'".$rec['ID']."'".')"></i>';
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
<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Job extends MY_Controller {  
          
        public function index() {  
            validateSession();
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();
			
			$where = "1 = 1";
			if (isset($_GET['find']))
				$where = "LOWER(a.title||';'||b.name||';'||c.name) like '%".strtolower($_GET['find'])."%'";
			
			$sql = "select a.*,
				decode(c.id,null,b.name||' ('||b.code||')',c.name||' - '||b.name||' ('||c.code||' - '||b.code||')') as DEPT_NAME
				from job a,dept b,dept c 
				where a.dept_id = b.id(+) and b.parent = c.id(+) and ".$where." order by a.id";			
			$query = $this->db->query($sql);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editJob('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query('select * from job where id = '.$id);
			$rec = $query->row_array();
			if (!isset($rec)) json_notfound();
			
			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['CODE'] == '')  json_error('Kode harus diisi');
			if ($_POST['TITLE'] == '') json_error('Nama harus diisi');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('JOB',$_POST,array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('JOB',$_POST,array('ID'));
			
			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('JOB',$_POST,array('ID'));

			json_success();
        }
    }  
?>  

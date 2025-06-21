<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Ovrcategory extends MY_Controller {  
          
        public function index() {  
            validateSession();
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$search = array_nvl($_GET, 'find', '');

			$sql = "select a.id,a.code,a.description,decode(b.name,null,'-',b.name||' ('||b.code||')') as DEPT_NAME 
				from ovrcat a,dept b where a.dept_id = b.id(+)";
			if ($search != '') $sql = $sql." and lower(a.code||';'||a.description||';'||nvl(b.name,'all')) like ?";
			$sql = $sql." order by nvl(a.dept_id,-255),a.code";

			$query = $this->db->query($sql, array("%".strtolower($search)."%"));

			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editOvr('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select id,code,description,nvl(dept_id,-255) as dept_id from ovrcat a where id = ".$id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['CODE'] == '') 			json_error('Kode harus diisi');
			if ($_POST['DESCRIPTION'] == '') 	json_error('Deskripsi harus diisi');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('OVRCAT', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('OVRCAT', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('OVRCAT', $_POST, array('ID'));

			json_success();
        }
    }  
?>
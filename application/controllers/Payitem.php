<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Payslip extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('payday', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select a.*,b.* from payitem a,bty b
				where a.code = b.code and a.payslip_id = ? order by a.id", $_GET['ID']);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				//$rec['CMD'] = '<i class="edit icon" onclick="editPayslip('."'".$rec['ID']."'".')"></i>';
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
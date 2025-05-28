<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Company extends MY_Controller {  
          
        public function index() {  
			validateSession();

            layout('company', array());
        }  
		
		public function get() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select t.*,to_char(established_at,'yyyy-mm-dd') established_at,
				to_char(registered_at,'yyyy-mm-dd') registered_at from company t");

			json_success($query->row_array());
        }  
		
        public function save()  {
			if (!isGranted()) json_forbidden();

			$rows = updateRecord('COMPANY', $_POST, array('ID'));

			if ($rows == 0) {
				json_error('bam!!');
			}
			
			json_success();
        }
      
    }  
?>  

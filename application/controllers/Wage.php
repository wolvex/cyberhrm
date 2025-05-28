<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Wage extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('wage', array("status" => FALSE));
        }
		
		public function view($id) {
			if (!isGranted()) {
				show_error('Forbidden', 403);
				return;
			}
			layout('wage', array("ID" => $id));
		}
    }  
?>
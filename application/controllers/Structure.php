<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Structure extends MY_Controller {  
          
        public function index() {
            validateSession();

            layout('structure', array());
        }  
		
    }  
?>  

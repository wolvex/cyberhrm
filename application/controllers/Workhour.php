<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Workhour extends MY_Controller {  
          
        public function index() {
            validateSession();

			layout('workhour', array());
        }  
		
    }  
?>  

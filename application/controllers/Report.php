<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Report extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('report', array());
		}

		public function query() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select a.* from report a,permissions b where 
				'report.'||a.name = b.module and b.role = ? order by code", $_SESSION['profile']['user_role']);
			$reports = $query->result_array();
			
			json_success($reports);
		}

		public function view() {
			
			$base_url = 'http://localhost:8383/birt/frameset?__report=report/cyberhrm/';

			$rptName = $this->resolveReport($_POST['rptName']);
			if ($rptName == '') json_forbidden();

			$base_url = $base_url.$rptName.'.rptdesign&__title=';
			foreach($_POST as $key => $val) {
				if ($key == 'rptName') continue;
				
				log_message("info", "Found ".$key." = ".$val);

				if (startsWith($key,'date'))
					$val = date_format(date_create($val),'Y-m-d');

				$base_url = $base_url.'&'.$key.'='.$val;
			}

			layoutReport('reportviewer', array(
				'url' => $base_url,
				'title' => $_POST['rptTitle']
			));
		}

		private function resolveReport($rptName) {
			switch( $rptName ) {
				case 'employees':
					break;
			}

			if (!isGranted('report.'.$rptName, 'preview')) {
				header('Location: '.base_url('report'));
				exit();
			}

			return $rptName;
		}
		
	}
?>
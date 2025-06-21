<?php
	class MY_Controller extends CI_Controller { 
		public function __construct() { 

			parent::__construct();

			$this->load->library('session');
			$this->load->library('form_validation');
			#$this->load->helper('form');
			#$this->load->helper('rbac');
			#$this->load->helper('layout');
			#$this->load->helper('func');

			
            $modules = array();
            $query = $this->db->query("select a.*,b.module,b.action from users a,permissions b WHERE a.role = b.role and 
                a.id = 'lutfi'");			
			foreach ($query->result_array() as $rec) {
                $modules[$rec["MODULE"]] = $rec["ACTION"];
			}
			$this->session->set_userdata('modules', $modules);

			$this->session->set_userdata('profile', array(
				"user_id" => "lutfi",
				"user_name" => "Lutfi Kamaludin",
				"user_role" => "ADMIN"
			));			
		} 

		public function getSequence($table) {
			$seqName = '';
			switch(strtolower($table)) {
				case 'employee':
					$seqName = 'seqemp';
					break;
				case 'empdoc':
					$seqName = 'seqdoc';
					break;
				case 'overtime':
					$seqName = 'seqovr';
					break;
				case 'carier': case 'dept':	case 'grade':
				case 'holiday':	case 'job': case 'absence':
				case 'shift': case 'ovrcat': case 'study':
				case 'family': case 'address': case 'cron':
				case 'schema': case 'empschema': case 'timesheet':
				case 'onleave': case 'adjustment': case 'payroll':
				case 'absent': case 'payslip': case 'payitem':
				case 'bucket':
					$seqName = 'seq'.$table;
					break;
				default:
					return;
			}
			$query = $this->db->query("select ".$seqName.".nextval as id from dual");
			$data = $query->row_array();
			return $data['ID'];
		}

		public function getDefault($field, $insert = false) {
			log_message('info', 'Getting default value for '.$field);
			
			if ($field == 'MODIFIED_AT' || ($insert && $field == 'CREATED_AT')) {
				return 'SYSDATE';
			} else if ($field == 'MODIFIED_BY' || ($insert && $field == 'CREATED_BY')) {
				return $_SESSION['profile']['user_id'];
			}
			return '';
		}

		/*
		public function insertRecord($table, $data, $keys) {
			$generated_keys = array();
			$fields = $this->db->list_fields($table);
			foreach ($fields as $field) {
				$value = "";
				if (array_key_exists($field,$data)) {
					$value = $data[$field];
				} else if (strpos($field, 'MODIFIED_') !== false ||
					strpos($field, 'CREATED_') !== false) {
					
				} else {
					continue;
				}				

				if (in_array($field, $keys)) {
					if ($value == '0' || $field == 'ID') {
						$value = $this->getSequence($table);
					}
					$this->db->set($field, $value);
					$generated_keys[$field] = $value;
				} else {
					if ($field == 'MODIFIED_AT' || $field == 'CREATED_AT') {
						$this->db->set($field, 'SYSDATE', false);
					} else if ($field == 'MODIFIED_BY' || $field == 'CREATED_BY') {
						$this->db->set($field, $_SESSION['profile']['user_id']);
					} else if ($value == '' || $value == '-255') {
						$this->db->set($field, 'NULL', false);
					} else if (endsWith($field, '_AT')) {
						$this->db->set($field, "to_date('".$value."','MONTH DD, YYYY')", false);
					} else if (endsWith($field, '_TIME')) {
						$this->db->set($field, "to_date('".$value."','HH24:MI')", false);
					} else if (strpos($field, 'CLOCK') !== false) {
						$this->db->set($field, "to_date('".$value."','MONTH DD, YYYY HH24:MI')", false);
					} else {
						$this->db->set($field, $value);
					}
				}
			}
			$this->db->insert($table);
			
			return $generated_keys;
		}

		public function updateRecord($table, $data, $keys) {
			//check completeness
			foreach ($keys as $field) {
				if (!array_key_exists($field,$data)) return 0;
			}
			
			$fields = $this->db->list_fields($table);
			foreach ($fields as $field) {
				$value = "";
				if (array_key_exists($field,$data)) {
					$value = $data[$field];
				} else if (strpos($field, 'MODIFIED_') !== false ||
					strpos($field, 'CREATED_') !== false) {
					
				} else {
					continue;
				}
				
				if (is_numeric(str_replace(',','',$value))) {
					$value = str_replace(',','',$value);
				}				
				
				if (in_array($field, $keys)) {
					$this->db->where($field, $value);
				} else {
					if ($field == 'MODIFIED_AT') {
						$this->db->set($field, 'SYSDATE', false);
					} else if ($field == 'MODIFIED_BY') {
						$this->db->set($field, $_SESSION['profile']['user_id']);
					} else if ($value == '' || $value == '-255') {
						$this->db->set($field, 'NULL', false);
					} else if (endsWith($field, '_AT')) {
						$this->db->set($field, "to_date('".$value."','MONTH DD, YYYY')", false);
					} else if (endsWith($field, '_TIME')) {
						$this->db->set($field, "to_date('".$value."','HH24:MI')", false);
					} else if (strpos($field, 'CLOCK') !== false) {
						$this->db->set($field, "to_date('".$value."','MONTH DD, YYYY HH24:MI')", false);
					} else {
						$this->db->set($field, $value);
					}
				}
			}
			$this->db->update($table);
			return $this->db->affected_rows();
		}

		public function deleteRecord($table, $data, $keys) {
			//check completeness
			foreach ($keys as $field) {
				if (!array_key_exists($field,$data)) return 0;
			}
			//assign keys
			foreach ($keys as $field) {
				$this->db->where($field, $data[$field]);
			}			
			$this->db->delete($table);
			return $this->db->affected_rows();
		}
		*/

		public function registerCron($code, $filename) {
			$this->db->set('ID', $this->getSequence('CRON'));
			$this->db->set('CODE', $code);
			$this->db->set('FILENAME', $filename);
			$this->db->set('STARTS_AT', 'SYSDATE', false);
			$this->db->set('STATUS', '99');
			$this->db->set('CREATED_AT', 'SYSDATE', false);
			$this->db->set('CREATED_BY', $_SESSION['profile']['user_id']);
			$this->db->insert('CRONTAB');
		}

		public function sendMail($to, $subject, $content) {
			$config = array(
				'protocol'  	=> 'smtp',
				'smtp_host' 	=> 'smtp.mail.yahoo.com',
				'smtp_port' 	=> 465,
				'smtp_crypto' 	=> 'ssl',
				'smtp_user' 	=> 'khamaludin@yahoo.com',
				'smtp_pass' 	=> 'IbnuSina78',
				'charset'   	=> 'utf-8',
				'newwline' 		=> "\r\n",
			);
			
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");

			$this->email->to($to);
			$this->email->from('khamaludin@yahoo.com','CyberHRM');
			$this->email->subject($subject);
			$this->email->message($content);

			//Send email
			$this->email->send();
		}
	}
?>

<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Holiday extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('holiday', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select id,holiday_at,description,
				decode(full_day,0,'Setengah hari','Sehari penuh') full_day,decode(type,0,'Normal','Istimewa') type
				from holiday where to_char(holiday_at,'YYYY') = '".$_GET['year']."' order by holiday_at");
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editHoliday('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select * from holiday a where id = ".$id);
			$rec = $query->row_array();
			if (!isset($rec)) json_notfound();

			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['HOLIDAY_AT'] == '') 	json_error('Tanggal harus diisi');
			if ($_POST['DESCRIPTION'] == '') 	json_error('Deskripsi harus diisi');
			if ($_POST['FULL_DAY'] == '') 		json_error('Durasi harus dipilih');
			if ($_POST['TYPE'] == '') 			json_error('Jenis harus dipilih');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('HOLIDAY', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('HOLIDAY', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('HOLIDAY', $_POST, array('ID'));

			json_success();
		}
		
		public function check($start, $end) {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select a.*,to_char(holiday_at,'YYYY-MM-DD') holiday_date 
				from holiday a where to_char(holiday_at,'YYYY-MM-DD') >= ? and to_char(holiday_at,'YYYY-MM-DD') <= ? 
				order by holiday_at", array($start, $end));
			if ($rec = $query->result_array()) {
				json_success($rec);
			} else {
				json_notfound();
			}
		}
    }  
?>
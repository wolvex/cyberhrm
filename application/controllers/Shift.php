<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Shift extends MY_Controller {  
          
        public function index() {  
            validateSession();
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select a.ID,a.CODE,a.DESCRIPTION,a.MIN_WORK_HOUR,
				TO_CHAR(start_time,'HH24:MI') START_TIME,TO_CHAR(end_time,'HH24:MI') END_TIME 
				from shift a order by code");
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editShift('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select a.ID,a.CODE,a.DESCRIPTION,a.MIN_WORK_HOUR,
				TO_CHAR(start_time,'HH24:MI') START_TIME,TO_CHAR(end_time,'HH:MI PM') END_TIME 
				from shift a where id = ".$id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['CODE'] == '') 			json_error('Kode harus diisi');
			if ($_POST['DESCRIPTION'] == '')	json_error('Deskripsi harus diisi');
			if ($_POST['START_TIME'] == '')  	json_error('Jam mulai harus diisi');
			if ($_POST['END_TIME'] == '') 		json_error('Jam akhir harus diisi');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			insertRecord('SHIFT', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('SHIFT', $_POST, array('ID'));
			
			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			deleteRecord('SHIFT', $_POST, array('ID'));

			json_success();
		}
		
		/**
		public function benefit($id = 0) {
			if (!isGranted()) json_forbidden();

			$sql = "select t.*,t.CODE||' - '||t.DESCRIPTION as NAME,
				TO_CHAR(start_time,'HH24:MI') START_TIME,TO_CHAR(end_time,'HH24:MI') END_TIME 
				from shift t";

			if ($id == 0) {
				
				$query = $this->db->query($sql." order by code");
				$data = array(); $i = 0;
				foreach ($query->result_array() as $rec) {
					$rec['CMD'] = '<i class="edit icon" onclick="editShift('."'".$rec['ID']."'".')"></i>';
					$data[] = $rec; $i++;
				}
				
				$output = array(
					"page" => 1,
					"total" => $i,
					"records" => $i,
					"rows" => $data
				);
				echo json_encode($output);

			} else {

				$query = $this->db->query($sql." where id = ?", $id);
				$rec = $query->row_array();
				$data = array();
				if (isset($rec)) {
					$data['status'] = TRUE;
					$data['shift'] = $rec;
				} else {
					$data['status'] = FALSE;
					$data['error'] = "Data tidak ditemukan";
				}
				echo json_encode($data);
			}
		}
		*/
    }  
?>
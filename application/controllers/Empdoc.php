<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Empdoc extends MY_Controller {  
          
        public function index() {  

		}
		
		public function query($id) {
			if (!isGranted()) json_forbidden();

			$sql = "select a.*,	to_char(a.modified_at,'DD-Mon-YYYY') modified_at
				from empdoc a where a.emp_id = ?
				order by a.modified_at desc";
			
			$query = $this->db->query($sql, $id);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				//$rec['CMD'] = '<i class="trash icon" onclick="deleteDoc('."'".$rec['ID']."'".')"></i>
				//	<i class="trash icon" onclick="deleteDoc('."'".$rec['ID']."'".')"></i>';
				$rec['CMD'] = '<div class="ui mini basic icon buttons"><div class="ui button" title="Download" 
					onclick="download('."'".str_replace("\\", "/", $rec['SAVED_NAME'])."'".')"><i class="download icon"></i></div>
					<div class="ui button" title="Delete" onclick="deleteDoc('."'".$rec['ID']."'".')"><i class="trash icon"></i></div></div>';
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
		
		private function validate() {
			$data = array(); $data['status'] = FALSE;
			if ($_POST['EMP_ID'] == '') {
				$data['error'] = 'Pilih pegawai ybs';
			} else if ($_POST['DESCRIPTION'] == '') {
				$data['error'] = 'Masukkan deskripsi';
			} else {
				$data['status'] = TRUE;
			}
			
			if($data['status'] === FALSE) {
				echo json_encode($data);
				exit();
			}
		}
		
		public function upload() {
			if (!isGranted()) json_forbidden();

			$this->validate();

			$this->load->library('Uploader');

			$upload_dir = 'upload/';
			$uploader = new Uploader('FILE_NAME');

			// Handle the upload
			$result = $uploader->handleUpload($upload_dir);
			if (!$result) {
  				exit(json_encode(array('success' => false, 'error' => $uploader->getErrorMsg())));  
			} else {
				$_POST['FILE_NAME'] = $uploader->getFileName();
				$_POST['SAVED_NAME'] = $uploader->getSavedFile();
				insertRecord('EMPDOC', $_POST, array('ID'));
			}
			
			echo json_encode(array('status' => true, 'error' => 'success'));
		}
      
        public function delete($id) {  
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select * from empdoc where id = ?", $id);
			$data = $query->row_array();

			unlink(str_replace("\\", "/", $data['SAVED_NAME']));
			deleteRecord('EMPDOC', array('ID' => $id), array('ID'));

			echo json_encode(array('status' => TRUE, 'name' => $data['SAVED_NAME']));
        }
    }  
?>
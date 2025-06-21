<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Payslip extends MY_Controller {  
          
        public function index() {  
			validateSession();

			layout('payday', array());
        }  
		
		public function query($id) {
			if (!isGranted()) json_forbidden();

			$find = '';
			if (isset($_GET['find'])) $find = $_GET['find'];

			$query = $this->db->query("select a.*,c.*,b.code emp_code,b.name emp_name
				from payslip a,employee b,v_payitem c 
				where a.emp_id = b.id and a.id = c.payslip_id and a.payroll_id = ? and
				lower(b.code||';'||b.name) like '%".strtolower($find)."%' order by a.id", $id);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="print icon" onclick="printSlip(false,'."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select a.*,b.name||' ('||b.code||')' emp_name,d.name schema_name,
				to_char(to_date(a.period||'01','YYYYMMDD'),'Month YYYY') period,
				to_char(a.start_at,'Month DD, YYYY') start_at,to_char(a.end_at,'Month DD, YYYY') end_at,
				to_char(a.issued_at,'Month DD, YYYY') issued_at,to_char(a.due_at,'Month DD, YYYY') due_at
				from payslip a,employee b,empschema c,schema d 
				where a.emp_id = b.id and a.empschema_id = c.id and c.schema_id = d.id and a.id = ?", $id);
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['EMP_ID'] == '') 	json_error('Pegawai harus dipilih');
			if ($_POST['PERIOD'] == '') 	json_error('Periode harus diisi');
			if ($_POST['START_AT'] == '') 	json_error('Tanggal awal harus dipilih');
			if ($_POST['END_AT'] == '') 	json_error('Tanggal akhir harus dipilih');
		}
		
		public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('PAYSLIP', $_POST, array('ID'));

			json_success();
        }
    }  
?>
<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Payday extends MY_Controller {  
          
        public function index() {
			validateSession();

			layout('payday', array('ID' => $_GET['id']));
        }  
		
		public function query($id) {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select a.*,c.*,b.code emp_code,b.name emp_name
				from payslip a,employee b,v_payitem c 
				where a.emp_id = b.id and a.id = c.payslip_id(+) and a.id = ? 
				order by a.id", $id);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editPayslip('."'".$rec['ID']."'".')"></i>';
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

			$query = $this->db->query("select a.*,
				to_char(to_date(period||'01','YYYYMMDD'),'fmMonth YYYY') period,to_char(due_at,'fmMonth DD, YYYY') due_at,
				to_char(start_at,'fmMonth DD, YYYY') start_at,to_char(end_at,'fmMonth DD, YYYY') end_at,
				to_char(created_at,'fmMonth DD, YYYY') created_at,to_char(printed_at,'fmMonth DD, YYYY') printed_at
				from payroll a where id = ?", $id);			
			if ($query->num_rows() <= 0) json_notfound();

			json_success($query->row_array());
		}
		
		private function validate() {
			if ($_POST['CODE'] == '') 		json_error('Jenis proses harus dipilih');
			if ($_POST['PERIOD'] == '') 	json_error('Periode harus diisi');
			if ($_POST['START_AT'] == '') 	json_error('Tanggal awal harus dipilih');
			if ($_POST['END_AT'] == '') 	json_error('Tanggal akhir harus dipilih');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			$_POST['STATUS'] = 'Q';
			$_POST['PERIOD'] = date_format(date_create_from_format('d F Y', '01'.$_POST['PERIOD']), 'Ym');
			insertRecord('PAYROLL', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();
			updateRecord('PAYROLL', $_POST, array('ID'));

			json_success();
        }
	  
		public function preview($id) {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select * from payroll where id = ?", $id);
			if ($query->num_rows() == 0)
				json_error('Maaf, data tidak ditemukan');
			
			$payroll = $query->row_array();
			if ($payroll['STATUS'] != 'C')
				json_error('Maaf, payroll ini tidak bisa dicetak karena statusnya belum selesai');
			
			$this->db->query("update payroll set printed_at = sysdate,printed_by = ? where id = ?", 
				array($_SESSION['profile']['user_id'], $id));

			json_success();
		}

        public function delete() {  
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select * from payroll where printed_at is not null and id = ?", $_POST['ID']);
			if ($query->num_rows() > 0)
				json_error('Maaf, payroll ini tidak bisa dihapus karena sudah tercetak');

			deleteRecord('PAYROLL', $_POST, array('ID'));

			json_success();
		}
    }  
?>
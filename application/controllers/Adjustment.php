<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Adjustment extends MY_Controller {  
          
        public function index() {  
			validateSession();
			
			layout('adjustment', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			if (!isset($_GET['month'])) $_GET['month'] = date('Y-m');

			$query = $this->db->query("select a.*,decode(a.code,
					'infaq','Infaq',
					'koperasi','Koperasi', 
					'installment','Cicilan Pinjaman'
				) code, b.name emp_name,
				to_char(a.adjust_at,'DD-Mon-YYYY') adjust_at,
				to_char(a.trx_at,'DD-Mon-YYYY') trx_at,
				a.amount*-1 amount
				from adjustment a, employee b
				where a.amount < 0 and a.emp_id = b.id and to_char(a.adjust_at,'YYYY-MM') = ?
				order by a.adjust_at,b.name", $_GET['month']);
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editAdjustment('."'".$rec['ID']."'".')"></i>';
				$data[] = $rec; $i++;
			}
			
			echo json_encode(array(
				"page" => 1,
                "total" => $i,
				"records" => $i,
				"rows" => $data
			));
		}
		
		public function get($id) {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select a.*,b.name emp_name,(a.amount*-1) amount 
				from adjustment a,employee b where a.emp_id = b.id and a.id = ?", $id);
			$rec = $query->row_array();
			if (!isset($rec)) json_notfound();

			json_success($rec);
		}
		
		private function validate() {
			if ($_POST['ADJUST_AT'] == '') 	json_error('Tanggal mulai harus diisi');
			if ($_POST['CODE'] == '') 		json_error('Jenis pemotongan harus dipilih');
			if ($_POST['EMP_ID'] == '') 	json_error('Pegawai harus dipilih');
		}
		
		public function create() {
			if (!isGranted()) json_forbidden();

			$this->validate();
			$_POST['AMOUNT'] = $_POST['AMOUNT']*-1;
			insertRecord('ADJUSTMENT', $_POST, array('ID'));

			json_success();
		}

        public function update() {  
			if (!isGranted()) json_forbidden();

			$this->validate();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, data tidak bisa diubah, karena sudah masuk proses payroll');

			$_POST['AMOUNT'] = $_POST['AMOUNT']*-1;
			updateRecord('ADJUSTMENT', $_POST, array('ID'));

			json_success();
        }
      
        public function delete() {  
			if (!isGranted()) json_forbidden();

			if ($this->alreadyCommitted($_POST['ID']))
				json_error('Maaf, data tidak bisa dihapus, karena sudah masuk proses payroll');
			
			deleteRecord('ADJUSTMENT', $_POST, array('ID'));

			json_success();
		}
		
		private function alreadyCommitted($id) {
			$query = $this->db->query("select * from adjustment where id = ? and payslip_id is not null", $id);
			return ($query->num_rows() > 0);
		}

		public function upload() {
			if (!isGranted()) json_forbidden();

			if (!isset($_POST["code"]) || !isset($_POST['fileContent']))
				json_error("Maaf, data yang dikirim tidak lengkap");

			$codes = array("infaq", "koperasi");
			if (!in_array($_POST["code"], $codes))
				json_error("Maaf, operasi ini tidak bisa disupport");

			$lines = explode("\r\n", $_POST['fileContent']);

			$this->db->trans_start();

			foreach($lines as $line) {
				$field = explode("\t", $line);
				
				$query = $this->db->query("select * from employee where code = ?", $field[0]);
				if ($query->num_rows() == 0) continue;

				$employee = $query->row_array();				
				$data = array(
					'ID' => '0',
					'EMP_ID' => $employee['ID'],
					'CODE' => $_POST['code'],
					'ADJUST_AT' => date('F d, Y'),
					'TRX_AT' => date('F d, Y'),
					'AMOUNT' => -1*$field[2],
					'REF_NO' => array_nvl($_POST, 'ref_no', '-'),
					'STATUS' => 'A'					
				);
				insertRecord('ADJUSTMENT', $data, array('ID'));
			}

			$this->db->trans_complete();

			json_success();
		}
    }  
?>
<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Paydays extends MY_Controller {  
          
        public function index() {  
			validateSession();
			
			layout('paydays', array());
        }  
		
		public function query() {
			if (!isGranted()) json_forbidden();

			$query = $this->db->query("select a.*,decode(a.code,
					'salary', 'Penggajian Bulanan',
					'incentive', 'Tunjangan Bulanan',
					'honorium', 'Honor Pekerja Harian'
				) code,to_char(to_date(period||'01','YYYYMMDD'),'Month YYYY') period,
				to_char(start_at,'DD-Mon-YYYY') start_at,to_char(end_at,'DD-Mon-YYYY') end_at,
				to_char(due_at,'DD-Mon-YYYY') due_at,to_char(created_at,'DD-Mon-YYYY') created_at,
				to_char(printed_at,'DD-Mon-YYYY') printed_at,
				decode(status,'Q','Masuk Antrian','P','Sedang Proses','C','Selesai Proses') status 
				from payroll a where to_char(a.created_at,'YYYY') = '".$_GET['year']."' 
				order by a.created_at desc, a.id desc");
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
				$rec['CMD'] = '<i class="edit icon" onclick="editPayroll('."'".$rec['ID']."'".')"></i>';
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
    }  
?>
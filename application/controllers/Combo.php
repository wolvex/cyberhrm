<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Combo extends MY_Controller {  
          
        public function index() {  
            
        }  
		
		public function query() {
			//if (!isGranted()) json_forbidden();

			$entity = explode(' ',$_GET['entity']);

			$sql = ""; $data = array();
			switch($entity[0]) {
				case 'department':
					$sql = "select -255 ID,'Semua Departemen' LABEL from dual union all 
						select ID,name||' ('||code||')' from dept where type = 1 order by id";
					break;
				case 'dept':
					$sql = "select ID,name||' ('||code||')' LABEL from dept where type = 1 order by id";
					break;
				case 'division':
					$sql = "select ID,substr(NAME,4)||' ('||substr(CODE,4)||')' AS LABEL from (
						select a.id,sys_connect_by_path(a.name,' - ') as NAME,sys_connect_by_path(a.code,' - ') as CODE
						from dept a,dept b where a.parent = b.id(+)
						start with a.parent is null connect by prior a.id = a.parent 
						order siblings by a.id)";
					break;
				case 'absence_type':
					$data[] = array("ID" => "0", "LABEL" => "Ijin/Cuti");
					$data[] = array("ID" => "1", "LABEL" => "Lembur");
					break;
				case 'job':
					$sql = "select ID, TITLE AS LABEL from job order by TITLE";
					break;
				case 'grade':
					$sql = "select a.ID,a.NAME AS LABEL 
						from grade a order by a.ID";
					break;		
				case 'certificate':
					$sql = "select ID,description as LABEL from certificate order by id";
					break;
				case 'schema':
					$sql = "select ID,name as LABEL from schema where status not in ('I', 'D') and 
						sysdate between effective_at and nvl(expire_at,sysdate+1)
						order by effective_at desc";
					break;
				case 'shift':
					$sql = "select ID,description||' ('||to_char(start_time,'HH24:MI')||' - '||to_char(end_time,'HH24:MI')||')' LABEL
						from shift order by code";
					break;
				case 'absence':
					$sql = "select ID,code||' - '||description LABEL from absence where type = 0 order by code";
					break;
				case 'adjustment':
					$data[] = array("ID" => "infaq", "LABEL" => "Infaq");
					$data[] = array("ID" => "koperasi", "LABEL" => "Koperasi");
					$data[] = array("ID" => "installment", "LABEL" => "Cicilan Pinjaman");
					break;
				case 'reimburse':
					$data[] = array("ID" => "meal", "LABEL" => "Uang Makan");
					$data[] = array("ID" => "medical", "LABEL" => "Biaya Kesehatan");
					break;
				case 'overtime':
					$data[] = array("ID" => "5", "LABEL" => "Lembur Biasa");
					$data[] = array("ID" => "6", "LABEL" => "Lembur Libur");
					$data[] = array("ID" => "7", "LABEL" => "Lembur Istimewa");
					break;
				case 'ovrcat':
					if (!isset($entity[1])) $entity[] = '0';
					$sql = "select ID,description LABEL from ovrcat 
						where dept_id is null or dept_id = ".$entity[1]." order by dept_id,code";
					break;
				case 'payroll':
					$data[] = array("ID" => "salary", "LABEL" => "Penggajian Bulanan");
					$data[] = array("ID" => "incentive", "LABEL" => "Tunjangan Bulanan");
					$data[] = array("ID" => "honorium", "LABEL" => "Honor Pekerja Harian");
					break;
				case 'timesheet':
					$date = $entity[1];
					$sql = "select absent_id as id, 'Masuk: '||to_char(b.clock_in,'DD-Mon HH24:MI')||
							' //  Pulang: '||to_char(b.clock_out,'DD-Mon HH24:MI')||
							' //  Aktual: '||-1*(early_minute + late_minute)||' menit' as label
						from timesheet a,absent b 
						where a.absent_id = b.id and a.emp_id = '".$entity[1]."' and to_char(work_at,'YYYY-MM-DD') = '".$entity[2]."'";
			}

			if ($sql != '') {
				$query = $this->db->query($sql); $i = 0;
				foreach ($query->result_array() as $rec) {
					$data[] = $rec; $i++;
				}
			}

			echo json_encode($data);
		}

    }  
?>  

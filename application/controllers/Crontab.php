<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Crontab extends MY_Controller {  
          
        public function index() {  
            
		}  
		
		public function populateTimesheet() {
			$dir = UPLOAD_DIR."/timesheet";
			$files = scandir($dir);

			$query = $this->db->query("select id,code from shift union all select id,code from absence");
			$codes = array();
			if ($rows = $query->result_array()) {
				foreach($rows as $row) {
					$codes[$row['CODE']] = $row['ID'];
				}
			}

			log_message('info', print_r($codes));

			if (count($codes) <= 0) return;

			foreach ($files as $name) {
				if (!endsWith($name, ".txt")) continue;

				if ($file = fopen($dir."/".$name, "r")) {
					$year = 0; $month = 0;
					while(!feof($file)) {
						$line = fgets($file);
						$data = explode("\t", $line);
						
						if (count($data) < 2) continue;

						if ($data[1] == 'Tahun') {
							$year = $data[2]; continue;
						} else if ($data[1] == 'Bulan') {
							$month = $data[2]; continue;
						} else if ($year == 0 || $month == 0 || trim($data[1]) == '' || count($data) < 30) {
							continue;
						}

						$id = substr('0000'.$data[1],-4);

						$query = $this->db->query("select id from employee where code = ?", $id);
						if ($rec = $query->row_array()) {
							$id = $rec['ID'];
						} else continue;

						for ($i=3; $i<count($data); $i++) {
							if (!isset($codes[$data[$i]])) continue;
							//if (strpos($codes, '|'.$data[$i].'|') === false) continue;

							$d = date_create_from_format('Y-n-j', $year.'-'.$month.'-'.($i-2));
							$date = date_format($d, 'Y-m-d');

							$query = $this->db->query("select id,nvl(work_minute,0) work_minute
								from timesheet where emp_id = ? and to_char(work_at,'YYYY-MM-DD') = ?", array($id, $date));
							if ($rec = $query->row_array()) {
								if ($rec['WORK_MINUTE'] != 0) continue;
								
								$query = $this->db->query("delete from timesheet 
									where emp_id = ? and to_char(work_at,'YYYY-MM-DD') = ?", array($id, $date));
							}

							$this->db->query("insert into timesheet (id,emp_id,work_at,shift_id) values 
								(seqtimesheet.nextval,?,to_date(?,'YYYY-MM-DD'),?)", array($id, $date, $codes[$data[$i]]));
						}						
					}
					fclose($file);
					rename($dir.'/'.$name, $dir.'/'.$name.'.done');
				}
			}
		}
		
		public function createLeaveBucket() {
			$codes = array('CT','CH','CM','CI');
			$today = date_create(date('Y-m-d'));

			foreach ($codes as $code) {
				$query = $this->db->query("select a.id,to_char(a.borned_at,'YYYY-MM-DD') borned_at,
					to_char(a.joined_at,'YYYY-MM-DD') joined_at, to_char(nvl(c.expire_at,sysdate-365),'YYYY-MM-DD') expire_at,
					nvl(balance,0) last_balance
					from employee a,(
						select emp_id,max(id) id from bucket where code = ? group by emp_id
					) b, bucket c
					where a.id = b.emp_id(+) and b.id = c.id(+)", $code);

				$rows = $query->result_array(); 
				foreach($rows as $rec) {
					$joined = date_create($rec['JOINED_AT']);
					$workage = date_diff($today, $joined);
					if ($workage->y < 1) continue; //kalau belum 1 tahun bekerja, tdk dpt cuti
					if ($workage->y < 10 && $code == 'CI') continue; //kalau belum 10 tahin bekerja, belum dpt cuti istimewa

					$expired = date_create($rec['EXPIRE_AT']);
					if ($today <= $expired) continue; //kalau bucket existing belum expire, skip

					$borned = date_create($rec['BORNED_AT']);
					if (date_format($today, 'm-d') < date_format($borned, 'm-d')) continue; //tanggal lahir belum sampai, skip

					$query = $this->db->query("select b.*,c.* from v_empschema a,schema_absence b,absence c 
						where a.SCHEMA_ID = b.schema_id and b.absence_id = c.id and a.emp_id = ? and c.code = ?",
						array($rec['ID'], $code));
					
					if ($schema = $query->row_array()) {

						$dte = date_create(date('Y-').date_format($borned, 'm-d'));
						$eff = date_format($dte, 'F d, Y');
						if ($code == 'CI') {
							date_add($dte, date_interval_create_from_date_string("5 year -1 day"));
						} else {
							date_add($dte, date_interval_create_from_date_string("1 year -1 day"));
						}
						$exp = date_format($dte, 'F d, Y');

						$balance = $rec['LAST_BALANCE'];
						if ($balance > $schema['CARRY_OVER']) {
							$balance = $schema['CARRY_OVER'];
						}
						$balance = $balance + $schema['ANNUAL_QUOTA'];

						$data = array(
							'ID' => '0',
							'EMP_ID' => $rec['ID'],
							'CODE' => $code,
							'BALANCE' => $balance,
							'EFFECTIVE_AT' => $eff,
							'EXPIRE_AT' => $exp
						);
						insertRecord('BUCKET', $data, array('ID'));

					}					
				}
	
			}
			
		}

		public function extractAbsentData() {
			$data = array();

			foreach($data as $rec) {
				$this->db->query("insert into amano (nip,optype,optime) values (?,?,?)", 
					array($rec['nip'], $rec['optype'], $rec['optime']));
				
				$time = strtotime($rec['optime']);

				if ($rec['optype'] == 'clock_in') {

					//to prevent clock in duplication, get possible alreay existent absent data ranging 5 hours prior and post clock in
					$query = $this->db->query("select id,to_char(clock_in,'YYYY-MM-DD HH24:MI') clock_in 
						from absent where to_char(clock_in,'YYYY-MM-DD HH24:MI') between ? and ?",
						array(date('Y-m-d H:i', $time-(5*3600)), date('Y-m-d H:i', $time+(5*3600))));
					if ($query->num_rows() == 0){
						//create new absent data
						$this->db->query("insert into absent (id, emp_id, clock_in, status, modified_at, modified_by)
							values (seqabsent.nextval,(select id from employee where code = ?),to_date(?,'YYYY-MM-DD HH24:MI'),
							'A',sysdate,'system')", array($rec['nip'], date('Y-m-d H:i', $time)));
					} else {
						$row = $query->first_row();
						$t = strtotime($row['CLOCK_IN']);
						if ($time < $t) {
							$this->db->query("update absent set clock_in = to_date(?,'YYYY-MM-DD HH24:MI') where id = ?",
								array(date('Y-m-d H:i', $time), $row['ID']));
						}
					}

				} else {

					//to prevent clock out duplication, get possible alreay existent absent data ranging 5 hours prior and post clock out
					$query = $this->db->query("select id,to_char(clock_out,'YYYY-MM-DD HH24:MI') clock_out
						from absent where to_char(clock_out,'YYYY-MM-DD HH24:MI') between ? and ?",
						array(date('Y-m-d H:i', $time-(5*3600)), date('Y-m-d H:i', $time+(5*3600))));
					if ($query->num_rows() == 0){
						//find existent clock in data to match
						$query = $this->db->query("select id,to_char(clock_in,'YYYY-MM-DD HH24:MI') clock_in
							from absent where clock_in is not null and clock_out is null and 
							to_char(clock_in,'YYYY-MM-DD HH24:MI') between ? and ?",
						array(date('Y-m-d H:i', $time-(5*3600)), date('Y-m-d H:i', $time+(5*3600))));

						//create new absent data
						$this->db->query("insert into absent (id, emp_id, clock_in, status, modified_at, modified_by)
							values (seqabsent.nextval,(select id from employee where code = ?),to_date(?,'YYYY-MM-DD HH24:MI'),
							'A',sysdate,'system')", array($rec['nip'], date('Y-m-d H:i', $time)));
					} else {
						$row = $query->first_row();
						$t = strtotime($row['CLOCK_IN']);
						if ($time < $t) {
							$this->db->query("update absent set clock_in = to_date(?,'YYYY-MM-DD HH24:MI') where id = ?",
								array(date('Y-m-d H:i', $time), $row['ID']));
						}
					}

				}
			}
		}

		public function resolveTimesheet() {
			$query = $this->db->query("select a.*,
				to_char(a.work_at,'YYYY-MM-DD') start_date,	to_char(a.work_at+1,'YYYY-MM-DD') end_date,
				to_char(b.start_time,'HH24:MI') start_clock, to_char(b.end_time,'HH24:MI') end_clock
				from timesheet a,shift b
				where a.shift_id = b.id and absent_id is null and payslip_id is null and 
				incentive_id is null and (sysdate-work_at) <= 30
				order by emp_id,work_at");
			$rows = $query->result_array();
			foreach($rows as $row) {
				$start = strtotime($row['START_DATE'].' '.$row['START_CLOCK']);
				$end   = strtotime($row['START_DATE'].' '.$row['END_CLOCK']);
				if ($row['START_CLOCK'] > $row['END_CLOCK'])
					$end = strtotime($row['END_DATE'].' '.$row['END_CLOCK']);

				//find absent data that can be linked to timesheet. Tolerance 1 hour before and 4 hours after shift start time
				$query = $this->db->query("select a.id,to_char(a.clock_in,'YYYY-MM-DD HH24:MI') clock_in,
					to_char(a.clock_out,'YYYY-MM-DD HH24:MI') clock_out
					from absent a where clock_in is not null and clock_out is not null and
					emp_id = ? and to_char(clock_in,'YYYY-MM-DD HH24:MI') between ? and ? order by clock_in", 
					array($row['EMP_ID'], date('Y-m-d H:i', $start-(1*3600)), date('Y-m-d H:i', $start+(4*3600))));
				$absent = $query->result_array();
				foreach($absent as $data) {
					$early = 0; $late = 0; $work = 0;

					$clock_in  = strtotime($data['CLOCK_IN']);
					$clock_out = strtotime($data['CLOCK_OUT']);

					if ($clock_out > $start && $clock_in < $end) {
						if ($start < $clock_in) $late = ($clock_in-$start)/60;
						if ($end > $clock_out) $early = ($end-$clock_out)/60;

						$work = ($clock_out-($start > $clock_in ? $start : $clock_in))/60;
						
						$this->db->query("update timesheet set absent_id = ?, work_minute = ?, late_minute = ?,
							early_minute = ? where id = ?", array(
								$data['ID'], $work, $late, $early, $row['ID']
							));
						
						break;
					}
				}
			}
			
		}

		public function resolveOvertime() {
			$query = $this->db->query("select a.*, to_char(a.start_clock,'YYYY-MM-DD HH24:MI') start_time,
				to_char(a.end_clock,'YYYY-MM-DD HH24:MI') end_time
				from overtime a
				where absent_id is null and payslip_id is null and (sysdate-start_clock) <= 30
				order by emp_id,start_clock");
			$rows = $query->result_array();
			foreach($rows as $row) {
				$start 		= strtotime($row['START_TIME']);
				$end   		= strtotime($row['END_TIME']);
			
				//find absent data that can be linked to timesheet. Tolerance 1 hour before and 4 hours after SPL start time
				$query = $this->db->query("select a.id,to_char(a.clock_in,'YYYY-MM-DD HH24:MI') clock_in,
					to_char(a.clock_out,'YYYY-MM-DD HH24:MI') clock_out
					from absent a where clock_in is not null and clock_out is not null and
					emp_id = ? and to_char(clock_in,'YYYY-MM-DD HH24:MI') between ? and ? order by clock_in", 
					array($row['EMP_ID'], date('Y-m-d H:i', $start-(1*3600)), date('Y-m-d H:i', $start+(4*3600))));
				$absent = $query->result_array();
				foreach($absent as $data) {
					$early = 0; $late = 0; $work = 0;

					$clock_in  	= strtotime($data['CLOCK_IN']);
					$clock_out 	= strtotime($data['CLOCK_OUT']);

					if ($clock_out > $start && $clock_in < $end) {
						$work = (($clock_out > $end ? $end : $clock_out)-($start > $clock_in ? $start : $clock_in))/60;				
						
						$this->db->query("update overtime set absent_id = ?, work_minute = ? where id = ?", 
							array($data['ID'], $work, $row['ID']));
						break;
					}
				}
			}
			
		}
	}  
?>  

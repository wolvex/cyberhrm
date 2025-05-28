<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Payroll extends MY_Controller {  
          
        public function index() {  
            
        }  
		
		public function process($id = 0) {
			$rows = array();

			if ($id == 0) {
				$query = $this->db->query("select t.*,to_char(t.due_at,'Month DD, YYYY') due_at,
					to_char(t.start_at,'Month DD, YYYY') start_at,to_char(t.end_at,'Month DD, YYYY') end_at
					from payroll t where status = 'Q' order by created_at,id");
				$rows = $query->result_array();
			} else {
				$query = $this->db->query("select t.*,to_char(t.due_at,'Month DD, YYYY') due_at,
					to_char(t.start_at,'Month DD, YYYY') start_at,to_char(t.end_at,'Month DD, YYYY') end_at
					from payroll t where status = 'Q' and id = ?", $id);
				$rows = $query->result_array();
			}

			foreach ($rows as $rec) {
				log_message('info', 'Processing payroll id '.$rec['ID']);

				$this->db->query("update payroll set status = 'I' where status = 'Q' and id = ?", $rec['ID']);
				if ($this->db->affected_rows() == 0) continue;

				$this->db->query("delete from payslip where payroll_id = ?", $rec['ID']);

				switch($rec['CODE']) {
					case 'salary':
						$this->payslip($rec); break;
					case 'incentive':
						$this->incentive($rec); break;
						break;
					case 'honorium':
						$this->honorium($rec); break;
				}

				$this->db->query("update payroll set status = 'C' where status = 'I' and id = ?", $rec['ID']);				
			}

            json_success();
		}
		
		private function payslip($payroll) {
			$employees = $this->getEmployess('PK'); //get  employee with P & K status

			foreach($employees as $employee) {
				log_message('info', 'Processing payslip for employee id '.$employee['EMPLOYEE_ID']);

				$this->db->trans_start();

				$payslip = $this->createPayslip($payroll, $employee);

				$payItems = array();
				$payItems = $this->calcSalary($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcInsurance($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcUnionFee($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcAdjustment($payslip, $payroll, $employee, $payItems);

				//calculate total nett salary
				$payItems = $this->calcTotal($payItems);

				$this->addPayItems($payslip['ID'], $payItems);

				$this->db->trans_complete();
			}
		}

		private function incentive($payroll) {
			$employees = $this->getEmployess('PK'); //get  employee with P & K status

			foreach($employees as $employee) {
				log_message('info', 'Processing incentive for employee id '.$employee['EMPLOYEE_ID']);

				$this->db->trans_start();

				$payslip = $this->createPayslip($payroll, $employee);

				$payItems = array();
				$payItems = $this->calcFoodIncentive($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcReimbursement($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcGradeIncentive($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcPremiIncentive($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcOvertime($payslip, $payroll, $employee, $payItems);

				//calculate total nett incentive
				$payItems = $this->calcTotal($payItems);

				$this->addPayItems($payslip['ID'], $payItems);

				$this->db->trans_complete();
			}
		}

		private function honorium($payroll) {
			$employees = $this->getEmployess('H'); //get  employee with H status

			foreach($employees as $employee) {
				log_message('info', 'Processing payslip for employee id '.$employee['EMPLOYEE_ID']);

				$this->db->trans_start();

				$payslip = $this->createPayslip($payroll, $employee);

				$payItems = array();
				$payItems = $this->calcSalary($payslip, $payroll, $employee, $payItems);

				if (date_format(date_create($payroll['END_AT']), 'd') > '15') {
					$payItems = $this->calcInsurance($payslip, $payroll, $employee, $payItems);
					$payItems = $this->calcUnionFee($payslip, $payroll, $employee, $payItems);
					$payItems = $this->calcAdjustment($payslip, $payroll, $employee, $payItems);
				}

				$payItems = $this->calcFoodIncentive($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcReimbursement($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcGradeIncentive($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcPremiIncentive($payslip, $payroll, $employee, $payItems);
				$payItems = $this->calcOvertime($payslip, $payroll, $employee, $payItems);

				//calculate total nett salary
				$payItems = $this->calcTotal($payItems);

				$this->addPayItems($payslip['ID'], $payItems);

				$this->db->trans_complete();
			}
		}

		private function getEmployess($status = "PHK") {
			$query = $this->db->query("select a.*,b.*,c.*,d.*,b.id carier_id,c.id empschema_id,
				a.id employee_id,nvl(e.wives,0) wives,nvl(e.children1,0) children1,nvl(e.children2,0) children2
				from employee a,v_carier b, v_empschema c,schema d,(
					select emp_id,
						sum(decode(relation_type,'S',1,0)) wives,
						sum(decode(relation_type,'C',
							decode(sign(age(borned_at)-21),-1,1,0)
						,0)) children1,
						sum(decode(relation_type,'C',
							decode(sign(age(borned_at)-21),-1,0,1)
						,0)) children2
					from family group by emp_id
				) e
				where a.id = b.emp_id and a.id = c.emp_id and instr(?, b.emp_status) > 0 and a.id = e.emp_id(+) and 
				c.schema_id = d.id(+)
				order by b.emp_status desc,b.dept_id,a.id", $status);
			return $query->result_array();
		}

		private function createPayslip($payroll, $employee) {
			$payslip = array(
				'ID' => '0',
				'CODE' => $payroll['CODE'],
				'EMP_ID' => $employee['EMPLOYEE_ID'],
				'CARIER_ID' => $employee['CARIER_ID'],
				'EMPSCHEMA_ID' => $employee['EMPSCHEMA_ID'],
				'PERIOD' => $payroll['PERIOD'],
				'STATUS' => 'A',
				'ISSUED_AT' => $payroll['DUE_AT'],
				'DUE_AT' => $payroll['DUE_AT'],
				'START_AT' => $payroll['START_AT'],
				'END_AT' => $payroll['END_AT'],
				'PAYROLL_ID' => $payroll['ID']
			);
			$keys = insertRecord('PAYSLIP', $payslip, array('ID'));
			$payslip['ID'] = $keys['ID'];
			return $payslip;
		}

		private function calcTotal($payItems) {
			$nett = 0; $infaq = 0;
			$exclude = array("ovrjal","ovrjbl","premiday","absentday","salaryday","gross","jkkprs","jkmprs","jhtprs","jpprs");
			foreach($payItems as $item => $amount) {
				if ($item == 'infaq') {
					$infaq = $amount;
				} elseif (!in_array($item, $exclude)) {
					$nett = $nett + $amount;
				}
			}
			$rounding = (100-($nett % 100));
			if ($rounding == 100) $rounding = 0;

			$payItems['nett'] = $nett;
			$payItems['rounding'] = $rounding;
			$payItems['grdtot'] = $nett + $rounding + $infaq;

			return $payItems;
		}

		private function calcSalary($payslip, $payroll, $employee, $payItems) {
			//hitung hari masuk & mangkir
			$query = $this->db->query("select a.*,b.min_work_hour,nvl(c.status,'-') absent_status,nvl(d.absence_code,'-') absence_code
				from timesheet a,shift b, 
					(select * from absent where emp_id = ? and status = 'A') c,
					(select t.*,s.code absence_code from onleave t,absence s where t.absence_id = s.id and t.status = 'A' and emp_id = ?) d
				where a.shift_id = b.id and a.absent_id = c.id(+) and a.onleave_id = d.id(+) and
				a.emp_id = ? and a.payslip_id is null and to_char(work_at,'YYYY-MM-DD') between ? and ? 
				order by a.id", array(
					$employee['EMPLOYEE_ID'],$employee['EMPLOYEE_ID'],$employee['EMPLOYEE_ID'],
					date_format(date_create($payslip['START_AT']), 'Y-m-d'),
					date_format(date_create($payslip['END_AT']), 'Y-m-d')
				));
			$rows = $query->result_array(); $days_in = 0; $days_out = 0;
			foreach($rows as $rec) {
				if ($rec['SHIFT_ID'] == 0) continue;
				
				$this->db->query("update timesheet set payslip_id = ? where id = ? and payslip_id is null", 
					array($payslip['ID'], $rec['ID']));
				if ($this->db->affected_rows() == 0) continue;

				if ($rec['ABSENT_STATUS'] == 'A') {
					if (($rec['WORK_MINUTE']/60) >= $rec['MIN_WORK_HOUR']) {
						$days_in++;
					} else {
						$days_out++;
					}
				} elseif ($rec['ABSENCE_CODE'] != '-') {
					if ($rec['ABSENCE_CODE'] == 'IT') { //ijin tanpa bayar
						$days_out++;
					} else {
						$days_in++;
					}
				} else {
					$days_out++;
				}
			}			
			
			//employee doesn't seem to have timesheet in system
			//if ($days_in == 0 && $days_out == 0) return $payItems;

			//normalize employee's wage
			$salary = $employee['GROSS_WAGE']; $absent = 0;
			//if ($salary < $employee['MIN_WAGE']) $salary = $employee['MIN_WAGE'];

			$payItems = sum_array($payItems, 'gross', $salary);

			if ($employee['EMP_STATUS'] == 'H') {
				//$days_in = 14; $days_out = 0;
				//hitung gaji berdasarkan hari masuk (pegawai harian)
				$salary = ceil(($days_in/$employee['DAYS_IN_MONTH']) * $salary);
			} else {
				//hitung potongan berdasarkan hari mangkir (pegawai permanen & kontrak)
				$absent = ceil(($days_out/$employee['DAYS_IN_MONTH']) * $salary);
			}

			$payItems = sum_array($payItems, 'salary', $salary);
			$payItems = sum_array($payItems, 'salaryday', $days_in);
			$payItems = sum_array($payItems, 'absentday', $days_out);
			$payItems = sum_array($payItems, 'absentcost', -1*$absent);
			
			return $payItems;
		}

		private function calcInsurance($payslip, $payroll, $employee, $payItems) {
			$wage = $payItems['gross'];

			$jhtfee = ceil($employee['JHT_PGW']/100*$wage);
			$jhtprs = ceil($employee['JHT_PRSH']/100*$wage);
			$jkmprs = ceil($employee['JKM_PRSH']/100*$wage);
			$jkkprs = ceil($employee['JKK_PRSH']/100*$wage);
			$jpfee = 0; $jpprs = 0;

			$diff = date_diff(date_create(date('Y-m-d')), date_create($employee['BORNED_AT']));
			if ($diff->y <= $employee['INSURED_AGE']) {
				if ($wage > $employee['INSURED_WAGE'])
					$wage = $employee['INSURED_WAGE'];

				$jpfee = ceil($employee['JP_PGW']/100*$wage);
				$jpprs = ceil($employee['JP_PRSH']/100*$wage);
			}
			
			$payItems['jpfee']  = -1*$jpfee;
			$payItems['jhtfee'] = -1*$jhtfee;
			$payItems['jpprs']  = -1*$jpprs;
			$payItems['jhtprs'] = -1*$jhtprs;
			$payItems['jkkprs'] = -1*$jkkprs;
			$payItems['jkmprs'] = -1*$jkmprs;
			
			return $payItems;
		}

		private function calcUnionFee($payslip, $payroll, $employee, $payItems) {
			$wage = $payItems['salary'];
			$fee = -1*ceil($employee['UNION_FEE']/100*$wage);

			$payItems['spsi'] = $fee;
			return $payItems;
		}

		private function calcAdjustment($payslip, $payroll, $employee, $payItems) {
			$query = $this->db->query("select *	from adjustment 
				where code in ('koperasi','installment') and status = 'A' and payslip_id is null and emp_id = ?
				order by id", $employee['EMPLOYEE_ID']);
			$rows = $query->result_array();
			foreach ($rows as $rec) {
				$this->db->query("update adjustment set payslip_id = ? where payslip_id is null and id = ?", 
					array($payslip['ID'], $rec['ID']));
				if ($this->db->affected_rows() == 0) continue;
			
				$payItems = sum_array($payItems, $rec['CODE'], $rec['AMOUNT']);
			}
			
			return $payItems;
		}

		/** incentive calculation */
		private function calcFoodIncentive($payslip, $payroll, $employee, $payItems) {
			$kg = $employee['FOOD_KG_SELF'];
			if ($employee['GENDER'] == 'P') {
				$kg = $kg + ($employee['FOOD_KG_WIFE']*$employee['WIVES']) + ($employee['FOOD_KG_CHILD']*$employee['CHILDREN1']);
			}

			return sum_array($payItems, 'beras', $kg * $employee['FOOD_KG_PRICE']);
		}

		private function calcReimbursement($payslip, $payroll, $employee, $payItems) {
			$query = $this->db->query("select *	from adjustment 
				where code in ('infaq','meal','medical','bonus') and status = 'A' and 
				payslip_id is null and emp_id = ? order by id", $employee['EMPLOYEE_ID']);
			$rows = $query->result_array();
			foreach ($rows as $rec) {
				$this->db->query("update adjustment set payslip_id = ? where payslip_id is null and id = ?", 
					array($payslip['ID'], $rec['ID']));
				if ($this->db->affected_rows() == 0) continue;
			
				$payItems = sum_array($payItems, $rec['CODE'], $rec['AMOUNT']);

				if ($rec['CODE'] == 'meal' && $rec['AMOUNT'] > 0)
					$payItems = sum_array($payItems, 'mealday', 1);
			}
		
			return $payItems;
		}

		private function calcPremiIncentive($payslip, $payroll, $employee, $payItems) {
			$query = $this->db->query("select a.*,c.min_work_hour,b.premi_attendance,b.premi_shift,allowance
				from timesheet a,schema_shift b,shift c,(select * from absent where emp_id = ? and status = 'A') d
				where a.shift_id = b.shift_id and b.shift_id = c.id and a.absent_id = d.id and 
				a.incentive_id is null and a.emp_id = ? and b.schema_id = ? and 
				to_char(a.work_at,'YYYY-MM-DD') between ? and ? order by a.id", 
				array(
					$employee['EMPLOYEE_ID'], $employee['EMPLOYEE_ID'], $employee['SCHEMA_ID'],
					date_format(date_create($payslip['START_AT']), 'Y-m-d'),
					date_format(date_create($payslip['END_AT']), 'Y-m-d')
				));
			$rows = $query->result_array();
			foreach($rows as $row) {
				$this->db->query("update timesheet a set incentive_id = ? where incentive_id is null and id = ?",
					array($payslip['ID'], $row['ID']));
				if ($this->db->affected_rows() == 0) continue;
				if (($row['WORK_MINUTE']/60) < $row['MIN_WORK_HOUR']) continue;

				$payItems = sum_array($payItems, 'premiday', 1);
				$payItems = sum_array($payItems, 'premi', $row['PREMI_ATTENDANCE']);
				$payItems = sum_array($payItems, 'shift', $row['PREMI_SHIFT']);
				$payItems = sum_array($payItems, 'meal', $row['ALLOWANCE']);

				if ($row['ALLOWANCE'] > 0)
					$payItems = sum_array($payItems, 'mealday', 1);
			}
			return $payItems;
		}

		private function calcOvertime($payslip, $payroll, $employee, $payItems) {
			$wage = ceil(($employee['GROSS_WAGE']+$payItems['beras']+$payItems['allowance'])/$employee['HOURS_IN_MONTH']);
			
			$query = $this->db->query("select a.*,b.*,c.code absence_code,a.id overtime_id
				from overtime a,schema_overtime b,absence c
				where a.absence_id = b.absence_id and b.absence_id = c.id and a.payslip_id is null and
				a.emp_id = ? and b.schema_id = ? and to_char(a.start_clock,'YYYY-MM-DD') between ? and ?
				order by a.id", 
				array(
					$employee['EMPLOYEE_ID'], $employee['SCHEMA_ID'],
					date_format(date_create($payslip['START_AT']), 'Y-m-d'),
					date_format(date_create($payslip['END_AT']), 'Y-m-d')
				));
			$rows = $query->result_array(); 
			$actual_hours = 0; $paid_hours = 0; $premi = 0; $shift = 0;
			foreach($rows as $row) {
				log_message('debug', 'Processing overtime '.print_r($row, true));

				$this->db->query("update overtime set payslip_id = ? where payslip_id is null and id = ?", 
					array($payslip['ID'], $row['OVERTIME_ID']));
				if ($this->db->affected_rows() == 0) continue;
				
				if ($row['WORK_MINUTE'] < 60) continue;

				$duration = floor($row['WORK_MINUTE']/60);
				$minutes = $row['WORK_MINUTE'] % 60;
				if ($minutes > 30) {
					$duration++;
				} elseif ($minutes > 0) {
					$duration += 0.5;
				}
				$actual_hours += $duration;

				//calculate tiered tariff
				for($i=1;$i<=4;$i++) {
					if ($row['HOUR'.$i] == 0 || $duration <= 0) continue;
					
					$hour = $row['HOUR'.$i];
					if ($duration < $hour) $hour = $duration;

					$paid_hours += ($hour * $row['FACTOR'.$i]);
					$duration = $duration - $hour;
				}

				log_message('debug', 'Actual hour: '.$paid_hours.' // Duration: '.$duration);

				//verifiy shift
				if ($row['ABSENCE_CODE'] == 'LI' || ceil($row['WORK_MINUTE']/60) < 4) continue;
				$query = $this->db->query("select * from shift a,schema_shift b	
					where a.id = b.shift_id and b.schema_id = ? and ? between to_char(start_time,'HHMI') and to_char(end_time,'HHMI') 
					order by to_char(start_time,'HHMI') desc",
					array($employee['SCHEMA_ID'], date_format(date_create($payslip['START_AT']), 'Hi')));
				$rec = $query->first_row();
				$premi = $premi + $rec['PREMI_ATTENDANCE'];
				$shift = $shift + $rec['PREMI_SHIFT'];
			}

			$payItems = sum_array($payItems, 'ovrjal', $actual_hours);
			$payItems = sum_array($payItems, 'ovrjbl', $paid_hours);
			$payItems = sum_array($payItems, 'overtime', ceil($paid_hours*$wage));
			$payItems = sum_array($payItems, 'premi', $premi);
			$payItems = sum_array($payItems, 'shift', $shift);

			return $payItems;
		}

		private function calcGradeIncentive($payslip, $payroll, $employee, $payItems) {
			$query = $this->db->query("select * from schema_grade where schema_id = ? and grade_id = ?", 
				array($employee['SCHEMA_ID'], $employee['GRADE_ID']));
			if ($query->num_rows() > 0) {
				$payItems = sum_array($payItems, 'allowance', $query->row_array()['ALLOWANCE']);
			}
			return $payItems;
		}		

		private function addPayItems($slipId, $payItems) {
			foreach ($payItems as $item => $amount) {
				if ($amount == 0) continue;				
				$rec = array(
					'ID' => '0',
					'PAYSLIP_ID' => $slipId,
					'CODE' => strtolower($item),
					'AMOUNT' => $amount
				);
				insertRecord('PAYITEM', $rec, array('ID'));
			}
		}
    }
?>
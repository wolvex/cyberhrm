<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class X100c extends MY_Controller {  
        public function index() {

        }

        public function query($id, $period) {
			if (!isGranted()) json_forbidden();

            $query = $this->db->query("select a.*,to_char(a.datetime,'DD-Mon-YYYY HH24:MI:SS') work_at, 
                decode(a.status,0,'Masuk','Pulang') status
                from x100c a, employee b where substr(a.pin,2) = b.code and 
                b.id = ? and to_char(a.datetime,'YYYY-MM') = ?
				order by a.datetime", array($id, $period));
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
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
        
        public function search() {
            if (!isGranted()) json_forbidden();
            
            $month = array_nvl($_GET, 'month', date('Y-m'));
            $find = '%'.array_nvl($_GET, 'find', '').'%';
            
            $query = $this->db->query("select a.*,to_char(a.datetime,'DD-Mon-YYYY HH24:MI:SS') work_at, 
                decode(a.status,0,'Masuk','Pulang') status,b.name,b.code
                from x100c a, employee b where substr(a.pin,2) = b.code and 
                lower(b.name||';'||b.code) like ? and to_char(a.datetime,'YYYY-MM') = ?
				order by a.datetime", array($find, $month));
			$data = array(); $i = 0;
			foreach ($query->result_array() as $rec) {
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

        private function send($xml) {
            $url = "http://192.168.1.201/iWsService";
            $headers = array(
                "Content-type: text/xml",
                "Content-length: " . strlen($xml),
                "Connection: close",
            );
            
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $data = curl_exec($ch);
            
            if(curl_errno($ch))
                return 'Error caught : '.curl_error($ch);
            else
                curl_close($ch);

            return $data;
        }

        private function write($file, $data) {
            $myfile = fopen(UPLOAD_DIR.'/backup/'.$file, "w") or die("Unable to open file!");
            fwrite($myfile, $data);
            fclose($myfile);
        }
        
        public function register() {
            $query = queryRecord("select * from employee where resigned_at is null and nvl(x100c,0) = 0");
            foreach($query as $rec) {
                $xml = '<SetUserInfo><ArgComKey Xsi:type="xsd:integer">220712</ArgComKey><Arg><PIN>8'.$rec['CODE'].'</PIN><Name>'.$rec['NAME'].'</Name></Arg></SetUserInfo>';
                $data = $this->send($xml);

                if (startsWith($data, 'Error')) {
                    json_error($data);
                }
                queryRecord("update employee set x100c = 1 where id = ?", $rec['ID']);
            }

            json_success();
        }

        public function unregister() {
            $query = queryRecord("select * from employee where resigned_at is not null and x100c = 1");
            foreach($query as $rec) {
                $xml = '<DeleteUser><ArgComKey Xsi:type="xsd:integer">220712</ArgComKey><Arg><PIN>8'.$rec['CODE'].'</PIN></Arg></DeleteUser>';
                $data = $this->send($xml);
                
                if(startsWith($data, 'Error')) {
                    json_error($data);
                }

                queryRecord("update employee set x100c = 0 where id = ?", $rec['ID']);
            }

            json_success();
        }

        public function fetch() {
            $xml = '<GetAttLog><ArgComKey xsi:type="xsd:integer">220712</ArgComKey><Arg><PIN xsi:type="xsd:integer">All</PIN></Arg></GetAttLog>';

            $data = $this->send($xml);
            if (startsWith($data, 'Error')) {
                json_error($data);
            }
            
            $log = new SimpleXMLElement($data);
            for ($i=0;$i<count($log->Row);$i++) {                
                executeQuery("insert /*+ ignore_row_on_dupkey_index(x100c, x100c_pk) */ 
                    into x100c (pin,datetime,verified,status,workcode) values (?,to_date(?,'YYYY-MM-DD HH24:MI:SS'),?,?,?)",
                    array(
                        $log->Row[$i]->PIN,
                        $log->Row[$i]->DateTime,
                        $log->Row[$i]->Verified,
                        $log->Row[$i]->Status,
                        $log->Row[$i]->WorkCode,
                    ));
            }

            $this->clearLog();

            json_success();
        }

        /** This function will match the punch-in with punch-out from x100c into absent table */
        public function absent() {
            ini_set('max_execution_time', 300);

            $logs = queryRecord("select a.id,nvl(b.id,0) as empid,to_char(a.datetime,'YYYY-MM-DD HH24:MI:SS') datetime,a.status
                from x100c a,employee b where nvl(a.flag,0) = 0 and substr(a.pin,2) = b.code(+)
                order by a.datetime,a.id");

            foreach($logs as $log) {
                if ($log['EMPID'] != 0) {
                    $absent = array(
                        'EMP_ID' => $log['EMPID'],
                        'STATUS' => 'A',
                        'MODIFIED_BY' => 'x100c',
                        'REMARKS' => 'Mesin absen'
                    );

                    if ($log["STATUS"] == 0) {
                        //checking in
                        //there can be more than 1 check-in in single day
                        //get the most recent check-in with no check-out yet, and see if its less than 2 hours in difference
                        //if more than 4 hours, then create new record
                        $query = queryRecord("select a.*,
                            to_char(clock_in,'YYYY-MM-DD HH24:MI:SS') punch_in,
                            to_char(clock_in+(4/24),'YYYY-MM-DD HH24:MI:SS') max_in
                            from absent a where clock_out is null and emp_id = ? and to_char(clock_in,'YYYY-MM-DD') = ?
                            order by clock_in desc",
                            array($absent['EMP_ID'], substr($log['DATETIME'],0,10)));
                        if (count($query) == 0 || $query[0]['MAX_IN'] < $log['DATETIME']) {
                            $absent['CLOCK_IN'] = $log['DATETIME'];
                            insertRecord('ABSENT', $absent, array('ID'));
                        }
                    } else {
                        //checking out
                        $query = queryRecord("select a.*,
                            to_char(clock_in,'YYYY-MM-DD HH24:MI:SS') punch_in,
                            to_char(nvl(clock_out,clock_in),'YYYY-MM-DD HH24:MI:SS') punch_out,
                            to_char(nvl(clock_out,clock_in)+(4/24),'YYYY-MM-DD HH24:MI:SS') max_out
                            from absent a where emp_id = ? 
                            order by nvl(clock_in,sysdate-(30*365)) desc,clock_out desc", array($absent['EMP_ID']));
                        if (count($query) > 0) {
                            if ($query[0]['PUNCH_IN'] == $query[0]['PUNCH_OUT'] || $query[0]['MAX_OUT'] >= $log['DATETIME']) {
                                //when clock_out is null or not more than 4 hour since last clock_out
                                $absent['ID'] = $query[0]['ID'];
                                $absent['CLOCK_OUT'] = $log['DATETIME'];
                                updateRecord('ABSENT', $absent, array('ID'));
                            } else if ($query[0]['MAX_OUT'] < $log['DATETIME']) {
                                //when clock_out is not more than 4 hour since last clock_out
                                $absent['CLOCK_OUT'] = $log['DATETIME'];
                                insertRecord('ABSENT', $absent, array('ID'));
                            }
                        }
                    }
                }

                executeQuery("update x100c set flag = 1 where id = ?", array($log["ID"]));
            }

            json_success();
        }

        public function timesheet() {
            ini_set('max_execution_time', 300);

            /*
            $query = queryRecord("select t.*,s.id shift_id,
                to_char(t.work_at,'YYYY-MM-DD') today,
                to_char(t.work_at+1,'YYYY-MM-DD') tomorrow,
                to_char(s.start_time,'HH24:MI:SS') start_time,
                to_char(s.end_time,'HH24:MI:SS') end_time
                from timesheet t,shift s 
                where t.shift_id = s.id and t.absent_id is null and t.onleave_id is null
                order by t.emp_id,t.work_at");
            */

            executeQuery("update timesheet set absent_id = null, onleave_id = null, late_minute = 0, early_minute = 0, work_minute = 0
                where payslip_id is null and incentive_id is null and trunc(work_at) >= trunc(sysdate-300)");

            $query = queryRecord("select t.*,s.id shift_id,
                to_char(t.work_at,'YYYY-MM-DD') today, to_char(t.work_at+1,'YYYY-MM-DD') tomorrow,
                to_char(s.start_time,'HH24:MI:SS') start_time, to_char(s.end_time,'HH24:MI:SS') end_time
                from timesheet t,shift s 
                where t.shift_id = s.id and t.payslip_id is null and t.incentive_id is null and trunc(t.work_at) >= trunc(sysdate-300)
                order by t.emp_id,t.work_at");

            foreach($query as $time) {
                if ($time['START_TIME'] > $time['END_TIME']) {
                    $time['END_TIME'] = $time['TOMORROW'].' '.$time['END_TIME'];
                } else {
                    $time['END_TIME'] = $time['TODAY'].' '.$time['END_TIME'];
                }
                $time['START_TIME'] = $time['TODAY'].' '.$time['START_TIME'];

                //check on leave requests
                $leave = queryRecord("select a.* from onleave a where a.status = 'A' and a.emp_id = ? and 
                    to_char(a.started_at,'YYYY-MM-DD') <= ? and to_char(a.ended_at,'YYYY-MM-DD') >= ?", 
                    array(
                        $time['EMP_ID'],
                        substr($time['START_TIME'],0,10),
                        substr($time['START_TIME'],0,10)
                    ));
                if (count($leave) > 0) {
                    //employee in on leave request
                    executeQuery("update timesheet set onleave_id = ? where id = ?", array($leave[0]['ID'], $time['ID']));
                    continue;
                }
                
                //check absent data
                $absent = queryRecord("select a.*,
                    to_char(a.clock_in,'YYYY-MM-DD HH24:MI:SS') punch_in, 
                    to_char(a.clock_out,'YYYY-MM-DD HH24:MI:SS') punch_out
                    from absent a,timesheet t
                    where a.id = t.absent_id(+) and t.id is null and a.emp_id = ?
                    order by nvl(a.clock_in,to_date('21001231','YYYYMMDD')),
                    nvl(a.clock_out,to_date('19780630','YYYYMMDD')) desc", array($time['EMP_ID']));
                foreach($absent as $log) {
                    $late = 0; $early = 0; $work = 0;
                    if ($time['SHIFT_ID'] == 0) { //OFF DUTY
                        if (substr($log['PUNCH_IN'],1,10) == substr($time['START_TIME'],1,10)) {
                            if ($log['PUNCH_OUT'] != '') {
                                $early = round((strtotime($log['PUNCH_IN'])-strtotime($log['PUNCH_OUT']))/60, 0);
                                $work = round((strtotime($log['PUNCH_OUT'])-strtotime($log['PUNCH_IN']))/60, 0);
                            }
                        } else {
                            continue;
                        }
                    } else {
                        if ($log['PUNCH_IN'] != '') {
                            $late = round((strtotime($log['PUNCH_IN'])-strtotime($time['START_TIME']))/60, 0);
                            echo 'Late => '.$time['EMP_ID'].' '.$log['PUNCH_IN'].'  '.$time['START_TIME'].'  '.$late.'<br>';

                            //if (abs($late) > 240) continue;
                            if ($late < -240) continue;
                            if ($late > 240) break;
                            
                            if ($log['PUNCH_OUT'] != '') {
                                $early = round((strtotime($time['END_TIME'])-strtotime($log['PUNCH_OUT']))/60, 0);
                                $work = round((strtotime($log['PUNCH_OUT'])-strtotime($log['PUNCH_IN']))/60, 0);
                            }
                        } else if ($log['PUNCH_OUT'] != '') {
                            $early = round((strtotime($time['END_TIME'])-strtotime($log['PUNCH_OUT']))/60, 0);
                            echo 'Early => '.$time['EMP_ID'].' '.$log['PUNCH_IN'].'  '.$time['START_TIME'].'  '.$early.'<br>';

                            //if (abs($early) > 240) continue;
                            if ($early < -240) break;
                            if ($early > 240) continue;
                        }
                    }

                    executeQuery("update timesheet set absent_id = ?,late_minute = ?,early_minute = ?,work_minute = ? where id = ?", 
                    array($log['ID'], $late, $early, $work, $time['ID']));
                
                    //break;
                }
            }

            json_success();
        }

        public function clearLog() {
            $xml = '<ClearData><ArgComKey xsi:type="xsd:integer">220712</ArgComKey><Arg><Value xsi:type="xsd:integer">3</Value></Arg></ClearData>';
            $data = $this->send($xml);
            return $data;
        }

        public function backup() {
            $xml = '<GetUserInfo><ArgComKey xsi:type="xsd:integer">220712</ArgComKey><Arg><PIN xsi:type="xsd:integer">All</PIN></Arg></GetUserInfo>';
            $data = $this->send($xml);
            if (startsWith($data, 'Error')) {
                json_error($data);
            }
            
            $this->write('users_'.date('Y_m_d').'.xml', $data);
            $user = new SimpleXMLElement($data);

            for ($i=0;$i<count($user->Row);$i++) {
                $xml = '<GetUserTemplate><ArgComKey xsi:type="xsd:integer">220712</ArgComKey><Arg><PIN xsi:type="xsd:integer">'.$user->Row[$i]->PIN2.'</PIN><FingerID xsi:type="xsd:integer">All</FingerID></Arg></GetUserTemplate>';
                $finger = $this->send($xml);
                if (startsWith($finger, 'Error')) continue;
                $this->write('tmpl_'.$user->Row[$i]->PIN2.'_'.date('Y_m_d').'.xml', $finger);
            }
            json_success();
        }

        
    }
?>
<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Dashboard extends MY_Controller {  
          
        public function index() {
            validateSession();

			layout('dashboard', array());
        }  

        public function getNewEmployee() {
            $query = $this->db->query("select id,code,name,joined_at from (select * from employee order by joined_at desc) a 
                where rownum <= 5");
            $rows = $query->result_array();
            json_success($rows);
        }

        public function getExpiryEmployee() {
            $query = $this->db->query("select id,code,name,joined_at,to_char(borned_at,'DD MON YYYY') borned_at from 
                (select * from employee where to_number(to_char(sysdate,'yyyy'))-to_number(to_char(borned_at,'yyyy')) >= 56 
                order by borned_at) a where rownum <= 5");
            $rows = $query->result_array();
            json_success($rows);
        }

        public function getDeptEmployee() {
            $query = $this->db->query("select c.name,count(*) jlh
                from employee a,v_carier b,dept c
                where a.id = b.emp_id and b.dept_id = c.id
                group by c.name order by count(*) desc");
            $rows = $query->result_array(); 
            $data = array(); $cnt = 0; $other = 0;
            foreach($rows as $row) {
                if ($cnt < 8) {
                    $data[] = $row;
                } else {
                    $other += $row['JLH'];
                }
                $cnt++;
            }
            $data[] = array('NAME' => 'Lain-Lain', 'JLH' => $other);

            json_success($data);
        }
        
        public function getEmployeeGrowth() {
            $query = $this->db->query("select to_char(joined_at,'yyyy') tahun,
                sum(decode(emp_status,'P',1,0)) permanen,
                sum(decode(emp_status,'K',1,0)) kontrak,
                sum(decode(emp_status,'H',1,0)) harian
                from employee a,v_carier b where a.id = b.emp_id
                group by to_char(joined_at,'yyyy') order by to_char(joined_at,'yyyy')");
            $rows = $query->result_array(); 
            $data = array(); $year = date('Y')-5;
            $permanen = array(); $kontrak = array(); $harian = array(); $years = array(); 
            $p =0 ; $k = 0; $h = 0; $c = 0;
            foreach($rows as $row) {
                $p += $row['PERMANEN']; $k += $row['KONTRAK']; $h += $row['HARIAN'];
                while ($row['TAHUN'] >= $year) {
                    $years[] = $year;
                    $permanen[] = $p;
                    $kontrak[] = $k;
                    $harian[] = $h;
                    $year++;
                }
            }
            $data[] = array("TAHUN" => $years, "PERMANEN" => $permanen, "KONTRAK" => $kontrak, "HARIAN" => $harian);
            
            json_success($data);
        }
    }  
?>
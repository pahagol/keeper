<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/** NOAUTH CONTROLLER* */
class Test extends CI_Controller {

    public function index() {
        gc_enable();
//$this->CI->output->enable_profiler(true);
        $this->load->library("project");
        set_time_limit(0);
        ini_set('memory_limit', '700M');    
        $this->project->archivate_referal_day_stat(); 
        $this->project->calc_day_stat();
        $this->recalc(); 
        /*
          $start_date = '2012-05-06';
          $check_date = $start_date;
          $end_date = '2012-05-07';

          do {
          echo $check_date . '\n';
          $this->project->calc_day_stat($check_date);
          $check_date = date("Y-m-d", strtotime("+1 day", strtotime($check_date)));
          } while ($check_date != $end_date);

         */
//$this->project->calc_day_stat('2012-03-31');
//$this->load->view("blank");
// echo "hello world";
//$ref_code="689735";
//$url = config_item("mlgame_xml_url");
//$url = $url . $ref_code . '&skey=' . md5(config_item("mlgame_secret_key") . $ref_code);
//$f=  file_get_contents($url);
//print($f);
    }

    /*

      public function change_pass($pass, $user_id) {
      $this->user->set_pass($pass, $user_id);
      }

      public function show_joomla_pass($pass) {
      echo $this->user->_joomla_hash_pass($pass);
      }
     * 
     */

    /*
      public function add_null_sites(){
      $q=$this->db->get("global_users");
      foreach($q->result() as $u){
      $this->db->insert("global_sites",array("user_id"=>$u->id,"url"=>"null","state"=>"active","attendance"=>0));
      $this->load->library("project");
      }
      }
     *
     */

	public function calcdaystat() {
        gc_enable();
//$this->CI->output->enable_profiler(true);
        $this->load->library("project");
        set_time_limit(0);
        ini_set('memory_limit', '700M');    
        //$this->project->archivate_referal_day_stat(); 
        $this->project->calc_day_stat();
	//   $this->recalc(); 
	}	
	
	public function recalc() {  
        set_time_limit(0);
        $this->load->library("project");
        $this->project->recalc_referals_profit();
        $this->project->recalc_user_day_stats();
        $this->project->recalc_users();
        $this->project->recalc_loyalty();
        $this->project->calc_sites_stats();
        $this->project->synchronize_sites();
    }
/*
    public function recalc_user() {
        $percent = 40;
        $user_id = 137;
        $q = $this->db->get_where("p1_referals", array("user_id" => $user_id));
        if ($q->num_rows()) {
            foreach ($q->result() as $r) {
                $z=$this->db->get_where("p1_ref_day_stats", array("referal_id"=>$r->id));
                if($z->num_rows()){
                    foreach($z->num_rows() as $rds){
                        $day_sum=0;
                        $day_profit=0;
                        $day_profit_mlgame=0;
                    }
                }
            }
        }
    }
 * 
 */

    /*
      public function del_user($id) {
      $this->db->delete("global_users", array('id' => $id));
      $this->db->delete("p1_users", array("user_id" => $id));
      $this->db->delete("global_users_projects", array("user_id" => $id));
      $q = $this->db->get_where("global_sites", array("user_id" => $id));
      if ($q->num_rows()) {
      foreach ($q->result() as $r) {
      $site_id = $r->id;
      $this->db->delete("global_sites", array("id" => $site_id));
      $this->db->delete("p1_sites", array("site_id" => $site_id));
      }
      }
      }
     * 
     */


//    function levels_process() {
//        set_time_limit(0);
//        ini_set('memory_limit', '700M');
//        $q = $this->db->select("ref_id")->from("p1_ref_levels")->group_by("ref_id")->get();
//        //$n = 0;
//        //$this->db->trans_start();
//        foreach ($q->result() as $r) {
//
//            //echo "<pre>";
//            //echo "<h1>".$r->ref_id."</h1>";
//            $qq = $this->db->order_by("date", "asc")->get_where("p1_ref_levels prl", array("prl.ref_id" => $r->ref_id));
//            $arr = $qq->result_array();
//
//            for ($i = 0; $i <= (count($arr) - 1); $i++) {
//                $cur_date = $arr[$i]['date'];
//                $next_date = empty($arr[$i + 1]['date']) ? null : $arr[$i + 1]['date'];
//                $real_next_date = date('Y-m-d', strtotime('+1 day', strtotime($cur_date)));
//                //print("<p>CUR_DATE:$cur_date  NEXT_DATE:$next_date  REAL_NEXT_DATE:$real_next_date </p>");
//                if ($next_date != $real_next_date) {
//                    if ($real_next_date != "2012-06-12") {
//                        echo "NEXT DATE not equal";
//                        do {
//                            echo "<p>inserting " . $arr[$i]['level'] . " " . $real_next_date . "</p>";
//                            //$this->db->insert("p1_ref_levels", array("ref_id" => $r->ref_id, "date" => $real_next_date, "level" => $arr[$i]['level']));
//                            $this->db->query("insert ignore into p1_ref_levels set ref_id='{$r->ref_id}',date='{$real_next_date}',level='{$arr[$i]['level']}'");
//                            $real_next_date = date('Y-m-d', strtotime('+1 day', strtotime($real_next_date)));
//                            if ($real_next_date == "2012-06-12")
//                                break;
//                        } while ($real_next_date != $next_date);
//                    } else {
//                        continue;
//                    }
//                }
//            }
//
//            //echo "/n";
//            //echo "</pre>";
//            //$n++;
//            // if ($n == 50)
//            //   die();
//        }
//        //$this->db->trans_complete();
//        /*
//          if ($this->db->trans_status() === FALSE) {
//          echo "PROCESS FAILED";
//          }else
//          echo "PROCESS FINISHED";
//         * 
//         */
//    }

	public function calclvlstat() {
        gc_enable();
        
		$this->load->library("project");
        set_time_limit(0);
        ini_set('memory_limit', '700M');    
		
        $this->project->calc_lvl_stat();
	}
}

/* End of file forward.php */
/* Location: ./application/controllers/forward.php */

<?php

class Project_model extends CI_Model {

    public $project;
    public $prefix;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->project = config_item("default_project_id");
        $this->prefix = "p" . $this->project;
    }

    /*     * *********** new methods (no class properties, straight params) ************ */

    function get_user_sites($p_id, $user_id) {
        $q = $this->db->join("global_sites gs", "gs.id = ps.site_id")->get("p{$p_id}_sites ps");
        if ($q->num_rows())
            return $q->row;else
            return false;
    }

    /*     * *************************************************************************** */

    public function __set($name, $value) {
        $this->$name = $value;
        if ($name == "project")
            $this->prefix = "p" . $this->project;
    }

    function get_project_info() {
        $q = $this->db->get_where("global_projects", "id = {$this->project}");
        if ($q)
            return $q->row();
        else
            return false;
    }

    function get_project_stats($user_id) {
        $project_info = $this->get_project_info();
        $payout_delay = $project_info->payout_delay;
        $date = $date = date("Y-m-d", time() - $payout_delay * 24 * 60 * 60);
        $this->db->select("pu.registers, pu.active_regs, pu.active_regs_reg_day, pu.earnings, pu.sum_to_pay, (pu.sum_to_pay - t2.last_earnings) current_sum_to_pay");
        $this->db->from("{$this->prefix}_users pu")->where("pu.user_id", $user_id);
        $this->db->join("(select sum(earnings) last_earnings,user_id from p1_day_stats where date>='{$date}' group by user_id) t2", "t2.user_id=pu.user_id","left");
        $q = $this->db->get();
        //echo $this->db->last_query();
        if ($q->num_rows()) {
            return $q->row();
        }else
            return false;
    }

    function get_last_project_stats($user_id) {
        $q = $this->db->order_by("date", "DESC")->get_where("{$this->prefix}_day_stats", "user_id = $user_id", 1);
	//	echo $this->db->last_query();
        if ($q->num_rows()) {
            return $q->row();
        }else
            return false;
    }

    function get_user_projects($user_id) {
        $q = $this->db->join("global_users_projects up", "up.project_id = p.id")->get_where("global_projects p", "up.user_id = $user_id");
        if ($q->num_rows()) {
            $arr = array();
            foreach ($q->result() as $r) {
                $arr[$r->id] = $r;
            }
            return $arr;
        }else
            return false;
    }

    function get_user_info($user_id) {
        $q = $this->db->get_where("{$this->prefix}_users", "user_id = $user_id/* AND status = 'active'*/");
        if ($q->num_rows()) {
            return $q->row();
        }else
            return false;
    }

    function get_all_projects() {
        $q = $this->db->get("global_projects");
        if ($q)
            return $q->result();
        else
            return false;
    }

    function get_project_by_id($id) {
        $q = $this->db->get_where("global_projects", "id = $id");
        if ($q)
            return $q->row();
        else
            return false;
    }

    function insert_click($user_id, $site_id, $project_id, $prx) {
        $q = $this->db->insert("{$this->prefix}_clicks", array("user_id" => $user_id,
            "site_id" => $site_id, "project_id" => $project_id, "prx" => $prx));
        if ($q) {
            return $this->db->insert_id();
        } else
            return false;
    }

    function get_click_by_id($id) {
        $q = $this->db->get_where("{$this->prefix}_clicks", "id = $id");
        if ($q)
            return $q->row();
        else
            return false;
    }

    function get_referal_by_name($name) {
        $q = $this->db->get_where("{$this->prefix}_referals", array("name" => $name));
        if ($q)
            return $q->row();
        else
            return false;
    }

    function insert_referal($p) {
        $this->db->set("reg_date", "NOW()", false);
        $q = $this->db->insert("{$this->prefix}_referals", $p);
        if ($q)
            return true;
        else
            return false;
    }

    function get_project_users() {
        $q = $this->db->get("{$this->prefix}_users");
        if ($q->num_rows() > 0) {
            return $q->result();
        } else
            return false;
    }

    function get_count_sites() {
        return $this->db->count_all_results("{$this->prefix}_sites");
    }

    function get_project_referals_as_named_array($user_id) { //used in calc day stat
        $q = $this->db->get_where("{$this->prefix}_referals", "user_id = $user_id");
        if ($q->num_rows() > 0) {
            //return $q->result();
            foreach ($q->result_array() as $r) {
                $arr[$r['name']] = $r;
            }
            //print_r($arr);
            return $arr;
        } else
            return false;
    }

    function batch_insert_ref_day_stat($arr) {
        if (!empty($arr)) {
            //print("<pre>batch_insert_ref_day_stat:<br/>");print_r($arr);print("</pre>");
            if ($this->db->insert_batch("{$this->prefix}_ref_day_stats", $arr) === false)
                return false;
            else {
                //echo $this->db->last_query();
                return true;
            }
        } else
            return false;
    }

    function batch_insert_ref($arr) {
        if (!empty($arr)) {
            // print("<pre>batch_insert_ref:<br/>");print_r($arr);print("</pre>");
            if ($this->db->insert_batch("{$this->prefix}_referals", $arr) === false)
                return false;
            else {
                //echo $this->db->last_query();
                return true;
            }
        } else
            return false;
    }

    function batch_update_ref($arr) {
        if (!empty($arr)) {
            //print("<pre>batch_update_ref:<br/>");print_r($arr);print("</pre>");
            if ($this->db->update_batch("{$this->prefix}_referals", $arr, "name") === false)
                return false;
            else {
                //echo $this->db->last_query();
                return true;
            }
        } else
            return false;
    }

    function batch_insert_user_day_stat($arr) {
        if (!empty($arr)) {
            //print("<pre>batch_insert_ref_day_stat:<br/>");print_r($arr);print("</pre>");
            if ($this->db->insert_batch("{$this->prefix}_day_stats", $arr) === false)
                return false;
            else {
                //echo $this->db->last_query();
                return true;
            }
        } else
            return false;
    }

    function batch_update_users($arr) {
        if (!empty($arr)) {
            //print("<pre>batch_update_ref:<br/>");print_r($arr);print("</pre>");
            if ($this->db->update_batch("{$this->prefix}_users", $arr, "user_id") === false)
                return false;
            else {
                //echo $this->db->last_query();
                return true;
            }
        } else
            return false;
    }

    function get_refs_by_names($names_arr) {
        if (!empty($names_arr)) {
            $q = $this->db->select("id, name") /* ->where_in("name", $names_arr) */->get("{$this->prefix}_referals");
            //echo $this->db->last_query();
            if ($q->num_rows()) {
                $arr = $q->result_array();
                //print_r($arr);
                $ret_arr = array();
                foreach ($arr as $r) {
                    $ret_arr[$r['name']] = $r['id'];
                }
                return $ret_arr;
            } else { //echo "num rows false";
                return false;
            }
        } else {
            //echo "no names arr";
            return false;
        }
    }

    function get_user_percent($user_id) {
        $q = $this->db->select("percent")->get_where("{$this->prefix}_users", "user_id = $user_id");
        if ($q->num_rows()) {
            $g = $q->row();
            return $g->percent;
        }else
            return false;
    }

    function get_ref_codes() {
        $q = $this->db->select("ref_code")->get("{$this->prefix}_users");
        if ($q->num_rows() > 0) {
            $arr = array();
            foreach ($q->result() as $r) {
                $arr[] = $r->ref_code;
            }
            //print_r($arr);
            return $arr;
        } else
            return false;
    }

    function insert_user($u) {
        $this->db->set($u);
        $this->db->set("connect_date", "NOW()", false);
        return $this->db->insert("{$this->prefix}_users");
    }

    function insert_site($p_id, $site) {
        $site['status'] = "active";
        if ($this->db->insert("p{$p_id}_sites", $site)) {
            return $this->db->insert_id();
        }else
            return false;
    }

    /* admin part */

    function get_all_earned() {
        $q = $this->db->select_sum("earnings", "allearned")->from("{$this->prefix}_users")->get();
        if ($q->num_rows()) {
            $obj = $q->row();
			//echo $obj->allearned;
            return $obj->allearned;
        }else
            return false;
    }

    function get_all_earned_mlgame() {
        $q = $this->db->select_sum("earnings_mlgame", "allearned")->from("{$this->prefix}_users")->get();
        if ($q->num_rows()) {
            $obj = $q->row();
            return $obj->allearned;
        }else
            return false;
    }

    function get_all_ref_paid() {
        $q = $this->db->select_sum("ref_paid", "allrefpaid")->from("{$this->prefix}_ref_day_stats")->get();
        if ($q->num_rows()) {
            $obj = $q->row();
			//echo $obj->allrefpaid;
            return $obj->allrefpaid;
        }else
            return false;
    }

    function get_all_sum_to_pay() {
        $q = $this->db->select_sum("sum_to_pay", "alltopay")->from("{$this->prefix}_users")->get();
        if ($q->num_rows()) {
            $obj = $q->row();
            return $obj->alltopay;
        }else
            return false;
    }

    function get_count_users() {
        $q = $this->db->select("count(*) as cnt")->from("{$this->prefix}_users pu")->join("global_users gu", "gu.id=pu.user_id")->get();
        return $q->row()->cnt;
    }

    function get_last_stats() {
        $q = $this->db->select_sum("clicks")->select_sum("registers")->select_sum("active_regs")->select_sum("earnings")->group_by("date")->order_by("date", "DESC")->get("{$this->prefix}_day_stats", 1);
        if ($q->num_rows())
            return $q->row();else
            return false;
    }

    function get_count_clicks() {
        return $this->db->count_all_results("{$this->prefix}_clicks");
    }

    function get_count_referals() {
        return $this->db->count_all_results("{$this->prefix}_referals");
    }

    function get_count_active() {
        $this->db->where("level > 1");
        return $this->db->count_all_results("{$this->prefix}_referals");
    }

    function get_count_active_on_reg_day() {
        $this->db->where("prl.level > 1");
        $this->db->join("{$this->prefix}_ref_levels prl", "prl.ref_id=pr.id and prl.date=pr.reg_date");
        return $this->db->count_all_results("{$this->prefix}_referals pr");
    }

    function get_weighted_average_percent() {
        $q = $this->db->select("sum(earnings * percent)/sum(earnings) as av_perc")->from("{$this->prefix}_users pu")->get();
        return round($q->row()->av_perc);
    }

    function get_all_sum_stats() {
        $q = $this->db->select_sum("earnings")->select_sum("earnings_mlgame")->get("{$this->prefix}_day_stats");
        if ($q->num_rows())
            return $q->row();else
            return false;
    }

    function get_users_for_dropdown() {
        $q = $this->db->from("{$this->prefix}_users pu")->join("global_users gu", "gu.id=pu.user_id")->order_by("name", "asc")->get();
        if ($q->num_rows()) {
            $arr = array();
            $arr[0] = "Все";
            foreach ($q->result() as $r) {
                $arr[$r->id] = $r->name;
            }
            return $arr;
        }else
            return false;
    }

}

?>
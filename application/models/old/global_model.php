<?php

class Global_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_count_sites() {
        return $this->db->count_all_results("global_sites");
    }

    function get_sum_payed() {
        $q = $this->db->select_sum("sum")->from("global_payouts")->where("type", "default")->get();
        if ($q->num_rows()) {
            $obj = $q->row();
            return $obj->sum;
        }return false;
    }

    function get_project($p_id) {
        $q = $this->db->get_where("global_projects", array("id" => $p_id));
        if ($q->num_rows()) {
            return $q->row();
        }else
            return false;
    }

    function get_projects_for_select() {
        $q = $this->db->get("global_projects");
        if ($q->num_rows()) {
            $arr = array(0 => "Все проекты");
            foreach ($q->result() as $p) {
                $arr[$p->id] = $p->name;
            }
            return $arr;
        }else
            return false;
    }

    function get_user_projects_for_select($user_id) {
        $q = $this->db->join("global_users_projects gup", "gup.project_id = gp.id")->get_where("global_projects gp", array("user_id" => $user_id));
        if ($q->num_rows()) {
            $arr = array();
            foreach ($q->result() as $p) {
                $arr[$p->id] = $p->name;
            }
            return $arr;
        }else
            return false;
    }

    function insert_user($p) {
        $this->db->set("reg_date", "NOW()", false);
        if ($this->db->insert("global_users", $p))
            return $this->db->insert_id();
        else
            return false;
    }

    function get_user_by_name($name) {
        $q = $this->db->get_where("global_users", array("name" => $name));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_user_by_email($email) {
        $q = $this->db->get_where("global_users", array("email" => $email));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_user_by_id($id) {
        $q = $this->db->get_where("global_users", array("id" => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_user_by_ref_code($ref_code) {   
        $q = $this->db->get_where("p1_users", array("ref_code" => $ref_code));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_user_by_site_id($site_id) {
        $q = $this->db->select("global_users.*,p1_users.ref_code")->join("global_users", "global_users.id=global_sites.user_id")->join("p1_users", "p1_users.user_id=global_users.id", "left")->get_where("global_sites", array("global_sites.id" =>
            $site_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_payouts($user_id, $type = "default") {
        $q = $this->db->where(array("user_id" => $user_id, "type" => $type))->order_by("date", "DESC")->
                get("global_payouts");
        if ($q->num_rows() > 0) {
            return $q->result();
        } else
            return false;
    }

    function get_sum_payots($user_id) {
        $q = $this->db->select_sum("sum", "allsum")->get("global_payouts");
        if ($q->num_rows() > 0) {
            $p = $q->row();
            return $p->allsum;
        } else
            return false;
    }

    function get_sites($user_id) {
        $q = $this->db->get_where("global_sites", "user_id = $user_id");
        if ($q->num_rows() > 0) {
            return $q->result();
        } else
            return false;
    }

    function get_children($user_id) {
        $q = $this->db->get_where("global_users", "parent_id = $user_id");
        if ($q->num_rows() > 0) {
            return $q->result();
        } else
            return false;
    }

    function get_count_children($user_id) {
        $q = $this->db->get_where("global_users", "parent_id = $user_id");
        return $q->num_rows();
    }

    function update_pass($pass, $user_id) {
        if ($this->db->update("global_users", array("password" => $pass), "id = $user_id"))
            return true;
        else
            return false;
    }

	function update_email($email, $user_id) {
        if ($this->db->update("global_users", array("email" => $email), "id = $user_id"))
            return true;
        else
            return false;
    }
	
    function update_state($state, $user_id) {
        if ($this->db->update("global_users", array("state" => $state), "id = $user_id"))
            return true;
        else
            return false;
    }

    /* admin part */

    function get_all_loyalty_payed() {
        $q = $this->db->select_sum("sum", "payed")->from("global_payouts")->where("type", "loyalty")->get();
        if ($q->num_rows()) {
            $obj = $q->row();
            return $obj->payed;
        }else
            return false;
    }

    function insert_payout($user_id, $sum, $type, $balance) {
        if (in_array($type, array("default", "loyalty"))) {
            $this->db->insert("global_payouts", array("user_id" => $user_id, "balance" => $balance, "sum" => $sum, "type" => $type));
        }
    }

    function get_payouts_sum($user_id, $type = "default") {
        $q = $this->db->select_sum("sum", "allsum")->from("global_payouts")->where("user_id", $user_id)->where("type", $type)->get();
        if ($q->num_rows()) {
            $obj = $q->row();
            return $obj->allsum;
        }
    }

    function insert_user_project($user_id, $p_id) {
        $arr['user_id'] = $user_id;
        $arr['project_id'] = $p_id;
        return $this->db->insert("global_users_projects", $arr);
    }

    function insert_site($site) {
        $site['state'] = "active";
        $this->db->set("date_added", "NOW()", false);
        if ($this->db->insert("global_sites", $site)) {
            return $this->db->insert_id();
        }else
            return false;
    }

    function update_project($p_id, array $params) {
        $allowed = array("percent_range", "percent", "ref_code","payout_delay");
        $allowed = array_flip($allowed);
        $params = array_intersect_key($params, $allowed);
        if (!empty($params)) {
            return $this->db->update("global_projects", $params, array("id" => (int) $p_id));
        } else {
            return false;
        }
    }

    function set_site_state($site_id, $state) {
        $states_arr = array("active", "banned", "moderate");
        if (in_array($state, $states_arr)) {
            $this->db->update("global_sites", array("state" => $state), array("id" => $site_id));
            $this->db->update("p1_sites", array("status" => $state), array("site_id" => $site_id));
            return true;
        }else
            return false;
    }

    function ban_site($site_id, $ban_reason = null) {
        $this->set_site_state($site_id, "banned");
        if ($ban_reason) {
            $this->db->update("p1_sites", array("reason_banned" => $ban_reason), array("site_id" => $site_id));
        }
    }

    function unban_site($site_id) {
        $this->set_site_state($site_id, "active");
    }

    function add_log($user_id, $action, $new_value, $old_value = null) {
        $action_arr = array('change_user_loyalty_percent', 'change_user_wallet', 'change_user_wmz_num', 'change_user_percent','change_user_ref_code','change_user_system_percent');
        if (!in_array($action, $action_arr))
            show_error("Unknown Log Action");
        $this->db->set("user_id", $user_id);
        $this->db->set("action", $action);
        $this->db->set("new_value", $new_value);
        if ($old_value)
            $this->db->set("old_value", $old_value);
        return $this->db->insert("global_log", array("user_id" => $user_id, "action" => $action, "new_value" => $new_value));
    }

}

?>
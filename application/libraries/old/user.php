<?php

class User {

    public $logged = false;
    public $id = false;
    public $name = false;
    public $earn = 0;
    public $admin = false;
    public $state = "new";
    public $simulate = false;

    function User() {
        $this->CI = &get_instance();
        if ($this->is_authorized()) {
            $this->logged = 1;
            $this->id = $this->CI->session->userdata("user_id");
            $this->name = $this->CI->session->userdata("user_name");
            $this->state = $this->CI->session->userdata("user_state");
            $this->simulate = $this->CI->session->userdata("simulate");

            if ($this->state == "not_confirmed" && !in_array($this->CI->uri->segment("1"), array("activate_account", "register", "login"))) {
                redirect("activate_account");
            } else if ($this->state == "new" && !in_array($this->CI->uri->segment("1"), array("new_user_form", "register", "login"))) {
                redirect("new_user_form");
            } else if ($this->state == "moderate" && !in_array($this->CI->uri->segment("1"), array("new_user_form", "register", "login"))) {
                redirect("new_user_form");
            }else if($this->state=="blocked" && !in_array($this->CI->uri->segment("1"), array("blocked", "register", "login"))){
                redirect("blocked");
            }
        } else {
            if (!in_array($this->CI->uri->segment("1"), config_item("noauth_controllers")))
                redirect("login");
        }
    }

    function login($name, $pass) {
        $this->CI->load->model("global_model");
        $user = $this->CI->global_model->get_user_by_name($name);
        if (!$user)
            return false;
        if ($this->_joomla_check_pass($pass, $user->password)) {
            $this->set_sess_vars($user);
            return true;
        } else
            return false;
    }

    function set_sess_vars($user) {
        $this->id = $user->id;
        $this->logged = 1;
        $this->name = $user->name;
        $this->state = $user->state;
        $this->CI->session->set_userdata("logged", 1);
        $this->CI->session->set_userdata("user_name", $user->name);
        $this->CI->session->set_userdata("user_id", $user->id);
        $this->CI->session->set_userdata("user_state", $user->state);
        if ($user->type == "admin") {
            $this->CI->session->set_userdata("admin", 1);
            $this->admin = true;
        }
    }

    function is_active() {
        if ($this->state == "registered")
            return true;else
            return false;
    }

    function is_existed_user($name) {
        $this->CI->load->model("global_model");
        $user = $this->CI->global_model->get_user_by_name($name);
        if (!$user)
            return false;
        else
            return true;
    }

    function is_admin() {
        if ($this->CI->session->userdata("admin"))
            return true;else
            return false;
    }

    function is_existed_email($email) {
        $this->CI->load->model("global_model");
        $user = $this->CI->global_model->get_user_by_email($email);
        if (!$user)
            return false;
        else
            return true;
    }

    function is_existed_id($id) {
        $this->CI->load->model("global_model");
        $user = $this->CI->global_model->get_user_by_id($id);
        if (!$user)
            return false;
        else
            return true;
    }

    function add_user($name, $full_name, $pass, $email, $parent_id = 0) {
        $p['name'] = $name;
        $p['full_name'] = $full_name;
        $p['password'] = $this->_joomla_hash_pass($pass);
        $p['email'] = $email;
        $p['type'] = "partner";
        $p['state'] = "not_confirmed";
        //$p['ref_code'] = $this->gen_ref_code();
        $p['parent_id'] = $parent_id;
        //$p['percent'] = config_item("default_percent");
        //$p['loyalty_percent'] = config_item("default_loyalty_percent");
        $this->CI->load->model("global_model");
        if ($user_id = $this->CI->global_model->insert_user($p))
            return $user_id;
        else
            return false;
    }

    function set_pass($pass, $user_id = false) {
        if (!$user_id)
            $user_id = $this->id;
        $this->CI->load->model("global_model", "gm");
        $hashed_pass = $this->_joomla_hash_pass($pass);
        if ($this->CI->gm->update_pass($hashed_pass, $user_id))
            return true;
        else
            return false;
    }

    function set_email($email, $user_id = false) {
        if (!$user_id)
            $user_id = $this->id;
        $this->CI->load->model("global_model", "gm");
        //return $this->db->update("global_model", array("email" => $email), array("id" => $user_id));
		if ($this->CI->gm->update_email($email, $user_id))
            return true;
        else
            return false;
    }

    /**
     * Is Authorized
     *
     * Checks whether user is authorized
     *
     * @access	public
     * @return	bool	
     */
    function is_authorized() {
        if ($this->CI->session->userdata("logged"))
            return true;
        return false;
    }

    function logout() {
        $this->CI->session->sess_destroy();
        redirect("login");
    }

    function info($id = false) {

        if (!$id)
            $id = $this->id;
        $this->CI->load->model("global_model", "gm");
        return $this->CI->gm->get_user_by_id($id);
    }

    function _joomla_hash_pass($pass, $salt = false) {
        if (!$salt)
            $salt = md5(uniqid(rand(), true)); //gen 32 char salt
        $p = md5($pass . $salt); //md5 pass and salt
        return $p . ":" . $salt;
    }

    function _joomla_check_pass($in_pass, $pass_str) {
        $arr = explode(":", $pass_str);
        //$pass=$arr[0];
        $salt = $arr[1];
        if ($this->_joomla_hash_pass($in_pass, $salt) == $pass_str)
            return true;
        else
            return false;
    }

    /** admin part * */
    function add_payout($user_id, $sum, $type) {
        $this->CI->load->model("global_model", "gm");
        $this->CI->load->model("project_model", "pm");

        $user = $this->CI->pm->get_user_info($user_id);
        if ($type == "default")
            $balance = $user->sum_to_pay;
        elseif ($type == "loyalty") {
            $user = $this->info($user_id);
            $balance = $user->loyalty_sum_to_pay;
        }

        if ($balance >= $sum) {
            $this->CI->gm->insert_payout($user_id, $sum, $type, $balance);
            $left = $balance - $sum;
            switch ($type) {
                case "default":
                    $data = array("sum_to_pay" => $left);
                    $this->CI->db->update("p1_users", $data, array("user_id" => $user_id));
                    $this->CI->db->update("global_users", $data, array("id" => $user_id));
                    break;
                case "loyalty":
					$data = array("sum_to_pay" => $left);
                    $this->CI->db->update("global_users", $data, array("id" => $user_id));
                    break;
            }
        }
    }

    function gen_reg_hash($name, $pass, $email) {
        return md5($name . $pass . $email . config_item("reg_confirm_salt"));
    }

    function set_state($state, $user_id) {
        $this->CI->load->model("global_model", "gm");
        $state_arr = array("registered", "new", "blocked");
        if (in_array($state, $state_arr)) {
            if ($this->CI->gm->update_state($state, $user_id)) {
                $this->state = $state;
                $this->CI->session->set_userdata("user_state", $state);
                return true;
            } else
                return false;
        }else {
            return false;
        }
    }

    function enable_user_simulation($user_id) {
        if ($this->is_admin()) {
            $this->CI->session->set_userdata("user_id", $user_id);
            $this->CI->session->set_userdata("real_id", $this->id);
            $this->CI->session->set_userdata("simulate", 1);
            $this->id = $user_id;
            $this->simulate = true;
        }
    }

    function disable_user_simulation($user_id) {
        if ($this->simulate) {
            $real_id = $this->CI->session->userdata("real_id");
            $this->id = $real_id;
            $this->CI->session->set_userdata("user_id", $real_id);
            $this->CI->session->unset_userdata("real_id");
            $this->CI->session->unset_userdata("simulate");
            $this->simulate = false;
        }
    }

}

?>

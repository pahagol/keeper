<?php
class User_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function insert_user($p)
    {
        $this->db->set("reg_date", "NOW()", false);
        if ($this->db->insert("global_users", $p))
            return $this->db->insert_id();
        else
            return false;
    }

    function get_user_by_name($name)
    {
        $q = $this->db->get_where("global_users", array("name" => $name));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_user_by_email($email)
    {
        $q = $this->db->get_where("global_users", array("email" => $email));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_user_by_id($id)
    {
        $q = $this->db->get_where("global_users", array("id" => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_user_by_ref_code($ref_code)
    {
        $q = $this->db->get_where("global_users", array("ref_code" => $ref_code));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_user_by_site_id($site_id)
    {
        $q = $this->db->select("global_users.*")->join("global_users",
            "global_users.id=global_sites.user_id")->get_where("global_sites", array("global_sites.id" =>
            $site_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        } else
            return false;
    }

    function get_ref_codes()
    {
        $q = $this->db->select("ref_code")->get("global_users");
        if ($q->num_rows() > 0) {
            return $q->result_array();
        } else
            return false;

    }

    function get_payouts($user_id, $type = "default")
    {
        $q = $this->db->where(array("user_id" => $user_id,"type"=>$type))->order_by("date", "DESC")->
            get("global_payouts");
        if ($q->num_rows() > 0) {
            return $q->result();
        } else
            return false;
    }

    function get_sum_payots($user_id)
    {
        $q=$this->db->select_sum("sum","allsum")->get("global_payouts");
        if ($q->num_rows() > 0) {
            $p=$q->row();
            return $p->allsum;
        } else
            return false;
    }

    function get_sites($user_id)
    {
        $q = $this->db->get_where("global_sites", "user_id = $user_id");
        if ($q->num_rows() > 0) {
            return $q->result();
        } else
            return false;
    }

    function get_children($user_id)
    {
        $q = $this->db->get_where("global_users", "parent_id = $user_id");
        if ($q->num_rows() > 0) {
            return $q->result();
        } else
            return false;
    }

    function update_pass($pass, $user_id)
    {
        if ($this->db->update("global_users", array("password" => $pass), "id = $user_id"))
            return true;
        else
            return false;
    }
    
    /* admin part */
    function get_all_loyalty_payed(){
        $q=$this->db->select_sum("sum","payed")->from("global_payouts")->where("type","loyalty")->get();
        if($q->num_rows()){
            $obj=$q->row();
            return $obj->payed;
        }else return false;
        
    }
    
    function get_count_sites(){
        return $this->db->count_all_results("global_sites");
    }
    
    function insert_payout($user_id, $sum,$type){
        if(in_array($type,array("default","loyalty"))){
            $this->db->insert("global_payouts",array(""));
        }
    }
    
    function get_payouts_sum($user_id,$type="default"){
        $q=$this->db->select_sum("sum","allsum")->from("global_payouts")->where("user_id",$user_id)->where("type",$type)->get();
        if($q->num_rows()){
            $obj=$q->row();
            return $q->allsum;
        }
    }


}
?>
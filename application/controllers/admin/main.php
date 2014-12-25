<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->user->is_admin())
            redirect("/");
    }

    public function index() {
        // $this->output->enable_profiler();
        $this->load->model("project_model", "pm");
        $this->load->model("global_model", "gm");
        $all_earned = $this->pm->get_all_earned();
        $all_earned_mlgame = $this->pm->get_all_earned_mlgame();
        $all_ref_paid = $this->pm->get_all_ref_paid();
        $data['all_payed'] = $this->db->select_sum("sum")->where("type","default")->get("global_payouts")->row()->sum;//$all_earned - $this->pm->get_all_sum_to_pay();
        $data['all_loyalty_payed'] = $this->db->select_sum("sum")->where("type","loyalty")->get("global_payouts")->row()->sum;//$this->gm->get_all_loyalty_payed();
        $data['all_system_earned'] = $all_ref_paid - $all_earned;
        $data['all_partners'] = $this->pm->get_count_users();
        $data['all_sites'] = $this->gm->get_count_sites();
        $y_stats = $this->pm->get_last_stats();
        $data['yest_clicks'] = $y_stats->clicks;
        $data['yest_regs'] = $y_stats->registers;
        $data['yest_active'] = $y_stats->active_regs;
        $data['yest_earnings'] = $y_stats->earnings;
        $q=$this->db->select("count(id) as cnt")->from("global_users")->where("state","not_confirmed")->get();
        if($q->num_rows())$data['count_not_confirmed']=$q->row()->cnt;else $data['count_not_confirmed']=0;
        $q=$this->db->select("count(id) as cnt")->from("global_users")->where("state","moderate")->get();
        if($q->num_rows())$data['count_moderate']=$q->row()->cnt;else $data['count_moderate']=0;
        $q=$this->db->select("count(id) as cnt")->from("global_users")->where("state","blocked")->get();
        if($q->num_rows())$data['count_blocked']=$q->row()->cnt;else $data['count_blocked']=0;


        $d['cont'] = $this->load->view('admin/main_view', $data, true);
        $this->load->view("global_view", $d);
    }

}

/* End of file main.php */
/* Location: ./application/controllers/index.php */

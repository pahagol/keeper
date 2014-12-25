<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Projects extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->user->is_admin())
            redirect("/");
    }

    public function index() {
        $this->load->model("project_model", "pm");
        $this->load->model("global_model", "gm");
        $q = $this->db->get("global_projects");
        $data['projects'] = $q->result();
        foreach ($data['projects'] as $p) {
            $this->pm->project = $p->id;
            $data['stats'][$p->id]['name'] = $p->name;
            $data['stats'][$p->id]['partners'] = $this->pm->get_count_users();
            $data['stats'][$p->id]['sites'] = $this->pm->get_count_sites();
            $data['stats'][$p->id]['clicks'] = $this->pm->get_count_clicks();
            $data['stats'][$p->id]['referals'] = $this->pm->get_count_referals();
            $data['stats'][$p->id]['active'] = $this->pm->get_count_active();
            $data['stats'][$p->id]['active_on_reg_day'] = $this->pm->get_count_active_on_reg_day();
            $data['stats'][$p->id]['percent'] = $p->percent;
            $data['stats'][$p->id]['payed'] = $this->gm->get_sum_payed();
            $data['stats'][$p->id]['to_pay'] = $this->pm->get_all_sum_to_pay();
        }
        $d['cont'] = $this->load->view('admin/projects_view', $data, true);
        $this->load->view("global_view", $d);
    }

}

/* End of file stats.php */
/* Location: ./application/controllers/admin/projects.php */

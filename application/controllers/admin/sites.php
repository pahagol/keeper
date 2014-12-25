<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sites extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->user->is_admin())
            redirect("/");
    }

    public function index() {
        $this->view();
    }

    //global view sites
    function view($params = array()) {

        $valid_params = array("proj", "state", "user_id");
        $params = elements($valid_params, $params);

        $this->load->helper("form");
        $this->load->helper('flexigrid');
        $this->load->model("global_model", "gm");
        $this->load->model("project_model", "pm");
        $colModel['url'] = array('Площадка', 100, TRUE, 'center', 2);
        $colModel['name'] = array('Партнер', 80, TRUE, 'center', 2);
        $colModel['clicks'] = array('Кликов', 60, TRUE, 'center', 0);
        $colModel['registers'] = array('Регистраций', 60, TRUE, 'left', 0);
        $colModel['active_regs'] = array('Активных', 60, TRUE, 'left', 0);
        $colModel['earnings'] = array('Вознаграждение', 80, TRUE, 'left', 0);
        $colModel['attendance'] = array("Посещаемость", 80, TRUE, 'center', 0);
        $colModel['state'] = array("Статус", 60, TRUE, 'center', 0);
        $colModel['ban'] = array("Бан", 60, FALSE, 'left', 0);
        $colModel['link'] = array("Ссылка", 170, FALSE, 'left', 0);
        $colModel['reason_banned'] = array("Причина бана", 170, FALSE, 'left', 0);

        $gridParams = array(
            'width' => 'auto',
            'height' => 'auto',
            'rp' => 15,
            'rpOptions' => '[10,15,20,25,40,100,1000,5000,10000]',
            'pagestat' => 'Показать: от {from} до {to} из {total} записей.',
            'blockOpacity' => 0.5,
            'title' => 'Статистика',
            'showTableToggleBtn' => true
        );
//
        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/sites_admin/{$this->uri->assoc_to_uri($params, true)}"), $colModel, 'earnings', 'desc', $gridParams);

        $data['js_grid'] = $grid_js;
        $dt['cont'] = $this->load->view('flexigrid', $data, true);
        $dt['projects'] = $this->gm->get_projects_for_select();
        $dt['users_drop'] = $this->pm->get_users_for_dropdown();
        $dt['states'] = array("all" => "Все", "active" => "Активные", "banned" => "Забаненные");
        $dt['p_id'] = $params['proj'];
        $dt['state'] = $params['state'];
        $dt['user_id'] = $params['user_id'];
        $d['cont'] = $this->load->view("admin/sites_view", $dt, true); //
        $d['submit_url'] = site_url("admin/sites/view");
        $this->load->view("global_view", $d);
    }

    function ban($params = array()) {
        //print_r($params);
        if (!empty($params['id'])) {
            $id = intval($params['id']);
            $q = $this->db->select("gu.id as user_id,name,url")->join("global_users gu", "gu.id=gs.user_id")->get_where("global_sites gs", array("gs.id" => $id));
            $site_url = $q->row()->url;
            $user_name = $q->row()->name;
            $user_id = $q->row()->user_id;
            $data['site_url'] = $site_url;
            $data['user_name'] = $user_name;
            $data['user_id'] = $user_id;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ban_reason', 'Причина Бана', 'required');

            if ($this->form_validation->run() == false) {
                $d['cont'] = $this->load->view("/admin/ajax/ban_site_view", $data, true);
                $this->load->view("global_view", $d);
            } else {
                $ban_reason = $this->input->post("ban_reason");
                $this->load->model("global_model", "gm");
                $this->gm->ban_site($id, $ban_reason);
                redirect("admin/sites");
            }
        }
    }

    function unban($params = array()) {
        if (!empty($params['id'])) {
            $id = intval($params['id']);
            $this->load->model("global_model", "gm");
            $this->gm->unban_site($id);
        }
        redirect("admin/sites");
    }

    function _remap($method) {
        if (method_exists($this, $method)) {
            $arr = $this->uri->ruri_to_assoc();
            return call_user_func(array($this, $method), $arr);
        }
        show_404();
    }

}

/* End of file stats.php */
/* Location: ./application/controllers/admin/partners.php */

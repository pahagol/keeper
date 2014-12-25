<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Projectc extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->user->is_admin())
            redirect("/");
    }

    function index() {
        //$this->view(config_item("default_project_id"));
    }

    function view($params = array()) {
        $this->load->helper("form");
        $this->load->model("project_model", "pm");
        $this->load->model("global_model", "gm");

        $valid_params = array("id", "state");
        $params = elements($valid_params, $params);
        $id = intval($params['id']);
        $state = $params['state'];


        $this->pm->project = $id;
        $p = $this->pm->get_project_info();
        $data['name'] = $p->name;
        $data['percent'] = $p->percent;
        $data['percent_range'] = $p->percent_range;
        $data['ref_code'] = $p->ref_code;
        $data['payout_delay']=$p->payout_delay;
        $data['url'] = $p->url;
        $data['desc'] = $p->description;
        $data['logo'] = config_item("project_logo_dir") . DIRECTORY_SEPARATOR . $p->logo;
        $data['id'] = $id;
        $data['state'] = $state;

        $data['partners'] = $this->pm->get_count_users();
        $data['sites'] = $this->pm->get_count_sites();
        $data['clicks'] = $this->pm->get_count_clicks();
        $data['referals'] = $this->pm->get_count_referals();
        $data['active'] = $this->pm->get_count_active();
        $data['active_on_reg_day'] = $this->pm->get_count_active_on_reg_day();
        $data['percent'] = $this->pm->get_weighted_average_percent();
        $data['payed'] = $this->gm->get_sum_payed();
        $data['to_pay'] = $this->pm->get_all_sum_to_pay();


        $earn_stats = $this->pm->get_all_sum_stats();
        $data['all_sum'] = $earn_stats->earnings;
        $data['all_sum_mlgame'] = $earn_stats->earnings_mlgame;
        $data['user_states'] = array(""=>"", "registered"=>"", "moderate"=>"", "not_confirmed"=>"", "blocked"=>"","new"=>"");
        foreach($data['user_states'] as $key=>$val){
            $data['user_states'][$key]=  get_user_status_transalate($key);
        }

        $this->load->helper('flexigrid');
        $colModel['name'] = array('Партнер', 105, TRUE, 'center', 2);
        $colModel['gu.state'] = array('Cтатус', 60, TRUE, 'center', 2);
        $colModel['pu.clicks'] = array('Кликов', 60, TRUE, 'center', 0);
        $colModel['pu.registers'] = array('Регистраций', 60, TRUE, 'left', 0);
        $colModel['pu.active_regs'] = array('Активных', 60, TRUE, 'left', 0);
        $colModel['pu.earnings'] = array('Вознаграждение', 80, TRUE, 'left', 0);
        $colModel['pu.earnings_mlgame'] = array('Вознаграждение по MlGame', 100, TRUE, 'left', 0);
        $colModel['pu.sum_to_pay'] = array('К выплате', 60, TRUE, 'left', 0);
        $colModel['pu.percent'] = array('% Партнера', 60, TRUE, 'left', 0);
        $colModel['count_sites'] = array('Площадок', 60, TRUE, 'left', 0);
        $colModel['ban'] = array('Действие', 80, FALSE, 'left', 0);

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

        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/admin_project_users/{$this->uri->assoc_to_uri($params, true)}"), $colModel, 'date', 'desc', $gridParams);
        $data['js_grid'] = $grid_js;
        $data['submit_url'] = "admin/projectc/view";
        $d['cont'] = $this->load->view("admin/project_view", $data, true);
        $this->load->view("global_view", $d);
    }

    function ajax_save() {
        $name = $this->input->post("name");
        $val = $this->input->post("val");
        $p_id = $this->input->post("id");
        $accepted = array("ref_code", "percent", "percent_range","payout_delay");
        $arr = array($name => trim($val));
        if ($name) {
            if (in_array($name, $accepted)) {
                $this->load->model("global_model", "gm");
                if ($this->gm->update_project($p_id, $arr)) {
                    echo "ok";
                    //echo $this->db->last_query();
                    return;
                }
            }
        }
        echo "no";
        return;
    }

    function ban_partner($id) {
        $this->db->update("p1_users", array("status" => "banned"), array("user_id" => $id));
        $this->db->update("global_users", array("state" => "blocked"), array("id" => $id));
        //echo $this->db->last_query();
        redirect("admin/projectc/view/id/1");
    }

    function unban_partner($id) {
        $this->db->update("p1_users", array("status" => "active"), array("user_id" => $id));
        $this->db->update("global_users", array("state" => "registered"), array("id" => $id));
        redirect("admin/projectc/view/id/1");
    }

    public function _remap($method) {
        $skip_methods = array("ajax_save", "ban_partner", "unban_partner");
        if (method_exists($this, $method)) {
            $arr = $this->uri->ruri_to_assoc();
            if (!in_array($method, $skip_methods)) {
                return call_user_func(array($this, $method), $arr);
            } else {
               $arr=array();
                $param=$this->uri->segment(4);
                if(!empty($param))$arr["varr"]=$param;
                return call_user_func_array(array($this, $method), $arr);
            }
        }
        show_404();
    }

}

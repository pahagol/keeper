<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stats extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->user->is_admin())
            redirect("/");
    }

    public function index() {
        //$this->output->enable_profiler();
        $this->by_date();
    }

    function by_date($params = array()) {
        $this->load->helper("form");
        $valid_params = array("date", "date_to", "user_id");
        $params = elements($valid_params, $params);

        $this->load->helper('flexigrid');
        $colModel['gu.name'] = array('Партнер', 120, TRUE, 'center', 2);
        $colModel['pds.date'] = array('Дата', 140, TRUE, 'center', 2);
        $colModel['clicks'] = array('Клики', 100, TRUE, 'center', 0);
        $colModel['registers'] = array('Регистрации', 100, TRUE, 'center', 0);
        $colModel['active_regs'] = array('Активных', 100, TRUE, 'center', 0);
        $colModel['earnings'] = array('Заработок Партнеров', 150, TRUE, 'left', 0);
		$colModel['earnings_mlgame'] = array('Заработок Системы', 150, TRUE, 'left', 0);

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

        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/stats_admin/{$this->uri->assoc_to_uri($params, true)}"), $colModel, 'date', 'desc', $gridParams);
        $yest = date("Y-m-d", time() - 24 * 60 * 60);
        //$data['prev_html']="";
        $data['js_grid'] = $grid_js;

        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $d['date'] = $params['date'];
        $d['date_to'] = $params['date_to'];
        $d['user_id'] = $params['user_id'];
        $this->load->model("project_model", "pm");
        $d['users_drop'] = $this->pm->get_users_for_dropdown();
        $d['submit_url'] = site_url("admin/stats/by_date");
        $dat['cont'] = $this->load->view("admin/stats_view", $d, true);
        $this->load->view("global_view", $dat);
    }

    function by_partner() {
        $this->load->helper('flexigrid');
        $colModel['pu.user_id'] = array('ID', 40, TRUE, 'center', 2);
        $colModel['name'] = array('Партнер', 80, TRUE, 'center', 2);
        $colModel['pu.ref_code'] = array('Реф код', 40, TRUE, 'center', 2);
        $colModel['gu.reg_date'] = array('Дата регистрации', 100, TRUE, 'center', 2);
        $colModel['earnings'] = array('Вознаграждение', 80, TRUE, 'left', 0);
        $colModel['pu.percent'] = array('Процент', 40, TRUE, 'left', 0);
        $colModel['pu.sum_to_pay'] = array('К выплате', 60, TRUE, 'left', 0);
        $colModel['delayed_sum_to_pay'] = array('Текущая выплата', 80, TRUE, 'left', 0);
        $colModel['pu.uclicks'] = array('Клики', 30, TRUE, 'center', 0);
        $colModel['pu.registers'] = array('Регистрации', 60, TRUE, 'left', 0);
        $colModel['regs'] = array('Регистраций в сутки', 80, FALSE, 'left', 0);
        $colModel['active_regs'] = array('Активных', 40, TRUE, 'left', 0);
        $colModel['ps.cnt_sites'] = array('Площадок', 50, TRUE, 'left', 0);


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

        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/stats_admin_by_partner"), $colModel, 'pu.earnings', 'desc', $gridParams);
        $yest = date("Y-m-d", time() - 24 * 60 * 60);
        $data['js_grid'] = $grid_js;

        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $dat['cont'] = $this->load->view("admin/stats_view", $d, true);
        $this->load->view("global_view", $dat);
    }

    function by_date_partner($params) {
        $valid_params = array("date", "date_to", "order", "earnings_not_null");
        $params = elements($valid_params, $params);
        if (!in_array($params['order'], array("ds.registers", "ds.active_regs", "ds.earnings"))) {
            $params['order'] = "ds.registers";
        }

        $this->load->helper('flexigrid');
        $colModel['user_id'] = array('ID', 100, TRUE, 'center', 2);
        $colModel['name'] = array('Партнер', 150, TRUE, 'center', 2);
        $colModel['ds.clicks'] = array('Клики', 100, TRUE, 'center', 0);
        $colModel['ds.registers'] = array('Регистрации', 100, TRUE, 'left', 0);
        $colModel['ds.active_regs'] = array('Активных на тот день', 100, TRUE, 'left', 0);
        $colModel['ds.earnings'] = array('Вознаграждение', 130, TRUE, 'left', 0);

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
        if ($params['date'] === false) {
            $q = $this->db->select_max("date")->from("p1_day_stats")->get();
            $dq = $q->row();
            $params['date'] = $dq->date;
        }//date("Y-m-d",time()-24*60*60);
        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/stats_admin_by_date_partner/{$this->uri->assoc_to_uri($params, true)}"), $colModel, $params['order'], 'desc', $gridParams);
        $data['js_grid'] = $grid_js;

        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $d['date'] = $params['date'];
        $d['date_to'] = $params['date_to'] ? $params['date_to'] : $params['date'];
        $d['earnings_not_null'] = $params['earnings_not_null'];
        $d['submit_url'] = site_url("admin/stats/by_date_partner");
        $dat['cont'] = $this->load->view("admin/stats_view", $d, true);
        $this->load->view("global_view", $dat);
    }

    function by_referal($params) {
        $this->load->helper("form");
        $valid_params = array("user_id","show_active");
        $params = elements($valid_params, $params);

        $this->load->helper('flexigrid');
        $colModel['pr.id'] = array('ID', 60, TRUE, 'center', 2);
        $colModel['pr.name'] = array('Реферал', 150, TRUE, 'center', 2);
        $colModel['pr.reg_date'] = array('Дата Реги', 100, TRUE, 'center', 2);
        $colModel['user_name'] = array('Кем Приведен', 80, TRUE, 'center', 0);
	//	$colModel['spent'] = array('Потрачено', 60, TRUE, 'left', 0);
        $colModel['spent'] = array('Введено', 60, TRUE, 'left', 0);
      //  $colModel['earned'] = array('Добыто в игре', 80, TRUE, 'left', 0);
      //   $colModel['inputted']= array('Введено', 60, TRUE, 'left', 0);
      //  $colModel['credit'] = array('Кредит', 60, TRUE, 'left', 0);
        $colModel['profit'] = array('Выплачено за него', 100, TRUE, 'left', 0);
        $colModel['level'] = array('Активность', 75, TRUE, 'left', 0);

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
        //if ($params['date'] === false) {
         //   $q = $this->db->select_max("date")->from("p1_day_stats")->get();
         //   $dq = $q->row();
            //$date=$dq->date;
        //}//date("Y-m-d",time()-24*60*60);
        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/stats_admin_by_referal/{$this->uri->assoc_to_uri($params, true)}"), $colModel, 'id', 'asc', $gridParams);
        $data['js_grid'] = $grid_js;

        $d['cont'] = $this->load->view('flexigrid', $data, true);
        //$d['date'] = $params['date'];
        $d['user_id'] = $params['user_id'];
        $d['show_active']=$params['show_active'];
        $this->load->model("project_model", "pm");
        $d['users_drop'] = $this->pm->get_users_for_dropdown();
        $d['submit_url'] = site_url("admin/stats/by_referal");
        $dat['cont'] = $this->load->view("admin/stats_view", $d, true);
        $this->load->view("global_view", $dat);
    }

    function by_date_referal($params) {
        $this->load->helper("form");
        $valid_params = array("date", "date_to", "order", "user_id");
        $params = elements($valid_params, $params);
        if (!in_array($params['order'], array("pr.id", "pr.name"))) {
            $params['order'] = "ds.day_profit";
        }

        $this->load->helper('flexigrid');
        $colModel['pr.id'] = array('ID', 100, TRUE, 'center', 2);
        $colModel['pr.name'] = array('Реферал', 150, TRUE, 'center', 2);
        //$colModel['pr.reg_date'] = array('Дата реги', 150, TRUE, 'center', 2);
        $colModel['ds.spent'] = array('Потрачено', 80, TRUE, 'center', 0);
        $colModel['ds.earned'] = array('Добыто', 80, TRUE, 'left', 0);
        $colModel['ds.inputted'] = array('Ввведено', 80, TRUE, 'left', 0);
        $colModel['ds.day_profit'] = array('Вознаграждение', 80, TRUE, 'left', 0);
        $colModel['ds.credit'] = array('Кредит', 80, TRUE, 'left', 0);
        $colModel['url'] = array('Пришел с площадки', 130, TRUE, 'left', 0);

        $gridParams = array(
            'width' => 'auto',
            'height' => 'auto',
            'rp' => 15,
            'rpOptions' => '[10,15,20,25,40,100,200,100,1000,5000,10000]',
            'pagestat' => 'Показать: от {from} до {to} из {total} записей.',
            'blockOpacity' => 0.5,
            'title' => 'Статистика',
            'showTableToggleBtn' => true
        );
        if ($params['date'] === false) {
            $q = $this->db->select_max("date")->from("p1_day_stats")->get();
            $dq = $q->row();
            $params['date'] = $dq->date;
        }//date("Y-m-d",time()-24*60*60);
        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/stats_admin_by_date_referal/{$this->uri->assoc_to_uri($params, true)}"), $colModel, $params['order'], 'desc', $gridParams);
        $data['js_grid'] = $grid_js;

        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $d['date'] = $params['date'];
        $d['date_to'] = $params['date_to'] ? $params['date_to'] : $params['date'];
        $d['user_id'] = $params['user_id'];
        $this->load->model("project_model", "pm");
        $d['users_drop'] = $this->pm->get_users_for_dropdown();
        $d['submit_url'] = site_url("admin/stats/by_date_referal");
        $dat['cont'] = $this->load->view("admin/stats_view", $d, true);
        $this->load->view("global_view", $dat);
    }

    function by_level_referal($params) {
        $this->load->helper("form");
        $this->load->model("project_model","pm");
        $valid_params = array("date", "reg_date","user_id");
        $params = elements($valid_params, $params);


        $this->load->helper('flexigrid');
        $colModel['name'] = array('Игрок', 200, TRUE, 'center', 2);
        $colModel['curlevel'] = array('Активность на данный день', 250, TRUE, 'center', 0);
        $colModel['reglevel'] = array('Активность на день регистрации', 200, TRUE, 'left', 0);
        $colModel['url'] = array('Пришел с площадки', 200, TRUE, 'left', 2);

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

        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/stats_admin_by_level_referal/{$this->uri->assoc_to_uri($params, true)}"), $colModel, 'name', 'desc', $gridParams);
        if(!empty($params['excel'])){
            $excel_data=build_excel_data(site_url("/admin/ajax/stats_admin_by_level_referal/{$this->uri->assoc_to_uri($params, true)}"), $colModel, 'name', 'desc', $gridParams);
            echo $excel_data;
            return;
        }
        $data['js_grid'] = $grid_js;

        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $d['date'] = $params['date'];
        $d['reg_date'] = $params['reg_date'];
        $d['user_id']=$params['user_id'];
        $d['users_drop'] = $this->pm->get_users_for_dropdown();
        $d['submit_url'] = site_url("admin/stats/by_level_referal");
        $dat['cont'] = $this->load->view("admin/stats_view", $d, true);
        $this->load->view("global_view", $dat);
    }

    public function _remap($method) {
        if (method_exists($this, $method)) {
            $arr = $this->uri->ruri_to_assoc();
            return call_user_func(array($this, $method), $arr);
        }
        show_404();
    }

}

/* End of file stats.php */
/* Location: ./application/controllers/stats.php */

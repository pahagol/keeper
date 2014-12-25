<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Refs extends CI_Controller {

    public function index() {
        $this->all();
    }

    function all($params = array()) {
        $this->load->helper("form");
        $this->load->model("global_model", "gm");
        $valid_params = array("proj", "reg_date","show_active");
        $params = elements($valid_params, $params);

        $this->load->helper('flexigrid');
        //$colModel['id'] = array('ID',80,TRUE,'center',2);
        $colModel['name'] = array('Игрок', 80, TRUE, 'center', 2);
        $colModel['reg_date'] = array('Дата реги', 100, TRUE, 'center', 0);
        $colModel['profit'] = array('Вознаграждение', 90, TRUE, 'left', 0);
        $colModel['spent'] = array('Введенные деньги (беспл.)', 90, TRUE, 'center', 0);
    //    $colModel['inputted'] = array('ЧЖ Потрачено', 60, TRUE, 'left', 0);
    //    $colModel['earned'] = array('Добыто', 60, TRUE, 'left', 0);
    //    $colModel['credit'] = array('Кредит', 60, TRUE, 'left', 0);
        $colModel['level'] = array('Активность', 60, TRUE, 'left', 0);
        $colModel['reg_level'] = array('Активность в день реги', 100, TRUE, 'left', 0);
        $colModel['url'] = array('Пришел с площадки', 120, TRUE, 'left', 2);

        $gridParams = array(
            'width' => 'auto',
            'height' => 'auto',
            'rp' => 15,
            'rpOptions' => '[10,15,20,25,40,100,1000,5000,10000]',
            'pagestat' => 'Показать: от {from} до {to} из {total} записей.',
            'blockOpacity' => 0.5,
            'title' => 'Статистика Моих Рефералов',
            'showTableToggleBtn' => true
        );

        $grid_js = build_grid_js('flex1', site_url("/ajax/refs/{$this->uri->assoc_to_uri($params, true)}"), $colModel, 'profit', 'desc', $gridParams);

        $data['js_grid'] = $grid_js;

        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $d['projects'] = $this->gm->get_user_projects_for_select($this->user->id);
        $d['p_id'] = $params['proj'];
        $d['reg_date'] = $params['reg_date'];
        $d['show_active']=$params['show_active'];
        $d['submit_url'] = site_url("refs/all");
        $dt['cont'] = $this->load->view("refs_view", $d, true);
        $this->load->view("global_view", $dt);
    }

    function by_date($params = array()) {
        $this->load->helper("form");
        $this->load->model("global_model", "gm");
        $valid_params = array("proj", "date","show_active");
        $params = elements($valid_params, $params);

        $this->load->helper('flexigrid');
        $colModel['name'] = array('Игрок', 80, TRUE, 'center', 2);
        $colModel['prds.query_date'] = array('Дата', 80, TRUE, 'center', 2);
        $colModel['reg_date'] = array('Дата Регистрации', 100, TRUE, 'center', 0);
        $colModel['prds.day_profit'] = array('Вознаграждение', 90, TRUE, 'left', 0);
        $colModel['prds.inputted'] = array('Введенные деньги (беспл.)', 90, TRUE, 'center', 0);
        $colModel['prds.spent'] = array('Потрачено', 60, TRUE, 'left', 0);
        $colModel['prds.earned'] = array('Добыто', 60, TRUE, 'left', 0);
        $colModel['prds.credit'] = array('Кредит', 60, TRUE, 'left', 0);
        $colModel['site'] = array('Пришел с площадки', 120, TRUE, 'left', 0);

        $gridParams = array(
            'width' => 'auto',
            'height' => 'auto',
            'rp' => 15,
            'rpOptions' => '[10,15,20,25,40,100,1000,5000,10000]',
            'pagestat' => 'Показать: от {from} до {to} из {total} записей.',
            'blockOpacity' => 0.5,
            'title' => 'Статистика Моих Рефералов',
            'showTableToggleBtn' => true
        );

        $grid_js = build_grid_js('flex1', site_url("/ajax/refs_by_date/{$this->uri->assoc_to_uri($params, true)}"), $colModel, 'prds.query_date', 'desc', $gridParams);

        $data['js_grid'] = $grid_js;

        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $d['projects'] = $this->gm->get_user_projects_for_select($this->user->id);
        $d['p_id'] = $params['proj'];
        $d['date'] = $params['date'];
        $d['show_active']=$params['show_active'];
        $d['submit_url'] = site_url("refs/by_date");
        $dt['cont'] = $this->load->view("refs_view", $d, true);
        $this->load->view("global_view", $dt);
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

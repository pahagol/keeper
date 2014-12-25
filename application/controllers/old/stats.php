<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stats extends CI_Controller {

    public function index() {
        $this->view();
    }

    function view($params = array()) {
        $this->load->helper("form");
        $this->load->model("global_model","gm");
        $valid_params = array("proj","site","date","date_to");
        $params = elements($valid_params, $params);
        $this->load->helper('flexigrid');
        $colModel['date'] = array('Дата', 150, TRUE, 'center', 2);
        $colModel['clicks'] = array('Клики', 150, TRUE, 'center', 0);
        $colModel['registers'] = array('Регистрации', 150, TRUE, 'left', 0);
        $colModel['active_regs'] = array('Активных на тот день', 150, TRUE, 'left', 0);
        $colModel['earnings'] = array('Вознаграждение', 150, TRUE, 'left', 0);

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

        $grid_js = build_grid_js('flex1', site_url("/ajax/stats/{$this->uri->assoc_to_uri($params, true)}"), $colModel, 'date', 'desc', $gridParams);

        $data['js_grid'] = $grid_js;
        
        $d['proj']=$params['proj'];
        $d['site']=$params['site'];
        $d['date']=$params['date'];
        $d['date_to']=$params['date_to'];
        $d['projects'] = $this->gm->get_user_projects_for_select($this->user->id);
        $d['submit_url']=site_url("stats/view");
        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $dat['cont'] = $this->load->view("stats_view", $d, true);
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

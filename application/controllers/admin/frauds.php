<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Frauds extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->user->is_admin())
            redirect("/");
    }

    public function index() {
        $this->load->helper('flexigrid');
        $colModel['gu.name'] = array('Партнер', 100, TRUE, 'center', 2);
        $colModel['pc.date'] = array('Дата', 100, TRUE, 'left', 0);
        $colModel['pc.sum'] = array('Сумма', 100, TRUE, 'left', 0);
        $colModel['pc.ref_name'] = array('Игрок', 100, TRUE, 'left', 0);

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

        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/admin_frauds"), $colModel, 'pc.date', 'desc', $gridParams);
        $data['js_grid'] = $grid_js;
        $d['cont'] = $this->load->view("flexigrid", $data, true);
        $this->load->view("global_view", $d);
    }

}

/* End of file frauds.php */
/* Location: ./application/controllers/admin/frauds.php */

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Referal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->user->is_admin())
            redirect("/");
    }

    function index() {
        
    }

    function view($id) {
        $id = intval($id);
        $q = $this->db->get_where("p1_referals", array("id" => $id));
        if ($q->num_rows()) {
            $ref = $q->row();
            $d['referal'] = $ref;
        } else {
            show_error("Реферал не найден");
        }

        $this->load->helper('flexigrid');
        $colModel['query_date'] = array('Дата', 60, TRUE, 'center', 2);
        $colModel['spent'] = array('Потрачено', 60, TRUE, 'center', 2);
        $colModel['earned'] = array('Заработано', 60, TRUE, 'left', 2);
        $colModel['inputted'] = array('Введено', 60, TRUE, 'left', 0);
        $colModel['ref_paid'] = array('Выплачено', 60, TRUE, 'left', 0);
        $colModel['ref_to_pay'] = array('К выплате', 60, TRUE, 'left', 0);
        $colModel['t1.level'] = array('Уровень', 60, TRUE, 'left', 0);
        $colModel['day_sum'] = array('Дневная сумма', 80, TRUE, 'left', 0);
        $colModel['day_profit'] = array('Дневное вознаграждение', 130, TRUE, 'left', 0);
        $colModel['day_profit_mlgame'] = array('Дневное вознаграждение по MLGame',140, TRUE, 'left', 0);
        $colModel['credit'] = array('Кредит', 60, TRUE, 'left', 0);

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
        $d['user']=$this->user->info($ref->user_id);
        $grid_js = build_grid_js('flex1', site_url("/admin/ajax/admin_referal_day_stats/$id"), $colModel, 'query_date', 'desc', $gridParams);
        $d['js_grid'] = $grid_js;
        $data['cont'] = $this->load->view("admin/referal_view", $d, true);
        $this->load->view("global_view", $data);
    }

}

?>
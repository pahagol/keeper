<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Finance extends CI_Controller {

    public function index() {
        /*
          $user=$this->user->info();
          $data['earns']=$user->earnings;
          $data['sum_to_pay']=$user->sum_to_pay;
          $this->load->model("global_model","gm");
          $data['payouts']=$this->gm->get_payouts($this->user->id);
          $data['all_sum']=$this->gm->get_sum_payots($this->user->id);
          $d['cont'] = $this->load->view('finance_view', $data, true);
          $this->load->view("global_view", $d);

         */
        $this->load->model("global_model","gm");
        $user = $this->user->info();
        $data['earns'] = $user->earnings;
        $data['sum_to_pay'] = $user->sum_to_pay;
        $data['sum_paid']=$this->gm->get_payouts_sum($user->id);

        $this->load->helper('flexigrid');
        $colModel['date'] = array('Дата', 150, TRUE, 'center', 0);
        $colModel['sum'] = array('Сумма', 150, TRUE, 'center', 2);
        $colModel['type'] = array('Тип', 150, TRUE, 'left', 0);


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

        $grid_js = build_grid_js('flex1', site_url("/ajax/finance"), $colModel, 'date', 'desc', $gridParams);

        $data['js_grid'] = $grid_js;
        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $dat['cont'] = $this->load->view("finance_view", $d, true);
        $this->load->view("global_view", $dat);
    }

}

/* End of file finance.php */
/* Location: ./application/controllers/finance.php */

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Loyalty extends CI_Controller {

    public function index() {
        $user = $this->user->info();
        $data['link'] = site_url("invite/from/" . $user->id);
        $data['percent'] = $user->loyalty_percent;

        $this->load->helper('flexigrid');
        $colModel['name'] = array('Приведенный Партнер', 120, TRUE, 'center', 2);
        $colModel['earnings'] = array('Заработок Партнера', 120, TRUE, 'center', 0);
        $colModel['registers'] = array('Регистрации Партнера', 120, TRUE, 'left', 0);
        $colModel['active_regs'] = array('Активных на сегодня', 120, TRUE, 'left', 0);
        $colModel['active_regs_reg_day'] = array('Активных на день Реги', 120, TRUE, 'left', 0);
        $colModel['parent_loyalty_percent'] = array('Процент по Партнеру', 120, TRUE, 'left', 0);
        $colModel['my_earnings']=array('Мой Заработок', 120, TRUE, 'left', 0);
        //$colModel['active_regs'] = array('Активных на тот день', 150, TRUE, 'left', 0);
        //$colModel['earnings'] = array('Вознаграждение', 150, TRUE, 'left', 0);

        $gridParams = array(
            'width' => 'auto',
            'height' => 'auto',
            'rp' => 15,
            'rpOptions' => '[10,15,20,25,40,100,1000,5000,10000]',
            'pagestat' => 'Показать: от {from} до {to} из {total} записей.',
            'blockOpacity' => 0.5,
            'title' => 'Статистика по Лояльности',
            'showTableToggleBtn' => true
        );

        $grid_js = build_grid_js('flex1', site_url("/ajax/loyalty/"), $colModel, 'earnings', 'desc', $gridParams);

        $data['js_grid'] = $grid_js;
        $dat['cont'] = $this->load->view("loyalty_view", $data, true);
        $this->load->view("global_view", $dat);
    }

    /*
      public function invite($user_id=false)
      {

      if($this->user->is_existed_id($user_id)){
      $this->input->set_cookie("loyalty_invite",$user_id,config_item("invite_lifetime"));
      $this->session->set_userdata("loyalty_invite",$user_id);
      }
      redirect("register");
      }
     * 
     */
}

/* End of file loyalty.php */
/* Location: ./application/controllers/loyalty.php */

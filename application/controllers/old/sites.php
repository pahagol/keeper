<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sites extends CI_Controller {

    public function index() {
/*
        $this->load->model("global_model", "gm");
        $data['sites'] = $this->gm->get_sites($this->user->id);

        $d['cont'] = $this->load->view('sites_view', $data, true);
        $this->load->view("global_view", $d);
 */

        $this->load->helper('flexigrid');
        $colModel['gs.url'] = array('Площадка', 130, TRUE, 'center', 2);
        $colModel['gs.attendance'] = array('Посещаемость', 130, TRUE, 'center', 0);
        $colModel['ps.clicks'] = array('Кликов', 100, TRUE, 'left', 0);
        $colModel['ps.registers'] = array('Регистраций', 100, TRUE, 'left', 0);
        $colModel['ps.active_regs'] = array('Активных', 100, TRUE, 'left', 0);
        $colModel['ps.active_regs_reg_day'] = array('Активных на день регистрации', 160, TRUE, 'left', 0);
        $colModel['ps.earnings'] = array('Вознаграждение', 130, TRUE, 'left', 0);
       
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

        $grid_js = build_grid_js('flex1', site_url("/ajax/sites"), $colModel, 'url', 'desc', $gridParams);

        $data['js_grid'] = $grid_js;
        $data['after_html']="<p style='margin-top: 20px'><a href='sites/add/1'>Добавить площадку</a><p>";
        $d['cont'] = $this->load->view('flexigrid', $data, true);
        $this->load->view("global_view", $d);
    }

    function add($p_id=1) {
        $this->load->library("form_validation");
        $this->form_validation->set_rules('url', 'Url', 'required|max_length[100]|min_length[5]|is_unique[global_sites.url]');
        $this->form_validation->set_rules("attendance", 'Attendance', 'integer');

        if ($this->form_validation->run()) {
            $site['user_id']=$this->user->id;
            $site['url']=$this->input->post("url");
            $site['attendance']=$this->input->post("attendance");
            $this->load->model("global_model","gm");
            $this->load->model("project_model","pm");
            
            $site_id=$this->gm->insert_site($site);
            
            
            if(!empty($p_id)){
                $this->pm->insert_site($p_id,array("site_id"=>$site_id));
                redirect("projectc/view/{$p_id}");    
            };
            redirect("sites");
        } else {
            $d['cont'] = $this->load->view("sites_add_view", null, true);
            $this->load->view("global_view", $d);
        }
    }

}

/* End of file sites.php */
/* Location: ./application/controllers/sites.php */

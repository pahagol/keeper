<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class New_user_form extends CI_Controller {

    function index() {
        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        //$this->form_validation->set_rules('site', 'Площадка', 'required|trim|is_unique[global_sites.url]|min_length[4]');
        //$this->form_validation->set_rules('attendance', 'Посещаемость', 'required|integer');
        $this->form_validation->set_rules('wallet', 'Кошелек', 'trim');

        if ($this->form_validation->run() == TRUE) {
            $wallet = $this->input->post("wallet");
            //$attendance = $this->input->post("attendance");
            $url = $this->input->post("site");
            $this->db->update("global_users", array("state" => "moderate", "wmz_num" => $wallet), array("id" => $this->user->id));
            /*
            $this->db->set("user_id", $this->user->id);
            $this->db->set("url", $url);
            $this->db->set("state", "moderate");
            $this->db->set("attendance", $attendance);
            $this->db->set("date_added", "NOW()", false);
            $this->db->insert("global_sites");
            $site_id = $this->db->insert_id();
            $this->db->insert("p1_sites", array("site_id" => $site_id, "status" => "moderate"));
             * 
             */
            $this->user->state = "moderate";
            $this->session->set_userdata("user_state", "moderate");
            //redirect("");

            send_admin_mail("MlGame Partner System. Новый партнер ожидает модерации", "Поступила заявка от нового Партнера {$this->user->name}. Партнер ожидает модерации");
        }

        $d['cont'] = $this->load->view("new_user_form_view", null, true);
        $this->load->view("global_view", $d);
    }

}

?>
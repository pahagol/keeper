<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Userc extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->user->is_admin())
            redirect("/");
    }

    public function index() {
        //$this->output->enable_profiler();
        $this->view();
    }

    function view($user_id = false) {
        //$this->output->enable_profiler();
        if ($user_id) {
            $this->load->model("project_model", "pm");
            $this->load->model("global_model", "gm");
            //$projects=$this->pm->get_user_projects($user_id);         
            $user = $this->user->info($user_id);
            $puser = $this->pm->get_user_info($user_id);

            $data['name'] = $user->name;
            $data['full_name']=$user->full_name;
            $data['id'] = $user->id;
            $data['reg_date'] = $user->reg_date;
            $data['ref_code'] = $puser ? $puser->ref_code : "";
            $data['percent'] = $puser ? $puser->percent : "";
            $data['system_percent'] = $puser ? $puser->system_percent : "";
            $data['loyalty_percent'] = $user->loyalty_percent;
            if ($user->parent_id != 0 && $parent_user = $this->user->info($user->parent_id)) {
                $data['by_partner']['name'] = $parent_user->name;
                $data['by_partner']['id'] = $parent_user->id;
            }else
                $data['by_partner'] = false;
            $data['email'] = $user->email;
            $data['regs'] = $puser->registers;
            $data['active'] = $puser->active_regs;
            $data['earned'] = $puser->earnings;
            $data['earned_mlgame'] = $puser ? $puser->earnings_mlgame : "";
            $data['earned_loyalty'] = $user->loyalty_earnings;
            $data['state'] = $user->state;
            $data['sum_to_pay'] = $puser ? $puser->sum_to_pay : "";
            $data['loyalty_sum_to_pay'] = $user->loyalty_sum_to_pay;
            $data['wmz_num']=$user->wmz_num;


            $data['payouts'] = $this->gm->get_payouts($user_id);
            $data['payouts_loyalty'] = $this->gm->get_payouts($user_id, "loyalty");
            
            $data['payouts_sum']=$this->gm->get_payouts_sum($user_id);
            $data['payouts_loyalty_sum']=$this->gm->get_payouts_sum($user_id,"loyalty");
            
            $data['count_children'] = $this->gm->get_count_children($user_id);
            if ($data['count_children'] > 0) {
                $data['child_users'] = $this->gm->get_children($user_id);
            }
            $q = $this->db->get_where("global_log", array("user_id" => $user_id));
            $data['global_log'] = $q->result();
            $d['cont'] = $this->load->view("admin/user_view", $data, true);
            $this->load->view("global_view", $d);
        }else
            show_error("Не задан идентификатор Партнера");
    }

    function add_payout() {
        $sum = $this->input->post("input_sum");
        $type = $this->input->post("type");
        $user_id = $this->input->post("user_id");
        if ($sum && $user_id && in_array($type, array("default", "loyalty"))) {
            $this->user->add_payout($user_id, $sum, $type);
        }
        redirect("admin/userc/view/{$user_id}");
    }

    function ajax_save() {
        $name = $this->input->post("name");
        $val = $this->input->post("val");
        $u_id = $this->input->post("id");
        $accepted = array("ref_code", "percent", "loyalty_percent", "system_percent");
        $arr = array($name => $val);
        if ($name) {
            if (in_array($name, $accepted)) {
                $this->load->model("global_model", "gm");
                $this->load->model("project_model", "pm");
                $user = $this->user->info($u_id);
                $puser = $this->pm->get_user_info($u_id);
                $old_value = null;
                switch ($name) {
                    case "ref_code":
                        $action = "change_user_ref_code";
                        $old_value = $puser->ref_code;
                        break;
                    case "percent":
                        $action = "change_user_percent";
                        $old_value = $puser->percent;
                        break;
                    case "loyalty_percent":
                        $action = "change_user_loyalty_percent";
                        $old_value = $user->loyalty_percent;
                        break;
                    case "system_percent":
                        $action = "change_user_system_percent";
                        $old_value = $puser->system_percent;
                        break;
                    default :
                        return;
                }


                $this->db->trans_start();
                if ($action == "change_user_loyalty_percent")
                    $this->db->update("global_users", $arr, array("id" => $u_id));
                else
                    $this->db->update("p1_users", $arr, array("user_id" => $u_id));
                $this->gm->add_log($u_id, $action, $val, $old_value);
                $this->db->trans_complete();

                if ($this->db->trans_status() === TRUE) {
                    echo "ok";
                } else {
                    echo "no";
                }
                return;
            }
        }
        echo "no";
        return;
    }

    function ban($id) {
        $this->db->update("p1_users", array("status" => "banned"), array("user_id" => $id));
        $this->db->update("global_users", array("state" => "blocked"), array("id" => $id));
        //echo $this->db->last_query();
        redirect("admin/userc/view/{$id}");
    }

    function unban($id) {
        $this->db->update("p1_users", array("status" => "active"), array("user_id" => $id));
        $this->db->update("global_users", array("state" => "registered"), array("id" => $id));
        //echo $this->db->last_query();
        redirect("admin/userc/view/{$id}");
    }

    function moderate($id) {
        $this->db->update("p1_users", array("status" => "active"), array("user_id" => $id));
        $this->db->update("global_users", array("state" => "registered"), array("id" => $id));
        $user = $this->user->info($id);
        $email = $user->email;     
		$text = 
<<<HD
Здравствуйте,<br><br>

Благодарим  Вас за то, что вы присоединились к партнерской программе Apocalypse 2056!<br><br>

Поздравляем - после рассмотрения нашими менеджерами  - Вам присвоен<br>
реферальный код!<br><br>

Ваш реферальный код и ссылку вы можете увидеть в Персональном кабинете на сайте  <a href="http://2056.aratog.com">http://2056.aratog.com</a> .<br><br>

Вы можете разместить на своих сайтах баннеры или текстовые ссылки с Вашим реферальным кодом.<br><br>

Вы можете воспользоваться готовыми баннерами, ссылку на которые вы можете найти в Вашем персональном кабинете, либо вы можете сделать свой баннер или поставить ссылку.<br><br>

Если вы перейдете на страничку баннеров из вашего персонального кабинета,то ваш реферальный код уже будет автоматически прописан в коде под каждым баннером. Вам останется только скопировать ссылку на баннер с уже встроенным кодом и вставить в код вашего сайта.<br><br>

Информация о статистике:<br>
Статистика обновляется один раз в сутки в 4 часа ночи.<br><br>

Если у вас появятся вопросы, или потребуется помощь с размещением баннеров мы с удовольствием поможем Вам.<br><br>

С уважением,<br>
Кирилл<br>
_________________________<br>
Менеджер по работе с партнерами<br>
Партнерская программа Apocalypse 2056 <br>
Web: <a href="http://2056.aratog.com">http://2056.aratog.com</a><br>
E-mail: support@2056.aratog.com<br>
HD;
        send_user_mail($email, 'Apocalypse 2056 Partner System. Завершение модерации', $text, 'html');
        //echo $this->db->last_query();
        redirect("admin/userc/view/{$id}");
    }

    function confirm($id) {
        $this->db->update("p1_users", array("status" => "new"), array("user_id" => $id));
        $this->db->update("global_users", array("state" => "inactive"), array("id" => $id));
        //echo $this->db->last_query();
        redirect("admin/userc/view/{$id}");
    }

    function simulate($user_id) {
        $this->user->enable_user_simulation($user_id);
        redirect("/");
    }

    function disable_simulate() {
        $this->user->disable_user_simulation();
        redirect("admin");
    }

}

/* End of file stats.php */
    /* Location: ./application/controllers/admin/user.php */

    
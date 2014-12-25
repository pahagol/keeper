<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends CI_Controller {

    public function index() {
        //$this->output->enable_profiler(true);
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Логин', 'required|max_length[80]|min_length[5]|alpha_numeric|is_unique[global_users.name]');
        $this->form_validation->set_rules('password', 'Пароль', 'required');
        $this->form_validation->set_rules("passconf", 'Подтверждение пароля', 'required|matches[password]');
        $this->form_validation->set_rules("email", 'Email', 'required|valid_email|is_unique[global_users.email]');

        if ($this->form_validation->run() == false) {
            $this->load->view('register_view');
        } else {
            $name = $this->input->post("username");
            $pass = $this->input->post("password");
            $email = $this->input->post("email");
            $full_name = $this->input->post("full_name");

            if ($this->session->userdata("loyalty_invite"))
                $parent_id = $this->session->userdata("loyalty_invite");
            elseif ($this->input->cookie("loyalty_invite"))
                $parent_id = $this->input->cookie("inetrek_loyalty_invite");
            else
                $parent_id = 0;
            
            if ($user_id = $this->user->add_user($name, $full_name, $pass, $email, $parent_id)) {
                $this->load->library("project");
                if ($this->project->add_user($user_id)) {
                    $hash = $this->user->gen_reg_hash($name, $user_id, $email);
					$site_url = site_url("register/confirm/$user_id/$hash");
					$site_url = '<a href="'.$site_url.'">'.$site_url.'</a>';
					
					$text = 
<<<HD
Здравствуйте!<br><br>

Вы подали заявку на регистрацию в партнерской программе Apocalypse 2056!<br><br>

Для подтверждения регистрации пожалуйста перейдите по ссылке {$site_url}<br><br>

Если Вы не проходили регистрацию, проигнорируйте это письмо.<br><br>

С уважением,<br>
Кирилл<br>
_________________________<br>
Менеджер по работе с партнерами<br>
Партнерская программа Apocalypse 2056<br>
Web: <a href="http://2056.aratog.com">http://2056.aratog.com</a><br>
E-mail: support@2056.aratog.com<br>
HD;
					
                    send_user_mail($email, 'Apocalypse 2056 Partner System. Подтверждение регистрации', $text, 'html');
                    
                    $this->user->set_sess_vars($this->user->info($user_id));
                    redirect("/");
                }else{
                    show_error("Произошла ошибка");
                }
            }
        }
    }

    function confirm($user_id, $hash) {
        //$this->output->enable_profiler();
        $u = $this->user->info($user_id);
        if ($hash == $this->user->gen_reg_hash($u->name, $u->id, $u->email)) {
            if ($this->user->set_state("new", $user_id)) {
                $this->session->set_flashdata('msg', array("type" => "msg", "text" => 'Вы успешно подтвердили аккаунт!'));
				
				$text = 
<<<HD
Здравствуйте, {$u->name}<br><br>

Спасибо за регистрацию в партнерской программе Apocalypse 2056!<br>
Мы рады сотрудничеству с Вами!<br><br>

После прохождения модерации, Вам будет назначен реферальный код. Не забудьте пожалуйста указать Ваш Z кошелек в системе WebMoney.<br><br>

Apocalypse 2056 - бесплатная браузерная онлайн игра, пост апокалиптическом жанре. Она включает в себя все лучшее, чем могут похвастаться online игры: красочную графику, реалистичные модели персонажей, большое разнообразие монстров, огромную глобальную карту и многое другое. К тому же для того, чтобы играть, вам не требуется качать клиент - вы просто регистрируетесь и сразу попадаете в игровой мир. <br><br>
 
Мы предлагаем лучшие условия по работе с нашей партнерской программой:<br>
Вы будете получать от 40% от всех платежей привлеченных Вами игроков, с увеличением до 50% в зависимости от объема трафика;<br>
Вы сможете вести раздельную статистику по Вашим нескольким сайтам;<br>
В партнерской программе внедрена система лояльности, которая позволяет получать от 3% с заработка привлеченных партнеров;<br>
Вы cможете видеть изменение кредита ваших игроков, их продвижение в уровнях, отслеживать активность игроков по дням;<br>
Широкий выбор рекламных материалов;<br>
Выплаты происходят 1 раз в месяц, каждое 5  число;<br>
Минимальная сумма вывода средств составляет 20$ (WebMoney);<br>
С нами у Вас всегда стабильный и высокий заработок!<br><br>

 
Также рекомендуем для Вас наши следующие проекты:<br><br>

1. mlgame.aratog.com<br>
Партнерская программа, разработанная специально для сверхпопулярной игры My Lands: black gem hunting.<br>
My Lands: black gem hunting - браузерная многопользовательская онлайн игра. Жанр - военно-экономическая стратегия в реальном времени, фэнтези.<br>
Эта партнерская программа базируется на таком же интерфейсе и наследует те же принципы что и 2056.aratog.com, так что разобраться в ней не составит труда.<br>
Программа mlgame.aratog.com предлагает Вам 30% от заработка с платящего игрока, с увеличением до 50% в зависимости от объема трафика.<br>
Зарегистрироваться можно по ссылке <a href="http://mlgame.aratog.com/register">http://mlgame.aratog.com/register</a><br><br>

2. Inetrek.com<br>
InetRek - это партнерская сеть с оплатой за конкретное действие пользователя (CPA, CPL).<br><br>

В Inetrek Лучшие тарифы по игровым кампаниям в рунете:<br><br>

Apocalypse 2056 - 0,60$ за регистрацию в игре<br>
My Lands - 0,42$ за регистрацию в игре<br>
1100 AD - 0,40$ за регистрацию в игре<br>
Королевство - 0,30$ за регистрацию в игре<br>
Техномагия - 0,40$ центов за регистрацию в игре<br><br>

Выплаты 1 раз в неделю, каждый четверг в автоматическом режиме, при достижении минимального баланса в 10$.<br><br>

Зарегистрироваться можно по ссылке <a href="http://inetrek.com/?page=registrations">http://inetrek.com/?page=registrations</a><br><br>

Мы готовы предоставить любую помощь с нашей стороны, для того, чтобы Вы начали зарабатывать в нашей партнерской программе.<br>
Если у Вас возникли какие либо вопросы, обращайтесь пожалуйста по адресу поддержки пользователей support@2056.aratog.com.<br><br>

С уважением,<br>
Кирилл<br>
_________________________<br>
Менеджер по работе с партнерами<br>
Партнерская программа Apocalypse 2056<br>
Web: <a href="http://2056.aratog.com">http://2056.aratog.com</a><br>
E-mail: support@2056.aratog.com
HD;
				
                send_user_mail($u->email, 'Apocalypse 2056 Partner System. Регистрация подтверждена', $text, 'html');
                redirect("new_user_form");
            }
            else
                show_error("Ошибка смены статуса");
        } else
            show_error("Не верный хеш");
    }

    function forgot() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("email", 'Email', 'required|valid_email');
        if ($this->form_validation->run() == false) {
            $this->load->view('forgot_view');
        } else {
            $email = $this->input->post("email");
            $q = $this->db->get_where("global_users", array("email" => $email));
            if ($q->num_rows()) {
                $u = $q->row();
                $this->load->library("email");
                $hash = $this->user->gen_reg_hash($u->name, $u->id, $u->email);
                send_user_mail($email, 'Mlgame Partners. Восстановление пароля', "Ваш логин - {$u->name} \nДля того, чтобы ввести новый пароль - перейдите по ссылке " . site_url("register/set_pass/{$u->id}/$hash"));
                redirect("/");
            }else
                show_error("Пользователь с таким имейлом не найден");
        }
    }

    function set_pass($user_id, $hash) {
        $this->load->library('form_validation');
        $u = $this->user->info($user_id);
        if ($hash == $this->user->gen_reg_hash($u->name, $u->id, $u->email)) {
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules("passconf", 'Pass Conf', 'required|matches[password]');
            if ($this->form_validation->run() == false) {
                $this->load->view("set_pass_view");
            } else {
                $pass = $this->input->post("password");
                $this->db->update("global_users", array("password" => $this->user->_joomla_hash_pass($pass)), array("id" => $user_id));
                redirect("/");
            }
        } else {
            show_error("Не верный хеш");
        }
    }

    /*
      public function check_login()
      {
      $name = $this->input->post("username");
      $pass = $this->input->post("password");
      if ($this->user->is_existed_user($name, $pass)) {
      $this->form_validation->set_message('check_login', 'Такой логин занят');
      return false;
      } else
      return true;
      }
     */
    /*
      public function check_email()
      {
      $name = $this->input->post("email");
      if ($this->user->is_existed_email($name, $pass)) {
      $this->form_validation->set_message('check_login', 'Такой Email занят');
      return false;
      } else
      return true;
      }
     */
}

?>
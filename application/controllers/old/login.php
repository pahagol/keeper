<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/** NOAUTH CONTROLLER**/
class Login extends CI_Controller
{


    public function index()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Логин',
            'required|max_length[80]|min_length[3]|alpha_numeric|callback_check_login');
        $this->form_validation->set_rules('password', 'Пароль', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('login_view');
        } else {
            if($this->user->is_admin()) redirect("admin");
            else redirect("/");
        }
    }

    public function logout()
    {
        $this->user->logout();
    }

//callback
    public function check_login()
    {
        $name = $this->input->post("username");
        $pass = $this->input->post("password");
        if (!$this->user->login($name, $pass)) {
            $this->form_validation->set_message('check_login', 'Не верный логин или пароль');
            return false;
        } else
            return true;
    }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */

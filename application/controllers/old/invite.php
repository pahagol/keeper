<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invite extends CI_Controller
{

    public function from($user_id=false)
    {
        
        if($this->user->is_existed_id($user_id)){
            $this->input->set_cookie("loyalty_invite",$user_id,config_item("invite_lifetime"));
            $this->session->set_userdata("loyalty_invite",$user_id);
        }
        redirect("register");
    }
}

/* End of file loyalty.php */
/* Location: ./application/controllers/loyalty.php */

<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/** NOAUTH CONTROLLER**/
class Forward extends CI_Controller
{

    public function index($site_id=false)
    {
        if(!$site_id)$site_id=$this->input->get("site");
        $proj_id=$this->input->get("proj")?$this->input->get("proj"):1;
        //echo $site_id;
        //echo $project_id;
        
        $this->load->model("project_model", "pm");
        $this->load->model("global_model", "gm");

        $user = $this->gm->get_user_by_site_id($site_id);
        $user_id = $user ? $user->id : false;
        $prx = $this->input->get("prx"); //prx from partner
        //$proj_id = config_item("default_project_id"); //$this->pm->get_project_by_site_id($site_id);
        $this->load->library("project", array("proj_id" => $proj_id));
        $click_id = $this->project->add_click($site_id, $user_id, $prx);
        
        //print_r($user);
	//	$params['bonus'] = $user ? $user->ref_code : ""; //refcode
		$params['ref'] = $user ? $user->ref_code : ""; //refcode
        $params['prx'] = $click_id;
        //print_r($params);
        $redirect_url = $this->project->gen_redirect_url($params);
        /** setting forward cookies **/
        if($site_id)$this->input->set_cookie("fwd_site_id",$site_id,config_item("forward_cookie_lifetime"));
        if($prx)$this->input->set_cookie("fwd_prx",$prx,config_item("forward_cookie_lifetime"));
        if($click_id)$this->input->set_cookie("fwd_click_id",$click_id,config_item("forward_cookie_lifetime"));
        if($user_id)$this->input->set_cookie("fwd_user_id",$user_id,config_item("forward_cookie_lifetime"));
        /** ****************** **/
        redirect($redirect_url);
    }
}

/* End of file forward.php */
/* Location: ./application/controllers/forward.php */

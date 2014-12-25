<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/** NOAUTH CONTROLLER**/
class Set_referal extends CI_Controller
{

    public function index()
    {
   //     ini_set('display_errors',1); 
 //error_reporting(E_ALL);
        //$ref_code = $this->input->get("bonus");
        //$prx_str = $this->input->get("prx"); //aratog_xxxxxx
        $referal_name = $this->input->get("name");
		$ref_code     = $this->input->get("ref");
	//	$ref_code     = str_replace('arspartner-', '', $this->input->get("ref"));
        
        $click_id=$this->input->cookie("fwd_click_id");
        if(!$ref_code) {
            $user_id=$this->input->cookie("fwd_user_id");
            if($user_id){
                $this->load->model("project_model","pm");
                $user = $this->pm->get_user_info($user_id);//$this->user->info($user_id);
                $ref_code = $user->ref_code;
            } 
        }
        
		//if(!$prx_str)$click_str=config_item("prx_cookie_prefix").$this->input->cookie("fwd_click_id");
        $this->load->library("project");
        //echo "hello".$click_str;
        log_message('error', "ADD REFERAL INDEX $ref_code, $referal_name ");
        $this->project->add_referal_from_set_referal($ref_code, $click_id, $referal_name);
    }
}

/* End of file set_referal.php */
/* Location: ./application/controllers/set_referal.php */

<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/** NOAUTH CONTROLLER**/
class Projectc extends CI_Controller
{
  
    public function index()
    {
        if(isset($_POST['project'])){
            $p_id=intval($_POST['project']);
            redirect("projectc/view/".$p_id);
        }else $p_id=false;
        
        $data['p_id']=$p_id;
        $this->load->model("project_model","pm");
        
        
        $data['projects']=$this->pm->get_user_projects($this->user->id);
        $d['cont'] = $this->load->view('project_view', $data, true);
        $this->load->view("global_view", $d);
    }
    
    function view($p_id){
        $this->load->model("global_model","gm");
        $this->load->model("project_model","pm");
        $this->load->library("project");
        $data['projects']=$this->pm->get_user_projects($this->user->id);
        $data['p_id']=$p_id;
        $data['project']=$this->gm->get_project($p_id);
        
        
        $data['p_user']=$this->pm->get_user_info($this->user->id);
        $user=$this->pm->get_user_info($this->user->id);
	//	$params['bonus'] = $user ? $user->ref_code : ""; //refcode
	//	$params['prx'] = $user ? $user->ref_code : ""; //refcode
        $params['ref'] = $user ? $user->ref_code : ""; //refcode
		$data['status']=  get_user_status_transalate($user->status);
        //print_r($params);
        $data['sites']=$this->gm->get_sites($this->user->id);
        if($user->status=="active"){
			$data['reflink'] = $this->project->gen_redirect_url($params);
			$data['bannerslink']=$this->project->gen_banners_url($params);
        }
        
		$d['cont'] = $this->load->view('project_view', $data, true);
        $this->load->view("global_view", $d);
    }
}

/* End of file project.php */
/* Location: ./application/controllers/project.php */


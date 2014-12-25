<?

class Project {

    public $id;
    public $redirect_url;

    function __construct($params = array()) {
        $this->CI = &get_instance();
        if (empty($params['proj_id']))
            $this->id = config_item("default_project_id");
        else
            $this->id = $params['proj_id'];
        $this->CI->load->model("project_model", "pm");
        $proj = $this->CI->pm->get_project_by_id($this->id);
        $this->redirect_url = $proj->redirect_url;
    }

    function add_click($site_id = false, $user_id = false, $prx = false) {

        if ($site_id) {
            $this->CI->load->model("global_model", "gm");
            if (!$user_id) {
                //echo "not user";
                $user = $this->CI->gm->get_user_by_site_id($site_id);
                if ($user) {
                    //echo "user got";
                    $user_id = $user->id;
                } else {
                    //echo "user false";
                    $g = false;
                    return $g;
                }
            }
            if ($user_id)
                return $this->CI->pm->insert_click($user_id, $site_id, $this->id, $prx);
            else
                return false;
        } else
            return false;
    }

    function add_referal_from_set_referal($ref_code = false, $click_id = false, $referal_name = false) {
        log_message('error', "ADD REFERAL $ref_code , $click_id , $referal_name ");
        if ($referal_name) {
            $p = array();
            $user_id = false;
            if (!$this->is_existed_referal($referal_name)) {
                $p['name'] = $referal_name;
                /*                 * * * */
                if ($ref_code) {
                    $p['ref_code'] = $ref_code;
                    $this->CI->load->model("global_model", "gm");
                    $user = $this->CI->gm->get_user_by_ref_code($ref_code);
                    $user_id = $user->user_id;
                    if ($user) {
                        $p['user_id'] = $user->user_id;
                    } else
                        log_message('error', "Set referal. User with ref_code $ref_code not found");
                } else
                    log_message('error', "Set referal. Ref_code is FALSE");
                if ($click_id) {
                    //$cl = explode("_", $click_str);
                    //if (!empty($cl[1])) {
                    // $click_id = $cl[1];
                    if ($this->is_existed_click($click_id, $user_id))
                        $p['click_id'] = $click_id;
                    else
                        $p['click_id_unknown'] = $click_id;
                    // } else
                    //   log_message("error", "Set referal. Click_str. Wrong format");
                } else
                    log_message("error", "Set referal. Click_str is FALSE");
                /*                 * ** * */
                $this->CI->pm->insert_referal($p);
            } else
                log_message('error', "Set referal. Name already exists");
        } else
            log_message('error', "Set referal. Name is FALSE");
    }

    function gen_redirect_url($params = array()) {
		switch ($this->id) {
            case 1: //mlgame
				$p = array();
                if (!empty($params['bonus']))
                    $p['bonus'] = $params['bonus'];
                if (!empty($params['prx']))
                    $p['prx'] = $params['prx'];
				
			//	$p['state'] = "register_simple";
				
				if (!empty($params['ref']))
                    $p['ref'] = $params['ref'];
                
                $params_str = http_build_query($p, '', '&');
                return $this->redirect_url . "/?" . $params_str;
                break;
        }
    }

    function gen_banners_url($params = array()) {
		switch ($this->id) {
            case 1: //mlgame
				$p = array();
                if (!empty($params['bonus']))
                    $p['bonus'] = $params['bonus'];
				if (!empty($params['prx']))
                    $p['prx'] = $params['prx'];
				
			//	$p['state'] = "register_simple";
				
				if (!empty($params['ref']))
                    $p['ref'] = $params['ref'];
                $params_str = http_build_query($p, '', '&');
			//	return $this->redirect_url . "/banners?" . $params_str;
				return "http://2056.aratog.com/banner?" . $params_str;
			//	return $this->redirect_url . "/?" . $params_str;
                break;
        }
    }

    function is_existed_click($id, $user_id = false) {
        $click = $this->CI->pm->get_click_by_id($id);
        if (!$user_id) {
            if ($click)
                return true;
            else
                return false;
        } else {
            if ($click && ($click->user_id == $user_id))
                return true;
            else
                return false;
        }
    }

    function is_existed_referal($name) {
        if ($this->CI->pm->get_referal_by_name($name))
            return true;
        else
            return false;
    }

    function gen_ref_code() {
        $this->CI->load->model("project_model");
        $this->CI->project_model->project = $this->id;
        $ref_arr = $this->CI->project_model->get_ref_codes();
        //print_r($ref_arr);
        //$max_code = max($ref_arr);
        $prefix = "aff_";
        $with_prefix_arr = array();
        foreach ($ref_arr as $key => $val) {
            if (strpos($val, $prefix, 0) !== false) {
                $with_prefix_arr[] = substr($val, 4);
            }
        }
        if (!empty($with_prefix_arr))
            $max_code = max($with_prefix_arr);else
            $max_code = 0;
        $new_code = $max_code++;
        return $prefix . $max_code;
    }

    function add_user($user_id) {
        $u['user_id'] = $user_id;
        $u['status'] = "inactive";
        $u['percent'] = config_item("default_percent");
        //$u['ref_code'] = NUll;//$this->gen_ref_code();
        //$u['connect_date']="NOW()";
        $this->CI->load->model("project_model", "pm");
        $this->CI->load->model("global_model", "gm");
        $this->CI->pm->project = $this->id;

        $this->CI->db->trans_start();
        $this->CI->pm->insert_user($u);
        $this->CI->gm->insert_user_project($user_id, $this->id);
        $this->CI->db->insert("global_sites", array("user_id" => $user_id, "url" => "null", "state" => "active", "attendance" => 0));
        $site_id = $this->CI->db->insert_id();
        $this->CI->db->insert("p1_sites", array("site_id" => $site_id, "status" => "active"));
        $this->CI->db->trans_complete();
        return $this->CI->db->trans_status();
    }

    /*     * ** mlgame only. must be implemented for all projects in future *** */

    function archivate_referal_day_stat() {
        //$users = $this->CI->pm->get_project_users();
        //foreach ($users as $u)
        $this->backup_partner_xml();
    }

    function backup_partner_xml() {
        $url = config_item("mlgame_xml_url") . date("Ymd") . ".xml";
		//$url = config_item("mlgame_xml_url") . "20120902.xml";
        //$url = $url . $ref_code . '&skey=' . md5(config_item("mlgame_secret_key") . $ref_code);
        //$f = file_get_contents($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "partner2056ru:8bHFAuTguL");
        $f = curl_exec($ch); // run the whole process 
        curl_close($ch);
        if (!$f) {
            //log_message("error", "Backup Partner Xml. Can't get file content. $url");
            echo "Backup Partner Xml. Can't get file content. $url";
            return false;
        } else {
            $date = date("Y-m-d", time() - (60 * 60 * 24) ); //save as yesterday date
            $path = $this->_gen_archive_folder_path($date);
            if (!file_exists($path))
                mkdir($path);
            $filename = $this->_gen_archive_file_path($date);
            if (file_exists($filename))
            //log_message("error", "Backup Partner XML. Filename already exists: $filename");
                echo "Backup Partner XML. Filename already exists: $filename";
            if (!file_put_contents($filename, $f)) {
                //log_message("error", "Backup partner XML. Can't PUT contents $filename");
                echo "Backup partner XML. Can't PUT contents $filename";
                return false;
            } else
                return true;
        }
    }

    function calc_day_stat($date = false) {
        gc_enable();
        if (!$date)
            $date = date("Y-m-d", time() - (60 * 60 * 24)); //yesterday date

	//	$date = '2012-09-24';
		
		
		
        log_message("error", "Calc Day Stat Started. Calc date is: $date . Real date is " .
                date("Y-m-d"));
        $path = $this->_gen_archive_folder_path($date);
        if (file_exists($path)) {
			// вытаскиваем партнёров
            $users = $this->CI->pm->get_project_users();
            $rds_ins = array(); //referal day stat batch insert array
            $r_ins = array(); //referal batch insert array
            $r_upd = array(); //referal batch update array
            $uds_ins = array();
            $u_upd = array();
            $rlvl_ins = array();


            $filename = $this->_gen_archive_file_path($date);
            if (file_exists($filename)) {
                if (filesize($filename)) {
					
                    $xml = simplexml_load_file($filename);
				//	var_dump($xml);
					
                    if ($xml) {
                        foreach ($users as $u) {
							
						//	var_dump($u);
							
                            if (!empty($xml->users)) {

                                $day_regs = 0;
                                $day_active = 0;
                                $day_earn = 0;
                                $day_ref_paid = 0;
                                $day_earn_mlgame = 0;

								// формируем массив рефералов (игроков)
                                $pr = $this->CI->pm->get_project_referals_as_named_array($u->user_id);
                                foreach ($xml->users->user as $r) {
								//	$ref_arr = explode("-", $r->ref);
                                    
								//	echo $ref_arr[1].' '.$u->user_id.'<br>';
									
								//	if ($ref_arr[1] == $u->user_id) {
									if ($r->ref == $u->ref_code) {	
                                        $r = $this->_xml_ref_to_db_ref($r);

                                        if (empty($pr[$r['name']])) {
                                           
                                            $r_db = false;
                                        }
                                        else
                                            $r_db = &$pr[$r['name']];

                                        if ($r_db) { //if existed referal
                                            //check for negative difference between now and yesterday. Yest vals have to be less than current, or equal.
										//	if ($r_db['spent'] <= $r['spent'] /*&& $r_db['earned'] <= $r['earned'] && $r_db['inputted'] <= $r['inputted']*/ && $r_db['ref_paid'] <= $r['ref_paid']) {
											if ($r['spent'] > 0  || $r['ref_paid'] > 0 || $r['level'] > 0) {	
                                                // level insert
                                                //if (/ $r['level'] > 1 && /$r['level'] != $r_db['level'])
                                                $rlvl_ins[] = array("ref_name" => $r['name'], "date" => $date, "level" => $r['level']);

                                                // calc and add referal day stats
                                                $rday = $this->calc_referal_day_stat($r, $r_db, $u->percent, $u->system_percent);
                                                if ($rday) {
                                                    $rday['query_date'] = $date;
                                                    $rday['referal_id'] = $r_db['id'];
                                                    $rds_ins[] = $rday;
                                                }
                                                $this->add_referal_for_update($r_upd, $r, $r_db, $date, $rday);
                                                // update day counters 
                                                if ($r_db['reg_date'] == $date) { //if existed referals belongs to this date
                                                    $day_regs++;
                                                    if ($r['level'] > 1)
                                                        $day_active++;
                                                }
                                                
                                            }
											else {
                                                log_message("error", "Calc day stats. ERROR. Referal {$r['name']} has negative values");
                                                continue;
                                            }
                                        } else { //if new referal
                                            // if ($r['level'] > 1)
                                            $rlvl_ins[] = array("ref_name" => $r['name'], "date" => $date, "level" => $r['level']);

                                            // generate referla day stats
                                            $rday = $this->calc_referal_day_stat($r, null, $u->percent, $u->system_percent);
                                            if ($rday) {
                                                $rday['name'] = $r['name'];
                                                $rday['query_date'] = $date;
                                                $rds_ins[] = $rday;
                                            }

                                            // adding new referal
                                            $this->add_referal_for_insert($r_ins, $r, $u->user_id, $date, $rday);

                                            // update day counters
                                            $day_regs++;
                                            if ($r['level'] > 1)
                                                $day_active++;
                                        }

                                        if ($rday)
                                            $day_earn += $rday['day_profit'];
                                        if ($rday)
                                            $day_ref_paid += $rday['ref_paid'];

                                        if ($rday)
                                            $day_earn_mlgame += $rday['day_profit_mlgame'];
                                    }
                                }

                                $uds_ins[] = array("user_id" => $u->user_id, "date" => $date, "registers" => $day_regs,
                                    "active_regs" => $day_active, "earnings" => $day_earn, "ref_paid" => $day_ref_paid,
                                    "earnings_mlgame" => $day_earn_mlgame);

                                $this->CI->load->model("global_model", "gm");
                                $payed_out = $this->CI->gm->get_payouts_sum($u->user_id, "default");

                                $u_upd[] = array("user_id" => $u->user_id, "registers" => $u->registers + $day_regs,
                                    "active_regs" => $u->active_regs + $day_active, "earnings" => $u->earnings + $day_earn,
                                    "earnings_mlgame" => $u->earnings_mlgame + $day_earn_mlgame, "sum_to_pay" => $u->earnings + $day_earn - $payed_out);

                                // "ref_paid" => $u->ref_paid + $day_ref_paid,
                            } else
                                log_message("error", "Calc Day Stats. Empty Users in XML for partner {$u->ref_code}");

                            // CHARGEBACKS   
                            if (!empty($xml->chargebacks)) {
                                foreach ($xml->chargebacks->chargeback as $c) {
                                    $c_date = substr((string) $c->date, 0, 10);
                                    $q = $this->CI->db->get_where("p1_chargebacks", array("user_id" => $u->user_id, "ref_name" => (string) $c->name, "date" => $c_date));
                                    if ($q->num_rows() <= 0) {
                                        $this->CI->db->insert("p1_chargebacks", array("user_id" => $u->user_id, "ref_name" => (string) $c->name, "date" => $c_date, "sum" => (float) $c->amount));
                                    }
                                }
                            }
                        }
                    } else
                        log_message("error", "Calc Day Stats. Can't parse XML with SIMPLEXML_LOAD_FILE $filename");
					
                }else
                    $xml = false;
            } else
                log_message("error", "Calc Day Stats. File $filename doesn't exist. Panic !!!");


//var_dump($uds_ins);
//var_dump($r_upd);
//var_dump($r_ins);			
            // write to db
            $this->CI->db->trans_start(true);
            log_message("error", "Inserting Referals");
            if (!$this->CI->pm->batch_insert_ref($r_ins))
                log_message("error", "Calc Day stat Batch Insert ref returned FALSE | ");
            //$this->CI->db->trans_rollback();
			
            if ($this->_set_rds_ins_ids($rds_ins, $r_ins, $rlvl_ins)) { //insert new referals and set ids in rds_ins array
                //print_r($rds_ins);
                //if (!$this->CI->pm->batch_insert_ref_day_stat($rds_ins))
                //  log_message("error", "Calc Day Stats. Batch insert ref day stats RETURNED FALSE".$this->CI->db->_error_message ());
                //пришлось сделать цикл(( ибо батч инсерт в ci умирает
                log_message("error", "Inserting Referal Day stats");
                //print_r($rds_ins);
                foreach ($rds_ins as &$r) {
                    if (!$this->CI->db->insert("p1_ref_day_stats", $r))
                        log_message("error", "Calc Day Stats. Batch insert ref day stats RETURNED FALSE" . $this->CI->db->_error_message());
                }
                foreach ($rlvl_ins as &$rlvl) {
                    if (!$this->CI->db->insert("p1_ref_levels", $rlvl))
                        log_message("error", "Calc Day Stats. Batch insert ref levels RETURNED FALSE" . $this->CI->db->_error_message());
                }
            } else
                log_message("error", "Calc Day Stats. Set rds ins ids returned FALSE");
			
			/*
			if ($this->_set_rds_ins_ids($rds_ins, $r_upd, $rlvl_ins)) { //insert new referals and set ids in rds_ins array
                //print_r($rds_ins);
                //if (!$this->CI->pm->batch_insert_ref_day_stat($rds_ins))
                //  log_message("error", "Calc Day Stats. Batch insert ref day stats RETURNED FALSE".$this->CI->db->_error_message ());
                //пришлось сделать цикл(( ибо батч инсерт в ci умирает
                log_message("error", "Inserting Referal Day stats");
                //print_r($rds_ins);
				
                foreach ($rds_ins as &$r) {
                    if (!$this->CI->db->insert("p1_ref_day_stats", $r))
                        log_message("error", "Calc Day Stats. Batch insert ref day stats RETURNED FALSE" . $this->CI->db->_error_message());
                }
				
                foreach ($rlvl_ins as &$rlvl) {
                    if (!$this->CI->db->insert("p1_ref_levels", $rlvl))
                        log_message("error", "Calc Day Stats. Batch update ref levels RETURNED FALSE" . $this->CI->db->_error_message());
                }
            } else
                log_message("error", "Calc Day Stats. Set rds ins ids returned FALSE");
			*/
			
			
            log_message("error", "Updating referals Referals");
            if (!$this->CI->pm->batch_update_ref($r_upd))
                log_message("error", "Calc Day Stats. Batch update ref RETURNED FALSE");
            log_message("error", "Inserting User Day Stats");
            if (!$this->CI->pm->batch_insert_user_day_stat($uds_ins))
                log_message("error", "Calc Day Stats. Batch insert user day stats RETURNED FALSE");
            log_message("error", "Updating users");
            if (!$this->CI->pm->batch_update_users($u_upd))
                log_message("error", "Calc Day Stats. Batch update users RETURNED FALSE");

            //$this->CI->db->trans_rollback();
            log_message("info", "Trans Status. " . $this->CI->db->trans_status());
            $this->CI->db->trans_complete();
			
        } else
            log_message("error", "Calc Day Stats. Folder $path doesn't exist. Panic !!!");

        log_message("error", "Calc Day Stat Finished");


        // free memory
        unset($r_ins);
        unset($rds_ins);
        unset($r_upd);
        unset($uds_ins);
        unset($u_upd);
        unset($rlvl_ins);
    }

    function calc_referal_day_stat($r, $r_db = null, $perc, $system_percent) {
        //$r - referal from xml source
        //$r_db - referal from db
        $rday = array(); //array of referal day changes

        $rday['day_profit'] = 0.00;
        $rday['day_profit_mlgame'] = 0.00;
        $rday['level'] = $r['level'];


        if ($r_db) {
            $rday['spent'] = round($r_db['spent'], 2);
            $rday['earned'] = round($r_db['earned'], 2);
            $rday['inputted'] = round($r_db['inputted'], 2);
            $rday['ref_paid'] = round($r_db['ref_paid'], 2);
            $rday['ref_to_pay'] = round($r_db['ref_to_pay'], 2);

            $rday['spent'] = $r['spent'] - $r_db['spent'];
            $rday['earned'] = $r['earned'] - $r_db['earned'];
            $rday['inputted'] = $r['inputted'] - $r_db['inputted'];
            $rday['ref_paid'] = $r['ref_paid'] - $r_db['ref_paid'];
        } else {
            $rday['spent'] = $r['spent'];
            $rday['earned'] = $r['earned'];
            $rday['inputted'] = $r['inputted'];
            $rday['ref_paid'] = $r['ref_paid'];
            $rday['ref_to_pay'] = $r['ref_to_pay'];
        }
        // if ($r_db)
        //    $r['ref_to_pay'] -= $r_db['ref_to_pay'];

        if ($rday['spent'] == 0 && $rday['ref_paid'] == 0/* && $rday['inputted'] == 0*/) {
            //log_message("debug", "Calc Referal Day Stats. No day operations for referal ");
            return false;
        } //skip if day operations are absent
        $rday['credit'] = $r_db ? $r_db['credit'] : 0.00;
        $rday['credit'] = round($rday['credit'], 2);

        $rday['day_sum'] = $rday['spent'] - $rday['earned'] - $rday['credit'];

        if ($rday['day_sum'] < 0) {
            //give credit
            $rday['credit'] = abs($rday['day_sum']);
            $rday['day_sum'] = 0;
        } else {
            $rday['credit'] = 0;
        }

        $rday['day_sum'] += $rday['ref_paid'];
	//	$rday['day_profit'] = round($rday['day_sum'] * $perc / 100, 2);
		$rday['day_profit'] = round($rday['ref_paid'] * $perc / 100, 2);

        if ($rday['ref_paid'] > 0) {
			
		//	$rday['day_profit_mlgame'] = round($rday['ref_paid'] / $system_percent/* config_item("mlgame_system_percent") */ *
        //          $perc, 2);
			
			$rday['day_profit_mlgame'] = round($rday['ref_paid'] * config_item("mlgame_system_percent") / 100, 2);
        }

        return $rday;
    }

    /** utilities * */
    function _gen_archive_folder_path($date) {
        return config_item("projects_xml_archive_path") . DS . $this->id . DS . $date;
    }

    function _gen_archive_file_path($date) {
        return config_item("projects_xml_archive_path") . DS . $this->id . DS . $date .
                DS . /* $ref_code . '-' . */ $date . '.xml';
    }

    function _xml_ref_to_db_ref($r) {
        $nr = array();
        $nr['name'] = (string) $r->name;
		$nr['ref_code'] = (string) $r->ref;
        $nr['spent'] = round((float) $r->spent, 2); //(float) $r->spent;
        $nr['earned'] = !empty($r->earned) ? round((float) $r->earned, 2) : 0; //(float) $r->earned;
        $nr['inputted'] = !empty($r->inputted) ? round((float) $r->inputted, 2) : 0; // (float)$r->inputted;
        $nr['ref_paid'] = round((float) $r->{'ref-paid'}, 2); //(float) $r->{'ref-paid'};
        $nr['ref_to_pay'] = !empty($r->{'ref-topay'}) ? round((float) $r->{'ref-topay'}, 2) : 0; //(float) $r->{'ref-to-pay'};
        $nr['level'] = (integer) $r->level;
        $nr['id'] = (integer) $r->id;
        return $nr;
    }

    function _get_ref_ins_ids($r_ins) {
        //print_r($r_ins);
        $names_arr = array();
        foreach ($r_ins as $r) {
            $names_arr[] = $r['name'];
        }
        return $this->CI->pm->get_refs_by_names($names_arr);
    }

    function _set_rds_ins_ids(&$rds_ins, $r_ins, &$rlvl_ins) {
	//	print_r($r_ins);
        $id_name_arr = $this->_get_ref_ins_ids($r_ins);
        if ($id_name_arr) {
         //   print_r($rds_ins);
            foreach ($rds_ins as &$rds) {
                if (empty($rds['referal_id'])) {
                    if (!empty($id_name_arr[$rds['name']])) {
                        $rds['referal_id'] = $id_name_arr[$rds['name']];
                        unset($rds['name']);
                    } else
                        log_message("error", "Calc Day Stats. Set RDS INS IDS. Can't find name in id_name_arr. {$rds['name']}");
                }
            }

		//	var_dump($rlvl_ins);
			
            foreach ($rlvl_ins as &$rlvl) {
                if (isset($rlvl['ref_name']) && isset($id_name_arr[$rlvl['ref_name']])) {
                    $rlvl['ref_id'] = $id_name_arr[$rlvl['ref_name']];
                    unset($rlvl['ref_name']);
                } else
                    log_message("error", "Calc Day Stats. Set RDS INS IDS. Can't find name in id_name_arr.");
            }

            return true;
        } else {
            echo "id name arr false";
            return false;
        }
    }

    function add_referal_for_insert(&$r_ins, $r, $user_id, $date, $rday = null) {
        if (empty($rday)) {
            $credit = 0;
            $profit = 0;
            $profit_mlgame = 0;
        } else {
            $credit = $rday['credit'];
            $profit = $rday['day_profit'];
            $profit_mlgame = $rday['day_profit_mlgame'];
        }
        $r_ins[] = array("name" => $r['name'], 
						"user_id" => $user_id, 
						"ref_code" => $r['ref_code'], 
						"reg_date" => $date, 
						"query_date" => $date, 
						"profit" => $profit, 
						"profit_mlgame" => $profit_mlgame, 
						"spent" => $r['spent'], 
						"earned" => !empty($r['earned']) ? $r['earned'] : 0, 
						"inputted" => !empty($r['inputted']) ? $r['inputted'] : 0, 
						"ref_paid" => !empty($r['ref_paid']) ? $r['ref_paid'] : 0, 
						"ref_to_pay" => !empty($r['ref_to_pay']) ? $r['ref_to_pay'] : 0, 
						"level" => empty($r['level']) ? 1 : $r['level'], 
						"credit" => $credit);
    }

    function add_referal_for_update(&$r_upd, $r, $r_db, $date, $rday = null) {
        if (empty($rday)) {
            $credit = $r_db['credit'];
            $profit = $r_db['profit'];
            $profit_mlgame = $r_db['profit_mlgame'];
        } else {
            $credit = $rday['credit'];
            $profit = $r_db['profit'] + $rday['day_profit'];
            $profit_mlgame = $r_db['profit_mlgame'] + $rday['day_profit_mlgame'];
        }
        //p.s. first param - is a key for update 
        $r_upd[] = array(
			"name" => $r['name'], 
			"update_date" => $date, 
			"profit" => $profit, 
			"profit_mlgame" => $profit_mlgame, 
			"spent" => $r['spent'], 
			"earned" => !empty($r['earned']) ? $r['earned'] : 0, 
			"inputted" => !empty($r['inputted']) ? $r['inputted'] : 0, 
			"ref_paid" => !empty($r['ref_paid']) ? $r['ref_paid'] : 0, 
			"ref_to_pay" => !empty($r['ref_to_pay']) ? $r['ref_to_pay'] : 0, 
			"level" => empty($r['level']) ? 1 : $r['level'], 
			"credit" => $credit);
    }

    /** RECOVERY FUNCTIONS * */
    function recalc_referals_profit() {
        $q = $this->CI->db->select("referal_id")->select_sum("day_profit", "profit")->select_sum("day_profit_mlgame", "profit_mlgame")->from("p1_ref_day_stats")->group_by("referal_id")->get();
        $arr = $q->result_array();
        foreach ($arr as $r) {
            $this->CI->db->update("p1_referals", array("profit" => $r['profit'], "profit_mlgame" => $r['profit_mlgame']), array("id" => $r['referal_id']));
        }
    }

    function recalc_user_day_stats() {
        $q = $this->CI->db->get("p1_day_stats");
        if ($q->num_rows()) {
            foreach ($q->result() as $r) {
                $u_id = $r->user_id;
                $date = $r->date;

                $q2 = $this->CI->db->select("COALESCE(count(pr.id),0) registers,COALESCE(sum(case when prl.level>1 then 1 else 0 end),0) active_regs", FALSE)
                        ->from("p1_referals pr")
                        ->join("p1_ref_levels prl", "prl.ref_id=pr.id and prl.date=pr.reg_date", "left")
                        ->where(array("user_id" => $u_id, "reg_date" => $date))
                        ->get();
                $row = $q2->row();
                $registers = $row->registers;
                $active_regs = $row->active_regs;

                $q3 = $this->CI->db->select("COALESCE(sum(day_profit),0) earnings,COALESCE(sum(day_profit_mlgame),0) earnings_mlgame", FALSE)
                        ->from("p1_ref_day_stats")
                        ->where("query_date='{$date}' and referal_id in (select id from p1_referals where user_id={$u_id})", null, FALSE)
                        ->get();
                $row = $q3->row();
                $earnings = $row->earnings;
                $earnings_mlgame = $row->earnings_mlgame;

                $this->CI->db->update("p1_day_stats", array("registers" => $registers, "active_regs" => $active_regs, "earnings" => $earnings, "earnings_mlgame" => $earnings_mlgame), array("user_id" => $u_id, "date" => $date));
            }
        }
    }

    function recalc_users() {
        /* recalc:
         * registers
         * active_regs
         * active_regs_reg_day
         * earnings
         * earnings_mlgame
         * sum_ref_paid
         * sum_to_pay
         */


        $q = $this->CI->db->select("count(*) as active_regs,user_id")->from("p1_referals")->where("level > 1")->group_by("user_id")->get();
        $arr1 = $q->result_array(); //кол-во активных по каждому рефералу
        $q = $this->CI->db->select("sum(active_regs) active_regs_reg_day,user_id")->from("p1_day_stats")->group_by("user_id")->get();
        $arr2 = $q->result_array(); //кол-во активных по каждому рефералу (на день регистрации)
        $q = $this->CI->db->select("count(*) as registers,pr.user_id,sum(profit) earnings,COALESCE((sum(profit) - COALESCE(t1.paysum, 0)),0) sum_to_pay,sum(profit_mlgame) earnings_mlgame,sum(ref_paid) sum_ref_paid", false)
                ->join("(select sum(sum) paysum,user_id from global_payouts group by user_id) t1", "t1.user_id=pr.user_id", "left")
                ->from("p1_referals pr")
                ->group_by("user_id")
                ->get();
		
		/*
		 * 
			SELECT count(*) as registers,
		 			pr.user_id,
					sum(profit) earnings,
					COALESCE((sum(profit)-t1.paysum),0) sum_to_pay,
					sum(profit_mlgame) earnings_mlgame,
					sum(ref_paid) sum_ref_paid
			FROM p1_referals pr		
            LEFT JOIN (select sum(sum) paysum,user_id from global_payouts group by user_id) t1 ON t1.user_id = pr.user_id
            GROUP BY user_id
		 */
        $arr3 = $q->result_array();
        foreach ($arr1 as $r) {
            $this->CI->db->update("p1_users", array("active_regs" => $r['active_regs']), array("user_id" => $r['user_id']));
            $this->CI->db->update("global_users", array("active_regs" => $r['active_regs']), array("id" => $r['user_id']));
        }
        foreach ($arr2 as $r) {
            $this->CI->db->update("p1_users", array("active_regs_reg_day" => $r['active_regs_reg_day']), array("user_id" => $r['user_id']));
            $this->CI->db->update("global_users", array("active_regs_reg_day" => $r['active_regs_reg_day']), array("id" => $r['user_id']));
        }
        foreach ($arr3 as $r) {
            $this->CI->db->update("p1_users", array("sum_to_pay" => $r['sum_to_pay'], "sum_ref_paid" => $r['sum_ref_paid'], "earnings_mlgame" => $r['earnings_mlgame'], "earnings" => $r['earnings'], "registers" => $r['registers']), array("user_id" => $r['user_id']));
            $this->CI->db->update("global_users", array("sum_to_pay" => $r['sum_to_pay'], "earnings" => $r['earnings'], "registers" => $r['registers']), array("id" => $r['user_id']));
        }
    }

    function recalc_loyalty() {
        /*
          $q=$this->CI->db->get("global_users");
          if($q->num_rows()){
          foreach ($q->result() as $u){

          }
          }
         * *
         */
        $q = $this->CI->db->from("global_users gu")
                ->select("sum(earnings) earnings,parent_id,parent_loyalty_percent")
                ->where("parent_id != 0")
                ->group_by("parent_id")
                ->get();
        if ($q->num_rows()) {
            foreach ($q->result() as $u) {
                $q = $this->CI->db->select("sum(sum) sum")->get_where("global_payouts gp", "gp.user_id = {$u->parent_id} and type='loyalty'");
                $sum_payed = $q->row()->sum;
                $loy_earn = round($u->earnings * $u->parent_loyalty_percent / 100, 2);
                $loy_to_pay = round($loy_earn - $sum_payed, 2);
                $this->CI->db->update("global_users", array("loyalty_earnings" => $loy_earn, "loyalty_sum_to_pay" => $loy_to_pay), array("id" => $u->parent_id));
            }
        }
    }

    /*
     * Пересчитывает всю информацию по площадкам(p1_sites) по данным из таблицы p1_referals
     */

    function calc_sites_stats() {
        $q = $this->CI->db->select("site_id as id")
                ->select("count(id) as registers")
                ->select_sum("profit", "earnings")
                ->group_by("site_id")
                ->get_where("p1_referals");
        if ($q->num_rows()) {
            $arr = $q->result_array();
            $this->CI->db->update_batch("global_sites", $arr, "id");
        }

        $q = $this->CI->db->select("user_id")
                ->select("count(id) as registers")
                ->select_sum("profit", "earnings")
                ->where("site_id is NULL")
                ->group_by("user_id")
                ->get("p1_referals");
        foreach ($q->result() as $r) {
            //$this->CI->db->update("p1_sites",array("registers"=>$r->earnings,"earnings"=>$r->registers),array("user_id"=>$r->user_id,"url"=>"null"));
            $this->CI->db->update("global_sites", array("registers" => $r->registers, "earnings" => $r->earnings), array("user_id" => $r->user_id, "url" => "null"));
        }

        $q = $this->CI->db->select("id, registers, earnings, clicks")->from("global_sites")->get();
        foreach ($q->result() as $r) {
            $this->CI->db->update("p1_sites", array("registers" => $r->registers, "earnings" => $r->earnings, "clicks" => $r->clicks), array("site_id" => $r->id));
        }
    }

    /* Добавляет недостающие сайты из
     * глобал к сайтам
     * в проекте
     */

    function synchronize_sites() {
        $q = $this->CI->db->get("global_sites");
        foreach ($q->result() as $r) {
            $this->CI->db->query("insert ignore p1_sites set site_id='{$r->id}',status='{$r->state}',registers='{$r->registers}',earnings='{$r->earnings}',clicks='{$r->clicks}'");
        }
    }

	function calc_lvl_stat()
	{
		
		$date = '2012-09-24';
		
	//	log_message("error", "Calc Day Stat Started. Calc date is: $date . Real date is " . date("Y-m-d"));
        $path = $this->_gen_archive_folder_path($date);
        if (file_exists($path)) {
            $users = $this->CI->pm->get_project_users();
            $rds_ins = array(); //referal day stat batch insert array
            $r_ins = array(); //referal batch insert array
            $r_upd = array(); //referal batch update array
            $uds_ins = array();
            $u_upd = array();
            $rlvl_ins = array();
			$rlvl_upd = array();


            $filename = $this->_gen_archive_file_path($date);
            if (file_exists($filename) && filesize($filename)) {
					
				$xml = simplexml_load_file($filename);
			//	var_dump($xml);

				if ($xml) {
					foreach ($users as $u) {

			//			var_dump($u);
						if (!empty($xml->users)) {
							
							$day_regs = 0;
							$day_active = 0;
							$day_earn = 0;
							$day_ref_paid = 0;
							$day_earn_mlgame = 0;

							$pr = $this->CI->pm->get_project_referals_as_named_array($u->user_id);
							foreach ($xml->users->user as $r) {
							//	$ref_arr = explode("-", $r->ref);

							//	echo $ref_arr[1].' '.$u->user_id.'<br>';

							//	if ($ref_arr[1] == $u->user_id) {
								if ($r->ref == $u->ref_code) {	
									$r = $this->_xml_ref_to_db_ref($r);

									$r_db = empty($pr[$r['name']]) ? false : $pr[$r['name']];
									
									if ($r_db){
										$rlvl_upd[] = array('ref_id' => $r_db['id'], 'date' => $date,'level' => $r['level']);
										if ($r['level'] > 1)
											$day_active++;
										
										$uds_ins[] = array("user_id" => $u->user_id, "date" => $date, "active_regs" => $day_active);
										
									}
									else{
										$rlvl_ins[] = array('ref_name' => $r['name'], 'level' => $r['level']);
									}
								}
							}	

						}
					}
					/*
					if (!empty($uds_ins)){
						foreach ($uds_ins as $ui){
							$this->CI->db->set("active_regs", $ui['active_regs']);
							$this->CI->db->where(array("user_id" => $ui['user_id'], "date" => $ui['date']));
							$this->CI->db->update("p1_day_stats");
							
							echo $this->CI->db->last_query();
						}
					}
					*/
				}
			}
		}
		
		var_dump($rlvl_upd);
		
		//var_dump($rlvl_ins);
	}
	
	
/* p1_referals
  ["user_id"]=>  string(3) "373"
  ["ref_code"]=>  string(14) "arspartner-373"
  ["status"]=>  string(6) "active"
  ["percent"]=>  string(2) "40"
  ["system_percent"]=>  string(2) "60"
  ["clicks"]=>  string(1) "0"
  ["registers"]=>  string(1) "0"
  ["active_regs"]=>  string(1) "0"
  ["active_regs_reg_day"]=>  string(1) "0"
  ["earnings"]=>  string(4) "0.00"
  ["earnings_mlgame"]=>  string(4) "0.00"
  ["sum_ref_paid"]=>  string(4) "0.00"
  ["sum_to_pay"]=>  string(4) "0.00"
  ["connect_date"]=>  string(19) "2012-08-13 16:07:43"
  ["modified"]=>  string(19) "2012-08-15 14:04:15"
*/	
	
/* xml
	["id"]=>  string(6) "214480"
	["name"]=>        string(9) "Powerlord"
	["reg_date"]=>        string(10) "2012-09-08"
	["ref"]=>        string(14) "arspartner-385"
	["spent"]=>        string(5) "28.00"
	["ref-paid"]=>        string(5) "16.80"
	["level"]=>        string(2) "19"
 */	
}

?>
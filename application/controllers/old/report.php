<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends CI_Controller {

    public function index() {
        
    }

    /*
     * Выдаем статистику только по рефералам 
     * зарегистрированным на эту дату, или
     * у которых на эту дату дневная сумма больше 0,
     * //или произошло изменение лвла
     */

    public function view($user_id, $skey = null, $date = null, $date_end = null) {
        //if(empty($skey)) show_error("Provide skey");
        
        $user = $this->user->info($user_id);
        $q = $this->db->get_where("global_users", array("id" => $user_id, "skey" => $skey));
        if (!$q->num_rows())
            show_error("Wrong auth");
        date_default_timezone_set("Europe/Kiev");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $xml = $this->input->post("xml", FALSE);
            $xml = simplexml_load_string(htmlspecialchars_decode($xml));
            $date = date("Y-m-d", (string) $xml->date_from);
            if (!empty($xml->date_to))
                $date_end = date("Y-m-d", (string) $xml->date_to);
            else
                $date_end = null;
        }

        if (!$date) {
            $q = $this->db->select_max("date")->from("p1_day_stats")->get();
            $row = $q->row();
            $date = $row->date;
        }



        $this->db->from("p1_referals pr")->where("pr.user_id", $user_id);
        if (!$date_end)
            $this->db->where("(pr.reg_date='{$date}' or day_sum > 0)");
        else {
            $this->db->where("((pr.reg_date>={$date} and pr.reg_date<{$date_end}) or day_sum>0)");
        }

        if (!$date_end)
            $this->db->join("p1_ref_day_stats prds", "prds.referal_id = pr.id and prds.query_date='{$date}'", "left");
        else {
            $this->db->join("(select sum(day_sum) as day_sum, referal_id,max(query_date) as query_date from p1_ref_day_stats where query_date>='{$date}' and query_date<'{$date_end}' group by referal_id) prds", "prds.referal_id=pr.id");
        }
        //$this->db->join("p1_ref_levels prl", "prl.ref_id=pr.id and prl.date='{$date}'", "left");
        $this->db->select("pr.name,pr.site_id,pr.prx,coalesce(pr.level,1) as level,day_sum,coalesce(prds.query_date,pr.query_date) ddate", false);

        $q = $this->db->get();
        //echo $this->db->last_query();
        $xml = "";
        if ($q->num_rows()) {
            $xml.="<?xml version='1.0' encoding='utf-8'?>
                    <items>";
            foreach ($q->result() as $r) {
                $xml.="
                   <item>
                    <id>{$r->name}</id>
                    <site_id>{$r->site_id}</site_id>
                    <prx>{$r->prx}</prx>
                    <level>{$r->level}</level>
                    <price>" . ($r->day_sum * 100) . "</price>
                    <currency>USD100</currency>
                    <date>" .strtotime($r->ddate) . "</date>
                   </item>
                ";
            }

            $xml.="</items>";
        } else {
            $xml = "<items></items>";
        }
        $this->output->set_content_type('text/xml');
        $this->output->set_output($xml);
    }

}

?>

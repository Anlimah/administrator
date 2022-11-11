<?php

namespace Src\Controller;

use Src\Controller\ExposeDataController;

class AdminController extends ExposeDataController
{

    public function fetchApplicants($country, $type, $program)
    {
        $WHERE = "";

        if ($country != "All") {
            $WHERE .= " AND p.`nationality` = '$country'";
        }
        if ($type != "All") {
            $WHERE .= " AND f.`id` = $type";
        }
        if ($program != "All") {
            $WHERE .= " AND r.`first_prog` LIKE '%$program%'";
        }

        $query = "SELECT a.`id`, p.`first_name`, p.`last_name`, p.`nationality`, f.`name` AS `app_type`, r.`first_prog`, fs.`declaration` 
                FROM `personal_information` AS p, `applicants_login` AS a, `form_type` AS f, 
                `purchase_detail` AS d, `program_info` AS r, `form_sections_chek` AS fs  
                WHERE p.`app_login` = a.`id` AND d.`form_type` = f.`id` AND d.`id` = a.`purchase_id` AND 
                r.`app_login` = a.`id` AND fs.`app_login` = a.`id`$WHERE";
        return $this->getData($query);
    }

    public function fetchAllApplicants()
    {
        $query = "SELECT a.`id`, p.`first_name`, p.`middle_name`, p.`last_name`, p.`nationality`, f.`name` AS `app_type`, r.`first_prog`, fs.`declaration` 
                FROM `personal_information` AS p, `applicants_login` AS a, `form_type` AS f, 
                `purchase_detail` AS d, `program_info` AS r, `form_sections_chek` AS fs  
                WHERE p.`app_login` = a.`id` AND d.`form_type` = f.`id` AND d.`id` = a.`purchase_id` AND 
                r.`app_login` = a.`id` AND fs.`app_login` = a.`id`";
        return $this->getData($query);
    }

    public function getAllApplicantsID()
    {
        $query = "SELECT l.id FROM academic_background AS a, applicants_login AS l
                WHERE a.app_login = l.id AND a.awaiting_result = 0";
        return $this->getData($query);
    }

    public function getApplicantsSubjects(int $loginID)
    {
        $query = "SELECT l.id, p.first_name, p.middle_name, p.last_name, r.type, r.subject, r.grade 
                FROM personal_information AS p, academic_background AS a, high_school_results AS r, applicants_login AS l
                WHERE p.app_login = l.id AND a.app_login = l.id AND r.acad_back_id = a.id AND a.awaiting_result = 0 AND l.id = :i";
        return $this->getData($query, array(":i" => $loginID));
    }

    public function fetchPrograms(int $type)
    {
        $param = array();
        if ($type != 0) {
            $query = "SELECT * FROM programs WHERE `type` = :t";
            $param = array(':t' => $type);
        } else {
            $query = "SELECT * FROM programs";
        }
        return $this->getData($query, $param);
    }
}

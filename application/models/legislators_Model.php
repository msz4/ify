<?php
class legislators_Model extends CI_Model
{
    public function fetch_Legislator($id)
    {
        $this->db->select("*");
        $this->db->from("legislators");
        $this->db->join('cons', 'cons.cons_id = legislators.cons');
        $this->db->where("id=".$id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getCons(){
        $this->db->select("*");
        $this->db->from("cons");
        $query = $this->db->get();
        return $query->result();
    }
    
    //called when the user wishes to view all legislators
    public function fetch_AllLegislators($limit, $start, $filter)
    {
        $this->db->select("*");
        $this->db->from("legislators");
        $this->db->join('cons', 'cons.cons_id = legislators.cons');
        if($filter!="all"){
            $this->db->where("legislators.chamber ='".$filter."'");
        }
        $this->db->where("legislators.term ='9th National Assembly (2019 - 2023)'");   //this should be removed in the future, and a filter should be added to let users choose whether they wish to iew current or past legislators
        $this->db->order_by("legislators.id", "ASC");
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query;
    }
}
?>
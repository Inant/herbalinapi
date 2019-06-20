<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MainModel extends CI_Model {

    public function insert($tb, $values)
    {
        $this->db->insert($tb, $values);
        
    }

    public function insert_batch($tb, $values)
    {
        $this->db->insert_batch($tb, $values);
    }

    public function update($tb, $values, $where)
    {
        $this->db->update($tb, $values, $where);
    }

    public function delete($tb, $where)
    {
        $this->db->delete($tb, $where);
    }

    public function getData($select,$tb,$join,$filter,$order)
    {
        $sql = $this->db->select($select);

        if($join!="") {
            for($i=0;$i<count($join);$i++){
                if($i%2!=0){
                    $sql = $this->db->join($join[$i-1],$join[$i],"right");
                }
            }
        }

        if($order!=""){
            if(is_array($order)){
                $sql = $this->db->order_by($order[0],$order[1]);
            }
            else{
                $sql = $this->db->order_by($order);
            }
        }
        if($filter!=""){
            $sql = $this->db->where($filter);
        }

        if(is_array($tb)){
            $sql = $this->db->get($tb[0],$tb[1],$tb[2]);
        }
        else{
            $sql = $this->db->get($tb);
        }


        return $sql->result_array();
    }

}

/* End of file MainModel.php */

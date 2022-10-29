<?php

namespace App\Config;

class Db{

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getDb();
    }

    public function create(array $data){
        $keys = array_keys($data);
        $values = array_values($data);
        $columnNames = implode(",", $keys);
        $row = "'".implode("','", $values)."'";
        try{
            $this->db->query("INSERT INTO USERS($columnNames) VALUES($row)");
            return true;
        }catch(\Exception $e){
            return false;
        }    
    }

    public function isExist($column, $data){
        $result = $this->db->query("SELECT * FROM users WHERE $column = '".$data."'");
        if($result-> num_rows){
            return true;
        }else{
            return false;
        }
    }


}
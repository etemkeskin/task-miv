<?php

namespace App\Config;

use mysqli;

class Database{

    private $db_host = 'localhost';
    private $db_username = 'root';
    private $db_password = '123456';
    private $db_name = 'task-miv';

    private $db;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
            $db = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name);
    
            if($db->connect_error){
                $response = [
                    'status' => 500,
                    'success' => false,
                    'message' => '',
                    'error' => 'Server Error',
                    'data' => [],
                    'errors' => [] 
                ];
                echo json_encode($response);
                die("Unable to connect database: " . $db->connect_error);
            }else{
                $this->db = $db;
            }
        } catch (\Throwable $th) {
            $response = [
                'status' => 500,
                'success' => false,
                'message' => '',
                'error' => 'Server Error',
                'data' => [],
                'errors' => [] 
            ];
            echo json_encode($response);
            die();
        }
        
    
    }
    
    public function getDb(){
        return $this->db;
    }

}
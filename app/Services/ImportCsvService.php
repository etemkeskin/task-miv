<?php

namespace App\Services;

use App\Config\Db;
use App\Helpers\ValidateEmail;
use App\Helpers\ValidatePhone;

class ImportCsvService{

    private $csvFileData = [];
    private $db;
    private $campaignDate;
    private $campaignName;
    private $willImport = true;

    public $importedLineNumber = 0;
    public $errors = [];

    public function __construct(array $csvFileData, $campaignName, $campaignDate)
    {
        $this->db = new Db();
        $this->csvFileData = $csvFileData;
        $this->campaignName = $campaignName;
        $this->campaignDate = $campaignDate;
    }

    public function importData(){
        
        for ($i=1; $i<count($this->csvFileData); $i++) { //skipping first row
            $name = $this->csvFileData[$i][0];
            $surname = $this->csvFileData[$i][1];
            $email = $this->csvFileData[$i][2];//unique
            $employeeId = $this->csvFileData[$i][3];//unique
            $phone = $this->csvFileData[$i][4];//unique
            $point = $this->csvFileData[$i][5];

            $emailResult = ValidateEmail::check($email);
            if(!$emailResult){
                $this->willImport = false;
                $this->errors [] = $email.' is not valid at '.$i.' line';
            }
            
            $phone = ValidatePhone::check($phone);

            if(!$phone){
                $this->willImport = false;
                $this->errors [] = $phone.' is not valid at '.$i.' line';
            }
 
            $isUniqueEmail = $this->db->isExist('email', $email);
            if($isUniqueEmail){
                $this->willImport = false;
                $this->errors [] = 'Email: '. $email.' must be unique at '.$i.' line';
            } 

            $isUniquePhone = $this->db->isExist('phone', $phone);
            if($isUniquePhone){
                $this->willImport = false;
                $this->errors [] = 'Phone: '.$phone.' must be unique at '.$i.' line';
            }

            $isUniqueEmployeeId = $this->db->isExist('employee_id', $employeeId);

            if($isUniqueEmployeeId){
                $this->willImport = false;
                $this->errors [] = 'Employee '. $employeeId.' must be unique at '.$i.' line';
            }

            $data = [
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'employee_id' => $employeeId,
                'phone' => $phone,
                'point' => $point,
                'campaign_name' => $this->campaignName,
                'campaign_date' => date("Y-m-01", strtotime($this->campaignDate)) 
            ];

            $this->save($data);
        }

    }

    public function save(array $data){
        if($this->willImport){
            $result = $this->db->create($data);
            if ($result) {
                $this->importedLineNumber += 1;
            }
        }
    }

}
<?php

require_once __DIR__.'/vendor/autoload.php';

use App\Services\ImportCsvService;

    $response = [
        'status' => 500,
        'success' => true,
        'message' => '',
        'error' => '',
        'data' => [],
        'errors' => [] 
    ];
    

    if (isset($_POST)) {

        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 
                                    'application/octet-stream', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 
                                     'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if(isset($_FILES['campaign_file']['name']) && in_array($_FILES['campaign_file']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['campaign_file']['name']);
            $extension = end($arr_file);
          
            if('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            }else{
                $response['status'] = 400;
                $response['success'] = false;
                $response['error'] = "Wrong data file format";
                echo json_encode($response);
            }
            $spreadsheet = $reader->load($_FILES['campaign_file']['tmp_name']);
      
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            if (!empty($sheetData) && isset($_POST['campaign_name']) && isset($_POST['campaign_date']) ) {

                $campaignName = $_POST['campaign_name'];
                $campaignDate = $_POST['campaign_date'];
                $importCsvService = new ImportCsvService($sheetData ,$campaignName, $campaignDate);
                $importCsvService->importData();

                $importCsvService->importedLineNumber;
                $importCsvService->errors;

                if((int)$importCsvService->importedLineNumber == 0){
                    $response['status'] = 400;
                    $response['success'] = false;
                    $response['message'] = $importCsvService->importedLineNumber. " records inserted successfully";
                    $response['errors'] = $importCsvService->errors;
                    echo json_encode($response);
                }else{
                    $response['status'] = 201;
                    $response['success'] = true;
                    $response['message'] = $importCsvService->importedLineNumber. " records inserted";
                    $response['errors'] = $importCsvService->errors;
                    echo json_encode($response);                 
                }
                
            }
        } else {
            $response['status'] = 400;
            $response['success'] = false;
            $response['error'] = "Upload only CSV file";
            echo json_encode($response);
        }
    }
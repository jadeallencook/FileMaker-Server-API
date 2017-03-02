<?php

    // require fm libs
    require_once('../fmview.php');
    require_once('../FileMaker.php');
    require_once('../error.php');

    // cache database auth
    $database = '';
    $username = '';
    $password = '';

    // create new fm connection
    $fm = & new FileMaker();
    $fm->setProperty('database', $database);
    $fm->setProperty('username', $username);
    $fm->setProperty('password', $password);
    $layout = $fm->getLayout($layoutName);

    // formats for dates and times
    $displayDateFormat = '%m/%d/%Y';
    $displayTimeFormat = '%I:%M %P';
    $displayDateTimeFormat = '%m/%d/%Y %I:%M %P';
    $submitDateOrder = 'mdy';

    // save functionality
    if (isset($_POST['save'])) {
        
        
    } else { // get info functionality
        
        // get data by field id
        if (isset($_POST['find']) && isset($_POST['id'])) {
            
            // cache needed info
            $field_name = $_POST['find'];
            
            // start the search 
            $request = $fm->newFindCommand($layoutName);
            $request->setLogicalOperator(FILEMAKER_FIND_OR);
            $request->addFindCriterion($field_name, '==' . $_POST['id']);
            $result = $request->execute();
            
            // test for errors
            if (FileMaker :: isError($result)) {
                $found = false;
            } else if ($result === NULL) {
                $found = true;
            }
            
            // get list of all fields
            $fields = $layout->listFields();

            // create json {}
            $json = new stdClass();
            
            if ($found === false) {
                // iterate over all fields
                for ($i = 0; $i < count($fields); ++$i) {
                    // set nonset fields for null
                    $json->$fields[$i] = null;
                }   
            } else {
                // get the record
                $records = $result->getRecords();
                $recid = $records[0]->getRecordId();
                $record = $fm->getRecordById($layoutName, $recid);
                ExitOnError($record);
                // iterate over all fields
                for ($i = 0; $i < count($fields); ++$i) {
                    // set nonset fields for null
                    if (empty($record->getField($fields[$i], 0))) {
                        $json->$fields[$i] = null;
                    } else {
                        $json->$fields[$i] = $record->getField($fields[$i], 0);
                    }
                }
            }
            
        } else { // or just return first row 
            $record = $fm->getRecordById($layoutName, 1);
            
            // get list of all fields
            $fields = $layout->listFields();

            // create json {}
            $json = new stdClass();

            // iterate over all fields
            for ($i = 0; $i < count($fields); ++$i) {
                // set nonset fields for null
                if (empty($record->getField($fields[$i], 0))) {
                    $json->$fields[$i] = null;
                } else {
                    $json->$fields[$i] = $record->getField($fields[$i], 0);
                }
            }
            
        }

        // return json {}
        echo json_encode($json);
    }
?>
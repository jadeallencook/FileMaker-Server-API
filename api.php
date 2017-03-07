<?php
    // use output buffering to capture any errors or extraneous output
    ob_start();

    // need to know what layout
    $layoutName = $_POST['layout'];

    // require fm libs
    require_once('../FileMaker.php');

    // cache database auth
    $database = '';
    $username = '';
    $password = '';

    // create new fm connection
    $fm = new FileMaker();
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
        
        // cache all fields 
        $fields = $layout->listFields();
        
        // process msg
        echo "SAVE: Starting the save process";
        
        // for adding to specific id
        if (isset($_POST['find']) && isset($_POST['id'])) {
            // cache needed info
            $findFieldName = $_POST['find'];
            $findId = $_POST['id'];
            
            // find the record
            $request = $fm->newFindCommand($layoutName);
            $request->setLogicalOperator(FILEMAKER_FIND_AND);
            $request->addFindCriterion($findFieldName, '==' . $findId);
            $findRecord = $request->execute();
            $records = $findRecord->getRecords();
            $recid = $records[0]->getRecordId();
            
            // exit if no record found
            if (!isset($recid)) exit;
            
            // assign the record object
            $record = $fm->getRecordById($layoutName, $recid);
            
            // process msg
            echo "\nRECORD: Existing record found " . $recid;
            
        } else {
            // process msg
            echo "\nRECORD: Adding a new record";
            
            // create new record
            $record =& $fm->newAddCommand($layoutName);
        }
        
        // iterate over all the key/values
        foreach ($_POST['data'] as $keyValuePair) {

            // cache keys and values 
            $key = $keyValuePair[0];
            $value = $keyValuePair[1];
            
            // make sure the field exists
            if (in_array($key, $fields)) {
                // set key/value pair
                $record->setField($key, $value); 
            } else {
                // process msg
                echo "\nERROR: The " . $key . " was not found in the fields";
            }
        }
        
        // commit new record
        if (isset($_POST['find']) && isset($_POST['id'])) $result = $record->commit();
        else $result = $record->execute();
        
        // error reporting
        if (FileMaker :: isError($result)) {
            // process msg
            echo "\nFAIL: Could not add new record to database.";
        }
        
        // final exit
        exit;
        
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

    // final exit process
    exit;

?>
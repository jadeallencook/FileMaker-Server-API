<?php
    // use output buffering to capture any errors or extraneous output
    ob_start();

    // get layout from ajax
    $layoutName = $_POST['layout'];

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
    
    // cache fields 
    $layout = $fm->getLayout($layoutName);
    $fields = $layout->listFields();
    $record = $fm->getRecordById($layoutName, 1);

    // formats for dates and times
    $displayDateFormat = '%m/%d/%Y';
    $displayTimeFormat = '%I:%M %P';
    $displayDateTimeFormat = '%m/%d/%Y %I:%M %P';
    $submitDateOrder = 'mdy';

    // create new json {}
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
    
    // end buffering, discarding buffer
    ob_end_clean();

    // return json {}
    echo json_encode($json);
    exit;

<?php

function ExportToExcel($data, $filename = "Report", $fields = array()) {
    if (!empty($data)) {
        $a = array_keys($data);
        $b = array_keys($data);

        $colnames = array_combine($b, $a);

// filename for download
        $filename = $filename . date('Ymd H-i-s') . ".csv";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: text/csv");

        $out = fopen("php://output", 'w');

        $flag = false;

        foreach ($data as $row) {
            if (!$flag) {
                // display field/column names as first row
                $firstline = $fields;
                fputcsv($out, $firstline, ',', '"');
                $flag = true;
            }
            array_walk($row, 'cleanData');
            fputcsv($out, array_values($row), ',', '"');
        }


        fclose($out);
        exit;
    }
}

function map_colnames($input) {
    global $colnames;
    return isset($colnames[$input]) ? $colnames[$input] : $input;
}

function cleanData(&$str) {
    if ($str == 't')
        $str = 'TRUE';
    if ($str == 'f')
        $str = 'FALSE';
    if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
        $str = "$str";
    }
    if (strstr($str, '"'))
        $str = '"' . str_replace('"', '""', $str) . '"';
}

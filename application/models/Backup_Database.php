<?php

/**
 * This file contains the Backup_Database class wich performs
 * a partial or complete backup of any given MySQL database
 * @author Daniel López Azaña <http://www.azanweb.com-->
 * @version 1.0
 */
// Report all errors
error_reporting(E_ALL);

/**
 * The Backup_Database class
 */
class Backup_Database extends CI_Model {

    /**
     * Host where database is located
     */
    var $host = '';

    /**
     * Username used to connect to database
     */
    var $username = '';

    /**
     * Password used to connect to database
     */
    var $passwd = '';

    /**
     * Database to backup
     */
    var $dbName = '';

    /**
     * Database charset
     */
    var $charset = '';

    /**
     * Backup the whole database or just some tables
     * Use '*' for whole database or 'table1 table2 table3...'
     * @param string $tables
     */
    public function __construct() {
        $this->dbName = $this->db->database;
    }

    public function backupTables($tables = '*', $outputDir = '.') {
        try {
            /**
             * Tables to export
             */
            if ($tables == '*') {
                $tables = array();
                $tablenames = $this->db->list_tables();
                foreach ($tablenames as $table) {
                    $tables[] = $table;
                }
            } else {
                $tables = is_array($tables) ? $tables : explode(',', $tables);
            }

            $sql = 'CREATE DATABASE IF NOT EXISTS ' . $this->dbName . ";\n\n";
            $sql .= 'USE ' . $this->dbName . ";\n\n";

            /**
             * Iterate tables
             */
            foreach ($tables as $table) {
                echo "Backing up " . $table . " table...";

                $result = $this->db->query('SELECT * FROM ' . $table);

                $numFields = count($this->db->list_fields($table));
                var_dump($numFields);
                $sql .= 'DROP TABLE IF EXISTS ' . $table . ';';
                $row2 = $this->db->query('SHOW CREATE TABLE ' . $table)->row_array();
                //var_dump($row2);
                $sql.= "\n\n" . $row2['Create Table'] . ";\n\n";

                for ($i = 0; $i < $numFields; $i++) {
                    foreach ($result->result_array() as $row) {
                        $sql .= 'INSERT INTO ' . $table . ' VALUES(';
                        for ($j = 0; $j < $numFields; $j++) {
                            $row[$j] = addslashes($row[$j]);
                            $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                            if (isset($row[$j])) {
                                $sql .= '"' . $row[$j] . '"';
                            } else {
                                $sql.= '""';
                            }

                            if ($j < ($numFields - 1)) {
                                $sql .= ',';
                            }
                        }

                        $sql.= ");\n";
                    }
                }

                $sql.="\n\n\n";

                echo " OK" . "
";
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return false;
        }

        return $this->saveFile($sql, $outputDir);
    }

    /**
     * Save SQL to file
     * @param string $sql
     */
    protected function saveFile(&$sql, $outputDir = '.') {
        if (!$sql)
            return false;

        try {
            $handle = fopen($outputDir . '/db-backup-' . $this->dbName . '-' . date("Ymd-His", time()) . '.sql', 'w+');
            fwrite($handle, $sql);
            fclose($handle);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return false;
        }

        return true;
    }

}

<?php

function getDbConnection() {
    static $db = null;

    if (!$db) {
        try {
            $dns = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';port=' . DB_PORT;
            $db = new PDO($dns, DB_USER, DB_PASS);

            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            exit('DB connection failed: ' . $e->getMessage());
        }
    }
    
    return $db;
}
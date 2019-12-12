<?php

require 'vendor/autoload.php';
require 'config.php';

use App\Models\Student;

$db = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT, DB_USER, DB_PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec('CREATE DATABASE IF NOT EXISTS ' . DB_NAME);
$db->exec('USE '. DB_NAME);

$db->exec("CREATE TABLE IF NOT EXISTS `students` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(30),
    `board` VARCHAR(10)
)");

$db->exec("CREATE TABLE IF NOT EXISTS `grades` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `student_id` INT,
    `grade1` tinyint(1),
    `grade2` tinyint(1),
    `grade3` tinyint(1),
    `grade4` tinyint(1),
    FOREIGN KEY (student_id) REFERENCES students(id)
)");

(new Student('Bojan', 'csm', ['grade1' => 10, 'grade3' => 5]))->save();
(new Student('Zoran', 'csm', ['grade3' => 3, 'grade1' => 2]))->save();
(new Student('Mirko', 'csmb', ['grade1' => 10, 'grade3' => 5, 'grade4' => 2]))->save();
(new Student('Darko', 'csmb', ['grade1' => 10, 'grade3' => 8, 'grade2' => 2]))->save();

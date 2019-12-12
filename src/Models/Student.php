<?php

namespace App\Models;

class Student {
    private $studentsTable = 'students';
    private $gradesTable = 'grades';
    private $db;
    
    public $id = null;
    public $name;
    public $board;
    private $grades = [
        'grade1' => null,
        'grade2' => null,
        'grade3' => null,
        'grade4' => null,
    ];

    public function __construct(string $name = null, string $board = null, array $grades = [])
    {
        $this->db = getDbConnection();

        $this->name = $name;
        $this->board = $board;
        $this->setGrades($grades);
    }

    public function setGrades(array $grades)
    {
        foreach($grades as $key => $value)
        {
            $this->grades[$key] = $value;
        }
    }

    public function getGrades()
    {
        return $this->grades;
    }

    public function save()
    {
        $this->db->beginTransaction();

        try 
        {
            $sql = "INSERT INTO $this->studentsTable (`name`, `board`) VALUES (?,?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$this->name, $this->board]);

            $this->id = $this->db->lastInsertId();

            $sql = "INSERT INTO $this->gradesTable (`student_id`, `grade1`, `grade2`, `grade3`, `grade4`) VALUES ($this->id, :grade1, :grade2, :grade3, :grade4)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($this->grades);

            $this->db->commit();
        } catch (\PDOException $e)
        {
            $this->db->rollBack();
        }
    }

    public function findById(int $id)
    {
        $sql = "SELECT students.*, grades.* FROM
                students JOIN grades on students.id = grades.student_id
                WHERE students.id = $id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute();
        
        if ($row = $stmt->fetch())
        {
            $this->id = $row->id;
            $this->name = $row->name;
            $this->board = $row->board;

            foreach($this->grades as $grade => $value)
            {
                $this->grades[$grade] = $row->$grade;
            }
        }
        
        return $this;
    }
}
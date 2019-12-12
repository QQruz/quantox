<?php

namespace App\Models;

class Student {
    /**
     * Student table
     *
     * @var string
     */
    private $studentsTable = 'students';

    /**
     * Grades table
     *
     * @var string
     */
    private $gradesTable = 'grades';

    /**
     * Holds db connection
     *
     * @var PDO
     */
    private $db;
    
    /**
     * Student id
     *
     * @var int|string
     */
    public $id = null;

    /**
     * Students name
     *
     * @var string
     */
    public $name;

    /**
     * Students board
     *
     * @var string
     */
    public $board;

    /**
     * Students grades
     *
     * @var array
     */
    private $grades = [
        'grade1' => null,
        'grade2' => null,
        'grade3' => null,
        'grade4' => null,
    ];

    /**
     * Constructor
     *
     * @param string $name
     * @param string $board
     * @param array $grades
     */
    public function __construct(string $name = null, string $board = null, array $grades = [])
    {
        // helper function
        $this->db = getDbConnection();

        $this->name = $name;
        $this->board = $board;
        $this->setGrades($grades);
    }

    /**
     * Sets grades
     *
     * @param array $grades
     * @return Student
     */
    public function setGrades(array $grades)
    {
        foreach($grades as $key => $value)
        {
            if (array_key_exists($key, $this->grades))
            {
                $this->grades[$key] = $value;
            }   
        }

        return $this;
    }

    /**
     * Gets grades
     *
     * @return array $grades
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * Saves student to db
     *
     * @return Student
     */
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

        return $this;
    }

    /**
     * Finds student by id
     *
     * @param integer $id
     * @return Student
     */
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

    /**
     * Returns all of the students
     *
     * @return array
     */
    public function all()
    {
        $sql = "SELECT students.*, grades.* FROM
                students JOIN grades on students.id = grades.student_id";
        
        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        $results = [];
        
        if ($rows = $stmt->fetchAll())
        {
            foreach($rows as $row)
            {
                $student = new self;
                $student->id = $row->id;
                $student->name = $row->name;
                $student->board = $row->board;
        
                foreach($student->grades as $grade => $value)
                {
                    $student->grades[$grade] = $row->$grade;
                }

                $results[] = $student;
            }

        }
        
        return $results;      
    }
}
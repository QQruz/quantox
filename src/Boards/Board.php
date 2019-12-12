<?php

namespace App\Boards;

use App\Models\Student;

abstract class Board 
{
    /**
     * Student varaible
     *
     * @var Student
     */
    protected $student;

    /**
     * Filtered students grades
     *
     * @var array
     */
    protected $grades;

    /**
     * Avrage grade
     *
     * @var float
     */
    protected $avrage;

    /**
     * Final result
     *
     * @var string
     */
    protected $finalResult = 'fail';

    /**
     * Constructor
     *
     * @param Student $student
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
        // we need only set grades
        $this->grades = array_filter($student->getGrades());
    }

    /**
     * Calculates avrage grade
     *
     * @return Board
     */
    protected function avrage()
    {
        $gradesCount = count($this->grades);

        $this->avrage = $gradesCount ? array_sum($this->grades) / $gradesCount : 0;
        
        return $this;
    }

    /**
     * Creates array for report data
     *
     * @return array
     */
    protected function toArray()
    {
        return [
            'student_id' => $this->student->id,
            'name' => $this->student->name,
            'grades' => $this->student->getGrades(),
            'avrage' => $this->avrage,
            'final_result' => $this->finalResult
        ];
    }

    /**
     * Calculates avrage and final result
     *
     * @return Board
     */
    abstract public function calculateResult();

    /**
     * Renders the report
     *
     * @return mixed
     */
    abstract public function render();
}
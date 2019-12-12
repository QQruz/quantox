<?php

namespace App\Boards;

use App\Models\Student;

abstract class Board 
{
    protected $student;
    protected $grades;
    protected $avrage;
    protected $finalResult = 'fail';

    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->grades = array_filter($student->getGrades());
    }

    protected function avrage()
    {
        $gradesCount = count($this->grades);

        $this->avrage = $gradesCount ? array_sum($this->grades) / $gradesCount : 0;
        
        return $this;
    }

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

    abstract public function calculateResult();
    abstract public function send();
}
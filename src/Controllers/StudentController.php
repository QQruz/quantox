<?php

namespace App\Controllers;

use App\Models\Student;

class StudentController
{
    private $boardsPath = 'App\Boards\\';

    public function index()
    {
        $students = (new Student)->all();
        
        $this->view('index', $students);
    }

    public function show(int $id)
    {
        $student = new Student;
        $student->findById($id);

        if ($student->id)
        {
            $board = $this->boardsPath . ucfirst($student->board);

            $board = new $board($student);

            echo $board->calculateResult()->send();
        } else
        {
            http_response_code(404);
            exit();
        }
    }

    private function view(string $file, $data) {
        require VIEWS_PATH . $file . '.php';
    }
}
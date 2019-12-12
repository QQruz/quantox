<?php

namespace App\Controllers;

use App\Models\Student;

class StudentController
{
    /**
     * Path to boards
     *
     * @var string
     */
    private $boardsPath = 'App\Boards\\';

    /**
     * Lists students
     *
     * @return void response 
     */
    public function index()
    {
        $students = (new Student)->all();
        
        $this->view('index', $students);
    }

    /**
     * Shows specific student
     *
     * @param integer $id
     * @return void response
     */
    public function show(int $id)
    {
        $student = new Student;
        $student->findById($id);

        if ($student->id)
        {
            $board = $this->boardsPath . ucfirst($student->board);

            $board = new $board($student);

            echo $board->calculateResult()->render();
        } else
        {
            http_response_code(404);
            exit();
        }
    }

    /**
     * Loads view file
     *
     * @param string $file view to be loaded
     * @param mixed $data
     * @return void response
     */
    private function view(string $file, $data) {
        require VIEWS_PATH . $file . '.php';
    }
}
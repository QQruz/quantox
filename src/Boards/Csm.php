<?php

namespace App\Boards;

class Csm extends Board 
{
    public function calculateResult()
    {
        $this->avrage();

        if ($this->avrage >= 7)
        {
            $this->finalResult = 'pass';
        }

        return $this;
    }

    public function send()
    {
        header("Content-type: application/json");
        return json_encode($this->toArray());
    }
}
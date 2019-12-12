<?php

namespace App\Boards;

class Csm extends Board 
{
    /**
     * Calculates avrage and final result
     *
     * @return Csm
     */
    public function calculateResult()
    {
        $this->avrage();

        if ($this->avrage >= 7)
        {
            $this->finalResult = 'pass';
        }

        return $this;
    }

    /**
     * Renders the report
     *
     * @return string report in JSON
     */
    public function render()
    {
        header("Content-type: application/json");
        return json_encode($this->toArray());
    }
}
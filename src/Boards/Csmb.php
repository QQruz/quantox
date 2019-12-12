<?php

namespace App\Boards;

class Csmb extends Board 
{
    /**
     * Removes the minimal grade
     *
     * @return Csmb
     */
    private function removeMinGrade()
    {
        if (count($this->grades) < 3)
        {
            return $this;
        }
        asort($this->grades);
        array_shift($this->grades);

        return $this;
    }

    /**
     * Calculates avrage and final result
     *
     * @return Csmb
     */
    public function calculateResult()
    {
        $this->removeMinGrade()->avrage();

        if (max($this->grades) > 8)
        {
            $this->finalResult = 'pass';
        }

        return $this;
    }

    /**
     * Renders the report
     *
     * @return \SimpleXMLElement
     */
    public function render()
    {
        $xml = new \SimpleXMLElement('<root/>');
        $arr = $this->toArray();
        $this->addToXml($xml, $arr);

        header("Content-type: text/xml");
        return $xml->asXML();
    }

    /**
     * Adds nodes to XML
     *
     * @param \SimpleXMLElement $xml
     * @param array $elements
     * @return void
     */
    private function addToXml(\SimpleXMLElement $xml, array $elements)
    {
        foreach ($elements as $key => $element)
        {
            if (is_array($element))
            {
                $node = $xml->addChild($key);
                // do the recurison if element is an array
                $this->addToXml($node, $element);
            } else 
            {
                $xml->addChild($key, $element);
            }
        }
    }
}
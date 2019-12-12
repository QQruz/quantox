<?php

namespace App\Boards;

class Csmb extends Board 
{
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

    public function calculateResult()
    {
        $this->removeMinGrade()->avrage();

        if (max($this->grades) > 8)
        {
            $this->finalResult = 'pass';
        }

        return $this;
    }

    public function send()
    {
        $xml = new \SimpleXMLElement('<root/>');
        $arr = $this->toArray();
        $this->addToXml($xml, $arr);

        header("Content-type: text/xml");
        return $xml->asXML();
    }

    private function addToXml(\SimpleXMLElement $xml, array $elements)
    {
        foreach ($elements as $key => $element)
        {
            if (is_array($element))
            {
                $node = $xml->addChild($key);
                $this->addToXml($node, $element);
            } else 
            {
                $xml->addChild($key, $element);
            }
        }
    }
}
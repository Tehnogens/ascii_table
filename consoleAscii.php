<?php

class AsciiTable
{
    private $angleChar = '+';
    private $lineChar  = '-';
    private $leftChar  = '|';
    private $dataArray = [];
    private $nlBr      = PHP_EOL;
    private $colorArr  = [];
    private $mainArr   = [];
    private $lengthArr = [];

    public function __construct($arr)
    {
        $this->mainArr = $arr;
        $this->setDataArray();
        $this->lengthArr = $this->getRowLength();
        $this->colorArr = ['Green' => 42, 'Blue' => 44, 'Pink' => 45];
    }

    private function setDataArray()
    {
        $arr = array_keys($this->mainArr[0]);

        foreach ($arr as $key => $value) {
            $this->dataArray[$value] = array_column($this->mainArr, $value);
        }
    }

    public function getHeader()
    {
        $arr = array_keys($this->mainArr[0]);
        $result  = '';
        $result .= $this->setLine();

        foreach ($arr as $key => $value){
            if($key + 1 == count($arr)){
                $result .= $this->setLineWithVal($value, true);
            }
            else{
                $result .= $this->setLineWithVal($value);
            }
        }
        $result .= $this->getData();
        return $result;
    }

    public function getData()
    {
        $data = $this->getDataRow();
        $result = '';

        foreach ($data as $key => $value){
            $result .= $this->setLine();
            $iter = 1;
            foreach ($value as $k => $val){
                if($iter == count($value)) {
                    if ($k == 'Color')
                        $result .= $this->getRowTemplate($val, $k, true, true);
                    else
                        $result .= $this->getRowTemplate($val, $k, false, true);
                }
                else{
                    if ($k == 'Color')
                        $result .= $this->getRowTemplate($val, $k, true);
                    else
                        $result .= $this->getRowTemplate($val, $k, false);
                }
                $iter++;
            }
            $result .= $this->nlBr;
        }
        $result .= $this->setLine();
        return $result;
    }

    private function getDataRow()
    {
        $arr = [];

        for($j = 0; $j < count($this->dataArray)-1; $j++) {
            $arr [] = array_map(function ($i) use ($j) {
                return $i[$j];
            }, $this->dataArray);
        }

        return $arr;
    }

    private function getRowTemplate($value, $key, $color, $last = false)
    {
        $lastElem = ($last == true) ? $this->leftChar : '';
        $text = '';

        if($color) {
            $colorCode = $this->colorArr[$value];
            $text .= $this->leftChar . "\033[" . $colorCode . "m" . str_pad($value, $this->lengthArr[$key]+5, ' ', STR_PAD_BOTH) . "\033[0m" . $lastElem;
        }
        else
            $text .= $this->leftChar . str_pad($value, $this->lengthArr[$key]+5, ' ', STR_PAD_BOTH) . $lastElem;

        return $text;
    }

    private function setLine()
    {
        $text = '';
        $iter = 1;
        foreach ($this->lengthArr as $value){
            if($iter == count($this->lengthArr))
                $text .= $this->angleChar . str_repeat($this->lineChar, (int)$value + 5) . $this->angleChar;
            else
                $text .= $this->angleChar . str_repeat($this->lineChar, (int)$value + 5) ;
            $iter++;
        }

        return $text . $this->nlBr;
    }

    private function setLineWithVal($value, $last = false, $length = 10)
    {
        $lastElem = ($last == true) ? $this->leftChar . PHP_EOL : '';
        $text = '';

        $text .= $this->leftChar . str_pad($value, $this->lengthArr[$value]+5, ' ', STR_PAD_BOTH) . $lastElem;

        return $text;
    }

    private function getRowLength()
    {
        $arr = [];

        foreach ($this->dataArray as $key => $value){
            $lengths = array_map('strlen', $value);
            $arr[$key] = max($lengths);
        }

        return $arr;
    }
}


$mainArray = [
    [
        'Name' => 'Trixie',
        'Color' => 'Green',
        'Element' => 'Earth',
        'Likes' => 'Flowers'
    ],
    [
        'Name' => 'Tinkerbell',
        'Element' => 'Air',
        'Likes' => 'Singning',
        'Color' => 'Blue'
    ],
    [
        'Name' => 'Blum',
        'Element' => 'Water',
        'Likes' => 'Dancing',
        'Name' => 'Blum',
        'Color' => 'Pink'
    ],
];

$cls = new AsciiTable($mainArray);
echo $cls->getHeader();



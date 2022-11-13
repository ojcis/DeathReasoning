<?php
class row
{
    private array $row;

    public function __construct(array $row)
    {
        $this->row=$row;
    }

    public function getRow(): array
    {
        return $this->row;
    }

    public function getNevardarbigaNave()
    {
        if ($this->row[1]=='Nevardarbīga nāve'){
            return $this->row;
        }
    }

    public function getVardarbigaNave()
    {
        if ($this->row[1]=='Vardarbīga nāve'){
            return $this->row;
        }
    }
}
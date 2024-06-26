<?php

class Order
{
    public string $column; 
    public string $type;

    public function __construct(?string $column='', ?string $type='')
    {
        $this->type = $type;
        $this->column = $column;
    }
}
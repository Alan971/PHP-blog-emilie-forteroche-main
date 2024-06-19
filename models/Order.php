<?php

class Order
{
    public string $type; 
    public string $upOrDown;

    public function __construct(?string $type='', ?string $upOrDown='')
    {
        $this->type = $type;
        $this->upOrDown = $upOrDown;
    }
}
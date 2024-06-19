<?php

class AdminOrderArticle
{
    private ?array $orderBy;
    private const UPARROW = "↑";
    private const DOWNARROW = "↓";

    public function __construct()
    {
        $this->orderBy['col'] = 'title';
        $this->orderBy['upOrDown'] = '↓'; 
    }

    public function getOrderBy(string $choice) : string
    {
        if($choice == 'col')
        {
            return $this->orderBy['col'];
        }
        elseif ($choice == 'upOrDown')
        {
            return $this->orderBy['upOrDown'];
        }

    }

    public function setOrderBy(string $type, string $upOrDown) : void
    {
        if($type!="")
        {
            if($type == $this->orderBy['col'])
            {
                if($upOrDown == self::UPARROW)
                {
                    $this->orderBy['upOrDown'] = self::DOWNARROW;
                }
                else 
                {
                    $this->orderBy['upOrDown'] = self::UPARROW;
                }
            }
            else
            {
                $this->orderBy['col'] = $type;
                $this->orderBy['upOrDown'] = self::DOWNARROW;
            }
        }
        else{
            $this->orderBy['col'] = 'title';
            $this->orderBy['upOrDown'] = '↓';   // ↑
        }
    }


}
<?php


class AdminOrderArticle
{
    private $orderBy; 
    private const UPARROW = "↑";
    private const DOWNARROW = "↓";

    public function __construct()
    {
        if ($this->orderBy === null) {
            $this->orderBy = new Order("title", "↓");
        }
    }
    
    // la fonction revoie soit l'état colonne soit l'état croissant
    public function getOrderBy() : Order
    {
            return $this->orderBy;         
    }

    public function setOrderBy(Order $nextOrder, Order $previousOrder ) : void
    {
        if($previousOrder->type!="")
        {
            if($previousOrder->type == $nextOrder->type)
            {
                $this->orderBy->type = $nextOrder->type;
                if($nextOrder->upOrDown == self::UPARROW)
                {
                    $this->orderBy->upOrDown = self::DOWNARROW;
                }
                else 
                {
                    $this->orderBy->upOrDown = self::UPARROW;
                }
            }
            else
            {
                $this->orderBy->type = $nextOrder->type;
                $this->orderBy->upOrDown = self::DOWNARROW;
            }
        }
        else{
            $this->orderBy->type = 'title';
            $this->orderBy->upOrDown = '↓';   
        }
    }
}
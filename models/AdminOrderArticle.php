<?php


class AdminOrderArticle
{
    private static $orderBy; 
    private const UPARROW = "↑";
    private const DOWNARROW = "↓";

    public function __construct()
    {
        if (self::$orderBy === null) {
            echo "0 ";
            self::$orderBy = new Order("title", "↓");
        }
    }

    // la fonction revoie soit l'état colonne soit l'état croissant
    public function getOrderBy() : Order
    {
            return self::$orderBy;         
    }

    public function setOrderBy(Order $previousOrder ) : void
    {
        if($previousOrder->type!="")
        {
            if($previousOrder->type == self::$orderBy->type)
            {
                echo "1 ";
                if($previousOrder->upOrDown == self::UPARROW)
                {
                    echo "2 ";
                    self::$orderBy->upOrDown = self::DOWNARROW;
                }
                else 
                {
                    echo "3 ";
                    self::$orderBy->upOrDown = self::UPARROW;
                }
            }
            else
            {
                echo "4 " . $previousOrder->type . "==" . self::$orderBy->type;
                self::$orderBy->type = $previousOrder->type;
                echo " et : " . $previousOrder->type . "==" . self::$orderBy->type;
                self::$orderBy->upOrDown = self::DOWNARROW;
            }
        }
        else{
            echo "5 ";
            self::$orderBy->type = 'title';
            self::$orderBy->upOrDown = '↓';   // ↑
        }
    }


}
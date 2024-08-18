<?php 

namespace PHP\View;

class CharityView
{
    public function showSuccess($message)
    {
        echo $message . "\n";
    }

    public function showAll($list)
    {
        echo "Charity List : \n";
        foreach($list as $line){
            echo "ID: " . $line['id'] . "; Charity name: " . $line['name'] . "; Charity email: " . $line['email'] . "\n";
        }
    }
}


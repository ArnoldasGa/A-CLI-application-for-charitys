<?php
namespace PHP\Models;

class Charity
{

    private $filePath;
    private $id;
    private $name;
    private $email;


    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function inpiutCharityToFile($name,$email) 
    { 
        $id = $this->getNextId();
        $charity = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
        ];
        if (file_exists($this->filePath)){
            $charityList = $this->getAll();
        } else {
            $charityList = [];
        }
        array_push($charityList, $charity);
        $charityList = json_encode($charityList);
        file_put_contents($this->filePath, $charityList);
    }

    private function getNextId()
    {
        $nextId = 1;
        if (file_exists($this->filePath)) {
            $charities = $this->getAll();
            if (!empty($charities)) {
                $lastCharity = end($charities);
                $nextId = $lastCharity['id'] + 1;
            }
        }
        return $nextId;
    }

    public function getAll()
    {
        if (file_exists($this->filePath)) {
            $fileContent = file($this->filePath);
            foreach ($fileContent as $line) {
                $chartys = json_decode($line, true);
            }
        return $chartys;
        }
    }

    public function edit()
    {
        echo "Enter Id of the charity that needs editing want to change: \n";
        $editId = trim(fgets(STDIN));
        $check = $this->charityExists($editId);
        if($check == false) 
        {
            return "Cherity dose not exist with this Id.";
        }
        if(file_exists($this->filePath)) {
            $charitys = $this->getAll();
        if(!empty($charitys)){
            $count = 0;
            foreach($charitys as $item)
            {
                $count++;
                if($item['id'] == $editId) {
                    $select = 1;
                    while ($select != 0 && $select != 9)
                    {
                        echo "What do you want to change: \n";
                        echo "1) Name.\n";
                        echo "2) Email.\n";
                        echo "9) Cancel.\n";
                        echo "0) Done.\n";
                        $select = trim(fgets(STDIN));
                        switch($select) {
                            case 1:
                                echo "Old name: " . $item['name'] . "\n";
                                echo "Input new name: ";
                                $input = trim(fgets(STDIN));
                                $item['name'] = $input;
                                break;
                            case 2:
                                echo "Old email: " . $item['email'] . "\n";
                                echo "Input new email: ";
                                $input = trim(fgets(STDIN));
                                if(filter_var($input, FILTER_VALIDATE_EMAIL)) {
                                    $item['email'] = $input;
                                    break;
                                } else {
                                    echo "Inputed email '$input' is invalid. \n";
                                    break;
                                }
                            case 9:
                                return "Canceled editing of the cherity.";
                            case 0:
                                $charitys[$count] = $item;
                                $charitys = json_encode($charitys);
                                file_put_contents($this->filePath, $charitys);
                                return "Done editing of the cherity.";
                        }
                    }
                }
            }
        }
        }
    }

    public function charityExists($id)
    {
        $check = false;
        if(file_exists($this->filePath)) {
            $charitys = $this->getAll();
        if(!empty($charitys)){
            foreach($charitys as $item)
            {
                if($item['id'] == $id)
                {
                    $check = true;
                }
            }
            }
        }
        return $check;
    }

    public function deleteCherity($id)
    {
        if(file_exists($this->filePath)) {
            $charitys = $this->getAll();
            if(!empty($charitys)){
                $count = 0;
                foreach($charitys as $item)
                {
                    if($item['id'] == $id)
                    {
                        unset($charitys[$count]);
                    }
                    $count++;
                }
                $charitys = array_values($charitys);
                $charitys = json_encode($charitys);
                file_put_contents($this->filePath, $charitys);
                echo "Done.\n";
            }
        }
    }

}

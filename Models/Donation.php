<?php
namespace PHP\Models;

class Donation
{
    private $filePath;
    private $id;
    private $name;
    private $amount;
    private $charityId;
    private $time;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    

    public function create($name,$amount,$charityId,$date)
    {
        $id = $this->getNextId();
        $donation = [
            'id' => $id,
            'name' => $name,
            'amount' => $amount,
            'charityId' => $charityId,
            'date' => $date,
        ];
        if (file_exists($this->filePath)){
            $donationList = $this->getAll();
        } else {
            $donationList = [];
        }
        array_push($donationList, $donation);
        $donationList = json_encode($donationList);
        file_put_contents($this->filePath, $donationList);
    }

    private function getNextId()
    {
        $nextId = 1;
        if (file_exists($this->filePath)) {
            $donation = $this->getAll();
            if (!empty($donation)) {
                $lastDonation = end($donation);
                $nextId = $lastDonation['id'] + 1;
            }
        }
        return $nextId;
    }

    public function getAll()
    {
        if (file_exists($this->filePath)) {
            $fileContent = file($this->filePath);
            foreach ($fileContent as $line) {
                $donation = json_decode($line, true);
            }
        return $donation;
        }
    }

    public function donationExists($id)
    {
        $check = false;
        if(file_exists($this->filePath)) {
            $donations = $this->getAll();
        if(!empty($donations)){
            foreach($donations as $item)
            {
                if($item['charityId'] == $id)
                {
                    $check = true;
                }
            }
            }
        }
        return $check;
    }

    public function deleteWithCharityId($id) {
        if(file_exists($this->filePath)) {
            $donations = $this->getAll();
            if(!empty($donations)){
                $count = 0;
                foreach($donations as $item)
                {
                    if($item['charityId'] == $id)
                    {
                        unset($donations[$count]);
                    }
                    $count++;
                }
                $donations = json_encode($donations);
                file_put_contents($this->filePath, $donations);
                echo "Done.\n";
            }
        }
    }
}

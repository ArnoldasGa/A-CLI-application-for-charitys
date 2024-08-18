<?php
namespace PHP\Controller;

use PHP\Models\Charity;
use PHP\View\CharityView;
use PHP\Models\Donation;
use PHP\View\DonationView;

class MainController
{
    private $charityController;
    private $donationController;

    public function __construct()
    {
        $this->charityController = new CharityController(
            new Charity('charity.txt'),
            new CharityView()
        );

        $this->donationController = new DonationController(
            new Donation('donation.txt'),
            new DonationView()
        );
    }
    public function run()
    {
        $input = 1;
        while ($input != 0) {
            echo "Selcet one of the options:\n";
            echo "1) View all charity.\n";
            echo "2) Add new charity.\n";
            echo "3) Edit charity.\n";
            echo "4) Delete charity.\n";
            echo "5) Add a new donation.\n";
            echo "0) Exit.\n";

            $input = (trim(fgets(STDIN)));

            switch ($input) {
                case 1:
                    $this->charityController->viewCharitys();
                    break;
                case 2:
                    $this->charityController->addCharitys();
                    break;
                case 3:
                    $this->charityController->editCharity();
                    break;
                case 4:
                    echo "Enter Id of the charity that needs to be deleted: \n";
                    $id = trim(fgets(STDIN));
                    $check = true;
                    while ($check == true)
                    {
                        $check = $this->donationController->checkIfDonationExists($id);
                        if($check == true){
                            echo "This charity has donations still want to delete it? It will delete donations for this charity too (Y/N) \n";
                            $question = strtoupper(trim(fgets(STDIN)));
                            if ($question == "Y")
                            {
                                $check = false;
                            } else if ($question == "N"){
                                echo "Canceling. \n";
                                break;
                            } else {
                                echo "Wrong input. \n";
                            }
                            if ($check == false) {
                                $this->donationController->deleteDonationsWithCharithId($id);
                            }
                        }
                        if ($check == false) {
                            $this->charityController->deleteCharityWithId($id);
                        }
                    }
                    break;
                case 5:
                    echo "Enter donors name: \n";
                    $nameD = trim(fgets(STDIN));
                    $check = false;
                    while ($check == false)
                    {
                        echo "Enter the amount donated (euro): \n";
                        $amount = trim(fgets(STDIN));
                        if (!is_numeric($amount) || $amount <= 0) {
                            echo "Invalid amount entered. Please enter a positive numeric value.\n";
                            echo "Do you want to try another amount ? (Y/N)\n";
                            $question = strtoupper(trim(fgets(STDIN)));
                            if($question == "N") {
                                return;
                            }
                        } else {
                            $check = true;
                        }
                    }
                    $check = false;
                    while ($check == false)
                    {
                        echo "Enter charity Id that was donated too: \n";
                        $charityId = trim(fgets(STDIN));
                        $check = $this->charityController->checkCharity($charityId);
                        if($check == false) {
                            echo "This chrity dosen't exists. \n";
                            echo "Do you want to try another Id ? (Y/N)\n";
                            $question = strtoupper(trim(fgets(STDIN)));
                            if($question == "N") {
                                return;
                            }
                        }
                    }
                    $check = false;
                    while ($check == false)
                    {
                        echo "Enter date:";
                        echo "Year: ";
                        $year = trim(fgets(STDIN));
                        echo "Month: ";
                        $month = trim(fgets(STDIN));
                        echo "Day: ";
                        $day = trim(fgets(STDIN));
                        if(checkdate($month,$day,$year)) {
                            $date = "'$year'/'$month'/'$day'";
                            $this->donationController->addDonation($nameD,$amount,$charityId,$date);
                            break;
                        } else {
                            echo "Date that was inputed was wrong.\n";
                        }
                    }
                break;
                case 0:
                    echo "Exiting \n";
                    exit(0);
                default:
                    echo "Invalid option. \n";
            }
        }
    }
}



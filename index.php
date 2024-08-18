<?php
echo "Program starting... \n";
require 'Models/Charity.php';
require 'Models/Donation.php';
require 'Controller/MainController.php';
require 'Controller/CharityController.php';
require 'Controller/DonationController.php';
require 'View/CharityView.php';
require 'View/DonationView.php';

use PHP\Controller\MainController;

$mainController = new MainController();
$mainController->run();

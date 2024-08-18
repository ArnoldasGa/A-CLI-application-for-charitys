<?php

namespace PHP\Controller;

use PHP\Models\Donation;
use PHP\View\DonationView;
use PHP\Models\Charity;
use PHP\View\CharityView;

class DonationController
{

    private $model;
    private $view;

    public function __construct(Donation $model, DonationView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }
    public function addDonation($name, $amount, $charityId, $date)
    {
        $this->model->create($name,$amount,$charityId,$date);
        $this->view->showSuccess("Donation added successfully");
    }

    public function checkIfDonationExists($id)
    {
        return $this->model->donationExists($id);
    }

    public function deleteDonationsWithCharithId($id){
        $this->model->deleteWithCharityId($id);
        $this->view->showSuccess("Donation Deleted ");
    }
}

<?php 
namespace PHP\Controller;

use PHP\Models\Charity;
use PHP\View\CharityView;
class CharityController
{

    private $model;
    private $view;

    public function __construct(Charity $model, CharityView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function viewCharitys() {
        $this->view->showAll($this->model->getAll());
    }

    public function addCharitys() {
        $choise = 0;
        while ($choise != 1 && $choise != 2)
        {
            echo "Select and option: \n";
            echo "1) Input single charity manualy. \n";
            echo "2) Use CSV file to inport charitys(CSV file neeeds to be in the folder where index.php is). \n";
            echo "3) Cancel. \n";
            $choise = trim(fgets(STDIN));
            if($choise == 1) {
                echo "Enter charity name: ";
                $name = trim(fgets(STDIN));
                echo "Enter charity email: ";
                $email = trim(fgets(STDIN));
                while (filter_var($email, FILTER_VALIDATE_EMAIL) != true)
                {
                    echo "Email '$email' is invalid please correct the email: \n";
                    $email = trim(fgets(STDIN));
                }
                $this->model->inpiutCharityToFile($name,$email);
                $this->view->showSuccess("Charity with the name: '$name' and email: '$email' has been added.");
            } else if ($choise == 2) {
                echo "Input files name (without .csv extension): \n";
                $fileName = trim(fgets(STDIN)) . ".csv";
                if (file_exists($fileName)) {
                    if (($handle = fopen($fileName, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $name = $data[0];
                            $email = $data[1];
                            while (filter_var($email, FILTER_VALIDATE_EMAIL) != true)
                            {
                                echo "Email '$email' is invalid please correct the email: \n";
                                $email = trim(fgets(STDIN));
                            }
                            $this->model->inpiutCharityToFile($name,$email);
                        }
                        fclose($handle);
                        $this->view->showSuccess("Charitys from '$fileName' has been added.");
                    } 
                } else {
                    echo "Error: File '$fileName' does not exist.\n";
                }
            } else if ($choise == 2) {
                $this->view->showSuccess( "Creation of new charity canceled.");
            }else {
                echo "Wrong input. \n";
            }
        }
    }

    public function editCharity() { 
        $this->view->showSuccess($this->model->edit());
    }

    public function checkCharity($id) {
        return $this->model->charityExists($id);
    }

    public function deleteCharityWithId($id){
        $this->model->deleteCherity($id);
        $this->view->showSuccess("Done editing");
    }

}


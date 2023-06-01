<?php

namespace App\Controller;

use src\MyClass\Session;

class ApiController extends AppController
{

    /**
     * @var string
     */
    private $current_menu;

    /**
     * @var session
     */
    private $session;

    public function __construct(){
        // Chargements des tables SQL
        parent::__construct();
        $this->current_menu = 'home';
        $this->session = Session::getSessionInstance();
        $this->loadModel('Intervention');
        $this->loadModel('Company');
        $this->loadModel('Client');
    }


    /**
     * @param int $year
     * @return void
     */
    public function statsForInterventionWithYear(int $year)
    {
        $dataPoints = array();
        $interventions = $this->Intervention->totalAmountGraphByYear($year);
        foreach($interventions as $row){
            $dataPoints[] = $row;
        }
        header("Content-Type: application/json");
        echo json_encode($dataPoints);
        exit();
    }

    public function statsForCustomerWithYear(int $year)
    {
        $dataPoints = array();
        $interventions = $this->Client->totalAmountGraphByYear($year);
        foreach($interventions as $row){
            $dataPoints[] = $row;
        }
        header("Content-Type: application/json");
        echo json_encode($dataPoints);
        exit();
    }

    /**
     * @param int $year
     * @return void
     */
    public function statsForInterTypeWithYear(int $year)
    {
        $dataPoints = array();
        $interventions = $this->Intervention->totalInterByTypeByYear($year);
        foreach($interventions as $row){
            $dataPoints[] = $row;
        }
        header("Content-Type: application/json");
        echo json_encode($dataPoints);
        exit();
    }
}
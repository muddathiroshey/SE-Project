<?php 
namespace App\Controllers;
use App\Core\Controller;

class BidController extends Controller
{   
    protected Data $conn;

    public function __construct()
    {
        $this->conn = new Data();
    }
    public function index(): void
    {
        $this->view('Bids/bid-submit');
    }
}
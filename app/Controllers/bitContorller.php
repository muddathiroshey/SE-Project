<?php 
namespace App\Controllers;
use App\Core\Controller;

class bitContorller extends Controller
{
    public function index(): void
    {
        $this->view('Bids/bid-submit');
    }
}
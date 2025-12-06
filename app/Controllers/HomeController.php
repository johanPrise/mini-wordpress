<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Page;

class HomeController extends Controller
{
    public function index()
    {
        $this->view("front/home");
    }
}

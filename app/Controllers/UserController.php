<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Session;

class UserController extends Controller
{
    public function index()
    {
        Session::requireAdmin(); // sécurité
        
        $users = User::all();

        $this->view("admin/users/index", [
            "users" => $users
        ], "admin");
    }
}

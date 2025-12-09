<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\UserController;

return [
    "/" => [HomeController::class, "index"],

    "/login" => [AuthController::class, "login"],
    "/register" => [AuthController::class, "register"],
    "/logout" => [AuthController::class, "logout"],

    "/activate" => [AuthController::class, "activate"],

    "/admin/users" => [UserController::class, "index"],

    "/forgot-password" => [AuthController::class, "forgotPassword"],
    "/reset-password" => [AuthController::class, "resetPassword"],
];

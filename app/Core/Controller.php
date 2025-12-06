<?php
namespace App\Core;

use App\Core\Session;
class Controller
{

  public function render($view, $data = []): void
  {
      extract($data);
      require_once "../App/Views/$view.php";
  }

  public function redirect($url): void
  {
      header("Location: $url");
      exit;
  }

  public function flash($key, $message): void
  {
      Session::set($key, $message);
  }

  public function getFlash($key)
  {
      $message = Session::get($key);
      Session::remove($key);
      return $message;
  }

}
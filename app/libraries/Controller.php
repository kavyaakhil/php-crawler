<?php

class Controller {

  protected $model;
  
  // Load Model
  public function model($model) {
    // Require model file
    require_once APP_ROOT . DIRECTORY_SEPARATOR .'Model' . DIRECTORY_SEPARATOR . $model . '.php';

    // Instantiate model
    return new $model();
  }

  // Load View
  public function view($view, $data = []) {
    // by default add the header file
    include_once APP_ROOT. DIRECTORY_SEPARATOR .'View'. DIRECTORY_SEPARATOR .'header.php';

    // check for view file
    (file_exists(APP_ROOT. DIRECTORY_SEPARATOR .'View' . DIRECTORY_SEPARATOR . $view . '.php')) ?
        require_once APP_ROOT. DIRECTORY_SEPARATOR .'View' . DIRECTORY_SEPARATOR .$view . '.php' : // Require view file
        die('View does not exist'); // View does not exit, Thus, stop the application

    // by default add the footer file
    include_once APP_ROOT . DIRECTORY_SEPARATOR .'View'. DIRECTORY_SEPARATOR .'footer.php';
  }
}

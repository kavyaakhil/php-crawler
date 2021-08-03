<?php

class Core {
  protected $currentController = 'BaseController';
  protected $currentmethod = 'index';
  protected $params = [];

  public function __construct() {
    $url = $this->getUserURL();
    
    if(file_exists(APP_ROOT. DIRECTORY_SEPARATOR .'Controller'. DIRECTORY_SEPARATOR . ucwords($url['controller']) . '.php')) {
      // if the file exists, we set it as the current controller
      $this->currentController = ucwords($url['controller']);
    }

    require_once APP_ROOT. DIRECTORY_SEPARATOR .'Controller'. DIRECTORY_SEPARATOR . $this->currentController . '.php';

    // instantiate controller class
    $this->currentController = new $this->currentController;

    /*
     * Check for second parameter of the url to check for method
     */
    if(isset($url['method'])) {
      if(method_exists($this->currentController, $url['method'])) {
          // if the method is there, we set the current method
          $this->currentmethod = $url['method'];
      }
    }

    // Get Params
    $this->params = isset($url['param']) ? [$url['param']] : [];

    // call a callback with array of params
    call_user_func_array([$this->currentController, $this->currentmethod], $this->params);
  }

  // Fetch the url paramters
  public function getUserURL() {
    if(isset($_GET['controller'])) {
      return $_GET;
    }
  }
}
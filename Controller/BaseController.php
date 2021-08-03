<?php

class BaseController extends Controller {
  /**
   * @var mixed
   */
  private $postModel;

  public function __construct() {
    $this->postModel = $this->model('Post');
  }

  // default method if there is no method
  public function index() {
    $posts = $this->postModel->getPosts();
    $data = array(
      'title' => 'You have reached the default page',
      'posts' => $posts,
    );

    $this->view('index', $data);
  }

}
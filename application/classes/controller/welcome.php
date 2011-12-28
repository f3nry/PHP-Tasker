<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {
  public function before() {
    if(Migrations::check()) {
      $this->request->redirect("migrations");
    }
  }

	public function action_index()
	{
		$this->response->body('hello, world!');
	}

} // End Welcome

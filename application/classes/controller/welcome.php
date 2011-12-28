<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {
<<<<<<< HEAD
  public function before() {
    if(Migrations::check()) {
      $this->request->redirect("migrations");
    }
  }
=======
>>>>>>> b89effc5b3a21bed9eb9eb6ae8ff0776df152165

	public function action_index()
	{
		$this->response->body('hello, world!');
	}

} // End Welcome

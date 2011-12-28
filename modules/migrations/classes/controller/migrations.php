<?php

class Controller_Migrations extends Controller_Template {
  public $template = 'migrations';

  public function action_index() {
    if(!Migrations::check()) {
      $this->request->redirect($this->request->referrer());
    }

    $migrations = Migrations::available_versions();
    $this->template->migration = $migrations[0];
  }

  public function action_execute() {
    $id = $this->request->param('id');

    $migration = Migrations::get_version($id);
    $migration->run_up();

    $this->request->redirect($_GET['redirect']);
  }
}

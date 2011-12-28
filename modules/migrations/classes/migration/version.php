<?php

/**
 * Represents a migration version file.
 */
class Migration_Version {

  protected $_file_name;

  protected $_version = null;

  /**
   * Create an instance with a specified file name;
   *
   * @param $file_name The file representing this migration.
   */
  public function __construct($file_name) {
    $this->_file_name = strtolower($file_name);
    $this->get_version();
  }

  public function get_file_name() {
    return $this->_file_name;
  }
  
  /**
   * Get the numeric version for this migration.
   *
   * This is parsed from the file name. If the file is named 1.sql, then this will be version 1.
   *
   * @returns integer
   */
  public function get_version() {
    if($this->_version === null) {
      $this->_version = preg_match("/^[0-9]+.sql/", $this->get_file_name());
    }

    return $this->_version;
  }

  public function get_file_contents() {
    $path = Migrations::migration_directory() . "/" . $this->get_file_name();

    if(file_exists($path)) {
      return file_get_contents($path);
    }

    return "";
  }

  public function get_up_sql() {
    $contents = $this->get_file_contents();

    return substr($contents, strpos($contents, "#!!!= UP") + 8, strpos($contents, "#!!!= DOWN") - 8);
  }

  public function run_up() {
    $result = DB::query(Database::INSERT, $this->get_up_sql())->execute();

    Migrations::set_version($this->get_version());

    return $result;
  }

  public function get_down_sql() {
    $contents = $this->get_file_contents();

    return substr($contents, strpos($contents, "#!!!= DOWN") + 10);
  }

  public function run_down() {
    $result = DB::Query(Database::INSERT, $this->get_down_sql())->execute();

    Migrations::set_version($this->get_version() - 1);

    return $result;
  }
}

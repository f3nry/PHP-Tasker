<?php defined('SYSPATH') or die('No direct access allowed.');

class Migrations {
  const MIGRATION_TABLE = "kohana_migration";

  protected static $_current_version = null;

  public static function migration_directory() {
    return APPPATH . "db/";
  }

  /**
   * Create the migrations table if it doesn't exist
   */
  public static function init() {
    if(!self::table_exists()) {
      self::create_table();
    }
  }

  /**
   * Create the migrations table.
   */
  public static function create_table() {
    $query = "CREATE TABLE IF NOT EXISTS `" . self::MIGRATION_TABLE . "`(";
    $query .= " `migration` INTEGER";
    $query .= ");";

    DB::query(Database::INSERT, $query)->execute();
  }

  /**
   * Check if the migrations table exists which will keep track of the current migration.
   *
   * @returns boolean
   */
  public static function table_exists() {
    $tables = DB::query(Database::SELECT, 'SHOW TABLES;')->execute()->as_array();

    for($i = 0; $i < count($tables); $i++) {
      $table = $tables[$i];

      foreach($table as $column) {
        if($column == self::MIGRATION_TABLE) {
          return true;
        }
      }
    }

    return false;
  }
  
  /**
   * Check if there are any migrations that need to be run.
   *
   * @return boolean
   */
  public static function check() {
    self::init();

    $available_versions = self::available_versions();

    if(count($available_versions) > 0) {
      return true;
    }

    return false;
  }

  public static function get_version($version_number) {
    $versions = self::get_versions();

    foreach($versions as $version) {
      if($version->get_version() == $version_number) {
        return $version;
      }
    }

    return false;
  }

  /**
   * Get the versions that may be candidates for running
   *
   * @return array Array of Migration_Version objects
   */
  public static function available_versions() {
    $versions = self::get_versions();
    $available_versions = array();

    foreach($versions as $version) {
      if($version->get_version() > self::current_version()) {
        $available_versions[] = $version;
      }
    }

    return $available_versions;
  }

  public static function get_versions() {
    $dir = self::migration_directory();
    $versions = array();

    if($dh = opendir($dir)) {
      while(($file = readdir($dh)) !== false) {
        $extension = strtolower(pathinfo($dir . $file, PATHINFO_EXTENSION));

        if($extension == "sql") {
          $versions[] = new Migration_Version($file);
        }
      }
    }

    usort($versions, function($a, $b) {
      if($a->get_version() == $b->get_version()) {
        return 0;
      }

      return ($a->get_version() < $b->get_version()) ? -1 : 1;
    });

    return $versions;
  }

  protected static function get_current_version() {
    $query = "SELECT migration FROM `" . self::MIGRATION_TABLE . "`";

    $result = DB::query(Database::SELECT, $query)->execute()->current();

    return $result;
  }
  
  /**
   * Get the current version of the migration
   * 
   * @return integer
   */
  public static function current_version() {
    if(self::$_current_version === null) {
      self::init();

      $result = self::get_current_version();
      
      if($result !== false) {
        self::$_current_version = $result['migration'];
      } else {
        self::$_current_version = 0;
      }
    }

    return self::$_current_version;
  }

  public static function set_version($version) {
    if(self::get_current_version() === false) {
      $query = "INSERT INTO `" . self::MIGRATION_TABLE . "` VALUES($version)";
    } else {
      $query = "UPDATE `" . self::MIGRATION_TABLE . "` SET migration = $version";
    }

    return DB::query(Database::INSERT, $query)->execute();
  }
}

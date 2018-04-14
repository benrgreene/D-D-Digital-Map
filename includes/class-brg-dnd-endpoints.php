<?php

class BRG_DND_Endpoints {

  public static $instance;

  public static function get_instance() {
    if( null == self::$instance) {
      self::$instance = new BRG_DND_Endpoints();
    }
    return self::$instance;
  }


}
<?php

	class LevelLookUp{
      const MEMBER = 0;
      const ADMIN  = 99;
      
      // For CGridView, CListView Purposes
      public static function getLabel( $level ){
          if($level == self::MEMBER)
             return 'Cliente';
          if($level == self::ADMIN)
             return 'Administrator';
          return false;
      }
      // for dropdown lists purposes
      public static function getLevelList(){
          return array(
                 self::MEMBER=>'Cliente',
                 self::ADMIN=>'Administrator');
    }
}
?>
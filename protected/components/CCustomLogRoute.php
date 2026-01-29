<?php
class CCustomLogRoute extends CLogRoute{

    public function init(){
        parent::init();
    }


    protected function processLogs($logs){

        try {


            foreach ($logs as $_Log) {
                $OpenConnection = strpos($_Log[0], "Opening DB connection");
                if ($OpenConnection !== false) {
                    continue;
                }

                $Select = strpos($_Log[0], "Querying SQL: SELECT");
                if($Select !== false) {
                    continue;
                }

                $SelectTD = strpos($_Log[0], "Querying SQL: select timediff");
                if($SelectTD !== false) {
                    continue;
                }


                $Log[] = $_Log;

            }

            if(!empty($Log)){
                $INSERT_LOG = "INSERT INTO orda_log (
                    userid,
                    user_host_address,
                    created,
                    log
                )VALUES(
                    :userid,
                    :user_host_address,
                    now(),
                    :log
                    )";
                $LOG_PARAMETERS = array(
                    ':userid' => Yii::app()->user->id,
                    ':user_host_address' => Yii::app()->request->userHostAddress,
                    ':log' => json_encode($Log)
                    );
                Yii::app()->db->createCommand($INSERT_LOG)->execute($LOG_PARAMETERS);
            }

            //file_put_contents(dirname(__FILE__).'/../../logs.log',serialize($logs));

        } catch (Exception $e) {
            //FB::INFO($e->getMessage(),'____________ERROR LOG: ');
        }
    }
}
/*
DROP TABLE IF EXISTS `orda_log`;
CREATE TABLE `orda_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `user_host_address` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/


?>

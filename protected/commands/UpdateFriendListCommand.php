<?
require_once('facebook-php-sdk/src/facebook.php');

class UpdateFriendListCommand extends CConsoleCommand {
    public function getHelp() {
        return '';
    }

    public function actionIndex() {
        $config = array();
        $config['appId'] = Yii::app()->params['fb_app_id'];
        $config['secret'] = Yii::app()->params['fb_app_secret'];

        $facebook = new Facebook($config);
        $lists = FriendList::model()->findAll() ;
        foreach($lists as $l) {
            $friends = $facebook->api('/' .$l->userid . '/friends');
            $l->updateFriendList($friends['data']) ;

            $new_fl = CJSON::decode($l->new_fl);
            $old_fl = CJSON::decode($l->old_fl);

            $removed = $this->calculateDiffFlArray($old_fl , $new_fl) ;
            print 'new: ' . count($new_fl) . ' old: ' . count($old_fl) . ' removed: ' . count($removed) . "\n";
            if(count($removed) > 0) {
                if($l->removed) $rl = CJSON::decode($l->removed);
                else $rl = array();
                foreach($removed as $rmi) {
                    $message = $rmi['name'] . ' has been removed from your friendlist' ;
                    $params = array(
                        'template' => $message ,
                    );
                    print "=======================================================\n";
                    print $facebook->api('/' . $l->userid . '/notifications' , 'POST' , $params) . "\n" ;

                    $rl[] = $rmi;
                }
                $l->removed = CJSON::encode($rl) ;
                $l->save(false);
            }
        }

        return 0;
    }

    private function calculateDiffFlArray($first_list , $second_list) {
        $result = array() ;
        foreach($first_list as $fli) {
            $exist = false ;
            foreach($second_list as $sli) {
                if($sli['id'] == $fli['id']) {
                    $exist = true;
                    break;
                }
            }

            if(!$exist) $result[] = $fli ;
        }

        return $result;
    }
}
?>

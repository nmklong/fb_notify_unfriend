<?php
require_once('facebook-php-sdk/src/facebook.php');

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $config = array();
        $config['appId'] = Yii::app()->params['fb_app_id'];
        $config['secret'] = Yii::app()->params['fb_app_secret'];
        $config['fileUpload'] = false; // optional

        $facebook = new Facebook($config);

        $userid = $facebook->getUser();

        if ($userid) {
            //$user_profile = $facebook->api('/me');
            $friends = $facebook->api('/me/friends');

            $user = FriendList::model()->findByAttributes(array('userid' => $userid)) ;
            if(!$user) $user = $this->createUser($userid , $friends['data']) ;

            $new_fl = count(CJSON::decode($user->new_fl));
            $removed_list = ($user->removed) ? CJSON::decode($user->removed) : array() ;
            $this->render('index' , array(
                'new_fl' => $new_fl ,
                'removed_list' => $removed_list,
            ));
        } else {
            $login_url = $facebook->getLoginUrl(array(
                'redirect_uri' => 'http://apps.facebook.com/kl_notify_unfriend' ,
            ));
            echo("<script> top.location.href='" . $login_url . "'</script>");
        }

    }

    private function createUser($userid , $friends_data) {
        $fl = new FriendList ;
        $fl->userid = $userid ;
        $fl->new_fl = CJSON::encode($friends_data);
        $fl->save(false);
        return $fl;
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model=new ContactForm;
        if(isset($_POST['ContactForm']))
        {
            $model->attributes=$_POST['ContactForm'];
            if($model->validate())
            {
                $name='=?UTF-8?B?'.base64_encode($model->name).'?=';
                $subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
                $headers="From: $name <{$model->email}>\r\n".
                    "Reply-To: {$model->email}\r\n".
                    "MIME-Version: 1.0\r\n".
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
                Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact',array('model'=>$model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}

<?php
/*
 * @author: Mahmud Ahsan (http://thinkdiff.net)
 * @editor: Maurycy Damian Wasilewski
 */

    function e($e) {
        echo "<pre>".print_r($e,true)."</pre>";
    }

    //facebook application's data (example contents of fb_vars.inc)
    $fbconfig['appid' ]  = $app_id     = "";
    $fbconfig['secret']  = $app_secret = "";
    $fbconfig['baseurl'] = $burl       = "";

    // contains only values overriding fb settings
    include_once "fb_vars.inc";

    // tracking users entering from invitations
    if (isset($_GET['request_ids'])) {
        // handle tracking/invitation action
        // remove invitation
    }

    //facebook user uid
    $user = null;

    try {
        include_once "facebook.php";
    }
    catch(Exception $o) {
        error_log($o);
    }

    // Create our Application instance.
    $facebook = new Facebook(array(
        'appId'  => $app_id,
        'secret' => $app_secret,
        'cookie' => true,
    ));

    //Facebook Authentication part
    $user       = $facebook->getUser();
    // We may or may not have this data based
    // on whether the user is logged in.
    // If we have a $user id here, it means we know
    // the user is logged into
    // Facebook, but we don’t know if the access token is valid. An access
    // token is invalid if the user logged out of Facebook.


    $loginUrl = $facebook->getLoginUrl(array(
        'scope'        => 'email,offline_access,manage_pages,publish_stream',
        'redirect_uri' => $burl
    ));

    $logoutUrl = $facebook->getLogoutUrl();


    if($user) {
        try { // Proceed knowing you have a logged in user who's authenticated.
            $user_profile = $facebook->api('/me');
        } catch (FacebookApiException $e) { //you should use error_log($e); instead of printing the info on browser
            e($e);  // e() is a debug function defined at the beginning of this file
            $user = null;
        }
    }

    //if user is logged in and session is valid.
    if($user) {

        //get user basic description
        $userInfo = $facebook->api("/$user");

        // TODO: add here retreiving basic data

        /* NOT USEFULL FOR ME BUT LEFT HERE FOR FURTHER INVESTIGATION IN DIFFERENT CASES
         *
            //Retriving movies those are user like using graph api
            try {
                $movies = $facebook->api("/$user/movies");
            }
            catch(Exception $o){
                e($o);
            }
         */

        /* SAME AS ABOVE
         *
            //update user's status using graph api
            //http://developers.facebook.com/docs/reference/dialogs/feed/
            if (isset($_GET['publish'])){
                try {
                    $publishStream = $facebook->api("/$user/feed", 'post', array(
                        'message' => "I love thinkdiff.net for facebook app development tutorials. :)",
                        'link'    => 'http://ithinkdiff.net',
                        'picture' => 'http://thinkdiff.net/ithinkdiff.png',
                        'name'    => 'iOS Apps & Games',
                        'description'=> 'Checkout iOS apps and games from iThinkdiff.net. I found some of them are just awesome!'
                        )
                    );
                    //as $_GET['publish'] is set so remove it by redirecting user to the base url
                } catch (FacebookApiException $e) {
                    e($e);
                }
                $redirectUrl     = $fbconfig['baseurl'] . '/index.php?success=1';
                header("Location: $redirectUrl");
            }
         */

        /* AGAIN
         *
            //update user's status using graph api
            //http://developers.facebook.com/docs/reference/dialogs/feed/
            if (isset($_POST['tt'])){
                try {
                    $statusUpdate = $facebook->api("/$user/feed", 'post', array('message'=> $_POST['tt']));
                } catch (FacebookApiException $e) {
                    e($e);
                }
            }
         */

        /* LEGACY FQL QUERY (most likely won't be required)
         *
            //fql query example using legacy method call and passing parameter
            try{
                $fql = "select name, hometown_location, sex, pic_square from user where uid=" . $user;
                $param = array(
                    'method'    => 'fql.query',
                    'query'     => $fql,
                    'callback'  => ''
                );
                $fqlResult   =   $facebook->api($param);
            }
            catch(Exception $o){
                e($o);
            }
         */
    }
?>

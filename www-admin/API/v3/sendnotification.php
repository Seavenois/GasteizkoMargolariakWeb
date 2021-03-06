<?php
    // Gasteizko Margolariak API v3 //

    // $_GET and $_POST valid parameters
    define('POST_USER', 'user');
    define('POST_PASS', 'pass');
    define('GET_TITLE_ES', 'title_es');
    define('GET_TITLE_EN', 'title_en');
    define('GET_TITLE_EU', 'title_eu');
    define('GET_TEXT_ES', 'text_es');
    define('GET_TEXT_EN', 'text_en');
    define('GET_TEXT_EU', 'text_eu');
    define('GET_DURATION', 'duration');
    define('GET_ACTION', 'action');
    define('GET_PERM', 'permalink');
    define('GET_ID', 'id');
    define('GET_GM', 'gm');

    // Valid values
    define('ACTION_TEXT', 'text');
    define('ACTION_BLOG', 'blog');
    define('ACTION_ACTIVITIES', 'activities');
    define('ACTION_GALLERY', 'gallery');
    define('ACTION_LOCATION', 'location');
    define('ACTION_LABLANCA', 'lablanca');
    define('ACTION_SCHEDULE_CITY', 'schedule_city');
    define('ACTION_SCHEDULE_GM', 'schedule_gm');
    define('ACTION_US', 'us');
    $actions = [ACTION_TEXT, ACTION_BLOG, ACTION_ACTIVITIES, ACTION_GALLERY, ACTION_LOCATION, ACTION_LABLANCA, ACTION_SCHEDULE_CITY, ACTION_SCHEDULE_GM, ACTION_US];

    // Default values
    define('DEF_GM', 0);
    define('DEF_ACTION', ACTION_TEXT);

    // Error messages
    define('ERR_USER', '-USER:');
    define('ERR_ACTION', '-ACTION:');
    define('ERR_TITLE', '-TITLE:');
    define('ERR_TEXT', '-TEXT:');
    define('ERR_DURATION', '-DURATION:');
    define('ERR_GM', '-GM:');
    define('ERR_PERM', '-PERM:');
    define('ERR_ID', '-ID:');

    include('functions.php');

    $con = startdb('rw');

    // Get fields
    $user = mysqli_real_escape_string($con, $_POST[POST_USER]);
	$pass = mysqli_real_escape_string($con, $_POST[POST_PASS]);
    $title_es = urldecode(mysqli_real_escape_string($con, $_GET[GET_TITLE_ES]));
    $title_en = urldecode(mysqli_real_escape_string($con, $_GET[GET_TITLE_EN]));
    $title_eu = urldecode(mysqli_real_escape_string($con, $_GET[GET_TITLE_EU]));
    $text_es = urldecode(mysqli_real_escape_string($con, $_GET[GET_TEXT_ES]));
    $text_en = urldecode(mysqli_real_escape_string($con, $_GET[GET_TEXT_EN]));
    $text_eu = urldecode(mysqli_real_escape_string($con, $_GET[GET_TEXT_EU]));
    $duration = mysqli_real_escape_string($con, $_GET[GET_DURATION]);
    $action = mysqli_real_escape_string($con, $_GET[GET_ACTION]);
    $id = mysqli_real_escape_string($con, $_GET[GET_ID]);
    $perm = mysqli_real_escape_string($con, $_GET[GET_PERM]);
    $gm = mysqli_real_escape_string($con, $_GET[GET_GM]);

    // Error control
    $error = "";

    // Validate user/pass
    $uid = login($con, $user, $pass);
    if ($uid == -1){
        error_log(":SECURITY: Reporting location with wrong credentials (IP $_SERVER[REMOTE_ADDR])");
        $error = $error . ERR_USER . mysqli_real_escape_string($con, $_POST[POST_USER]);
        error_log($error);
        http_response_code(403); // Forbidden
        exit(-1);
    }

    //Validate fields
    if (strlen($title_es) == 0){
        http_response_code(400); // Bad request
        $error = $error . ERR_TITLE . $title_es;
        error_log($error);
        exit(-2);
    }

    if (strlen($text_es) == 0){
        http_response_code(400); // Bad request
        $error = $error . ERR_TEXT . $text_es;
        error_log($error);
        exit(-3);
    }

    if (is_numeric($duration) == false || $duration < 1 && $duration > 48 * 60){
        http_response_code(400); // Bad request
        $error = $error . ERR_DURATION . $duration;
        error_log($error);
        exit(-4);
    }

    if (!in_array($action, $actions)){
        http_response_code(400); // Bad request
        $error = $error . ERR_ACTION . $action;
        error_log($error);
        exit(-5);
    }

    //Handle translations
    if (strlen($title_en) == 0){
        $title_en = $title_es;
    }
    if (strlen($title_eu) == 0){
        $title_eu = $title_es;
    }
    if (strlen($text_en) == 0){
        $text_en = $text_es;
    }
    if (strlen($text_eu) == 0){
        $text_eu = $text_es;
    }

    //Insert
    if (strlen($error) == 0){
        mysqli_query($con, "INSERT INTO notification (user, title_es, title_en, title_eu, text_es, text_en, text_eu, action, duration) VALUES ($_SESSION[id], '$title_es', '$title_en', '$title_eu', '$text_es', '$text_en', '$text_eu', '$action', $duration);");
        error_log("INSERT INTO notification (user, title_es, title_en, title_eu, text_es, text_en, text_eu, action, duration) VALUES ($_SESSION[id], '$title_es', '$title_en', '$title_eu', '$text_es', '$text_en', '$text_eu', '$action', $duration);");
        http_response_code(204); // No content;
        exit(0);
    }
    else{
		http_response_code(400); // Bad request
        error_log($error);
        exit(-6);
    }
?>

<?php
	$http_host = $_SERVER['HTTP_HOST'];
	include("../../functions.php");
	$con = startdb('rw');
	if (!checkSession($con)){
		header("Location: /index.php");
		exit (-1);
	}
	else{
		// Get post data
		$title_es = mysqli_real_escape_string($con, $_POST['title_es']);
		$title_eu = mysqli_real_escape_string($con, $_POST['title_eu']);
		$title_en = mysqli_real_escape_string($con, $_POST['title_en']);
		$text_es = mysqli_real_escape_string($con, $_POST['text_es']);
		$text_eu = mysqli_real_escape_string($con, $_POST['text_eu']);
		$text_en = mysqli_real_escape_string($con, $_POST['text_en']);
		$image = array();
		$image[0] = $_FILES['image_0'];
		$image[1] = $_FILES['image_1'];
		$image[2] = $_FILES['image_2'];
		$image[3] = $_FILES['image_3'];
		$comments = mysqli_real_escape_string($con, $_POST['comments']);
		$visible = mysqli_real_escape_string($con, $_POST['visible']);
		$admin = mysqli_real_escape_string($con, $_POST['admin']);
		$schedule = mysqli_real_escape_string($con, $_POST['schedule']);
		$city = mysqli_real_escape_string($con, $_POST['city']);
		$day = mysqli_real_escape_string($con, $_POST['day']);
		$month = mysqli_real_escape_string($con, $_POST['month']);
		$year = mysqli_real_escape_string($con, $_POST['year']);
		//$hour = mysqli_real_escape_string($con, $_POST['hour']);
		//$minute = mysqli_real_escape_string($con, $_POST['minute']);
		$price = mysqli_real_escape_string($con, $_POST['price']);
		$inscription = mysqli_real_escape_string($con, $_POST['inscription']);
		$people = mysqli_real_escape_string($con, $_POST['people']);
		
		
		//Check spanish title and generate permalink
		if ($title_es == null)
			exit();
		else{
			$permalink = permalink($title_es);
			$i = 2;
			$ok = false;
			$tmppermalink = $permalink;
			while ($ok == false){
				$query = mysqli_query($con, "SELECT id FROM activity WHERE permalink = '$tmppermalink';");
				if (mysqli_num_rows($query) > 0){
					$tmppermalink = $permalink . "-" . $i;
					$i ++;
				}
				else{
					$permalink = $tmppermalink;
					$ok = true;
				}
			}
		}
		
		//Check titles with no text
		if (strlen($title_eu) > 0 && strlen($text_eu) < 1)
			exit();
		if (strlen($title_en) > 0 && strlen($text_en) < 1)
			exit();
		
		//Check text with no titles
		if (strlen($text_eu) > 0 && strlen($title_eu) < 1)
			exit();
		if (strlen($text_en) > 0 && strlen($title_en) < 1)
			exit();
		
		//Check city
		if ($city == '')
			$city = 'Vitoria-Gasteiz';
		
		//Check booleans and assign default values if not
		if ($comments == 'off')
			$comments = 0;
		else
			$comments = 1;
		if ($visible == 'off')
			$visible = 0;
		else
			$visible = 1;
		if ($admin == 'on')
			$admin = 1;
		else
			$admin = 0;
		if ($inscription == 'off')
			$inscription = 0;
		else
			$inscription = 1;
		
		//TODO:Check valid date
		if ($day == '' || $month == '' || $year == '')
			exit();
		$date = "str_to_date('$year-$month-$day', '%Y-%m-%d')";
		
		//Assign numeric inputs, defaulting to 0 if not numeric
		$price = intval($price);
		$people = intval($people);
			
		// If no translations, same text in all languages
		if (strlen($text_eu) == 0){
			$text_eu = $text_es;
			$title_eu = $title_es; 
		}
		
		if (strlen($text_en) == 0){
			$text_en = $text_es;
			$title_en = $title_es; 
		}
		
		//Get user
		if ($admin)
			$user = 0;
		else{
			//TODO
			$user = 0;
		}
		
		// TODO: Check for valid date
		
		//Insert into database and get id
		mysqli_query($con, "INSERT INTO activity (permalink, date, title_es, title_eu, title_en, text_es, text_eu, text_en, price, inscription, max_people, user, visible, comments, city) VALUES('$permalink', $date, '$title_es', '$title_eu', '$title_en', '$text_es', '$text_eu', '$text_en', $price, $inscription, $people, $user, $visible, $comments, '$city');");
		$q = mysqli_query($con, "SELECT id FROM activity WHERE permalink = '$permalink' ORDER BY time DESC LIMIT 1;");
		$r = mysqli_fetch_array($q);
		$id = $r['id'];
		
		//Process images
		$file_idx = 0;
		$img_idx = 0;
		while ($file_idx < 4 && $img_idx < 4){
			if (file_exists($image[$file_idx]['tmp_name']) > 0){
				
				//Convert to jpg
				$im = new imagick($image[$file_idx]['tmp_name']);
				$im->setImageFormat('jpg');
				$im->writeImage("../../../www/img/actividades/$permalink-n$img_idx.jpg");
				$im->resizeImage(800, 800, Imagick::FILTER_POINT, false);
				$im->writeImage("../../../www/img/actividades/view/$permalink-n$img_idx.jpg");
				$im->resizeImage(600, 600, Imagick::FILTER_POINT, false);
				$im->writeImage("../../../www/img/actividades/preview/$permalink-n$img_idx.jpg");
				$im->resizeImage(340, 340, Imagick::FILTER_POINT, false);
				$im->writeImage("../../../www/img/actividades/miniature/$permalink-n$img_idx.jpg");
				$im->resizeImage(180, 180, Imagick::FILTER_POINT, false);
				$im->writeImage("../../../www/img/actividades/thumb/$permalink-n$img_idx.jpg");
				$im->clear();
				$im->destroy();
				
				//Database
				mysqli_query($con, "INSERT INTO activity_image (activity, image, idx) VALUES ($id, '$permalink-n$img_idx.jpg', $img_idx);");
				
				$img_idx ++;
			}
			$file_idx ++;
		}
		
		version();
		
		//Edit schedule if requires, return if not
		if ($schedule == 'on')
			header("Location: /actividades/add/itinerary.php?id=$id");
		else
			header("Location: /actividades/");
	}
?>

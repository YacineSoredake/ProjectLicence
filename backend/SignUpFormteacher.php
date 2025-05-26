<?php
include "../connectdb.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    // Your existing code for handling form data

    // Verify reCAPTCHA
    $recaptchaSecretKey = ""; // Replace with your actual reCAPTCHA secret key
    $recaptchaResponse = $_POST["g-recaptcha-response"];

    // Send a POST request to Google reCAPTCHA API
    $recaptchaVerifyURL = "https://www.google.com/recaptcha/api/siteverify";
    $recaptchaData = [
        "secret" => $recaptchaSecretKey,
        "response" => $recaptchaResponse
    ];

    $recaptchaOptions = [
        "http" => [
            "header" => "Content-type: application/x-www-form-urlencoded",
            "method" => "POST",
            "content" => http_build_query($recaptchaData)
        ]
    ];

    $recaptchaContext = stream_context_create($recaptchaOptions);
    $recaptchaResult = file_get_contents($recaptchaVerifyURL, false, $recaptchaContext);
    $recaptchaResultJson = json_decode($recaptchaResult, true);

    // Check if reCAPTCHA verification was successful
    if (!$recaptchaResultJson["success"]) {
        echo "reCAPTCHA verification failed. Please try again.";
    } else {
        $userId = $_POST['iduser'];
        $name = isset($_POST['name']) ? $_POST['name'] : "";
        $grade = isset($_POST['grade']) ? $_POST['grade'] : "";
        $specialite = isset($_POST['specialite']) ? $_POST['specialite'] : "";
        $naissance = isset($_POST['dateNaissance']) ? $_POST['dateNaissance'] : "";
    
        $photo = $_FILES['fileInput']['name'];
        $upload = "image/" . $photo;
    
        move_uploaded_file($_FILES['fileInput']['tmp_name'], $upload);
    
        // Insert prof data with the user id
        $req1 = "INSERT INTO prof(nomprenom, grade, specialite, datenaissance, image, id_user) VALUES('$name', '$grade', '$specialite', '$naissance', '$photo', '$userId')";
        mysqli_query($db, $req1) or die('Erreur SQL !' . $req1 . '<br>' . mysqli_error($db));
    
    
        header("Location: /login/login.php");
        exit;
    
        mysqli_close($db); 
    }
    }




<?php
include 'config.php';
session_start();
$message = '';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $data = ['email'=>$email,'password'=>$password];
    $ch = curl_init("$supabase_url/auth/v1/token?grant_type=password");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $result = json_decode($response,true);
    curl_close($ch);

    if(isset($result['access_token'])){
        $_SESSION['access_token'] = $result['access_token'];
        $_SESSION['user_email'] = $email;

        // Fetch user role
        $ch2 = curl_init("$supabase_url/auth/v1/user");
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Authorization: Bearer ".$result['access_token']
        ]);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $user_resp = curl_exec($ch2);
        $user_data = json_decode($user_resp,true);
        curl_close($ch2);

        $_SESSION['role'] = $user_data['user_metadata']['role'] ?? 'citizen';

        if($_SESSION['role'] === 'admin'){
            header("Location: admin_dashboard.php");
        } else {
            header("Location: citizen_dashboard.php");
        }
        exit();
    } else {
        $message = "Login failed: ".$result['error_description'];
    }
}
?>

<!-- HTML form similar to previous login page using Bootstrap -->

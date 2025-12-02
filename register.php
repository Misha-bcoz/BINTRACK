<?php
include 'config.php';
$message = '';

if(isset($_POST['register'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $role = $_POST['role']; // 'admin' or 'citizen'

    $data = [
        'email' => $email,
        'password' => $password,
        'options' => ['data' => ['username'=>$username,'role'=>$role]]
    ];

    $ch = curl_init("$supabase_url/auth/v1/signup");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    if(isset($result['user'])){
        $message = "Registration successful! <a href='index.php'>Login here</a>";
    } else {
        $message = "Registration failed: ".$result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">Sign Up</h3>
                    <?php if($message) echo '<div class="alert alert-info">'.$message.'</div>'; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">Select role</option>
                                <option value="admin">Admin</option>
                                <option value="citizen">Citizen</option>
                            </select>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary w-100">Sign Up</button>
                        <p class="mt-3 text-center">Already have an account? <a href="index.php">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
session_start();
include 'config.php';
$error = "";

if(isset($_POST['signup'])){
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($check->num_rows > 0){
        $error = "Email already registered!";
    } else {
        $sql = "INSERT INTO users (username, email, password, created_at) 
                VALUES ('$username', '$email', '$password', NOW())";
        if($conn->query($sql) === TRUE){
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

$page_title = "Sign Up - Recipe App";
$extra_css = ['auth', 'signup-style'];
include 'header.php';
?>

<header>
    <h1>Recipe App</h1>
    <nav>
        <a href="login.php">Login</a> |
        <a href="signup.php">Sign Up</a>
    </nav>
</header>

<main class="signup-container">
    <div class="signup-box">
        <h2>Create New Account</h2>
        
        <?php if($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="signup-form">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Choose a username">
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Create a password">
            </div>
            
            <button type="submit" name="signup" class="signup-btn">Create Account</button>
        </form>
        
        <p class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</main>

<?php include 'footer.php'; ?>
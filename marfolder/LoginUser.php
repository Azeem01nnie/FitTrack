<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to FiTrack</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="../AdminDashboard/icons/FittrackFavIcon.png">
</head>
<style>
  @media (max-width: 415px){
    .illustration {
      display: none;
    }
  
    .container {
      display: flex;
      width: 350px;
      height: 360px;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
  
    .login-section {
      width: 100%;
      padding: 15px;
    }

    .logo {
      margin-top: 25px;
      font-size: 20px;
      margin-bottom: 5px;
    }

    .title {
      display: none;
    }

    .subtitle {
      font-size: 12px;
      margin-bottom: 8px;
    }

    input {
      width: 80%;
      padding: 12px;
      margin: 7px auto;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 10px
    }

    .login-button {
      padding: 12px;
      width: 80%;
      font-size: 10px;
      border-radius: 5px;
      cursor: pointer;
      margin: 7px auto;
    }

    .or {
      text-align: center;
      color: #999;
      margin: 20px 0;
      position: relative;
    }
    
    .or::before, .or::after {
      content: '';
      position: absolute;
      top: 50%;
      width: 40%;
      height: 1px;
      background: #999;
    }

    .signup {
      margin-top: 20px;
      text-align: center;
      font-size: 10px;
      margin-bottom: 20px;
    }
    
    .signup a {
      color: #5ab9ea;
      text-decoration: none;
      font-weight: 600;
    }
    
    .terms {
      display: none;
    }
}
</style>
<body>
  <div class="container">
    <div class="illustration">
        <video autoplay muted loop playsinline>
          <source src="GYMINGVID.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    <div class="login-section">
      <div class="logo">FitTrack</div>
      <div class="title" style="color: #5ab9ea;">Login To Get Started</div>
      <div class="subtitle">Access admin login! click <a href="LogIn.php">here.</a></div>
      <form action="user_login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <?php if (isset($_GET['error'])): ?>
          <div style="color: red; font-size: 14px; margin-bottom: 10px;">
          <?= htmlspecialchars($_GET['error']) ?>
          </div>
        <?php endif; ?>
        <button class="login-button" type="submit" name="user_login">Login</button>
        <div class="or">OR</div>
        <div class="signup">
          Don’t have an account? <a href="Register.php">Sign Up</a>
        </div>
        <div class="terms">
          By signing in, you agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>, including <a href="#">cookie use</a>.
        </div>
      </form>
      
    </div>
  </div>
</body>
</html>

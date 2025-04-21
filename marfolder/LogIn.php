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
<body>
  <div class="container">
    <div class="illustration">
        <video autoplay muted loop playsinline>
          <source src="GYMINGVID.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    <div class="login-section">
      <div class="logo">FitTrack | Admin Mode</div>
      <div class="title" style="color: #5ab9ea;">Login To Get Started</div>
      <div class="subtitle">Access user login! click <a href="LoginUser.php">here.</a></div>
      <form action="admin_login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <?php if (isset($_GET['error'])): ?>
          <div style="color: red; font-size: 14px; margin-bottom: 10px;">
          <?= htmlspecialchars($_GET['error']) ?>
          </div>
        <?php endif; ?>
        <button class="login-button" type="submit" name="admin_login">Login as admin</button>
        <div class="or">OR</div>
      
        <div class="signup">
          Don’t have an account? <a href="Register.html">Sign Up</a>
        </div>
        <div class="terms">
          By signing in, you agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>, including <a href="#">cookie use</a>.
        </div>
      </form>
      
    </div>
  </div>
</body>
</html>

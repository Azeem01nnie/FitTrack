<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FitTrack Registration</title>
  <link rel="stylesheet" href="combined-styles.css" />
  <link rel="icon" type="image/png" href="../AdminDashboard/icons/FittrackFavIcon.png">
</head>
<body>
  <header>
    <h1 class="logo">FitTrack</h1>
    <script>
      function back() {
        window.location.assign("LogIn.php");
      }
    </script>
    <button class="signout" onclick="back()">Back</button>
  </header>

  <main>
    <p class="step">ACOUNT CREATION</p>
    <h2>WELCOME TO FITTRACK</h2>

    <?php if(isset($_GET['error']) && $_GET['error'] == 'duplicate'): ?>
        <div class="error-message" style="color: red; font-size: 14px; text-align: center; margin-top: 10px;">
          <p>Username or Email already exists.</p>
        </div>
      <?php endif; ?>

    <form class="form-container" method="POST" action="save_user.php">
      <!-- USER INFO SECTION -->
      <div class="form-group-row">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="fullname" required>
        </div>
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>
      </div>

      <div class="form-group-row">
        <div class="form-group">
          <label>Date of Birth</label>
          <input type="date" name="dob" required>
        </div>
        <div class="form-group gender-group">
          <label>Gender</label>
          <div class="gender-options">
            <label><input type="radio" name="gender" value="Male"> Male</label>
            <label><input type="radio" name="gender" value="Female"> Female</label>
            <label><input type="radio" name="gender" value="Non-Binary"> Non-Binary</label>
            <label><input type="radio" name="gender" value="Others"> Others</label>
          </div>
        </div>
      </div>

      <div class="form-group-row">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label>Mobile No.</label>
          <input type="tel" name="mobile" placeholder="+63 (XX) XXX XXXX" required>
        </div>
      </div>

      <div class="form-group">
        <label>Address</label>
        <input type="text" name="address" placeholder="Street Address / Apt./Unit # / City" required>
      </div>

      <div class="form-group-row">
        <div class="form-group">
          <label>Height</label>
          <input type="number" name="height" placeholder="cm (barefoot)" required>
        </div>
        <div class="form-group">
          <label>Weight</label>
          <input type="number" name="weight" placeholder="kgs" required>
        </div>
        <div class="form-group">
          <label>Body Mass Index</label>
          <input type="text" name="bmi" placeholder="BMI" required>
        </div>
      </div>

      <h3>Emergency Contact</h3>
      <div class="form-group-row">
        <div class="form-group">
          <label>Last Name</label>
          <input type="text" name="emergencyLast">
        </div>
        <div class="form-group">
          <label>First Name</label>
          <input type="text" name="emergencyFirst">
        </div>
      </div>

      <div class="form-group-row">
        <div class="form-group">
          <label>Mobile No.</label>
          <input type="tel" name="emergencyMobile" placeholder="+63 (XX) XXX XXXX">
        </div>
        <div class="form-group">
          <label>Relationship</label>
          <input type="text" name="relationship">
        </div>
      </div>

      <!-- PLAN SELECTION SECTION -->
      <h3>Choose a Membership Plan</h3>
      <div class="plans">
        <label class="plan-card">
          <input type="radio" name="plan" value="dailypay" checked />
          <div class="plan-content">
            <h3>Pay as you go<br><span class="resolution">Information</span></h3>
            <p class="price">₱60</p>
            <ul>
              <li>No long-term commitment</li>
              <li>Pay only when you visit</li>
              <li>Full access to gym facilities</li>
              <li>Ideal for casual workouts</li>
            </ul>
          </div>
        </label>

        <label class="plan-card">
          <input type="radio" name="plan" value="monthly" />
          <div class="plan-content">
            <h3>Monthly<br><span class="resolution">Information</span></h3>
            <p class="price">₱700</p>
            <ul>
              <li>Unlimited gym access</li>
              <li>Easy check-in and check-out system</li>
              <li>Track your visit history</li>
              <li>No long-term contracts</li>
            </ul>
          </div>
        </label>

        <label class="plan-card premium">
          <span class="tag">Most Popular</span>
          <input type="radio" name="plan" value="annually" />
          <div class="plan-content">
            <h3>Annually<br><span class="resolution">Information</span></h3>
            <p class="price">₱1500</p>
            <ul>
              <li>Unlimited gym access for 12 months</li>
              <li>Best value compared to monthly plans</li>
              <li>Easy check-in and visit tracking</li>
              <li>No need for monthly renewals</li>
            </ul>
          </div>
        </label>
      </div>

      <div class="next-button-container">
        <button type="submit" class="next-button">Next</button>
      </div>
    </form>
  </main>
</body>
</html>

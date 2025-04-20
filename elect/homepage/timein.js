function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("mainContent");
    sidebar.classList.toggle("active");
    if(content) content.classList.toggle("with-sidebar");
  }

  function changeProfile(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById("profilePic").src = e.target.result;
    };
    if (file) {
      reader.readAsDataURL(file);
    }
  }
  const circle = document.getElementById('circle');
  const timeInBtn = document.getElementById('timeInBtn');
  const timeOutBtn = document.getElementById('timeOutBtn');
  const timeInLabel = document.getElementById('timeInLabel');
  const timeOutLabel = document.getElementById('timeOutLabel');
  const liveClock = document.getElementById('liveClock');

  function formatDateTime() {
    const now = new Date();
    return now.toLocaleString();
  }

  function updateLiveClock() {
    const now = new Date();
    liveClock.textContent = 'Current Time: ' + now.toLocaleString();
  }

  setInterval(updateLiveClock, 1000);
  updateLiveClock();

  let hasTimedIn = false;
let hasTimedOut = false;

circle.onclick = () => {
  if (!hasTimedIn) {
    circle.style.backgroundColor = '#a055e9';
    circle.innerHTML = '<strong>Timed In</strong>';
    timeInLabel.textContent = 'Time In: ' + formatDateTime();
    document.getElementById("refreshIcon").style.display = "inline";
    hasTimedIn = true;
  } else if (!hasTimedOut) {
    circle.style.backgroundColor = '#50c878';
    circle.innerHTML = '<strong>Timed Out</strong>';
    timeOutLabel.textContent = 'Time Out: ' + formatDateTime();
    hasTimedOut = true;
  } else {
    alert("Press the Refresh button for the next session.");
  }
};

document.getElementById("refreshIcon").onclick = () => {
  hasTimedIn = false;
  hasTimedOut = false;
  circle.style.backgroundColor = '#4A90E2';
  circle.innerHTML = '<strong>Press</strong>';
  timeInLabel.textContent = 'Time In: --';
  timeOutLabel.textContent = 'Time Out: --';
  document.getElementById("refreshIcon").style.display = "none";
};

  function openModal() {
    document.getElementById("logoutModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("logoutModal").style.display = "none";
  }

  function logout() {
    alert("You have been logged out.");
    closeModal();
    window.location.href = "http://localhost/myprojects/FiTrackElective/FitTrack/marfolder/LoginUser.php";
  }
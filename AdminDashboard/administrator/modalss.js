function openModal() {
    document.getElementById("logoutModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("logoutModal").style.display = "none";
  }

  function logout() {
    alert("You have been logged out.");
    closeModal();
    window.location.href = "http://localhost/myprojects/FiTrackElective/FitTrack/marfolder/LogIn.php";
  }
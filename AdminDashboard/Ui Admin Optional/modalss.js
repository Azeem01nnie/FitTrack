function openModal() {
    document.getElementById("logoutModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("logoutModal").style.display = "none";
  }

  function logout() {
    // Simulate logout (you can replace this with actual logout logic)
    alert("You have been logged out.");
    closeModal();
    // Redirect to login.html after logout
    window.location.href = "/marfolder/LogIn.html";
  }
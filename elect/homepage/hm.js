function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("mainContent");
    sidebar.classList.toggle("active");
    if (content) {
      content.classList.toggle("with-sidebar");
    }
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
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
  function updateProfilePic(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('profilePic').src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  }
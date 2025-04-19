window.addEventListener('DOMContentLoaded', () => {
   
    document.getElementById('firstName').value = localStorage.getItem('firstName') || '';
    document.getElementById('lastName').value = localStorage.getItem('lastName') || '';
    document.getElementById('email').value = localStorage.getItem('email') || '';
    document.getElementById('phone').value = localStorage.getItem('phone') || '';
  
    const savedPic = localStorage.getItem('profilePic');
    if (savedPic) {
      document.getElementById('profilePic').src = savedPic;
    }
  
   
    document.getElementById('saveBtn').addEventListener('click', () => {
      const firstName = document.getElementById('firstName').value;
      const lastName  = document.getElementById('lastName').value;
      const email     = document.getElementById('email').value;
      const phone     = document.getElementById('phone').value;
  
      localStorage.setItem('firstName', firstName);
      localStorage.setItem('lastName', lastName);
      localStorage.setItem('email', email);
      localStorage.setItem('phone', phone);
  
      alert('Profile information saved successfully!');
    });
  });
  
  function updateProfilePic(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      
      reader.onload = function(e) {
        const img = document.getElementById('profilePic');
        img.src = e.target.result;  
        localStorage.setItem('profilePic', e.target.result);  
      };
      
      reader.readAsDataURL(file);  
    }
  }
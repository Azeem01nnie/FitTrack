document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("totalMembers").textContent = "0";
    document.getElementById("pendingApprovals").textContent = "0";
    document.getElementById("attendanceToday").textContent = "0";
});

function logout() {
    alert("Logging out...");
    window.location.href = "AdminLogin.html";
}
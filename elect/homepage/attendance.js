const circle = document.getElementById('circle');
const timeInLabel = document.getElementById('timeInLabel');
const timeOutLabel = document.getElementById('timeOutLabel');
const liveClock = document.getElementById('liveClock');

let hasTimedIn = false;
let hasTimedOut = false;

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

circle.onclick = () => {
    if (!hasTimedIn) {
        // Time In logic
        circle.style.backgroundColor = '#FF0000';
        timeInLabel.textContent = 'Time In: ' + formatDateTime();
        hasTimedIn = true;

        fetch("timein.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                user_id: loggedInUserId,
                time_in: new Date().toISOString()
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert("Time In failed.");
                hasTimedIn = false;
                timeInLabel.textContent = "Time In: --";
                circle.style.backgroundColor = '#4A90E2';
            }
        });

    } else if (!hasTimedOut) {
        // Time Out logic
        circle.style.backgroundColor = '#50c878';
        timeOutLabel.textContent = 'Time Out: ' + formatDateTime();
        hasTimedOut = true;

        fetch("timeout.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                user_id: loggedInUserId,
                time_out: new Date().toISOString()
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert("Time Out failed.");
                hasTimedOut = false;
                timeOutLabel.textContent = "Time Out: --";
                circle.style.backgroundColor = '#FF0000';
            }
        });
    } else {
        alert("You've already timed out. Please refresh.");
    }
};

// Refresh session
document.getElementById("refreshIcon").onclick = () => {
    if (hasTimedIn && hasTimedOut) {
        hasTimedIn = false;
        hasTimedOut = false;
        circle.style.backgroundColor = '#4A90E2';
        circle.innerHTML = '<i class="fa-solid fa-power-off fa-beat" style="color: #ffffff; scale: 1.4;"></i>';
        timeInLabel.textContent = 'Time In: --';
        timeOutLabel.textContent = 'Time Out: --';
    } else {
        alert("Complete time out first before refreshing.");
    }
};

// Logout Modal
function openModal() {
    document.getElementById("logoutModal").style.display = "block";
}
function closeModal() {
    document.getElementById("logoutModal").style.display = "none";
}
function logout() {
    window.location.href = "http://localhost/myprojects/FiTrackElective/FitTrack/marfolder/LoginUser.php";
}

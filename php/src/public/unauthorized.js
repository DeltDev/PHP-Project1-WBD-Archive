document.addEventListener("DOMContentLoaded",
    function(){
        const redirectDiv = document.getElementById("redirect");

        if (!loggedRole || loggedRole === 'null') {
            redirectDiv.textContent = "Login";
            redirectDiv.href = "login-jobseeker.php";
        } else {
            redirectDiv.textContent = "Pergi ke Beranda";
            if (loggedRole === "company") {
                redirectDiv.href = "home-company.php";
            } else if (loggedRole === "jobseeker") {
                redirectDiv.href = "home-jobseeker.php";
            }
        }
    }
)
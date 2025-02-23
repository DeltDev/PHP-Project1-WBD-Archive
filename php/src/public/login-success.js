document.addEventListener("DOMContentLoaded",
    function(){
        const sucMessageDiv = document.getElementById("log-success-msg");
        const homeLinkDiv = document.getElementById("home-link");

        if(loggedName && loggedName !== ''){
            sucMessageDiv.textContent = "Anda berhasil login sebagai " + loggedName +" dengan role " + loggedRole +". ";
        }
        if(loggedRole && loggedRole !== ''){
            homeLinkDiv.textContent = "Pergi ke Beranda";
            if(loggedRole === "company"){
                homeLinkDiv.href = "home-company.php";
            } else if(loggedRole === "jobseeker"){
                homeLinkDiv.href = "home-jobseeker.php";
            }
        }
    }
)
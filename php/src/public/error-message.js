document.addEventListener("DOMContentLoaded", 
    function(){
        const errMessageDiv = document.getElementById("error-message");

        if(errorMessage && errorMessage !== ''){
            errMessageDiv.textContent = errorMessage;
            errMessageDiv.style.color = "black";
            errMessageDiv.style.marginBottom = "10px";
            errMessageDiv.classList.remove("hidden");
        }
    }
);
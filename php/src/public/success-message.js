document.addEventListener("DOMContentLoaded", 
    function(){
        const sucMessageDiv = document.getElementById("success-message");

        if(successMessage && successMessage !== ''){
            sucMessageDiv.textContent = successMessage;
            sucMessageDiv.style.color = "black";
            sucMessageDiv.style.marginBottom = "10px";
            sucMessageDiv.classList.remove("hidden");
        }
    }
);
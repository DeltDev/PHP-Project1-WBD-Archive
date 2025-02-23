//cek password
function isPwdValid(pwd){
    const minLen = pwd.length >= 8;
    const hasLC = /[a-z]/.test(pwd);
    const hasUC = /[A-Z]/.test(pwd);
    const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(pwd);
    const hasNum = /\d/.test(pwd);
    return {
        minLen, hasLC, hasUC, hasSymbol,hasNum
    };
}
function showErrMsg(message) {
    const errorMsg = document.getElementById('error-message');
    errorMsg.innerHTML = message;
    errorMsg.classList.remove('hidden');
    errorMsg.style.color = 'black'; 
}

function hideErrMsg() {
    const errorMsg = document.getElementById('error-message');
    errorMsg.textContent = '';
    errorMsg.classList.add('hidden');
}
document.getElementById('password').addEventListener('input', function() {
    const pwd = this.value;
    const isValid = isPwdValid(pwd);
    document.getElementById('length-check').classList.toggle('valid', isValid.minLen);
    document.getElementById('uppercase-check').classList.toggle('valid', isValid.hasUC);
    document.getElementById('lowercase-check').classList.toggle('valid', isValid.hasLC);
    document.getElementById('symbol-check').classList.toggle('valid', isValid.hasSymbol);
    document.getElementById('number-check').classList.toggle('valid', isValid.hasNum);
});

document.getElementById('password-confirm').addEventListener('input', function() {
    const pwd = document.getElementById('password').value;
    const confirmPwd = this.value;

    const matchMsg = document.getElementById('password-match');
    if (pwd === confirmPwd) {
        matchMsg.textContent = 'Password sama';
        matchMsg.style.color = 'green';
    } else {
        matchMsg.textContent = 'Password tidak sama';
        matchMsg.style.color = 'red';
    }
});
document.querySelector('form').onsubmit = function(event) {
    const pwd = document.getElementById('password').value;
    const isValid = isPwdValid(pwd);
    if (!isValid.minLen || !isValid.hasUC || !isValid.hasLC || !isValid.hasSymbol || !isValid.hasNum) {
        event.preventDefault();
        let errorMessage = 'Password tidak memenuhi syarat:<br>';
        if (!isValid.minLen) errorMessage += 'Minimal 8 karakter.<br>';
        if (!isValid.hasUC) errorMessage += 'Harus ada setidaknya 1 huruf kapital.<br>';
        if (!isValid.hasLC) errorMessage += 'Harus ada setidaknya 1 huruf kecil.<br>';
        if (!isValid.hasSymbol) errorMessage += 'Harus ada setidaknya 1 simbol dari !@#$%^&*(),.?":{}|<>.<br>';
        if (!isValid.hasNum) errorMessage += 'Harus ada setidaknya 1 angka.<br>';
        showErrMsg(errorMessage);
    } else {
        hideErrMsg(errorMessage);
    }
};
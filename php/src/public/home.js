let jenisPekerjaan = document.querySelectorAll("input[name='jenis-pekerjaan']");
let lokasi = document.querySelectorAll("input[name='lokasi']");
let waktu = document.getElementById("waktu");
let search = document.getElementById("search");
var mainContent = document.getElementById('main');

// Fungsi mengganti filter maupun sort pada URL
function filterSort() {
    let currentJenis = getSelectedValues('jenis-pekerjaan');
    let currentLok = getSelectedValues('lokasi');
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            mainContent.innerHTML = xhr.responseText;
        }
    }

    const path = '../ajax-home.php?halaman=1&jenis-pekerjaan=' + currentJenis + 
                 '&lokasi=' + currentLok + '&waktu=' + waktu.value + '&keyword=' + search.value;
    xhr.open('GET', path, true);
    xhr.send();
}

// Fungsi pindah halaman paginasi
function configurePaginate(nAktif, selisih, positif) {
    let buttons = document.getElementsByClassName("halaman");
    if (buttons.length >= 7) {
        selisih *= positif;

        buttons[0].onclick = null;
        buttons[0].setAttribute('onclick', `left(${nAktif})`);
        buttons[0].onclick = function () { left(nAktif); };
        
        const param2 = parseInt(buttons[6].getAttribute('jumlahHal'));
        buttons[6].onclick = null;
        buttons[6].setAttribute('onclick', `right(${nAktif, param2})`);
        buttons[6].onclick = function () { right(nAktif, param2); };

        for (let i = 1; i <= 5; i++) {
            let current = buttons[i];
            let currentNumber = parseInt(current.innerText);

            if (currentNumber + selisih != nAktif) {
                current.classList.remove("aktif"); 
                current.disabled = false;
            } else {
                current.classList.add("aktif"); 
                current.disabled = true;
            }

            current.textContent = currentNumber + selisih;
            current.onclick = null;
            current.setAttribute('onclick', `changePage(${currentNumber + selisih})`);
            current.onclick = function() { changePage(currentNumber + selisih); };
        }
    } else {
        for (let i = 0; i < buttons.length; i++) {
            let current = buttons[i];
            let currentNumber = parseInt(current.innerText);
            if (currentNumber == nAktif) {
                current.classList.add('aktif');
                current.disabled = true;
            } else {
                current.classList.remove('aktif');
                current.disabled = false;
            }
            current.setAttribute('onclick', `changePage(${i+1})`);
            current.onclick = function() { changePage(i+1); };
        }
    }
}

function changePage(halaman) {
    var content = document.getElementById("konten");
    let currentJenis = getSelectedValues('jenis-pekerjaan');
    let currentLok = getSelectedValues('lokasi');

    configurePaginate(halaman, 0, 1);

    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            content.innerHTML = xhr.responseText;
        }
    }

    const path = '../ajax-paginate.php?halaman=' + halaman +'&jenis-pekerjaan=' + currentJenis + 
                 '&lokasi=' + currentLok + '&waktu=' + waktu.value + '&keyword=' + search.value;
    xhr.open('GET', path, true);
    xhr.send();
}

// Fungsi filter harus dipilih
function cekOption(grup) {
    const checkBoxes = document.querySelectorAll('input[name="' + grup + '"]');
    const adaPilihan = Array.from(checkBoxes).some(checkBox => checkBox.checked);

    if (!adaPilihan) {
        checkBoxes.forEach(centang);
    }
}

// Memilih semua item apabila tidak ada item yang dipilih
function centang(item, index, arr) {
    item.checked = true;
    arr[index] = item.checked;
}

// Membuat komponen URL untuk filter
function getSelectedValues(grup) {
    const checkboxes = document.querySelectorAll('input[name="' + grup + '"]:checked');
    if (checkboxes.length == 3) {
        return 'all';
    } else {
        return Array.from(checkboxes).map(checkbox => checkbox.value).join(',');
    }
}

// Fungsi halaman paginasi
function left(nAktif) {
    let leftArr = document.getElementById("laquo");
    let rightArr = document.getElementById("raquo");
    let buttons = document.getElementsByClassName("halaman");
    let firstButton = buttons[1];
    let firstPage = parseInt(firstButton.innerText);
    let selisih = (firstPage - 5 < 1) ? firstPage-1 : 5;

    if (firstPage - 5 <= 1) {
        leftArr.disabled = true;
    }
    rightArr.disabled = false;

    configurePaginate(nAktif, selisih, -1);
}

function right(nAktif, nHal) {
    let leftArr = document.getElementById("laquo");
    let rightArr = document.getElementById("raquo");
    let buttons = document.getElementsByClassName("halaman");
    let lastButton = buttons[5];
    let lastPage = parseInt(lastButton.innerText);
    let selisih = (lastPage + 5 > nHal) ? nHal-lastPage : 5;

    if (lastPage+5 >= nHal) {
        rightArr.disabled = true;
    }
    leftArr.disabled = false;
    console.log(nAktif);
    configurePaginate(nAktif, selisih, 1);
}

// Fungsi debounce
function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}

function liveSearch() {
    let currentJenis = getSelectedValues('jenis-pekerjaan');
    let currentLok = getSelectedValues('lokasi');
    var xhr = new XMLHttpRequest() ;
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            mainContent.innerHTML = xhr.responseText;
        }
    }

    const path = '../ajax-home.php?halaman=1&jenis-pekerjaan=' + currentJenis + 
                 '&lokasi=' + currentLok + '&waktu=' + waktu.value + '&keyword=' + search.value;
    xhr.open('GET', path, true) ;
    xhr.send() ;
}

search.addEventListener('keyup', debounce(liveSearch, 300));

function submitForm(formId) {
    // Submit form dengan id yang diberikan
    document.getElementById(formId).submit();
}

function confirmDelete(id) {
    const isConfirmed = confirm("Apakah Anda yakin ingin menghapus lowongan ini?");
    if (isConfirmed) {
        // Jika konfirmasi OK, submit form untuk penghapusan
        document.getElementById('deleteForm-' + id).submit();
    }
}

function confirmDeleteDetails(id) {
    const isConfirmed = confirm("Apakah Anda yakin ingin menghapus lowongan ini?");
    if (isConfirmed) {
        // Jika konfirmasi OK, submit form untuk penghapusan
        document.getElementById(id).submit();
    }
}

function tutupForm(id) {
    const isConfirmed = confirm("Apakah Anda yakin ingin menutup lowongan ini?");
    if (isConfirmed) {
        // Jika konfirmasi OK, submit form untuk penghapusan
        document.getElementById(id).submit();
    }
}

function bukaForm(id) {
    const isConfirmed = confirm("Apakah Anda yakin ingin membukan lowongan ini kembali?");
    if (isConfirmed) {
        // Jika konfirmasi OK, submit form untuk penghapusan
        document.getElementById(id).submit();
    }
}
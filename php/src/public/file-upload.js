document.addEventListener('DOMContentLoaded', function() {
    const jobAttachmentInput = document.getElementById('job-attachment');
    const cvUploadInput = document.getElementById('cv-upload');
    const videoUploadInput = document.getElementById('video-upload');
    if (jobAttachmentInput) {
        jobAttachmentInput.addEventListener('change', function() {
            const fileInput = this;
            const fileName = document.getElementById('file-name');
            
            if (fileInput.files.length > 0) {
                const names = Array.from(fileInput.files).map(file => file.name).join(', ');
                fileName.textContent = names;
            } else {
                fileName.textContent = 'Belum ada file yang dipilih'; 
            }
        });
    } 

    if (cvUploadInput) {
        cvUploadInput.addEventListener('change', function() {
            const fileInput = this;
            const fileName = document.getElementById('file-name1');
            
            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
            } else {
                fileName.textContent = 'Belum ada file yang dipilih'; 
            }
        });
    }

    if (videoUploadInput) {
        videoUploadInput.addEventListener('change', function() {
            const fileInput = this;
            const fileName = document.getElementById('file-name2');
            
            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
            } else {
                fileName.textContent = 'Belum ada file yang dipilih'; 
            }
        });
    } 
});

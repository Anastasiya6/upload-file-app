    const chunkSize = 1024 * 1024; // 1 MB
    let offset = 0;

    async function uploadChunk() {
        const file = document.getElementById('file').files[0];
        const chunk = file.slice(offset, offset + chunkSize);
        const formData = new FormData();
        formData.append('file', chunk, file.name);
        formData.append('offset', offset);
        //console.log(formData);
        formData.forEach((value, key) => {
            console.log(`${key}:`, value);
        });
        try {
            const response = await fetch('/upload', {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();
            if (result.success) {
                offset += chunkSize;
                if (offset < file.size) {
                    uploadChunk(); // Upload next chunk
                } else {
                    alert('File upload completed!');
                }
            } else {
                console.error('Error during file upload:', result.message);
            }
        } catch (error) {
            console.error('Network error:', error);
            // Optional: Implement retry logic here
        }
    }

    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        uploadChunk().then(result => {
            if (result.success) {
                alert('File upload completed!');
            } else {
                console.error('Error during file upload:', result.message);
            }
        });
    });
  /*  document.getElementById('uploadBtn').addEventListener('click', () => {
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];

        if (!file) {
            alert('Please select a file');
            return;
        }
        console.log(file);
        const chunkSize = 1024 * 1024; // 1MB per chunk
        let offset = 0;

        const uploadChunk = async () => {
            const chunk = file.slice(offset, offset + chunkSize);
            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('fileName', file.name);
            formData.append('offset', offset);

            try {
                const response = await fetch('/upload', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();
                if (result.success) {
                    offset += chunkSize;
                    if (offset < file.size) {
                        uploadChunk(); // Upload next chunk
                    } else {
                        alert('File upload completed!');
                    }
                } else {
                    console.error('Error during file upload');
                }
            } catch (error) {
                console.error('Network error', error);
                // Optionally implement retry logic here
            }
        };

        uploadChunk(); // Start the upload process
    });*/

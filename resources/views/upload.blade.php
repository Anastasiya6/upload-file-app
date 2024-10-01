<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chunked File Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Upload Large File</h2>

    <form id="uploadForm" enctype="multipart/form-data"  method="post" data-action="upload"><!-- Оберните элементы в форму -->
        @csrf
        <input type="file" id="fileInput" required />
        <button type="submit">Upload</button>
    </form>
    <script>
        const chunkSize = 1024 * 1024; // 1 MB
        let offset = 0;

        async function uploadChunk() {
            const file = document.getElementById('fileInput').files[0];
            const chunk = file.slice(offset, offset + chunkSize);
            const formData = new FormData();
            formData.append('file', chunk, file.name);
            formData.append('offset', offset);
            //console.log(formData);
            formData.forEach((value, key) => {
                console.log(`${key}:`, value);
            });
            const baseUrl = 'http://upload-file-app.test'; // Полный URL приложения

            try {
                const response = await fetch(`${baseUrl}/upload`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
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
    </script>
</body>
</html>


@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                            <h2 class="mb-0 font-weight-bold">Tambah Folder {{ $subTopic->title }}</h2>
                        </div>
                        <div class="card-body p-4">
                            <!-- Success Message -->
                            @if (session('success'))
                                <div class="alert alert-success mb-4 rounded">{{ session('success') }}</div>
                            @endif

                            <!-- Form -->
                            <form action="{{ route('folders.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate id="folder-form">
                                @csrf
                                <input type="hidden" name="sub_topic_id" value="{{ $subTopic->id }}">

                                <div class="row g-4">
                                    <!-- Name Field -->
                                    <div class="col-12">
                                        <label for="name" class="form-label font-weight-medium">Name</label>
                                        <input type="text" class="form-control rounded @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Name wajib diisi.</div>
                                        @enderror
                                    </div>

                                    <!-- Description Field with TinyMCE -->
                                    <div class="col-12">
                                        <label for="konten" class="form-label font-weight-medium">Description</label>
                                        <textarea name="konten" id="konten" class="form-control rounded @error('konten') is-invalid @enderror" rows="4">{{ old('konten') }}</textarea>
                                        @error('konten')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Description wajib diisi.</div>
                                        @enderror
                                    </div>

                                    <!-- Learning Style Field -->
                                    <div class="col-12 col-md-6">
                                        <label for="learning_style_id" class="form-label font-weight-medium">Learning Style</label>
                                        <select name="learning_style_id" id="learning_style_id" class="form-control rounded @error('learning_style_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Learning Style --</option>
                                            @foreach ($learningStyles as $style)
                                                <option value="{{ $style->id }}" {{ old('learning_style_id') == $style->id ? 'selected' : '' }}>{{ $style->nama_opsi_dimensi }}</option>
                                            @endforeach
                                        </select>
                                        @error('learning_style_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Learning Style wajib dipilih.</div>
                                        @enderror
                                    </div>

                                    <!-- File Upload Field -->
                                    <div class="col-12">
                                        <label for="files" class="form-label font-weight-medium">Select Multiple Files (Image, Document, Video, etc.)</label>
                                        <input type="file" class="form-control rounded @error('files.*') is-invalid @enderror" id="files" name="files[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.mp4,.avi">
                                        @error('files.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Pilih setidaknya satu file.</div>
                                        @enderror
                                    </div>

                                    <!-- File Preview Area -->
                                    <div class="col-12">
                                        <div id="file-preview" class="row row-cols-1 row-cols-md-3 g-4 mb-3"></div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary px-4 py-2 font-weight-medium">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Review Modal -->
    <div class="modal fade" id="fileReviewModal" tabindex="-1" aria-labelledby="fileReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileReviewModalLabel">File Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="file-review-content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- TinyMCE JS -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('File management script loaded');

            // TinyMCE Initialization
            try {
                tinymce.init({
                    selector: '#konten',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    tinycomments_mode: 'embedded',
                    tinycomments_author: 'Author name',
                    setup: function (editor) {
                        editor.on('init', function () {
                            console.log('TinyMCE initialized');
                        });
                        editor.on('error', function (e) {
                            console.error('TinyMCE error:', e);
                        });
                    }
                });
            } catch (e) {
                console.error('TinyMCE initialization failed:', e);
            }

            // File Management
            const fileInput = document.getElementById('files');
            const filePreview = document.getElementById('file-preview');
            const fileReviewModalElement = document.getElementById('fileReviewModal');
            const fileReviewModal = new bootstrap.Modal(fileReviewModalElement, { backdrop: 'static' });
            const fileReviewContent = document.getElementById('file-review-content');
            let selectedFiles = [];

            // Update file input with current selected files
            function updateFileInput() {
                try {
                    const dataTransfer = new DataTransfer();
                    selectedFiles.forEach(file => dataTransfer.items.add(file));
                    fileInput.files = dataTransfer.files;
                    console.log('File input updated with', selectedFiles.length, 'files');
                    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                } catch (e) {
                    console.error('Error updating file input:', e);
                }
            }

            // Render file preview
            function renderFilePreview() {
                console.log('Rendering file preview for', selectedFiles.length, 'files');
                filePreview.innerHTML = ''; // Clear current preview
                selectedFiles.forEach((file, index) => {
                    console.log('Rendering file:', file.name, 'Type:', file.type, 'Size:', file.size);
                    const div = document.createElement('div');
                    div.className = 'col';
                    div.dataset.index = index;

                    // File Preview
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = file.previewUrl || URL.createObjectURL(file);
                        img.className = 'img-fluid rounded';
                        img.style.maxHeight = '100px';
                        img.style.cursor = 'pointer';
                        img.onclick = () => {
                            console.log('Image clicked:', file.name);
                            showFileReview(file, index);
                        };
                        img.onerror = () => console.error('Failed to load image preview for:', file.name);
                        div.appendChild(img);
                    } else if (file.type === 'application/pdf') {
                        const icon = document.createElement('i');
                        icon.className = 'bi bi-file-earmark-pdf text-danger';
                        icon.style.fontSize = '50px';
                        icon.style.cursor = 'pointer';
                        icon.onclick = () => {
                            console.log('PDF icon clicked:', file.name);
                            showFileReview(file, index);
                        };
                        div.appendChild(icon);
                    } else if (file.type.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = file.previewUrl || URL.createObjectURL(file);
                        video.className = 'img-fluid rounded';
                        video.style.maxHeight = '100px';
                        video.controls = true;
                        video.onclick = () => {
                            console.log('Video clicked:', file.name);
                            showFileReview(file, index);
                        };
                        video.onerror = () => console.error('Failed to load video preview for:', file.name);
                        div.appendChild(video);
                    } else {
                        const icon = document.createElement('i');
                        icon.className = 'bi bi-file-earmark text-primary';
                        icon.style.fontSize = '50px';
                        icon.style.cursor = 'pointer';
                        icon.onclick = () => {
                            console.log('Generic file icon clicked:', file.name);
                            showFileReview(file, index);
                        };
                        div.appendChild(icon);
                    }

                    // File Name
                    const fileName = document.createElement('p');
                    fileName.textContent = file.name;
                    fileName.className = 'text-muted small mt-2';
                    div.appendChild(fileName);

                    // Remove Button
                    const removeBtn = document.createElement('button');
                    removeBtn.textContent = 'Remove';
                    removeBtn.className = 'btn btn-sm btn-danger mt-1';
                    removeBtn.onclick = () => {
                        console.log('Removing file:', file.name);
                        if (file.previewUrl) {
                            URL.revokeObjectURL(file.previewUrl);
                            file.previewUrl = null;
                        }
                        selectedFiles.splice(index, 1);
                        renderFilePreview();
                        updateFileInput();
                        validateFileInput();
                    };
                    div.appendChild(removeBtn);

                    filePreview.appendChild(div);
                });
                console.log('File preview rendering completed');
            }

            // Show file review in modal
            function showFileReview(file, index) {
                console.log('showFileReview called for:', file.name, 'Type:', file.type, 'Index:', index);
                fileReviewContent.innerHTML = ''; // Clear previous content
                try {
                    // File Information
                    console.log('Adding file info for:', file.name);
                    const fileInfo = document.createElement('div');
                    fileInfo.innerHTML = `
                        <p><strong>Nama:</strong> ${file.name}</p>
                        <p><strong>Ukuran:</strong> ${(file.size / 1024).toFixed(2)} KB</p>
                        <p><strong>Tipe:</strong> ${file.type}</p>
                    `;
                    fileReviewContent.appendChild(fileInfo);

                    // File Preview
                    let previewUrl = file.previewUrl;
                    if (!previewUrl) {
                        console.log('Creating new preview URL for:', file.name);
                        previewUrl = URL.createObjectURL(file);
                        file.previewUrl = previewUrl;
                    }
                    console.log('Using preview URL:', previewUrl);

                    if (file.type.startsWith('image/')) {
                        console.log('Rendering image preview for:', file.name);
                        const img = document.createElement('img');
                        img.src = previewUrl;
                        img.className = 'img-fluid rounded';
                        img.style.maxHeight = '400px';
                        img.style.width = '100%';
                        img.alt = file.name;
                        img.onerror = () => {
                            console.error('Failed to load image in modal for:', file.name);
                            fileReviewContent.innerHTML += '<p class="text-danger">Gagal memuat gambar.</p>';
                        };
                        fileReviewContent.insertBefore(img, fileInfo);
                    } else if (file.type === 'application/pdf') {
                        console.log('Rendering PDF link for:', file.name);
                        const link = document.createElement('a');
                        link.href = previewUrl;
                        link.download = file.name;
                        link.textContent = 'Unduh PDF: ' + file.name;
                        link.className = 'btn btn-primary mt-2';
                        fileReviewContent.insertBefore(link, fileInfo);
                        fileReviewContent.innerHTML += '<p class="text-warning mt-2">Pratinjau PDF tidak didukung di modal. Silakan unduh file.</p>';
                    } else if (file.type.startsWith('video/')) {
                        console.log('Rendering video preview for:', file.name);
                        const video = document.createElement('video');
                        video.src = previewUrl;
                        video.className = 'img-fluid rounded';
                        video.style.maxHeight = '400px';
                        video.style.width = '100%';
                        video.controls = true;
                        video.onerror = () => {
                            console.error('Failed to load video in modal for:', file.name);
                            fileReviewContent.innerHTML += '<p class="text-danger">Gagal memuat video.</p>';
                        };
                        fileReviewContent.insertBefore(video, fileInfo);
                    } else {
                        console.log('Rendering generic file icon for:', file.name);
                        const icon = document.createElement('i');
                        icon.className = 'bi bi-file-earmark text-primary';
                        icon.style.fontSize = '100px';
                        fileReviewContent.insertBefore(icon, fileInfo);
                    }

                    console.log('Opening modal for:', file.name);
                    fileReviewModal.show();
                    console.log('Modal opened successfully');
                } catch (e) {
                    console.error('Error in showFileReview:', e);
                    fileReviewContent.innerHTML = '<p class="text-danger">Gagal menampilkan pratinjau file: ' + e.message + '</p>';
                }
            }

            // Validate file input
            function validateFileInput() {
                console.log('Validating file input, selected files:', selectedFiles.length);
                if (selectedFiles.length === 0) {
                    fileInput.classList.add('is-invalid');
                } else {
                    fileInput.classList.remove('is-invalid');
                }
            }

            // Handle file selection
            fileInput.addEventListener('change', function () {
                console.log('File input changed');
                try {
                    const newFiles = Array.from(this.files);
                    console.log('Selected', newFiles.length, 'new files');
                    newFiles.forEach(newFile => {
                        if (!selectedFiles.some(file => file.name === newFile.name && file.size === newFile.size && file.lastModified === newFile.lastModified)) {
                            newFile.previewUrl = URL.createObjectURL(newFile);
                            selectedFiles.push(newFile);
                            console.log('Added file:', newFile.name, 'Type:', newFile.type);
                        } else {
                            console.log('Duplicate file ignored:', newFile.name);
                        }
                    });
                    renderFilePreview();
                    updateFileInput();
                    validateFileInput();
                    this.value = '';
                    console.log('File input cleared for next selection');
                } catch (e) {
                    console.error('Error handling file selection:', e);
                }
            });

            // Form Validation
            const form = document.getElementById('folder-form');
            form.addEventListener('submit', function (event) {
                console.log('Form submission attempted');
                if (!form.checkValidity() || selectedFiles.length === 0) {
                    event.preventDefault();
                    event.stopPropagation();
                    validateFileInput();
                    console.log('Form validation failed');
                }
                form.classList.add('was-validated');
            }, false);

            // Cleanup URLs on page unload
            window.addEventListener('beforeunload', () => {
                console.log('Cleaning up preview URLs');
                selectedFiles.forEach(file => {
                    if (file.previewUrl) {
                        console.log('Revoking URL for:', file.name);
                        URL.revokeObjectURL(file.previewUrl);
                    }
                });
            });

            // Ensure modal cleanup on hide
            fileReviewModalElement.addEventListener('hidden.bs.modal', () => {
                console.log('File review modal closed');
                fileReviewContent.innerHTML = '';
            });
        });
    </script>
@endsection


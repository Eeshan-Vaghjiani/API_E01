<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submission</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border: none;
            border-radius: 10px;
        }
        .card-header {
            background-color: #4a90e2;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 20px;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .btn-primary {
            background-color: #4a90e2;
            border-color: #4a90e2;
        }
        .btn-primary:hover {
            background-color: #357abd;
            border-color: #357abd;
        }
        .message-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">View Submission</h3>
                            <p class="mb-0">Submission details</p>
                        </div>
                        <a href="{{ route('submissions.index') }}" class="btn btn-light">Back to List</a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="info-label mb-1">Submission ID</p>
                                <p class="text-muted">#{{ $submission->id }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label mb-1">Submitted On</p>
                                <p class="text-muted">{{ $submission->created_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="info-label mb-1">Name</p>
                                <p>{{ $submission->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label mb-1">Email</p>
                                <p><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="info-label mb-1">Message</p>
                            <div class="message-box">
                                {{ $submission->message }}
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="btn-group">
                                <a href="{{ route('submissions.edit', $submission) }}"
                                   class="btn btn-warning text-white me-2">Edit Submission</a>
                                <form action="{{ route('submissions.destroy', $submission) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this submission?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Submission</button>
                                </form>
                            </div>
                            <small class="text-muted">Last updated: {{ $submission->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

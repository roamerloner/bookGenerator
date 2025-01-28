<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Information Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <title>Book Generator</title>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="language">Language</label>
                    <select class="form-control" id="language">
                        <option value="en_US">English (US)</option>
                        <option value="de_DE">German (Germany)</option>
                        <option value="fr_FR">French (France)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="seed">Seed</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="seed">
                        <button class="btn btn-outline-secondary" type="button" id="randomSeed">🔀</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="likes">Average Likes: <span id="likesValue">5.0</span></label>
                    <input type="range" class="form-control-range" id="likes" min="0" max="10" step="0.1" value="5.0">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="reviews">Average Reviews</label>
                    <input type="number" class="form-control" id="reviews" min="0" max="10" step="0.1" value="4.7">
                </div>
            </div>
        </div>

        
        <div class="table-responsive">
            <table class="table table-hover" id="booksTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author(s)</th>
                        <th>Publisher</th>
                    </tr>
                </thead>
                <tbody id="booksTableBody">
                </tbody>
            </table>
            <div id="loading" class="text-center d-none">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/infinite-scroll.js"></script>
</body>
</html>
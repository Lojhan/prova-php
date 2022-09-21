<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" type="text/css" href="public/css/movies.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="public/css/footer.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="public/css/header.css" media="screen" />

    <title>Movies</title>
</head>
<body>
    <?php require_once 'public/components/header.php' ?>
    <section class="container">
        <h1 class="title">Movies</h1>
        <button id="add-movie-button">Add movie</button>
        <section class="movies"></section>
    </section>
    <?php require_once 'public/components/footer.php' ?>

    <div class="modal-overlay">
        <div class="modal">
            <h1 class="title">Add Movie</h1>
            <form action="" id="movie-form">
                <section>
                    <input type="hidden" name="id" value="">
                    <section>
                        <label for="name">Name</label>
                        <input type="text" name="name" value="">
                    </section>
                    <section>
                        <label for="email">Description</label>
                        <input type="text" name="description" value="">
                    </section>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" id="cancel">Cancel</button>
                </section>
            </form>
        </div>
    </div>
    <script src="public/js/movies.js" type="module"></script>
    <script src="public/js/modal.js" type="module"></script>
</body>
</html>
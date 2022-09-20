<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" type="text/css" href="public/css/users.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="public/css/footer.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="public/css/header.css" media="screen" />
    <title>Users</title>
</head>
<body>
    <?php require_once 'public/components/header.php' ?>
    <section class="container">
        <h1 class="title">Users</h1>
        <button id="add-user-button">Add User</button>
        <section class="users"></section>
    </section>
    <?php require_once 'public/components/footer.php' ?>

    <div class="modal-overlay">
        <div class="modal">
            <h1 class="title">Add User</h1>
            <form action="" id="user-form">
                <section>
                    <input type="hidden" name="id" value="">
                    <section>
                        <label for="name">Name</label>
                        <input type="text" name="name" value="">
                    </section>
                    <section>
                        <label for="email">Email</label>
                        <input type="email" name="email" value="">
                    </section>
                    <section>
                        <label for="password">Password</label>
                        <input type="password" name="password" value="">
                    </section>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" id="cancel">Cancel</button>
                </section>
            </form>
        </div>
    </div>
    <script src="public/js/users.js" type="module"></script>
    <script src="public/js/modal.js" type="module"></script>
</body>
</html>
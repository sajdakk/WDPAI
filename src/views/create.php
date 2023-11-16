<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/global.css">
    <link rel="stylesheet" type="text/css" href="/public/css/create.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>

    <title>Create book</title>
</head>

<body>
    <nav>
        <div class="menu-hamburger">
            <input id="menu__toggle" type="checkbox" />
            <label class="menu__btn" for="menu__toggle">
                <span></span>
            </label>

            <ul class="menu__box">
                <li><a class="menu__item" href="/">Home</a></li>
                <li><a class="menu__item" href="/top">Top</a></li>
                <li><a class="menu__item" href="/profile">Profile</a></li>
                <li class="divider"></li>
                <li><a class="secondary_menu__item" href="/create">Add book</a></li>
                <li><a class="secondary_menu__item" href="/favorites">Favorites</a></li>
                <li><a class="secondary_menu__item" href="/login">Log in</a></li>
                <li><a class="secondary_menu__item" href="/registration">Sign up</a></li>
            </ul>
        </div>
        <div class="header-one-side">
            <div id="small-logo">
                Flipbook</div>
            <ul class="menu">
                <li><a href="/">Home</a></li>
                <li><a href="/top">Top</a></li>
                <li><a href="/profile">Profile</a></li>
            </ul>
        </div>
        <div class="header-one-side">
            <div class="text-button">
                <i class="material-icons">menu_book</i>
                <a class="text-header" href="/create">Create book</a>
            </div>
            <div class="text-button">
                <i class="material-icons">favorite</i>
                <a class="text-header" href="/favorites">Favorites</a>
            </div>
            <div class="header-form">
                <button class="secondary-button" onclick="routeToLogin()">Log
                    in</button>
                <button onclick="routeToRegistration()">Sign up</button>
                </form>
                <script>
                    function routeToLogin() {
                        window.location.href = '/login';
                    }

                    function routeToRegistration() {
                        window.location.href = '/registration';
                    }
                </script>
            </div>
        </div>
    </nav>
    <main>
        <div class="create-content">
            <div class="header">
                Create book
            </div>
            <form class="create-form" action="create.php" method="post">
                <input type="text" id="title" name="title" placeholder="Title" required>
                <input type="text" id="language" name="language" placeholder="Language" required>
                <input type="text" id="date-of-pub" name="date-of-pub" placeholder="Date of publication" required>
                <input type="int" id="page-cout" name="page-cout" placeholder="Page count" required>
                <input type="text" id="isbn-number" name="isbn-number" placeholder="ISBN number" required>
                <input type="text" id="description" name="description" placeholder="Description" required>
                <div class="image-section">
                    <div id="imagePreview"></div>
                    <label for="imageUpload" class="custom-file-input">
                        <span class="custom-file-input-label">Choose image</span>
                    </label>
                    <input type="file" id="imageUpload" name="image" accept="image/*" onchange="previewImage()">
                </div>
                <select id="genre" name="genre" required>
                    <option value="" disabled selected>Genre</option>
                    <option value="epic">Epic</option>
                    <option value="liryc">Liryc</option>
                    <option value="drama">Drama</option>
                </select>
                <div class="header">
                    Author’s section
                </div>

                <button type="submit">Send book to review</button>

            </form>

            <script>
                function previewImage() {
                    const imageUpload = document.getElementById('imageUpload');
                    const imagePreview = document.getElementById('imagePreview');

                    if (imageUpload.files && imageUpload.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const preview = document.createElement('img');
                            preview.src = e.target.result;
                            imagePreview.innerHTML = '';
                            imagePreview.appendChild(preview);
                        };

                        reader.readAsDataURL(imageUpload.files[0]);
                    } else {
                        imagePreview.innerHTML = 'No image selected';
                    }
                }
            </script>
        </div>
    </main>
</body>

</html>
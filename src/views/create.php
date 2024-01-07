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
    <script src="/public/js/common.js"></script>
    <script src="/public/js/create.js"></script>

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
                <?php if ($isLogged): ?>
                    <li><a class="menu__item" href="/profile">Profile</a></li>
                <?php endif; ?>

                <?php if ($isAdmin): ?>
                    <li><a class="menu__item" href="/admin">Admin</a></li>
                <?php endif; ?>

                <li class="divider"></li>
                <li><a class="secondary_menu__item" href="/create">Add book</a></li>
                <li><a class="secondary_menu__item" href="/favorites">Favorites</a></li>
                <?php if (!$isLogged): ?>
                    <li><a class="secondary_menu__item" href="/login">Log in</a></li>
                    <li><a class="secondary_menu__item" href="/register">Sign up</a></li>

                <?php else: ?>
                    <li>

                        <a class="secondary_menu__item">
                            <button onclick="logout()">Log out</button>
                        </a>

                    </li>

                <?php endif; ?>


            </ul>
        </div>
        <div class="header-one-side">
            <div id="small-logo">Flipbook</div>
            <ul class="menu">
                <li><a href="/">Home</a></li>
                <li><a href="/top">Top</a></li>
                <?php if ($isLogged): ?>
                    <li><a href="/profile">Profile</a></li>
                <?php endif; ?>

                <?php if ($isAdmin): ?>
                    <li><a href="/admin">Admin</a></li>
                <?php endif; ?>
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
                <?php if (!$isLogged): ?>
                    <button class="secondary-button" onclick="routeToLogin()">Log
                        in</button>
                    <button onclick="routeToRegistration()">Sign up</button>
                    </form>

                <?php else: ?>
                    <button class="secondary-button" onclick="logout()">Log out</button>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main>
        <div class="create-content">
            <?php if (!$isLogged): ?>
                <div class="header">
                    You need to be logged in to create a book
                </div>
            <?php else: ?>
                <div class="header">
                    Create book
                </div>
                <form class="create-form" action="create" method="post" ENCTYPE="multipart/form-data">
                    <input type="text" id="title" name="title" placeholder="Title"
                        value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>" required>
                    <?php if (isset($errors['title'])): ?>
                        <div class="errors">
                            <?= $errors['title'] ?>
                        </div>
                    <?php endif; ?>

                    <select id="language" name="language" required>
                        <option value="<?= isset($_POST['language']) ? htmlspecialchars($_POST['language']) : '' ?>"
                            disabled selected>Language</option>
                        <?php foreach ($languages as $language): ?>
                            <option value="  <?php echo $language->getId() ?>">
                                <?php echo $language->getLanguage() ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                    <?php if (isset($errors['language'])): ?>
                        <div class="errors">
                            <?= $errors['language'] ?>
                        </div>
                    <?php endif; ?>

                    <input type="date" id="date-of-pub" name="date-of-pub" placeholder="Date of publication"
                        value="<?= isset($_POST['date-of-pub']) ? htmlspecialchars($_POST['date-of-pub']) : '' ?>" required>
                    <?php if (isset($errors['date-of-pub'])): ?>
                        <div class="errors">
                            <?= $errors['date-of-pub'] ?>
                        </div>
                    <?php endif; ?>
                    <input type="int" id="page-count" name="page-count" placeholder="Page count"
                        value="<?= isset($_POST['page-count']) ? htmlspecialchars($_POST['page-count']) : '' ?>" required>
                    <?php if (isset($errors['page-count'])): ?>
                        <div class="errors">
                            <?= $errors['page-count'] ?>
                        </div>
                    <?php endif; ?>
                    <input type="text" id="isbn-number" name="isbn-number" placeholder="ISBN number"
                        value="<?= isset($_POST['isbn-number']) ? htmlspecialchars($_POST['isbn-number']) : '' ?>" required>
                    <?php if (isset($errors['isbn-number'])): ?>
                        <div class="errors">
                            <?= $errors['isbn-number'] ?>
                        </div>
                    <?php endif; ?>
                    <input type="text" id="description" name="description" placeholder="Description"
                        value="<?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?>" required>
                    <?php if (isset($errors['description'])): ?>
                        <div class="errors">
                            <?= $errors['description'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="image-section">
                        <div id="imagePreview"></div>
                        <label for="imageUpload" class="custom-file-input">
                            <span class="custom-file-input-label">Choose image</span>
                        </label>
                        <input type="file" id="imageUpload" name="image" accept="image/*"
                            value="<?= isset($_POST['image']) ? htmlspecialchars($_POST['image']) : '' ?>"
                            onchange="previewImage()">
                    </div>
                    <?php if (isset($errors['image'])): ?>
                        <div class="errors">
                            <?= $errors['image'] ?>
                        </div>
                    <?php endif; ?>
                    <select id="genre" name="genre"
                        value="<?= isset($_POST['genre']) ? htmlspecialchars($_POST['genre']) : '' ?>" required>
                        <option value="" disabled selected>Genre</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?php echo $genre->getId() ?>">
                                <?php echo $genre->getGenre() ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                    <?php if (isset($errors['genre'])): ?>
                        <div class="errors">
                            <?= $errors['genre'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="header">
                        Author’s section
                    </div>
                    <input type="text" id="searchInput" oninput="filterCheckboxes()" placeholder="Search authors">

                    <div class="checkboxContainer">
                        <?php foreach ($authors as $author): ?>
                            <div class="checkbox-input">
                                <input type="checkbox" name="authors[]"
                                    value="<?php echo $author->getName() . " " . $author->getSurname() ?>">
                                <?php echo $author->getName() . " " . $author->getSurname()
                                    ?>
                                </input>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <?php if (isset($errors['authors'])): ?>
                        <div class="errors">
                            <?= $errors['authors'] ?>
                        </div>
                    <?php endif; ?>

                    <div class="subtitle">
                        New authors
                    </div>
                    <div class="add-author-section">
                        <div class="add-author">
                            <input type="text" id="authorName" name="authorName" placeholder="Author name">
                            <input type="text" id="authorSurname" name="authorSurname" placeholder="Author surname">
                        </div>
                        <button type="button" onclick="addAuthor()">Add new author</button>
                    </div>
                    <div id="errors">
                        <?php echo $error
                            ?>
                    </div>
                    <button type="submit">Send book to review</button>
                </form>
            <?php endif; ?>


        </div>
    </main>
</body>

</html>
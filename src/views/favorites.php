<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/global.css">
    <link rel="stylesheet" type="text/css" href="/public/css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="/public/css/top.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>

    <title>Favorites</title>
</head>

<body id="body">
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
                    <li><a class="secondary_menu__item" href="/registration">Sign up</a></li>

                <?php else: ?>
                    <li>
                        <form action="logout" method="post">
                            <a class="secondary_menu__item">
                                <button type="submit">Log out</button>
                            </a>
                        </form>
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
                    <script>
                        function routeToLogin() {
                            window.location.href = '/login';
                        }

                        function routeToRegistration() {
                            window.location.href = '/registration';
                        }
                    </script>

                <?php else: ?>
                    <form action="logout" method="post">
                        <button class="secondary-button" type="submit">Log out</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main>
        <div class="top-content">
            <?php if (!$isLogged): ?>
                <div class="header">
                    You need to be logged in to see your favorites
                </div>
            <?php else: ?>

                <div class="header">
                    Fovorites
                </div>
                <div class="news">
                    <?php foreach ($books as $book): ?>


                        <div class="news-container" onclick="routeToDetails('<?= $book->getId() ?>')">
                            <img class="news-image" src=<?= 'public/uploads/' . $book->getImage() ?> alt="News Image 1">
                            <div class="news-description">
                                <div class="card-header">
                                    <div class="title">
                                        <div class="inter-semibold-16">
                                            <?= $book->getTitle() ?>

                                        </div>
                                        <form action="toggleFavorite" method="post">
                                            <input type="hidden" name="book-id" value="<?= $book->getId() ?>">

                                            <button type="submit"> <i class="material-icons">
                                                    <?php
                                                    $contains = false;
                                                    foreach ($favorites as $favorite) {
                                                        if ($favorite->getBookId() == $book->getId()) {
                                                            $contains = true;
                                                            break;
                                                        }
                                                    }

                                                    if ($contains) {
                                                        echo 'favorite';
                                                    } else {
                                                        echo 'favorite_outline';
                                                    }
                                                    ?>
                                                </i></button>

                                        </form>

                                    </div>
                                    <div class="inter-regular-12">
                                        <?php

                                        echo $bookIdToAuthors[$book->getId()];
                                        ?>

                                    </div>

                                </div>
                                <div class="extra-info">
                                    <div class="score">
                                        <i class="material-icons">star_border</i>
                                        <div class="inter-light-14">
                                            4.5 / 5
                                        </div>
                                    </div>
                                    <div class="inter-extra-light-14">
                                        104 reviews
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <script>
                        function routeToDetails(bookId) {
                            window.location.href = '/details/' + bookId;
                        }


                    </script>



                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
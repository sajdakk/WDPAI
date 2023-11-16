<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/global.css">
    <link rel="stylesheet" type="text/css" href="/public/css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>

    <title>Dashboard</title>
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
            <div id="small-logo">Flipbook</div>
            <ul class="menu">
                <li><a class="selected" href="/">Home</a></li>
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

        <div class="elements">
            <div class="header">   
               <?= $title ?>

            </div>
            <form class="header-form" action="dashboard.php" method="post">
                <input type="text" id="title" name="title" placeholder="Title" required>
                <input type="text" id="author" name="author" placeholder="Author" required>
                <button type="submit">Search</button>
            </form>
            <div class="dashboard-content">
                <div class="header">
                    May interest you
                </div>
                <div class="news">
                <?php foreach($books as $book): ?>
                    <div class="news-container">
                        <img class="news-image" src=  <?= $book->getImageUrl() ?>  alt="News Image 1">
                        <div class="news-description">
                            <div class="card-header">
                                <div class="title">
                                    <div class="inter-semibold-16">
                                        <?= $book->getTitle() ?> 
                                        
                                    </div>
                                    <i class="material-icons">favorite_outline</i>
                                </div>
                                <div class="inter-regular-12">
                                    Rowling, J.K.
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

                    <!-- <div class="news-container">
                        <img class="news-image" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                        <div class="news-description">
                            <div class="card-header">
                                <div class="title">
                                    <div class="inter-semibold-16">
                                        Harry Potter and the philosopher's stone
                                    </div>
                                    <i class="material-icons">favorite_outline</i>
                                </div>
                                <div class="inter-regular-12">
                                    Rowling, J.K.
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
                    <div class="news-container">
                        <img class="news-image" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                        <div class="news-description">
                            <div class="card-header">
                                <div class="title">
                                    <div class="inter-semibold-16">
                                        Harry Potter and the philosopher's stone
                                    </div>
                                    <i class="material-icons">favorite</i>
                                </div>
                                <div class="inter-regular-12">
                                    Rowling, J.K.
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
                    <div class="news-container">
                        <img class="news-image" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                        <div class="news-description">
                            <div class="card-header">
                                <div class="title">
                                    <div class="inter-semibold-16">
                                        Harry Potter and the philosopher's stone
                                    </div>
                                    <i class="material-icons">favorite_outline</i>
                                </div>
                                <div class="inter-regular-12">
                                    Rowling, J.K.
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
                    <div class="news-container">
                        <img class="news-image" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                        <div class="news-description">
                            <div class="card-header">
                                <div class="title">
                                    <div class="inter-semibold-16">
                                        Harry Potter and the philosopher's stone
                                    </div>
                                    <i class="material-icons">favorite</i>
                                </div>
                                <div class="inter-regular-12">
                                    Rowling, J.K.
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
                    <div class="news-container">
                        <img class="news-image" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                        <div class="news-description">
                            <div class="card-header">
                                <div class="title">
                                    <div class="inter-semibold-16">
                                        Harry Potter and the philosopher's stone
                                    </div>
                                    <i class="material-icons">favorite_outline</i>
                                </div>
                                <div class="inter-regular-12">
                                    Rowling, J.K.
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

                    <div class="news-container">
                        <img class="news-image" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                        <div class="news-description">
                            <div class="card-header">
                                <div class="title">
                                    <div class="inter-semibold-16">
                                        Harry Potter and the philosopher's stone
                                    </div>
                                    <i class="material-icons">favorite</i>
                                </div>
                                <div class="inter-regular-12">
                                    Rowling, J.K.
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
                    </div> -->
                </div>
            </div>
        </div>
    </main>
</body>

</html>
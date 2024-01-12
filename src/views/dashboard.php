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
    <script src="/public/js/common.js"></script>
    <script src="/public/js/dashboard.js"></script>

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
                <li><a class="selected" href="/">Home</a></li>
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

        <div class="elements">
            <div class="header">
                What can I find for you?

            </div>
            <form class="header-form">
                <input type="text" id="title" name="title" placeholder="Title">
                <input type="text" id="author_name" name="name" placeholder="Author's name">
                <input type="text" id="author_surname" placeholder="Author's surname">
                <button onclick="search(event);">Search</button>
            </form>
            <div class="dashboard-content">
                <?php if ($mayInterestYou): ?>

                    <div class="header" id="may-interest-you">
                        May interest you
                    </div>
                <?php endif; ?>
                <div class="news">
                    <?php foreach ($books as $book): ?>
                        <div class="news-container" onclick="routeToDetails('<?= $book->id ?>')">
                            <img class="news-image" src=<?= '/public/uploads/' . $book->image ?> alt="News Image 1">
                            <div class="news-description">
                                <div class="card-header">
                                    <div class="title">
                                        <div class="inter-semibold-16">
                                            <?= $book->title ?>
                                        </div>
                                        <?php if ($isLogged): ?>
                                            <button onclick="toggleFavorite(event, <?= $book->id ?>);">
                                                <i class="material-icons">
                                                    <?php
                                                    $contains = false;
                                                    foreach ($favorites as $favorite) {
                                                        if ($favorite->getBookId() == $book->id) {
                                                            $contains = true;
                                                            break;
                                                        }
                                                    }

                                                    echo $contains ? 'favorite' : 'favorite_outline';
                                                    ?>
                                                </i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" onclick="showToast(event)">
                                                <!-- Use type="button" to prevent form submission -->
                                                <i class="material-icons">favorite_outline</i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="inter-regular-12">
                                        <?= $book->authors_string ?>
                                    </div>
                                </div>
                                <div class="extra-info">
                                    <div class="score">
                                        <i class="material-icons">star_border</i>
                                        <div class="inter-light-14">
                                            <?= round($book->average_rate, 1) ?>/5
                                        </div>
                                    </div>
                                    <div class="inter-extra-light-14">
                                        <?= $book->rate_count ?> reviews
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/global.css">
    <link rel="stylesheet" type="text/css" href="/public/css/book-details.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>Book details</title>
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
        <div class="page-content">
            <div class="mobile-page-content">
                <div class="details">
                    <img class="book-image" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                    <div class="info">
                        <div class="headline-h1-semibold">
                            Pan Tadeusz
                        </div>
                        <div class="headline-h4-regular">
                            Author: Adam Mickiewicz
                        </div>
                        <div class="headline-h4-regular">
                            Language: polish
                        </div>
                        <div class="headline-h4-regular">
                            Date of publication: 20.11.2023 r.
                        </div>
                        <div class="headline-h4-regular">
                            Page count: 400
                        </div>
                        <div class="headline-h4-regular">
                            ISBN number: 400
                        </div>
                        <div class="headline-h4-regular">
                            Genre: lyric
                        </div>
                    </div>
                    <div class="stars">
                        <i class="material-icons custom-icon">star_border</i>
                        <div class="inter-light-24">
                            4.5/5
                        </div>
                    </div>
                </div>
                <div class="mobile-info">
                    <div class="headline-h1-semibold">
                        Pan Tadeusz
                    </div>
                    <div class="headline-h4-regular">
                        Author: Adam Mickiewicz
                    </div>
                    <div class="headline-h4-regular">
                        Language: polish
                    </div>
                    <div class="headline-h4-regular">
                        Date of publication: 20.11.2023 r.
                    </div>
                    <div class="headline-h4-regular">
                        Page count: 400
                    </div>
                    <div class="headline-h4-regular">
                        ISBN number: 400
                    </div>
                    <div class="headline-h4-regular">
                        Genre: lyric
                    </div>
                </div>
            </div>
            <div class="description">
                Lorem Ipsum jest tekstem stosowanym jako przykładowy wypełniacz w przemyśle poligraficznym. Został
                po
                raz pierwszy użyty w XV w. przez nieznanego drukarza do wypełnienia tekstem próbnej książki. Pięć
                wieków
                później zaczął być używany przemyśle elektronicznym, pozostając praktycznie niezmienionym.
                Spopularyzował się w latach 60. XX w. wraz z publikacją. Lorem Ipsum jest tekstem stosowanym jako
                przykładowy wypełniacz w przemyśle poligraficznym. Został po raz pierwszy użyty w XV w. przez
                nieznanego
                drukarza do wypełnienia tekstem próbnej książki. Pięć wieków później zaczął być używany przemyśle
                elektronicznym, pozostając praktycznie niezmienionym. Spopularyzował się w latach 60. XX w. wraz z
                publikacją
            </div>
            <div class="headline-h1-semibold">
                Add your review
            </div>
            <div class="card">
                <img class="imagePreview" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                <div class="review-content">
                    <div class="inter-semibold-16">
                        Anita | 10.10.2010 r.
                    </div>
                    <form class="header-form" action="dashboard.php" method="post">
                        <input type="text" id="review" name="review" placeholder="Type something..." required>
                        <div class="review-mobile-stars">
                            <i class="material-icons review-icon">star_border</i>
                            <i class="material-icons review-icon">star_border</i>
                            <i class="material-icons review-icon">star_border</i>
                            <i class="material-icons review-icon">star_border</i>
                            <i class="material-icons review-icon">star_border</i>
                        </div>
                        <button type="submit">Sent to review</button>
                    </form>
                    <div class="review-stars">
                        <i class="material-icons review-icon">star_border</i>
                        <i class="material-icons review-icon">star_border</i>
                        <i class="material-icons review-icon">star_border</i>
                        <i class="material-icons review-icon">star_border</i>
                        <i class="material-icons review-icon">star_border</i>
                    </div>
                </div>

            </div>
            <div class="headline-h1-semibold">
                What people are saying?
            </div>
            <div class="card">
                <div class="mobile-left-side-card">
                    <img class="imagePreview" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                    <div class="mobile-stars">
                        <i class="material-icons custom-icon">star_border</i>
                        <div class="inter-light-24">
                            4.5/5
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="inter-semibold-16">
                        Anna | 10.10.2010 r.
                    </div>
                    <div class="dmsans-regular-14">
                        Lorem Ipsum jest tekstem stosowanym jako przykładowy wypełniacz w przemyśle
                        poligraficznym. Został po raz pierwszy użyty w XV w. Lorem Ipsum jest tekstem stosowanym
                        jako przykładowy wypełniacz w przemyśle poligraficznym. Został po raz pierwszy użyty w
                        XV w. Lorem Ipsum jest tekstem stosowanym jako przykładowy wypełniacz w przemyśle
                        poligraficznym. Został po raz pierwszy użyty w XV w..
                    </div>
                </div>
                <div class="stars">
                    <i class="material-icons custom-icon">star_border</i>
                    <div class="inter-light-24">
                        4.5/5
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="mobile-left-side-card">
                    <img class="imagePreview" src="https://cdn2.thecatapi.com/images/bnr.jpg" alt="News Image 1">
                    <div class="mobile-stars">
                        <i class="material-icons custom-icon">star_border</i>
                        <div class="inter-light-24">
                            4.5/5
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="inter-semibold-16">
                        Anna | 10.10.2010 r.
                    </div>
                    <div class="dmsans-regular-14">
                        Lorem Ipsum jest tekstem stosowanym jako przykładowy wypełniacz w przemyśle
                        poligraficznym. Został po raz pierwszy użyty w XV w. Lorem Ipsum jest tekstem stosowanym
                        jako przykładowy wypełniacz w przemyśle poligraficznym. Został po raz pierwszy użyty w
                        XV w. Lorem Ipsum jest tekstem stosowanym jako przykładowy wypełniacz w przemyśle
                        poligraficznym. Został po raz pierwszy użyty w XV w..
                    </div>
                </div>
                <div class="stars">
                    <i class="material-icons custom-icon">star_border</i>
                    <div class="inter-light-24">
                        4.5/5
                    </div>
                </div>
            </div>
    </main>
</body>

</html>
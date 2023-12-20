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
                        >
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
                <script>
                    function routeToLogin() {
                        window.location.href = '/login';
                    }

                    function routeToRegistration() {
                        window.location.href = '/register';
                    }

                    function logout() {
                        window.location.href = '/logout';
                    }
                </script>

            </div>
        </div>
    </nav>
    <main>
        <div class="page-content">
            <div class="mobile-page-content">
                <div class="details">
                    <img class="book-image" src=<?= '/public/uploads/' . $book->getImage() ?> alt="News Image 2">
                    <div class="info">
                        <div class="headline-h1-semibold">
                            <?= $book->getTitle() ?>
                        </div>
                        <div class="headline-h4-regular">
                            Author:
                            <?= $book->getAuthorsString() ?>
                        </div>
                        <div class="headline-h4-regular">
                            Language:
                            <?= $languageString ?>
                        </div>
                        <div class="headline-h4-regular">
                            Date of publication:
                            <?= $book->getDateOfPublication() ?>
                        </div>
                        <div class="headline-h4-regular">
                            Page count:
                            <?= $book->getPageCount() ?>
                        </div>
                        <div class="headline-h4-regular">
                            ISBN number:
                            <?= $book->getIsbnNumber() ?>
                        </div>
                        <div class="headline-h4-regular">
                            Genre:
                            <?= $genreString ?>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="material-icons custom-icon">star_border</i>
                        <div class="inter-light-24">
                            <?= $book->getAverageRate() ?>/5
                        </div>
                    </div>
                </div>
                <div class="mobile-info">
                    <div class="headline-h1-semibold">
                        <?= $book->getTitle() ?>
                    </div>
                    <div class="headline-h4-regular">
                        Author:
                        <?= $book->getAuthorsString() ?>
                    </div>
                    <div class="headline-h4-regular">
                        Language:
                        <?= $languageString ?>
                    </div>
                    <div class="headline-h4-regular">
                        Date of publication:
                        <?= $book->getDateOfPublication() ?>
                    </div>
                    <div class="headline-h4-regular">
                        Page count:
                        <?= $book->getPageCount() ?>
                    </div>
                    <div class="headline-h4-regular">
                        ISBN number:
                        <?= $book->getIsbnNumber() ?>
                    </div>
                    <div class="headline-h4-regular">
                        Genre:
                        <?= $genreString ?>
                    </div>
                </div>
            </div>
            <div class="description">
                <?= $book->getDescription() ?>
            </div>
            <div class="headline-h1-semibold">
                Add your review
            </div>
            <? if (!$isLogged): ?>
                <div class="inter-semibold-16">
                    You have to be logged in to add a review
                </div>
            <? elseif ($hasAlreadyReview): ?>
                <div class="inter-semibold-16">
                    You have already reviewed this book
                </div>
            <?php else: ?>

                <div class="card">
                    <?php if ($userAvatar == null): ?>
                        <div class="placeholder-image">
                        </div>
                    <?php else: ?>
                        <img class="imagePreview" src=<?= 'public/uploads/' . $userAvatar ?> alt="News Image 1">
                    <?php endif; ?>
                    <div class="review-content">
                        <div class="inter-semibold-16">
                            <?= $userName ?> |
                            <?= $nowString ?>
                        </div>
                        <form class="header-form" id="reviewForm" action="addReview" method="post">
                            <input type="text" id="review" name="review" placeholder="Type something..." required>
                            <div class="review-mobile-stars" onclick="selectStar(event)">
                                <i class="material-icons review-icon" onclick="selectStar(1)">star_border</i>
                                <i class="material-icons review-icon" onclick="selectStar(2)">star_border</i>
                                <i class="material-icons review-icon" onclick="selectStar(3)">star_border</i>
                                <i class="material-icons review-icon" onclick="selectStar(4)">star_border</i>
                                <i class="material-icons review-icon" onclick="selectStar(5)">star_border</i>
                            </div>
                            <input type="hidden" name="rate" id="review-rate" value="0" />
                            <button type="submit" onclick="changeFormAction()">Sent to review</button>
                        </form>
                        <div class="review-stars">
                            <i class="material-icons review-icon" onclick="selectStar(1)">star_border</i>
                            <i class="material-icons review-icon" onclick="selectStar(2)">star_border</i>
                            <i class="material-icons review-icon" onclick="selectStar(3)">star_border</i>
                            <i class="material-icons review-icon" onclick="selectStar(4)">star_border</i>
                            <i class="material-icons review-icon" onclick="selectStar(5)">star_border</i>
                        </div>

                        <script>
                            function selectStar(value) {
                                const stars = document.querySelectorAll('.review-stars > i');

                                stars.forEach(star => star.textContent = 'star_border');
                                for (let i = 0; i < value; i++) {
                                    stars[i].textContent = 'star';
                                }

                                const reviewRate = document.querySelector('#review-rate');
                                reviewRate.value = value;
                            }

                            function logout() {
                                // Get the current base URL
                                const baseURL = window.location.origin;

                                const newURL = `${baseURL}/logout`;

                                // Change the form action attribute
                                document.getElementById('logoutForm').action = newURL;

                                // Submit the form
                                document.getElementById('logoutForm').submit();
                            }

                            function changeFormAction() {
                                // Get the current base URL
                                const baseURL = window.location.origin;

                                // Replace everything after the base URL with '/addReview'
                                const newURL = `${baseURL}/addReview`;



                                // Change the form action attribute
                                document.getElementById('reviewForm').action = newURL;

                                // Submit the form
                                document.getElementById('reviewForm').submit();
                            }
                        </script>
                    </div>

                </div>
            <?php endif; ?>
            <div class="headline-h1-semibold">
                What people are saying?
            </div>
            <?php if ($reviews == null): ?>
                <div class="inter-semibold-16">
                    No reviews yet
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="card">
                        <div class="mobile-left-side-card">
                            <?php if (empty($review->getUserAvatar())): ?>
                                <div class="placeholder-image">
                                </div>
                            <?php else: ?>
                                <img class="imagePreview" src=<?= '/public/uploads/' . $review->getUserAvatar() ?>
                                    alt="News Image 1">
                            <?php endif; ?>


                            <div class="mobile-stars">
                                <i class="material-icons custom-icon">star_border</i>
                                <div class="inter-light-24">
                                    <?= $review->getRate() ?>/5
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="inter-semibold-16">
                                <?= $review->getUserName() ?> |
                                <?= $review->getUploadDate() ?>
                            </div>
                            <div class="dmsans-regular-14">
                                <?= $review->getContent() ?>
                            </div>
                        </div>
                        <div class="stars">
                            <i class="material-icons custom-icon">star_border</i>
                            <div class="inter-light-24">
                                <?= $review->getRate() ?>/5
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

    </main>
</body>

</html>
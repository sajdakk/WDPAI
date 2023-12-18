<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/global.css">
    <link rel="stylesheet" type="text/css" href="/public/css/profile.css">
    <link rel="stylesheet" type="text/css" href="/public/css/admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>

    <title>Admin</title>
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
                <li><a class="menu__item" href="/admin">Admin</a></li>
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
                <li><a href="/profile">Profile</a></li>
                <li><a class="selected" href="/admin">Admin</a></li>
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
        <div class="profile-content">
            <?php if (!$isLogged): ?>
                <div class="header">
                    You need to be logged in to see your profile.
                </div>
            <?php else: ?>
                <div class="profile-menu">
                    <div class="profile-menu-item selected-profile-menu-item" onclick="toggleMenuItem(this, 'reviews')">
                        <div class="icon-background">
                            <i class="material-icons custom-icon">menu_book</i>
                        </div>
                        <div class="inter-semibold-16">
                            Waiting Reviews
                        </div>
                    </div>
                    <div class="profile-menu-item not-selected-profile-menu-item" onclick="toggleMenuItem(this, 'books')">
                        <div class="icon-background">
                            <i class="material-icons custom-icon">menu_book</i>
                        </div>
                        <div class="inter-semibold-16">
                            Waiting Books
                        </div>
                    </div>
                </div>
                <div class="list" id="reviewsList">
                    <? if (empty($reviews)): ?>
                        <div class="empty-header-container">
                            <div class="inter-semibold-16">
                                No reviews to accept or reject
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="card" onclick="routeToDetails('<?= $review->getBookId() ?>')">
                            <div class="card-content">
                                <div class="card-header">
                                    <div class="inter-semibold-16">
                                        <?= $review->getBookTitle() ?>
                                    </div>
                                    <div class="inter-regular-12">
                                        <?= $review->getBookAuthors() ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        <?= $review->getContent() ?>
                                    </div>

                                </div>
                            </div>
                            <div class="profile-card-right-side">
                                <form action="/toggleReviewStatus" method="post">
                                    <input type="hidden" name="review-id" value="<?= $review->getId() ?>">
                                    <input type="hidden" name="action" value="accept">
                                    <a class="secondary_menu__item">
                                        <button type="submit">Accept</button>
                                    </a>
                                </form>

                                <form action="/toggleReviewStatus" method="post">
                                    <input type="hidden" name="review-id" value="<?= $review->getId() ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button class="secondary-button" type="submit">Reject</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="list" id="booksList" style="display: none;">
                <? if (empty($books)): ?>
                        <div class="empty-header-container">
                            <div class="inter-semibold-16">
                                No books to accept or reject
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($books as $book): ?>
                        <div class="card" onclick="routeToDetails('<?= $book->getId() ?>')">
                            <div class="card-content">
                                <div class="card-header">
                                    <div class="inter-semibold-16">
                                        <?= $book->getTitle() ?>
                                    </div>
                                    <div class="inter-regular-12">
                                        <?= $book->getAuthorsString() ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        Description:
                                        <?= $book->getDescription() ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        Date of publication:
                                        <?= $book->getDateOfPublication() ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        Page count:
                                        <?= $book->getPageCount() ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        ISBN number:
                                        <?= $book->getIsbnNumber() ?>
                                    </div>

                                </div>
                            </div>
                            <div class="profile-card-right-side">

                                <form action="/toggleBookStatus" method="post">
                                    <input type="hidden" name="book-id" value="<?= $book->getId() ?>">
                                    <input type="hidden" name="action" value="accept">
                                    <a class="secondary_menu__item">
                                        <button type="submit">Accept</button>
                                    </a>
                                </form>

                                <form action="/toggleBookStatus" method="post">
                                    <input type="hidden" name="book-id" value="<?= $book->getId() ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button class="secondary-button" type="submit">Reject</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="list">

                    <script>
                        function routeToDetails(bookId) {
                            window.location.href = '/details/' + bookId;
                        }

                        function toggleMenuItem(clickedItem, listType) {
                            // Get all menu items
                            const menuItems = document.querySelectorAll('.profile-menu-item');

                            // Remove "selected" class from all items
                            menuItems.forEach(item => {
                                item.classList.remove('selected-profile-menu-item');
                                item.classList.add('not-selected-profile-menu-item');
                            });

                            // Add "selected" class to the clicked item
                            clickedItem.classList.add('selected-profile-menu-item');
                            clickedItem.classList.remove('not-selected-profile-menu-item');

                            // Show/hide the corresponding list based on the selected menu item
                            const reviewsList = document.getElementById('reviewsList');
                            const booksList = document.getElementById('booksList');

                            if (listType === 'reviews') {
                                reviewsList.style.display = 'flex';
                                booksList.style.display = 'none';
                            } else {
                                reviewsList.style.display = 'none';
                                booksList.style.display = 'flex';
                            }
                        }

                    </script>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
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
    <script src="/public/js/common.js"></script>
    <script src="/public/js/admin.js"></script>

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
        <div class=" header-one-side">
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

                <?php else: ?>

                    <button class="secondary-button" onclick="logout()">Log out</button>

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
                        <div class="inter-semibold-16">Waiting Reviews</div>
                    </div>
                    <div class="profile-menu-item not-selected-profile-menu-item" onclick="toggleMenuItem(this, 'books')">
                        <div class="icon-background">
                            <i class="material-icons custom-icon">menu_book</i>
                        </div>
                        <div class="inter-semibold-16">
                            Waiting Books
                        </div>
                    </div>
                    <div class="profile-menu-item not-selected-profile-menu-item" onclick="toggleMenuItem(this, 'users')">
                        <div class="icon-background">
                            <i class="material-icons custom-icon">groups</i>
                        </div>
                        <div class="inter-semibold-16">
                            All Users
                        </div>
                    </div>
                </div>

                <div class="list" id="reviewsList">
                    <div class="empty-header-container">
                        <div class="inter-semibold-16" id="review-empty-header" ,
                            style="display:  <?= (empty($reviews)) ? 'initial' : 'none' ?> ">
                            No reviews to accept or reject
                        </div>
                    </div>
                    <?php foreach ($reviews as $review): ?>
                        <div class="card" id="review-card-<?= $review->getId() ?>"
                            onclick="routeToDetails('<?= $review->getBookId() ?>')">
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
                                <a class="secondary_menu__item">
                                    <button
                                        onclick="toggleReviewStatus(event, <?= $review->getId() ?>, 'accept')">Accept</button>
                                </a>
                                <button class="secondary-button"
                                    onclick="toggleReviewStatus(event, <?= $review->getId() ?>, 'reject')">Reject</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="list" id="booksList" style="display: none;">
                    <div class="empty-header-container" id="book-empty-header" ,
                        style="display:  <?= (empty($books)) ? 'initial' : 'none' ?> ">
                        <div class="inter-semibold-16">
                            No books to accept or reject
                        </div>
                    </div>
                    <?php foreach ($books as $book): ?>
                        <div class="card" id="book-card-<?= $book->id ?>" onclick="routeToDetails('<?= $book->id ?>')">
                            <div class="card-content">
                                <div class="card-header">
                                    <div class="inter-semibold-16">
                                        <?= $book->title ?>
                                    </div>
                                    <div class="inter-regular-12">
                                        <?= $book->authorsString ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        Description:
                                        <?= $book->description ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        Date of publication:
                                        <?= $book->date_of_publication ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        Page count:
                                        <?= $book->page_count ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        ISBN number:
                                        <?= $book->isbn_number ?>
                                    </div>

                                </div>
                            </div>
                            <div class="profile-card-right-side">

                                <a class="secondary_menu__item">
                                    <button onclick="toggleBookStatus(event, <?= $book->id ?>, 'accept')">Accept</button>
                                </a>

                                <button class="secondary-button"
                                    onclick="toggleBookStatus(event, <?= $book->id ?>, 'reject')">Reject</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="list" id="usersList" style="display: none;">
                    <? if (empty($users)): ?>
                        <div class="empty-header-container">
                            <div class="inter-semibold-16">
                                No users to accept or reject
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($users as $user): ?>
                        <div class="user-card" id="user-card-<?= $user->getId() ?>">
                            <div class="card-content">
                                <div class="card-header">
                                    <div class="inter-semibold-16">
                                        <?= $user->getName() . ' ' . $user->getSurname() ?>
                                    </div>
                                    <div class="dmsans-regular-14">
                                        E-mail:
                                        <?= $user->getEmail() ?>
                                    </div>
                                    <div class="inter-regular-12">
                                        Role:
                                        <?= $user->getRole() ?>
                                    </div>


                                </div>
                            </div>
                            <div class="profile-card-right-side">
                                <a class="secondary_menu__item">
                                    <button onclick="toggleUserStatus(event, <?= $user->getId() ?>)">
                                        <?php if ($user->getRole() == 'admin') {
                                            echo 'Remove admin';
                                        } else {
                                            echo 'Add admin';
                                        } ?>

                                    </button>
                                </a>
                                <button class="secondary-button"
                                    onclick="removeUser(event, <?= $user->getId() ?>, <?= ($currentUserId == $user->getId()) ? 'true' : 'false' ?>)">
                                    Delete account
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
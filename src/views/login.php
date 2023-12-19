<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/login.css">
    <link rel="stylesheet" type="text/css" href="/public/css/global.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>

    <title>Login</title>
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
                    <li><a class="menu__item" href="/admin">Admin</a></li>
                <?php endif; ?>
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
                <?php if ($isLogged): ?>
                    <li><a href="/profile">Profile</a></li>
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
        <div class="page-content">
            <div id="login-container">
                <div id="logo">
                    Flipbook</div>
                <div id="sign-up">
                    Log in</div>
                <form class="login-form" action="login" method="POST">
                    <input type="text" id="username" name="email" placeholder="E-mail" required>
                    <div class="password-toggle">
                        <input type="password" name="password" id="password" placeholder="Password">
                        <div class="toggle-button" onclick="togglePassword()"><i class="material-icons"
                                id="visibilityIcon">visibility</i></div>
                    </div>
                    <div id="validation">
                        <?php if (isset($messages)) {
                            foreach ($messages as $message) {
                                echo $message;
                            }
                        } ?>
                    </div>
                    <button type="submit">Sign in</button>

                </form>
                <script>
                    function togglePassword() {
                        const passwordInput = document.getElementById('password');
                        const visibilityIcon = document.getElementById('visibilityIcon');

                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            visibilityIcon.textContent = 'visibility_off';
                        } else {
                            passwordInput.type = 'password';
                            visibilityIcon.textContent = 'visibility';
                        }
                    }
                </script>
                <div id="have-account-row">
                    <div id="have-account">
                        You don't have an account?</div>
                    <div id="have-account-answer">
                        Sign Up</div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
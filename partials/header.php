<?php
session_start();
?>

<header class="main-title">

    <h1>Photo Impact</h1>

    <p>Inmortalizing your best moments</p>

</header>

<ul class="topnav">

    <li>
        <a id="link-home" href="../Page/index.php">
            Home
        </a>
    </li>

    <li class="dropdown">

        <a href="javascript:void(0)" class="dropbtn">
            Our Services
        </a>

        <div class="dropdown-content">

            <a href="../Page/quinceanos.php">
                Quinceaños
            </a>

            <a href="../Page/wedding.php">
                Weddings
            </a>

            <a href="../Page/event.php">
                Private events
            </a>

            <a href="../Page/photoshoot.php">
                Photoshoot
            </a>

        </div>

    </li>

    <li>
        <a id="link-quote" href="../user/make-a-quote.php">
            Make a quote
        </a>
    </li>

    <li>
        <a id="about" href="../Page/about.php">
            About us
        </a>
    </li>

    <li>
        <a id="contact" href="../Page/contactUs.php">
            Contact us
        </a>
    </li>

    <?php if (
        isset($_SESSION["role"]) &&
        $_SESSION["role"] === "admin"
    ): ?>

        <!-- ADMIN LINKS -->

        <li>
            <a
                id="manage-user-link"
                href="../admin/manage-users.php"
            >
                Users
            </a>
        </li>

        <li>
            <a
                id="manage-quotes-link"
                href="../admin/manage-quotes.php"
            >
                Quotes
            </a>
        </li>

        <li>
            <a
                id="manage-bookings-link"
                href="../admin/manage-bookings.php"
            >
                Bookings
            </a>
        </li>

    <?php endif; ?>

    <div class="nav-auth">

        <?php if (isset($_SESSION["user_id"])): ?>

            <li>

                <a
                    class="nav-login"
                    href="../Page/logout.php"
                >
                    Log out
                </a>

            </li>

        <?php else: ?>

            <li>

                <a
                    id="nav-signUp"
                    href="../Page/signUp.php"
                >
                    Sign up
                </a>

            </li>

            <li>

                <a
                    class="nav-login"
                    href="../Page/login.php"
                >
                    Log in
                </a>

            </li>

        <?php endif; ?>

    </div>

</ul>
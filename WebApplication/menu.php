<?php
include_once 'util.php';
my_session_start();
?>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <link rel="icon" href="img/favicon.png" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="css/responsive.css">
        <link href="css/StyleSheet1.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    </head>
    <body>
        <!--================Header Menu Area =================-->
        <header class="header_area">
            <div class="main_menu">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container">
                        <p class="navbar-brand logo_h"><img src="img/favicon.png" alt="">  Access To Database</p> 
                    </div>

                    <div style="position: absolute;right: 200px;">
                        <?php
                            if (isset($_SESSION["user_email_address"])) {
                        ?>
                                <span id="login">
                        <?php
                                if (isset($_SESSION["user_firstname"])) {
                                    echo "Hi,".$_SESSION['user_firstname'];
                                }
                        ?>
                                &nbsp;  <a href='logout.php'>Logout</a>
                                </span>
                        <?php
                            }
                            else {
                        ?>
                                <span id="login"><a href='login.php'>Login</a></span>
                        <?php
                            }
                        ?>
                    </div>
                </nav>
                <div class="container">
                    <div class="row" style="background: #4dbf1c; line-height: 40px; border-radius: 5px;">
                        <a href="accessdb.php" id="database_dashboard" class="link_btn">Database</a>
                        <a href="manage_concepts.php" id="manage_concepts" class="link_btn">Concepts</a>
                        <?php
                        // if the current user is an administrator, add those options to the menu
                        if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "Admin") {
                        ?>
                            <a href="admin_console.php" id="admin_console" class="link_btn">Admin Console</a>
                        <?php
                        }
                        ?>
                    </div>
                    <br>
                </div>
            </div>
        </header>
    </body>
</html>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
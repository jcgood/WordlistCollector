<?php
function my_session_start()
{
    if (empty($_SESSION)) {
        session_start();
    }
}

function my_session_destroy()
{
    if (!empty($_SESSION)) {
        session_destroy();
    }
}

function my_session_unset()
{
    if (!empty($_SESSION)) {
        session_unset();
    }
}

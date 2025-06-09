<?php
session_start();
session_unset();
session_destroy();
if (file_exists('cart_data.json')) {
    unlink('cart_data.json');
}
header('Location: index.php');

<?php
if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
    header('Location: index.php');
    exit();
}
?>
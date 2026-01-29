<?php

include 'config.php';

if(!isset($_SESSION['id'])){
    header('Location: connexion.php');
    exit();
}
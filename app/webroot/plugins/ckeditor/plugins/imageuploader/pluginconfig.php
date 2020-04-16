<?php

// checking lang value
if(isset($_COOKIE['sy_lang'])) {
    $load_lang_code = $_COOKIE['sy_lang'];
} else {
    $load_lang_code = "en";
}

// including lang files
switch ($load_lang_code) {
    case "en":
        require(__DIR__ . '/lang/en.php');
        break;
    case "pl":
        require(__DIR__ . '/lang/pl.php');
        break;
    case "ru":
        require(__DIR__ . '/lang/ru.php');
        break;
    case "ro":
        require(__DIR__ . '/lang/ro.php');
        break;
}

if(isset($_POST["newpath"]) or isset($_POST["extension"]) or isset($_GET["file_style"])){
    session_start();
}

// Version of the plugin
$currentpluginver = "4.1.8";

// Show/Hide the settings button
$show_settings = false;

// username and password
$username = "";
$password = "";

// ststem icons
$sy_icons = array(
    "cd-ico-browser.ico",
    "cd-icon-block.png",
    "cd-icon-browser.png",
    "cd-icon-bug.png",
    "cd-icon-close-black.png",
    "cd-icon-close-grey.png",
    "cd-icon-close.png",
    "cd-icon-coffee.png",
    "cd-icon-credits.png",
    "cd-icon-delete.png",
    "cd-icon-disable.png",
    "cd-icon-done.png",
    "cd-icon-download.png",
    "cd-icon-edit.png",
    "cd-icon-english.png",
    "cd-icon-faq.png",
    "cd-icon-german.png",
    "cd-icon-hideext.png",
    "cd-icon-image.png",
    "cd-icon-images.png",
    "cd-icon-list.png",
    "cd-icon-logout.png",
    "cd-icon-password.png",
    "cd-icon-polish.png",
    "cd-icon-qedit.png",
    "cd-icon-qtrash.png",
    "cd-icon-refresh.png",
    "cd-icon-select.png",
    "cd-icon-settings.png",
    "cd-icon-showext.png",
    "cd-icon-translate.png",
    "cd-icon-updates.png",
    "cd-icon-upload-big.png",
    "cd-icon-upload-grey.png",
    "cd-icon-upload.png",
    "cd-icon-use.png",
    "cd-icon-version.png",
    "cd-icon-warning.png",
);

// show/hide file extension
$file_extens = $_COOKIE["file_extens"];

// show/hide news section
$news_sction = "no";

// file_style
$file_style = "block";

// Path to the upload folder, please set the path using the Image Browser Settings menu.

$foldershistory = array();
$useruploadroot = "http://$_SERVER[HTTP_HOST]";
$browserfolder = pathinfo("$_SERVER[REQUEST_URI]");
$browserfolder = ltrim($browserfolder["dirname"], '/');
$usersiteroot = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
$useruploadfolder = "$browserfolder/uploads";
$useruploadpath = $usersiteroot."/$useruploadfolder/";
$foldershistory[] = $useruploadfolder;

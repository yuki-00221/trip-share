<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripShare</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/nav.js" defer></script>
    <?php
    if (function_exists('extra_head')) {
        extra_head();
    }
    ?>
</head>

<body>
    <?php include 'templates/nav.php'; ?>
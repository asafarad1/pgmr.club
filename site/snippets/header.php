<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$site->title() ?></title>
    <link rel="stylesheet" href="https://use.typekit.net/ynz2pzx.css">
    <?=css( "assets/css/style.css?v=" . filemtime( $kirby->root() . "/assets/css/style.css" ) ) ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-29LPXNC6NF"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-29LPXNC6NF');
    </script>
</head>
<body>
    
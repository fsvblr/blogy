<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{block name=title}Blogy{/block}</title>
        <link rel="stylesheet" href="/resources/dist/css/style.css">
    </head>
    <body>
        <header class="main-header">
            <div class="container">
                <a href="/" class="logo">Blogy</a>
            </div>
        </header>
        <main class="main-content">
            <div class="container">
                {block name=content}{/block}
            </div>
        </main>
        <footer class="main-footer">
            <div class="container">
                <p>Copyright © 2026. All Rights Reserved.</p>
            </div>
        </footer>
        <script src="/resources/dist/js/main.js"></script>
    </body>
</html>
{extends file='index.tpl'}

{block name=title}Blogy - Page Not Found (404){/block}

{block name=content}
    <div class="error-page-container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Page Not Found</h2>
            <p class="error-text">Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>

            <div class="error-actions">
                <a href="/" class="back-home-btn">Back to Homepage</a>
            </div>
        </div>
    </div>
{/block}

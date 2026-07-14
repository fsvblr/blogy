{extends file='index.tpl'}

{block name=title}Blogy - {$article.title|escape}{/block}

{block name=content}
    <article class="single-article-page">

        <header class="article-header">
            <h1>{$article.title|escape}</h1>

            <div class="article-meta-panel">
                <span class="meta-item date">📅 {$article.created}</span>
                <span class="meta-item views">👁️ Hits: {$article.hits}</span>

                {if !empty($article.categories)}
                    <span class="meta-item article-categories">
                        📁 Categories:
                        {foreach $article.categories as $cat}
                            <a href="/category/{$cat.slug|escape}" class="meta-category-link">{$cat.title|escape}</a>{if !$cat@last}, {/if}
                        {/foreach}
                    </span>
                {/if}
            </div>
        </header>

        <div class="main-article-image">
            <img src="/resources/images/{$article.image}" alt="{$article.title|escape}">
        </div>

        <div class="article-content">
            <p class="intro-text"><strong>{$article.introtext|escape}</strong></p>
            <div class="full-text">
                {$article.fulltext|escape|nl2br}
            </div>
        </div>

        {if !empty($article.related)}
            <section class="related-articles-section">
                <div class="related-header">
                    <h2>Related Articles</h2>
                </div>

                <div class="articles-grid">
                    {foreach $article.related as $related_item}
                        <article class="article-card">
                            <div class="article-image">
                                <img src="/resources/images/{$related_item.image}" alt="{$related_item.title|escape}">
                            </div>
                            <div class="article-body">
                                <h3>{$related_item.title|escape}</h3>
                                <p class="article-text">{$related_item.introtext|truncate:150:"..."|escape}</p>
                                <a href="/article/{$related_item.slug|escape}" class="read-more">Continue Reading</a>
                            </div>
                        </article>
                    {/foreach}
                </div>
            </section>
        {/if}

    </article>
{/block}

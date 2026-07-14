{extends file='index.tpl'}

{block name=title}Blogy - Home page{/block}

{block name=content}
    {foreach $categories as $category}
        {if !empty($category.articles)}
            <section class="category-section">
                <div class="category-header">
                    <h2>{$category.title|escape}</h2>
                    <a href="/category/{$category.slug|escape}" class="view-all-btn">View All</a>
                </div>

                <div class="articles-grid">
                    {foreach $category.articles as $article}
                        <article class="article-card">
                            <div class="article-image">
                                <img src="/resources/images/{$article.image}" alt="{$article.title|escape}">
                            </div>
                            <div class="article-body">
                                <h3>{$article.title|escape}</h3>
                                <p class="article-text">{$article.introtext|truncate:200:"..."|escape}</p>
                                <a href="/article/{$article.slug|escape}" class="read-more">Continue Reading</a>
                            </div>
                        </article>
                    {/foreach}
                </div>
            </section>
        {/if}
    {/foreach}
{/block}

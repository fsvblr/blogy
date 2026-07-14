{extends file='index.tpl'}

{block name=title}Blogy - Category "{$category.title|escape}"{/block}

{block name=content}
    <div class="category-page">
        <header class="category-page-header">
            <h1>{$category.title|escape}</h1>
            {if !empty($category.description)}
                <p class="category-description">{$category.description|escape}</p>
            {/if}
        </header>

        {if !empty($category.articles)}
            <div class="sorting-panel">
                <form method="GET" action="#">
                    <label for="orderBy">Sort by:</label>
                    <select name="orderBy" id="orderBy" onchange="this.form.submit()">
                        <option value="created" {if isset($smarty.get.orderBy) && $smarty.get.orderBy == 'created' || !isset($smarty.get.orderBy)}selected{/if}>Publication date</option>
                        <option value="hits" {if isset($smarty.get.orderBy) && $smarty.get.orderBy == 'hits'}selected{/if}>Number of views</option>
                    </select>

                    <select name="orderDir" id="orderDir" onchange="this.form.submit()">
                        <option value="DESC" {if isset($smarty.get.orderDir) && $smarty.get.orderDir == 'DESC' || !isset($smarty.get.orderDir)}selected{/if}>Descending</option>
                        <option value="ASC" {if isset($smarty.get.orderDir) && $smarty.get.orderDir == 'ASC'}selected{/if}>Ascending</option>
                    </select>

                    <input type="hidden" name="page" value="1">
                </form>
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
                            <div class="article-meta">👁️ Hits: {$article.hits}</div>
                            <a href="/article/{$article.slug|escape}" class="read-more">Continue Reading</a>
                        </div>
                    </article>
                {/foreach}
            </div>

            {if $category.pagination.totalPages > 1}
                {if isset($smarty.get.page)}
                    {assign var="current_page" value=$smarty.get.page}
                {else}
                    {assign var="current_page" value=1}
                {/if}

                {if isset($smarty.get.orderBy)}
                    {assign var="url_order" value=$smarty.get.orderBy}
                {else}
                    {assign var="url_order" value="created"}
                {/if}

                {if isset($smarty.get.orderDir)}
                    {assign var="url_dir" value=$smarty.get.orderDir}
                {else}
                    {assign var="url_dir" value="DESC"}
                {/if}

                <nav class="pagination">
                    {* Back button *}
                    {if $current_page > 1}
                        <a href="?page={$current_page - 1}&orderBy={$url_order}&orderDir={$url_dir}" class="page-link">&laquo; Back</a>
                    {/if}

                    {* Display page numbers from 1 to totalPages *}
                    {for $p=1 to $category.pagination.totalPages}
                        <a href="?page={$p}&orderBy={$url_order}&orderDir={$url_dir}"
                           class="page-link {if $p == $current_page}active{/if}">
                            {$p}
                        </a>
                    {/for}

                    {* Next button *}
                    {if $current_page < $category.pagination.totalPages}
                        <a href="?page={$current_page + 1}&orderBy={$url_order}&orderDir={$url_dir}" class="page-link">Next &raquo;</a>
                    {/if}
                </nav>
            {/if}

        {else}
            <div class="no-articles-message">
                <p>There are no published articles in this category yet.</p>
                <a href="/" class="back-to-home">To the home page</a>
            </div>
        {/if}

    </div>
{/block}

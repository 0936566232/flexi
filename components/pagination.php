<div class="pagination">
    <?php
    echo paginate_links(array(
        'base' => get_pagenum_link(1) . '%_%',
        'format' => '/page/%#%',
        'current' => $paged,
        'total' => $query->max_num_pages,
    ));
    ?>
</div>

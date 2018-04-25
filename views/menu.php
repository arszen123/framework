<ul id="menu">
    <!-- TODO -->
    <?php foreach ($menu as $key => $item) : ?>
        <li <?= (ltrim($uri, '/')=== ltrim($key,'/'))? 'class="active"':''?>><a href="/ql0sz4/<?= $key ?>"><?= $item ?></a></li>
    <?php endforeach; ?>
</ul>
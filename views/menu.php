<ul id="menu">
    <?php foreach ($menu as $key => $item) : ?>
        <li <?= (ltrim($uri, '/')=== ltrim($key,'/'))? 'class="active"':''?>><a href="<?= $key ?>"><?= $item ?></a></li>
    <?php endforeach; ?>
</ul>
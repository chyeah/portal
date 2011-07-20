
</section>
<section id="sidebar">
    <p><?php echo time(); ?></p>
    <?php
    if(!empty($side_nav))
    {
        echo '<ul>';
        foreach($side_nav as $link)
            echo '<li>' . $link . '</li>';
        echo '</ul>';
    }
    ?>
</section>
</div>
<footer>
    <div><s>About</s> &middot; <s>Legal</s> &middot; <s>Sitemap</s></div>
    <p>Copyright &copy; All rights reserved, 2011. Version <?php echo $version; ?></p>
</footer>
</body>
</html>
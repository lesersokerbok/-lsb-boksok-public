<header class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="container">

    <div class="navbar-header">
      <ul class="nav navbar-nav pull-left">
        <li>
          <a class="navbar-nav-brand" href="<?php echo home_url(); ?>/">
            <?php bloginfo( 'name' ) ?>
          </a>
        </li>
      </ul>
      
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
        <div class="hamburger">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </div>
      </button>
    </div>

    <nav class="collapse navbar-collapse" role="navigation">
      <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'pull-left nav navbar-nav'));
        endif;
      ?>

      <?php
        if (has_nav_menu('secondary_navigation')) :
          wp_nav_menu(array('theme_location' => 'secondary_navigation', 'menu_class' => 'pull-right icon-nav nav navbar-nav', 'link_before' => '<span>', 'link_after' => '</span>'));
        endif;
      ?>
    </nav>

  </div>
</header>

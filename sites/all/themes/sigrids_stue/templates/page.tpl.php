<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <title><?php print $head_title; ?></title>
    <?php print $head; ?>
    <?php print $styles; ?>
    <?php print $scripts; ?>
  </head>
  <body class="<?php print $body_classes; ?>"<?php print drupal_attributes($attr)?>>

  <?php if ($site_aak_topbar): ?>
    <?php print $site_aak_topbar ?>
  <?php endif; ?>
  <?php if ($topbar): ?>
    <div class="topbar">
      <div class="topbar-inner container-12 grid-12">
        <?php print $topbar; ?>
      </div>
    </div>
  <?php endif; ?>
  <div class="header container-12 grid-12">
    <div class="header-inner">

      <?php if ($site_logo): ?>
        <div id="logo" class="logo"><?php print $site_logo ?></div>
      <?php endif; ?>

      <?php if ($site_slogan): ?>
        <div id="site-slogan" class="site-slogan"><?php print $site_slogan ?></div>
      <?php endif; ?>

      <?php if ($header): ?>
        <?php print $header; ?>
      <?php endif; ?>
    </div>
  </div>

  <div id="page" class="container-12 grid-12">
    <div class="page-inner">
      <?php if ($top_region): ?>
        <div id="top-region" class="region">
          <?php print $top_region; ?>
        </div>
      <?php endif ?>

      <?php if ($left ): ?>
        <div id="left" class="<?php print ns('grid-4', $right, 1, 'rc', 3); ?> alpha region">
          <?php print $left; ?>
        </div>
      <?php endif ?>

      <?php if ($is_front): ?>
        <div id="main" class="<?php print ns('grid-8', $left && !$right, 4, $left && $right, 3, $right && !$left, 4, $right && $left, 3, 'rc', 9); ?> alpha omega">
      <?php endif ?>
      <?php if (!$is_front): ?>
        <div id="main" class="<?php print ns('grid-12', $left && !$right, 4, $left && $right, 3, $right && !$left, 4, $right && $left, 3, 'rc', 9); ?> alpha omega">
      <?php endif ?>
        <div id="main-inner">
          <?php print $messages; ?>
          <?php print $help; ?>

          <?php if ($tabs): ?>
            <div class="tabs">
              <?php print $tabs; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($title)): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>

          <?php if ($content_top): ?>
            <div id="content-top" class="region"><?php print $content_top ?></div>
          <?php endif; ?>
          <div id="content" class=""><?php print $content ?></div>
      </div>

      <?php if ($content_bottom): ?>
        <div id="content-bottom" class="grid-8 region"><?php print $content_bottom ?></div>
      <?php endif; ?>

    </div>

    </div>
  </div>

  <?php print $closure ?>
  </body>
</html>
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

      <?php if ($mission): ?>
        <div id="mission" class="mission"><p><?php print $mission ?></p></div>
      <?php endif; ?>

      <div id="main" class="<?php print ns('grid-12', $left && !$right, 6, $left && $right, 3, $right && !$left, 6, $right && $left, 3, 'rc', 9); ?>">

        <?php print $messages; ?>
        <?php print $help; ?>

        <?php if ($tabs): ?>
          <div class="tabs">
            <?php print $tabs; ?>
          </div>
        <?php endif; ?>

        <?php if (!$is_front && !empty($title)): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>

        <?php if ($content_top): ?>
          <div id="content-top" class="region"><?php print $content_top ?></div>
        <?php endif; ?>
        <div id="content" class=""><?php print $content ?></div>
        <?php if ($content_bottom): ?>
          <div id="content-bottom" class="region"><?php print $content_bottom ?></div>
        <?php endif; ?>
      </div>
 
      <?php if ($right): ?>
        <div id="right" class="<?php print ns('grid-4', $left, 1, 'rc', 3); ?> region">
          <?php print $right; ?>
        </div>
      <?php endif ?>
    </div>
  </div>

  <?php if ($three_column_first || $three_column_second || $three_column_last): ?>
    <div class="three-column">
      <div class="container-12">
      <?php if ($three_column_first): ?>
        <div class="three-column grid-4 first region">
          <?php print $three_column_first; ?>
        </div>
      <?php endif ?>

      <?php if ($three_column_second): ?>
        <div class="three-column grid-4 second region">
          <?php print $three_column_second; ?>
        </div>
      <?php endif ?>

      <?php if ($three_column_last): ?>
        <div class="three-column grid-4 last region">
          <?php print $three_column_last; ?>
        </div>
      <?php endif ?>
      </div>
    </div>
  <?php endif ?>

  <?php print $closure ?>
  </body>
</html>
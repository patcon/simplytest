<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
?>

<div id="page-top">
  <div id="header-wrap">
    <header id="header" class="clearfix" role="banner">
      <hgroup>
        <div id="site-cred-outer">
          <div id="site-cred-inner">
            <?php if ($logo): ?>
            <div id="logo">
              <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" /></a>
              </div>
            <?php endif; ?>
            <div id="sitename-slogan">
              <div id="sitename">
                <h1><a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"><?php print $site_name; ?></a></h1>
              </div>
              <?php if ($site_slogan): ?>
              <div id="site-slogan">
                <?php print $site_slogan; ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </hgroup>
      <nav id="navigation" class="clearfix" role="navigation">
        <div id="menu-menu-container">
         <div id="main-menu">
          <?php 
            if (module_exists('i18n')) {
              $main_menu_tree = i18n_menu_translated_tree(variable_get('menu_main_links_source', 'main-menu'));
            } else {
              $main_menu_tree = menu_tree(variable_get('menu_main_links_source', 'main-menu'));
            }
            print drupal_render($main_menu_tree);
          ?>
         </div>
        </div>
      </nav>
    </header>
  </div>
  <?php if($page['featured_help'] && $page['featured']) : ?>
    <div id="header-help-flag"><a href="#">What is this?</a></div>
  <?php endif; ?>

  <div id="homepage-featured-wrap">
    <div id="homepage-featured-pattern">
      <?php if($page['featured']) : ?>
      <div id="homepage-featured">
        <?php if(!$page['content']) : ?>
          <?php print $messages; ?>
        <?php endif; ?>
          <div class="column B">
            <?php print render ($page['featured']); ?>
          </div>
      </div>
      <?php if($page['featured_help']) : ?>
      <div id="header-help">
        <?php print render ($page['featured_help']); ?>
      </div>
       <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if($page['preface_first'] || $page['preface_middle'] || $page['preface_last'] || $page['content']) : ?>
<div id="wrap">
  <div id="main" class="clearfix">
  
   <?php if($page['preface_first'] || $page['preface_middle'] || $page['preface_last']) : ?>
    <div id="preface-wrapper" class="in<?php print (bool) $page['preface_first'] + (bool) $page['preface_middle'] + (bool) $page['preface_last']; ?> clearfix">
          <?php if($page['preface_first']) : ?>
          <div class="column A">
            <?php print render ($page['preface_first']); ?>
          </div>
          <?php endif; ?>
          <?php if($page['preface_middle']) : ?>
          <div class="column B">
            <?php print render ($page['preface_middle']); ?>
          </div>
          <?php endif; ?>
          <?php if($page['preface_last']) : ?>
          <div class="column C">
            <?php print render ($page['preface_last']); ?>
          </div>
          <?php endif; ?>
    </div>
    <?php endif; ?>
  
    <?php print render($page['header']); ?>
    
    <?php print render($page['secondary_content']); ?>
    
    <section id="post-content" role="main">
      <?php if (theme_get_setting('breadcrumbs')): ?><div id="breadcrumbs"><?php if ($breadcrumb): print $breadcrumb; endif;?></div><?php endif; ?>
      <?php print $messages; ?>
      <?php if ($page['content_top']): ?><div id="content_top"><?php print render($page['content_top']); ?></div><?php endif; ?>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?><h1 class="page-title"><?php print $title; ?></h1><?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php if (!empty($tabs['#primary'])): ?><div class="tabs-wrapper clearfix"><?php print render($tabs); ?></div><?php endif; ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
      <?php print render($page['content']); ?>
    </section> <!-- /#main -->

    <?php if ($page['sidebar_first']): ?>
      <aside id="sidebar" role="complementary" class="sidebar clearfix">
       <?php print render($page['sidebar_first']); ?>
      </aside>  <!-- /#sidebar-second -->
    <?php endif; ?>

    <div class="clear"></div>
    <?php print render($page['footer']); ?>
    
    <?php if($page['bottom_first'] || $page['bottom_middle'] || $page['bottom_last']) : ?>
    <div id="bottom-teaser" class="in<?php print (bool) $page['bottom_first'] + (bool) $page['bottom_middle'] + (bool) $page['bottom_last']; ?>">
          <?php if($page['bottom_first']) : ?>
          <div class="column A">
            <?php print render ($page['bottom_first']); ?>
          </div>
          <?php endif; ?>
          <?php if($page['bottom_middle']) : ?>
          <div class="column B">
            <?php print render ($page['bottom_middle']); ?>
          </div>
          <?php endif; ?>
          <?php if($page['bottom_last']) : ?>
          <div class="column C">
            <?php print render ($page['bottom_last']); ?>
          </div>
          <?php endif; ?>
    </div> <!-- end bottom first etc. -->
    <?php endif; ?>

  </div>
</div>
<?php endif; ?>
<div id="footer-wrap">
  <?php if ($page['footer_first'] || $page['footer_second'] || $page['footer_third'] || $page['footer_fourth']): ?>
  <div id="bottom">
    <div id="bottom-wrapper" class="in<?php print (bool) $page['footer_first'] + (bool) $page['footer_second'] + (bool) $page['footer_third'] + (bool) $page['footer_fourth']; ?> clearfix">
     <?php if ($page['footer_first']): ?>
      <div class="column A"><?php print render($page['footer_first']); ?></div>
      <?php endif; ?>
      <?php if ($page['footer_second']): ?>
      <div class="column B"><?php print render($page['footer_second']); ?></div>
      <?php endif; ?>
      <?php if ($page['footer_third']): ?>
      <div class="column C"><?php print render($page['footer_third']); ?></div>
      <?php endif; ?>
      <?php if ($page['footer_fourth']): ?>
      <div class="column D"><?php print render($page['footer_fourth']); ?></div>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<div id="footer-bottom-wrap">
  <div id="footer-bottom">
    <div id="powered-by">
      <span><?php print t('Powered by'); ?> <a href="http://drupal.org">Drupal</a></span>
    </div>
    <?php if ($page['footer_menu']): ?>
      <div id="footer-menu-wrap">
        <?php print render($page['footer_menu']); ?>
      </div>
    <?php endif; ?>
    <div id="created-by">
      <span><?php print t('Created by'); ?> <a href="http://patrickd.de/">patrickd</a></span>
    </div>
  </div>
</div>

<?php
/**
 * Created by PhpStorm.
 * User: kwickham
 * Date: 2/15/17
 * Time: 12:07 PM
 */

global $WPISUTheme;


//echo $WPISUTheme->renderNavbarSiteSearch();
?>
<form role="search" method="get"
      action="<?php echo $WPISUTheme->escape( $WPISUTheme->getOption( 'search_action' ) ) ?>">
    <div class="form-group">
        <label>
            <span class="screen-reader-text"><?php _x( 'Search for:', 'label' ) ?></span>
            <input type="search" class="search-field form-control"
                   placeholder="<?php echo $WPISUTheme->escape( $WPISUTheme->getOption( 'search_placeholder' ) ) ?>"
                   name="s"/>
        </label>
        <input type="submit" class="search-submit btn"
               value="<?php echo $WPISUTheme->escape( $WPISUTheme->getOption( 'search_submit' ) ) ?>">
    </div>
</form>
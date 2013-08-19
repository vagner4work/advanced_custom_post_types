<?php
/**
 * Fork of the WordPress screen options
 * User: kevindees
 * Date: 8/16/13
 * Time: 10:26 PM
 * To change this template use File | Settings | File Templates.
 */

class acpt_layout extends acpt {

  private $_tabs = array();
  private $_sidebar = null;

  /**
   * Gets the help tabs registered for the screen.
   *
   * @since 3.4.0
   *
   * @return array Help tabs with arguments.
   */
  public function get_tabs() {
    return $this->_tabs;
  }

  /**
   * Gets the arguments for a help tab.
   *
   * @since 3.4.0
   *
   * @param string $id Help Tab ID.
   * @return array Help tab arguments.
   */
  public function get_tab( $id ) {
    if ( ! isset( $this->_tabs[ $id ] ) )
      return null;
    return $this->_tabs[ $id ];
  }

  /**
   * Add a help tab to the contextual help for the screen.
   * Call this on the load-$pagenow hook for the relevant screen.
   *
   * @since 3.3.0
   *
   * @param array $args
   * - string   - title    - Title for the tab.
   * - string   - id       - Tab ID. Must be HTML-safe.
   * - string   - content  - Help tab content in plain text or HTML. Optional.
   * - callback - callback - A callback to generate the tab content. Optional.
   * @return $this
   */
  public function add_tab( $args ) {
    $defaults = array(
      'title'    => false,
      'id'       => false,
      'content'  => '',
      'callback' => false,
    );
    $args = wp_parse_args( $args, $defaults );

    $args['id'] = sanitize_html_class( $args['id'] );

    // Ensure we have an ID and title.
    if ( ! $args['id'] || ! $args['title'] )
      return;

    // Allows for overriding an existing tab with that ID.
    $this->_tabs[ $args['id'] ] = $args;

    return $this;
  }

  /**
   * Removes a help tab from the contextual help for the screen.
   *
   * @since 3.3.0
   *
   * @param string $id The help tab ID.
   */
  public function remove_tab( $id ) {
    unset( $this->_tabs[ $id ] );
  }

  /**
   * Removes all help tabs from the contextual help for the screen.
   *
   * @since 3.3.0
   */
  public function remove_tabs() {
    $this->_tabs = array();
  }

  /**
   * Gets the content from a contextual help sidebar.
   *
   * @since 3.4.0
   *
   * @return string Contents of the help sidebar.
   */
  public function get_sidebar() {
    return $this->_sidebar;
  }

  /**
   * Add a sidebar to the contextual help for the screen.
   * Call this in template files after admin.php is loaded and before admin-header.php is loaded to add a sidebar to the contextual help.
   *
   * @since 3.3.0
   *
   * @param string $content Sidebar content in plain text or HTML.
   */
  public function set_sidebar( $content ) {
    $this->_sidebar = $content;
  }

  /**
   * Render the screen's help section.
   *
   * This will trigger the deprecated filters for backwards compatibility.
   *
   * @since 3.3.0
   */
  public function make($style = 'default') {
    switch($style) {
      case 'default' :
        $this->help_style_tabs();
        break;
      case 'metabox' :
        $this->metabox_style_tabs();
        break;
    }

  }

  private function metabox_style_tabs() {
    ?>
    <div class="tabbed">
    <div class="tabbed-sections">
      <ul class="acpt-tabs alignleft">
        <?php
        $class = ' class="active"';
        $tabs = $this->get_tabs();
        foreach ( $tabs as $tab ) :
          $link_id  = "tab-link-{$tab['id']}";
          $panel_id = "tab-panel-{$tab['id']}";
          ?>
          <li id="<?php echo esc_attr( $link_id ); ?>"<?php echo $class; ?>>
            <a href="<?php echo esc_url( "#$panel_id" ); ?>">
              <?php echo esc_html( $tab['title'] ); ?>
            </a>
          </li>
          <?php
          $class = '';
        endforeach;
        ?>
      </ul>
    </div>
    <div class="acpt-sections clearfix">
      <?php
      $classes = 'tab-section active';
      foreach ( $tabs as $tab ):
        $panel_id = "tab-panel-{$tab['id']}";
        ?>

        <div id="<?php echo esc_attr( $panel_id ); ?>" class="<?php echo $classes; ?>">
          <?php
          // Print tab content.
          echo $tab['content'];

          // If it exists, fire tab callback.
          if ( ! empty( $tab['callback'] ) )
            call_user_func_array( $tab['callback'], array( $this, $tab ) );
          ?>
        </div>
        <?php
        $classes = 'tab-section';
      endforeach;
      ?>
    </div>
    </div>
    <?php
  }

  private function help_style_tabs() {
    // Default help only if there is no old-style block of text and no new-style help tabs.
    $help_sidebar = $this->get_sidebar();

    $help_class = '';
    if ( ! $help_sidebar ) :
      $help_class .= ' no-sidebar';
    endif;

    // Time to render!
    ?>
    <div id="screen-meta" class="acpt-options metabox-prefs">

      <div id="contextual-help-wrap" class="<?php echo esc_attr( $help_class ); ?>" >
        <div id="contextual-help-back"></div>
        <div id="contextual-help-columns">
          <div class="contextual-help-tabs">
            <ul>
              <?php
              $class = ' class="active"';
              $tabs = $this->get_tabs();
              foreach ( $tabs as $tab ) :
                $link_id  = "tab-link-{$tab['id']}";
                $panel_id = "tab-panel-{$tab['id']}";
                ?>
                <li id="<?php echo esc_attr( $link_id ); ?>"<?php echo $class; ?>>
                  <a href="<?php echo esc_url( "#$panel_id" ); ?>">
                    <?php echo esc_html( $tab['title'] ); ?>
                  </a>
                </li>
                <?php
                $class = '';
              endforeach;
              ?>
            </ul>
          </div>

          <?php if ( $help_sidebar ) : ?>
            <div class="contextual-help-sidebar">
              <?php echo $help_sidebar; ?>
            </div>
          <?php endif; ?>

          <div class="contextual-help-tabs-wrap">
            <?php
            $classes = 'help-tab-content active';
            foreach ( $tabs as $tab ):
              $panel_id = "tab-panel-{$tab['id']}";
              ?>

              <div id="<?php echo esc_attr( $panel_id ); ?>" class="inside <?php echo $classes; ?>">
                <?php
                // Print tab content.
                echo $tab['content'];

                // If it exists, fire tab callback.
                if ( ! empty( $tab['callback'] ) )
                  call_user_func_array( $tab['callback'], array( $this, $tab ) );
                ?>
              </div>
              <?php
              $classes = 'help-tab-content';
            endforeach;
            ?>
          </div>
        </div>
      </div>
    </div>
  <?php
  }

}
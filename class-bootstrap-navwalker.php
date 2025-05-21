<?php
class Bootstrap_Navwalker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="dropdown-menu">';
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = implode(' ', $item->classes);
        $active_class = in_array('current-menu-item', $item->classes) ? ' active' : '';
        $output .= '<li class="nav-item ' . $classes . '">';
        $output .= '<a class="nav-link' . $active_class . '" href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
    }
}
?>

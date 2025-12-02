<?php
function nav_link($href, $label, $iconSvg) {
    $current = $_SERVER['REQUEST_URI'];
    
    $target = str_replace('../', '', $href);

    $active = strpos($current, $target) == true;

    $baseClass = "flex items-center gap-3 font-medium transition";
    $activeClass = $active ? "text-blue-600 font-semibold" : "text-gray-700 hover:text-blue-600";

    echo "
        <a href='$href' class='$baseClass $activeClass'>
            <span>$iconSvg</span>
            $label
        </a>
    ";
}

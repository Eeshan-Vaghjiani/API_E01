<?php
    require_once "load.php";
    $ObjLayouts->heading();
    $ObjMenus->main_menu();
    echo '<div class="row">';
    $ObjContents->about_content(); // Load the main content of the page
    $ObjContents->sidebar();      // Load the sidebar content
    echo '</div>';
    $ObjLayouts->footer();
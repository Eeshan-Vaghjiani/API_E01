<?php
    // Load all required classes
    require_once "load.php";
    
    // Output the page structure
    $ObjLayouts->heading();       // Load the heading and opening tags
    
    $ObjMenus->main_menu();       // Load the main navigation menu
    $ObjContents->slideshow();
    // Main content and sidebar structure in a row
    echo '<div class="row">';
    $ObjContents->main_content(); // Load the main content of the page
    $ObjContents->sidebar();      // Load the sidebar content
    echo '</div>';
    
    $ObjLayouts->footer();        // Load the footer and closing tags
?>

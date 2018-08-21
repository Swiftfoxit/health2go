<?php
add_action('jobboard_search_form', 'jb_search_form_before', 5);
add_action('jobboard_search_form', 'jb_search_form_keywords', 10);
add_action('jobboard_search_form', 'jb_search_form_types', 20);
add_action('jobboard_search_form', 'jb_search_form_specialisms', 30);
add_action('jobboard_search_form', 'jb_search_form_locations', 40);
add_action('jobboard_search_form', 'jb_search_form_actions', 50);
add_action('jobboard_search_form', 'jb_search_form_after', 100);
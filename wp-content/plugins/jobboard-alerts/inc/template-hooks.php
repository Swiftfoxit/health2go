<?php


add_action('jobboard_endpoint_employer_alerts', 'jb_alerts_form_alerts');
add_action('jobboard_endpoint_employer_notices', 'jb_alerts_form_notices');

add_action('jobboard_endpoint_candidate_alerts', 'jb_alerts_form_alerts');
add_action('jobboard_endpoint_candidate_notices', 'jb_alerts_form_notices');


add_action('jobboard_form_notices', 'jb_template_form_dynamic', 10);
add_action('jobboard_form_notices', 'jb_alerts_form_notices_actions', 20);
add_action('jobboard_form_alerts', 'jb_alerts_form_alerts_sections', 10);
add_action('jobboard_form_alerts', 'jb_alerts_form_alerts_actions', 20);
add_action('jobboard_form_alerts_fields', 'jb_template_form_dynamic');
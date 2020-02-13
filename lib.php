<?php

/**
 * This function extends the navigation with the tool items
 *
 * @param navigation_node $navigation The navigation node to extend
 * @param stdClass $course The course to object for the tool
 * @param context $context The context of the course
 * @throws coding_exception
 * @throws moodle_exception
 */
function enrol_presumar_extend_navigation_course($navigation, $course, $context) {
    global $DB;
    if (is_siteadmin()) {
        $url = new moodle_url('/local/eabccoursegrades/index.php', array('courseid' => $course->id));
        $settingsnode = navigation_node::create(get_string('ver_notas', 'local_eabccoursegrades'), $url, navigation_node::TYPE_SETTING,
            null, null, new pix_icon('i/settings', ''));
        $navigation->add_node($settingsnode);
    }
}
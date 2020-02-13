<?php
/**
 *
 * @author eimar
 */

defined('MOODLE_INTERNAL') || die();

include_once("$CFG->libdir/formslib.php");

class request_form extends moodleform
{
    public function definition()
    {
        global $CFG, $USER, $DB;
        $courses = get_courses();
        $mform = $this->_form;
        $mform->addElement('header', 'datos', 'Consultar');
        $list = array();
        $list[""] = get_string("choose");

        foreach ($courses as $id => $course) {
            if ($id == 1) {
                continue;
            }
            $list[$id] = $course->fullname;
        }
        
        $mform->addElement('autocomplete', 'courseid', 'Materia', $list, []);
        $mform->setDefault('courseid', '');
        $mform->addRule('courseid', get_string('required'), 'required');

        $this->add_action_buttons(false, 'Consultar');
    }

    /**
     * @param array $data
     * @param array $files
     * @return array
     * @throws dml_exception
     */
    function validation($data, $files)
    {
        global $DB, $CFG;
        $errors = parent::validation($data, $files);


        return $errors;
    }
}

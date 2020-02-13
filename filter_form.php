<?php

/**
 *
 * @author eimar
 */

use local_eabccoursegrades\utils;

defined('MOODLE_INTERNAL') || die();

include_once("$CFG->libdir/formslib.php");

class filter_form extends moodleform
{
    public function definition()
    {
        global $CFG, $USER, $DB;
        $mform = $this->_form;
        $mform->addElement('header', 'datos', 'Consultar');

        $planes = [];
        $planes[""] = get_string("choose");
        $planes = $planes + utils::get_options('plan');        
        $mform->addElement('select', 'plan', 'Plan', $planes);
        $mform->setDefault('plan', '');


        $turnos = [];
        $turnos[""] = get_string("choose");
        $turnos = $turnos + utils::get_options('turno');
        $mform->addElement('select', 'turno', 'Turno', $turnos);
        $mform->setDefault('turno', '');
        
        $cuatrimestres = [];
        $cuatrimestres[""] = get_string("choose");
        $cuatrimestres = $cuatrimestres + utils::get_options('cuatrimestre');
        $mform->addElement('select', 'cuatrimestre', 'Cuatrimestre', $cuatrimestres);
        $mform->setDefault('cuatrimestre', '');


        $mform->addElement('text', 'sede', 'Sede', 'maxlength="12" size="20"');
        $mform->setType('sede', PARAM_TEXT);
        $mform->setDefault('sede', '');

        $courses = get_courses();

        $list[""] = get_string("choose");
        $aux = [];
        foreach ($courses as $id => $course) {

            $teacher = utils::get_profesor($course);

            if (empty($teacher)) {
                continue;
            }
            if ($id == 1 || in_array($teacher->email, $aux)) {
                continue;
            }

            $aux[] = $teacher->email;

            $list[$id] = $teacher->lastname . ' ' . $teacher->firstname;
        }

        $mform->addElement('autocomplete', 'teacherid', 'Docente', $list, []);
        $mform->setDefault('teacherid', '');

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

<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External functions
 *
 * @package   format_galicia
 * @copyright 2019 Eimar Urbina  <eeimar@e-abclearning.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_eabccoursegrades\external;

use dml_exception;
use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use invalid_parameter_exception;
use local_eabccoursegrades\utils;

defined('MOODLE_INTERNAL') || die();

class course_grades extends external_api
{
    /**
     * @return external_function_parameters
     */
    public static function get_grades_data_parameters()
    {
        /**
         * parametros que acepta el ws
         */
        return new external_function_parameters(
            [
            ]
        );
    }

    /**
     * @param int $courseid
     * @param string $params
     * @return array
     * @throws dml_exception
     * @throws invalid_parameter_exception
     */
    public static function get_grades_data()
    {
        global $CFG;

        $params = self::validate_parameters(
            self::get_grades_data_parameters(),
            array(
            )
        );
        $return = [];
        $grades = utils::get_course_grades();
        $data['users'] = $grades;
        $return[] = $data;
        return $return;
    }

    /**
     * @return external_multiple_structure
     */
    public static function get_grades_data_returns()
    {
        return new external_multiple_structure(new external_single_structure(
            [
                'users' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'name' => new external_value(PARAM_TEXT, ''),
                            'username' => new external_value(PARAM_TEXT, ''),
                        ]
                    ),
                    'List of users'
                )
            ]
        ));

        // return new external_multiple_structure(
        //     new external_single_structure(array(
        //         'docente' => new external_value(PARAM_TEXT, 'Docente'),
        //         'fecha' => new external_value(PARAM_TEXT, 'Fecha de Impresión'),
        //         'carrera' => new external_value(PARAM_TEXT, 'Carrera, campo de usuario'),
        //         'materia' => new external_value(PARAM_TEXT, 'Curso'),
        //         'sede' => new external_value(PARAM_TEXT, 'Sede, campo de usuario'),
        //         'cuatrimestre' => new external_value(PARAM_TEXT, ''),
        //         'turno' => new external_value(PARAM_TEXT, ''),
        //         'plan' => new external_value(PARAM_TEXT, ''),
        //         'curso' => new external_value(PARAM_TEXT, 'Grupo'),
        //         'usuarios' => new external_multiple_structure(
        //             new external_single_structure(array(
        //                 'ord' => new external_value(PARAM_TEXT, 'Orden de usuario'),
        //                 'legajo' => new external_value(PARAM_TEXT, 'Legajo de usuario'),
        //                 'nombre' => new external_value(PARAM_TEXT, 'Nombre'),
        //                 'apellido' => new external_value(PARAM_TEXT, 'Apellido'),
        //                 'obs' => new external_value(PARAM_TEXT, 'observaciones'),
        //                 'notafinal' => new external_value(PARAM_TEXT, 'Nota final'),
        //             ))
        //         )
        //     ))
        // );
    }
}

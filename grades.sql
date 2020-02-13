SELECT 
(SELECT ud.data
FROM prefix_user_info_data AS ud 
WHERE ud.fieldid=2 
AND ud.userid=u.id
) AS Sede,
(SELECT ud.data
FROM prefix_user_info_data AS ud 
WHERE ud.fieldid=3 
AND ud.userid=u.id
) AS Carrera,
#co.id,
co.fullname AS Curso,
g.name AS grupo,
u.username AS Usuario,
u.lastname AS Apellido,
u.firstname AS Nombre,

CASE 
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 1 THEN 'Ausente - Nunca Cursó'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 2 THEN 'Dejó la Cursada'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 3 THEN '2 - Reprobado'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 4 THEN '3 - Reprobado'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 5 THEN '4 - Aprobado'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 6 THEN '5 - Aprobado'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 7 THEN '6 - Aprobado'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 8 THEN '7 - Aprobado'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 9 THEN '8 - Aprobado'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 10 THEN '9 - Aprobado'
	WHEN (SELECT ag.grade
FROM prefix_assign AS assign
RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
WHERE gi.scaleid=19
AND assign.course=co.id AND ag.userid=u.id) = 11 THEN '10 - Aprobado'
	ELSE ""
	END as Nota_Final

FROM
prefix_role_assignments ra
JOIN prefix_context con ON con.id=ra.contextid
JOIN prefix_course AS co ON co.id=con.instanceid
JOIN prefix_user AS u ON u.id=ra.userid
JOIN prefix_groups_members AS gm ON gm.userid=u.id
JOIN prefix_groups AS g ON g.id=gm.groupid AND g.courseid=co.id
JOIN prefix_grade_items AS gi ON gi.courseid=co.id

WHERE con.contextlevel=50
AND ra.roleid=5
AND gi.scaleid=19
#AND co.id=200
#%%FILTER_COURSES:co.id%%
ORDER BY sede,carrera,curso,grupo,usuario,apellido,nombre,nota_final

Filtrar por plan, sede, materia, curso y docente.
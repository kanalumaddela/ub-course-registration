-- get user's whose id is 1
SELECT * FROM users where id = 1;

-- get the courses a user whose id is 1 has registered for
SELECT courses.*
FROM courses
JOIN course_sections on course_sections.course_id = courses.id
JOIN student_registrations on student_registrations.course_section_id = course_sections.id
WHERE student_registrations.user_id = 1;

-- get all the departments and the # of courses they have
SELECT departments.*, COUNT(c.id) as course_count
FROM departments
JOIN courses c on departments.id = c.department_id
GROUP BY department_id;

-- get all departments that have more than 1 advisor
SELECT departments.*, COUNT(da.user_id) as advisor_count
FROM departments
JOIN department_advisors da on da.department_id = departments.id
GROUP BY departments.id
HAVING COUNT(da.user_id) > 1;

-- get all course sections and the seats left in the class
SELECT c.name as course_name, c.name_shorthand as course_name_shorthand, course_sections.*, (SELECT COUNT(user_id) FROM student_registrations WHERE student_registrations.course_section_id = course_sections.id AND student_registrations.status = 'registered' ) as seats_taken
FROM course_sections
JOIN courses c on c.id = course_sections.course_id;

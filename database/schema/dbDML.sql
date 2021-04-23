-- buildings
INSERT INTO buildings (id, name) VALUES (1, 'Mandeville Hall');
INSERT INTO buildings (id, name) VALUES (2, 'Arnold Bernhard Arts-Hum Cntr');
INSERT INTO buildings (id, name) VALUES (3, 'Wahlstrom Library');
INSERT INTO buildings (id, name) VALUES (4, 'Eleanor N. Dana Hall');
INSERT INTO buildings (id, name) VALUES (5, 'Health Science Center');
INSERT INTO buildings (id, name) VALUES (6, 'Chiropractic College');
INSERT INTO buildings (id, name) VALUES (7, 'Engineering-Technology Bldg');
INSERT INTO buildings (id, name) VALUES (8, 'Carlson Hall');
INSERT INTO buildings (id, name) VALUES (9, 'Dana Hall of Science');
INSERT INTO buildings (id, name) VALUES (10, 'Bates Hall');
INSERT INTO buildings (id, name) VALUES (11, 'Cooper Hall');
INSERT INTO buildings (id, name) VALUES (12, 'South Hall');
INSERT INTO buildings (id, name) VALUES (13, 'Wheeler Recreation Center');

-- catalogs
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (1, null, 'spring', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (5, null, 'fall', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (6, null, 'summer', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (11, 'Nutrition', 'spring', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (12, 'Nutrition', 'summer', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (13, 'Nutrition', 'fall', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (14, 'Health Sciences', 'spring', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (19, 'Health Sciences', 'fall', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (20, 'Health Sciences', 'summer', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (22, 'Eli', 'fall', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (23, 'Eli', 'spring', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (24, 'Eli', 'summer', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (25, 'Pa', 'spring', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (26, 'Pa', 'summer', 2021, 1);
INSERT INTO catalogs (id, name, semester, year, is_active) VALUES (28, 'Pa', 'fall', 2021, 1);

-- departments
INSERT INTO departments (id, name, prefix) VALUES (11, 'Anatomy', 'AN');
INSERT INTO departments (id, name, prefix) VALUES (20, 'Biomedical Engineering', 'BMEG');
INSERT INTO departments (id, name, prefix) VALUES (28, 'Chemistry', 'CHEM');
INSERT INTO departments (id, name, prefix) VALUES (29, 'Chinese', 'CHIN');
INSERT INTO departments (id, name, prefix) VALUES (44, 'Computer Engineering', 'CPEG');
INSERT INTO departments (id, name, prefix) VALUES (45, 'Computer Science', 'CPSC');
INSERT INTO departments (id, name, prefix) VALUES (55, 'Economics', 'ECON');
INSERT INTO departments (id, name, prefix) VALUES (59, 'Electrical Engineering', 'ELEG');
INSERT INTO departments (id, name, prefix) VALUES (104, 'Mathematics', 'MATH');
INSERT INTO departments (id, name, prefix) VALUES (105, 'Mechanical Engineering', 'MEEG');

-- courses
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (302, 'Bioelectronics', 'BMEG-412', '412', 'Discipline of biomedical Engineering has emerged due to integration of engineering principles and technology into medicine. This course is intended for engineers and engineering students interested in persuing career in biomedical engineering and health related filed. This copurse will first introduction Applications of electrical engineering principles to biology, medicine, behavior, or health will be identified during first half of the semester. Second half of the course will focus on research, design, development and application of biosensors and Bioelectronics.', null, 20);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (306, 'Digital Signal Proc I', 'BMEG-443', '443', 'This is an introductory course in Digital Signal Processing (DSP) for graduate Electrical and Computer Engineering students. Sometime will be spent initially reviewing major concepts in signals and systems. Major topics to be covered in ELEG 443 include: time-domain analysis of discrete-time (DT)systems(convolution, difference equations), the z transform, frequency analysis for DT signals and systems (DTFT,DFT,FFT),digital filter design, and selected advanced topics as time permits.', null, 20);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (317, 'Co-op Work Experience', 'BMEG-500', '500', 'Students will work for a company in a role that is appropriate for an MS-BMEG graduate,or near graduation. Through this experience students will apply biomedical engineering principles and theory in a practical setting. The student will write a paper summarizing the tasks and accomplishments encountered within the organization, as well as make engineering recommendations for improvement of the biomedical engineering process in the company, or division in which s/he was employed.', null, 20);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (396, 'General Chemistry I', 'CHEM-103', '103', 'A study of basic chemical principles and their application. This course is designed for the science and engineering majors and includes theoretical and experimental studies of such topics as composition and structure of matter, stoichiometry, chemical reactions, chemical bonding, gases, atomic and molecular structure, and periodic trends. Prerequisite: 2 years high school mathematics. 3 lecture hours, 1 discussion period, 1 three-hour laboratory period, 4 semester hours  Lab Fee Assessed', 4, 28);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (401, 'Intro Chem I Lab', 'CHEM-113L', '113L', null, null, 28);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (400, 'Introductory Chemistry', 'CHEM-113', '113', 'An introductory course in chemistry for liberal arts and pre-professional students who wish to broaden their general education or feel that their previous preparation was inadequate. Pre-med and science majors are strongly advised to take Chemistry 103, although credits may be given for the Chemistry 113, 103 and 104 sequence. 3 lecture hours, 1 two-hour laboratory or discussion periods per week, 4 semester hours  Lab Fee Assessed', 4, 28);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (754, 'Adv Prob- Cptr Science', 'CPSC-597B', '597B', 'Lecture hours and topics to be arranged with Department Chair. 2 credit hours', null, 45);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (699, 'Database Design', 'CPSC-350', '350', 'Survey of data structure used in data bases, relations, hierarchical and network data models, theoretical issues in data base processing, practical issues in data base design. Programming and implementation. 3 lecture hours, 3 semester hours', 3, 45);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (719, 'Intro to Robotics', 'CPSC-460', '460', 'Basic robotics including: position and velocity sensing, actuations, control theory, robot coordinate systems, robot kinematics, differential motions, path control, dynamics, and force control. Robot sensing, simulation of manipulators, automation, and robot programming languages are also investigated. Prerequisite: Computer Science 102, Match 214 or Math 314, or permission of instructor. 3 semester hours ', 3, 45);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (2316, 'Engineering Graphics', 'MEEG-112', '112', 'This course provides an introduction to engineering graphics and visualization including engineering drawing and 3-D solid modeling with a computer aided design (CAD) package. Topics include the design process, multiview projection and sectioning, dimensioning, tolerancing, and working drawings.', null, 105);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (2371, 'Applied Thermodynamics', 'MEEG-462', '462', 'This course is designed to develop an understanding of Thermodynamic concepts and their applications with an integrative modeling and analysis approach to thermal-fluid systems.  The topics include: principles of thermal energy conversion; properties of pure substances and mixtures; first and second laws of thermodynamics; entropy; exergy; closed and open systems of various types; applications of the principles of thermodynamics to components and systems, including pumps, compressors, engines, turbines, power plants, renewable energy systems, power and refrigeration cycles.', null, 105);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (2370, 'Intro to Robotics', 'MEEG-460', '460', 'Basic robotics including: position and velocity sensing, actuations, control theory, robot coordinate systems, robot kinematics, differential motions, path control, dynamics, and force control. Robot sensing, simulation of manipulators, automation, and robot programming languages are also investigated. Prerequisite: Computer Science 102, Match 214 or Math 314, or permission of instructor. 3 semester hours', 3, 105);
INSERT INTO courses (id, name, name_shorthand, number, description, credits, department_id) VALUES (2427, 'Master''s Program III', 'MEEG-597C', '597C', 'Lecture hours and topics to be arranged with Department Chair. 1 credit hour ', null, 105);

-- course_sections
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (592, 'G', 10, '2021-05-17', '2021-07-02', 'A. Abuzneid', 754, 6);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (544, 'AG', 15, '2021-08-23', '2021-12-10', null, 754, 5);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (557, 'AA', 10, '2021-01-25', '2021-05-07', 'A. Abuzneid', 754, 1);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (2020, '11NS', 36, '2021-08-23', '2021-12-10', null, 396, 5);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (540, 'AB', 15, '2021-08-23', '2021-12-10', null, 754, 5);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (561, 'JL', 10, '2021-01-25', '2021-05-07', 'J. Lee', 754, 1);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (1704, '12', 25, '2021-01-25', '2021-05-07', 'Z. Li', 2316, 1);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (591, 'F', 10, '2021-05-17', '2021-07-02', 'A. Mahmood', 754, 6);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (539, 'AA', 15, '2021-08-23', '2021-12-10', null, 754, 5);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (1294, 'A', 25, '2021-01-25', '2021-05-07', 'P. Patra', 317, 1);
INSERT INTO course_sections (id, number, seats, start_date, end_date, faculty, course_id, catalog_id) VALUES (1388, '11', 25, '2021-01-25', '2021-05-07', 'J. Lee', 699, 1);

-- course_section_schedules
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (542, 539, 'Online', null, null, null, 1, null, null);
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (543, 540, 'Online', null, null, null, 1, null, null);
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (547, 544, 'Online', null, null, null, 1, null, null);
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (560, 557, 'Online', null, null, null, 1, null, null);
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (564, 561, 'Online', null, null, null, 1, null, null);
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (595, 592, 'Online', null, null, null, 1, null, null);
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (1426, 1294, 'Online', null, null, null, 1, null, null);
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (1521, 1388, 'Lecture', '09:30:00', '10:45:00', 'T TH', 0, 8, '152');
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (1907, 1704, 'Lecture', '11:00:00', '12:15:00', 'M W', 0, 7, '113');
INSERT INTO course_section_schedules (id, course_section_id, type, start_time, end_time, days, is_online, building_id, room) VALUES (2254, 2020, 'Lecture', '09:30:00', '10:40:00', 'M W F', 0, 1, '210');

-- locations
INSERT INTO locations (id, name) VALUES (26, 'Tanzania');
INSERT INTO locations (id, name) VALUES (7, 'Trinity Catholic High School');
INSERT INTO locations (id, name) VALUES (2, 'Waterbury');
INSERT INTO locations (id, name) VALUES (1, 'Main Campus');
INSERT INTO locations (id, name) VALUES (4, 'Riverbend');
INSERT INTO locations (id, name) VALUES (16, 'Marrakech,Inc');
INSERT INTO locations (id, name) VALUES (24, 'Hungary');
INSERT INTO locations (id, name) VALUES (18, 'Bullard Havens Tech');
INSERT INTO locations (id, name) VALUES (17, 'Pomeraug High School');
INSERT INTO locations (id, name) VALUES (22, 'Aquaculture Sci/Tech Center');

-- users
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (1, 'Samuel Maddela', 'smaddela@my.bridgeport.edu', null, '$2y$10$Z6zqvopUh4TA4DTIuJVoq.MCHvtYVSYt.ocL9Qze8qu8z72EzzZxG', null, null, null, null, null, '2021-04-12 14:49:22', '2021-04-12 14:49:22');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (493, 'Delphia Bartell', 'darian72@example.net', '2021-04-12 14:49:26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, 'wV7zQRTFRL', null, null, '2021-04-12 14:49:27', '2021-04-12 14:49:27');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (279, 'Oral Cassin', 'rodger28@example.org', '2021-04-12 14:49:26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, 'aoKvn6cY2R', null, null, '2021-04-12 14:49:27', '2021-04-12 14:49:27');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (486, 'Mr. Isaias Rohan', 'iskiles@example.net', '2021-04-12 14:49:26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, 'KV6uwalWwC', null, null, '2021-04-12 14:49:27', '2021-04-12 14:49:27');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (430, 'Dr. Filiberto Moore', 'kling.dawson@example.net', '2021-04-12 14:49:26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, 'I5Hh709Q6s', null, null, '2021-04-12 14:49:27', '2021-04-12 14:49:27');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (298, 'Miss Amber O''Connell', 'jacques.schuster@example.net', '2021-04-12 14:49:26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, '9JVgAOhkLx', null, null, '2021-04-12 14:49:27', '2021-04-12 14:49:27');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (595, 'Eulah Kemmer', 'cleve.heathcote@example.net', '2021-04-12 14:49:26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, '9ZAIv0nXmR', null, null, '2021-04-12 14:49:27', '2021-04-12 14:49:27');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (326, 'Elmira Barton', 'deangelo81@example.org', '2021-04-12 14:49:26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, 'ZqycA4IEeZ', null, null, '2021-04-12 14:49:27', '2021-04-12 14:49:27');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (598, 'Jaycee Johnson', 'wiza.caitlyn@example.net', '2021-04-12 14:49:26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, 'cMoo2ibwIF', null, null, '2021-04-12 14:49:27', '2021-04-12 14:49:27');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (518, 'Emmie Vandervort', 'jullrich@example.net', '2021-04-12 14:49:26', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, 'nHqChgh0aB', null, null, '2021-04-12 14:49:27', '2021-04-12 14:49:27');
INSERT INTO users (id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, remember_token, current_team_id, profile_photo_path, created_at, updated_at) VALUES (133, 'Darius Yundt', 'krystel65@example.com', '2021-04-12 14:49:25', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', null, null, '2oIV0XO3TC', null, null, '2021-04-12 14:49:26', '2021-04-12 14:49:26');

-- department_advisors
INSERT INTO department_advisors(department_id, user_id) VALUES (45, 1);
INSERT INTO department_advisors(department_id, user_id) VALUES (45, 595);
INSERT INTO department_advisors(department_id, user_id) VALUES (44, 493);
INSERT INTO department_advisors(department_id, user_id) VALUES (59, 598);
INSERT INTO department_advisors(department_id, user_id) VALUES (59, 1);
INSERT INTO department_advisors(department_id, user_id) VALUES (59, 518);
INSERT INTO department_advisors(department_id, user_id) VALUES (104, 430);
INSERT INTO department_advisors(department_id, user_id) VALUES (105, 133);
INSERT INTO department_advisors(department_id, user_id) VALUES (29, 518);
INSERT INTO department_advisors(department_id, user_id) VALUES (20, 279);

-- messages
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (155, 1, 493, 'Et aperiam unde molestiae tenetur ullam doloribus a itaque tenetur sunt.', null, '2021-04-12 14:49:30');
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (154, 1, 493, 'Tempora aut est et quia corporis numquam tempora voluptatibus. Blanditiis consequatur odit vel et ipsum quasi. Et recusandae sint ea doloremque perspiciatis enim ut.', null, '2021-04-12 14:49:30');
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (189, 1, 298, 'Magni cumque ut atque illo dolores esse sunt animi. Maxime sequi porro vel cum laudantium. Vitae molestiae et inventore placeat numquam qui.', null, '2021-04-12 14:49:30');
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (23, 1, 595, 'Odit exercitationem totam officiis ut aperiam illo aspernatur atque ad.', null, '2021-04-12 14:49:29');
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (158, 1, 326, 'Vitae ipsa illum doloremque est. Laborum quas dolore commodi ut. Sint quod iusto inventore sed excepturi.', null, '2021-04-12 14:49:30');
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (173, 1, 518, 'Eos quod accusamus fuga qui accusamus. Nemo cum aut id dolor officiis. Qui unde quasi qui sit.', null, '2021-04-12 14:49:30');
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (7, 1, 133, 'Illum vero voluptas aut ut. Voluptatum reprehenderit enim dolorem eum a aut. Nam ullam et fugit aliquam.', null, '2021-04-12 14:49:29');
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (130, 1, 133, 'Accusantium consequatur perferendis qui quod non ipsa totam maiores. Necessitatibus aut nobis in voluptas sint autem at. Vel assumenda reiciendis blanditiis vero nobis et.', null, '2021-04-12 14:49:30');
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (185, 1, 518, 'Laborum debitis rerum doloremque inventore eos est doloribus asperiores pariatur omnis.', null, '2021-04-12 14:49:30');
INSERT INTO messages (id, author_id, recipient_id, content, read_at, created_at) VALUES (90, 1, 430, 'Ut porro adipisci facere accusamus sed cum. Ut sed rerum nobis. Velit et similique sit minus.', null, '2021-04-12 14:49:30');

-- student_registrations
INSERT INTO student_registrations (user_id, course_section_id, status) VALUES (1, 1388, 'approved');
INSERT INTO student_registrations (user_id, course_section_id, status) VALUES (1, 1704, 'approved');
INSERT INTO student_registrations (user_id, course_section_id, status) VALUES (1, 591, 'denied');
INSERT INTO student_registrations (user_id, course_section_id, status) VALUES (1, 540, 'registered');
INSERT INTO student_registrations (user_id, course_section_id, status) VALUES (1, 2020, 'planned');
INSERT INTO student_registrations (user_id, course_section_id, status) VALUES (1, 544, 'denied');

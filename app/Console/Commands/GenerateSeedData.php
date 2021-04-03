<?php

namespace App\Console\Commands;

use DateTime;
use Illuminate\Console\Command;

class GenerateSeedData extends Command
{

    protected static $models = [
        'departments' => true,
        'courses'     => true,
        'sections'    => false,
        'schedules'   => false,
        'terms'       => true,
        'locations'   => true,
        'buildings'   => true,
        'faculty'     => true,
    ];

    /**
     * @var array
     */
    protected $data = [];

    private $counts = [];

    private $cacheCheck = [];

    /**
     * @var string
     */
    protected $exportDir;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-seed-data {--sql} {--skip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate JSON data from CSV of UB\'s courses/sections';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('sql') && $this->option('skip')) {
            $this->generateSql();
        }

        foreach (static::$models as $model => $bool) {
            $this->counts[$model] = 1;
            $this->data[$model] = [];
            $this->cacheCheck[$model] = [];
        }

        $this->cacheCheck['scheduleInternal'] = [];

        $this->exportDir = base_path().'/database/seeders/_json/';

        $coursesCsv = array_map('str_getcsv', file(base_path().'/database/seeders/_csv/ub-courses.csv'));
        $sectionsCsv = array_map('str_getcsv', file(base_path().'/database/seeders/_csv/ub-sections.csv'));

        array_shift($coursesCsv);
        array_shift($sectionsCsv);

        $this->withProgressBar($coursesCsv, function ($data) {
            $this->addCourse($data);
        });
        $this->newLine();


        $this->withProgressBar($sectionsCsv, function ($data) {
            $this->addSection($data);
        });
        $this->newLine();

        foreach (static::$models as $model => $convertToArray) {
            $this->saveFile($model.'.json', $convertToArray ? array_values($this->data[$model]) : $this->data[$model]);
        }

        if ($this->option('sql')) {
            $this->generateSql();
        }


        return 0;
    }

    public function generateSql()
    {
        // todo:
    }

    public function addCourse($raw, $return = false)
    {
        $blacklist = [
            'PG-916'   => '',
            'CPSC-605' => '',
            'CIHT-261' => '',
        ];

        if (isset($blacklist[$name_shorthand = $raw[1]]) || empty($raw[0])) {
//            $this->error('Course: '.$name_shorthand.' skipped due to known invalid data');

            return;
        }

        if (!isset($this->data['courses'][$name_shorthand])) {
            $course = static::getCourseTemplate();

            $parsed_shorthand = static::parseShorthandName($name_shorthand);


            if (!empty($raw[2])) {
                $course['credits'] = static::checkDescForCredits($raw[2]);
            }

            $course['id'] = $this->counts['courses'];
            $course['name'] = str_replace('^', '', $raw[0]);
            $course['name_shorthand'] = $name_shorthand;
            $course['number'] = $parsed_shorthand['course_number'];
            $course['description'] = $raw[2] ?: null;
            $course['academic_level'] = $raw[4];

            foreach (static::explodeTrim($raw[5], ';') as $location) {
                $location = $this->addLocation($location, true);
                $course['location_ids'][] = $location['id'];
                $course['location_names'][] = $location['name'];
            }

            $department = $this->addDepartment($raw[3], $parsed_shorthand['department_prefix'], true);
            $course['department_id'] = $department['id'];
            $course['department_name'] = $department['name'];

            $this->data['courses'][$name_shorthand] = $course;

            $this->counts['courses']++;
        }

        if ($return) {
            return $this->data['courses'][$name_shorthand];
        }

        return;
    }

    public function addSection($raw, $return = false)
    {
        $name_shorthand = $raw[2];

        $parsed_shorthand = static::parseShorthandName($name_shorthand);

        $hash = md5($raw[2].'-'.$raw[8]);

        if (!isset($this->cacheCheck['sections'][$hash])) {
            $section = [
                'id'              => null,
                'name_shorthand'  => null,
                'number'          => null,
                'seats'           => null,
                'start_timestamp' => null,
                'end_timestamp'   => null,
//                'types'           => [],
                'notes'           => null,
                'faculty_ids'     => [],
                'faculty_names'   => [],
                'term_id'         => null,
                'department_id'   => null,
                'department_name' => null,
                'course_id'       => null,
                'course_name'     => null,
                'schedule_ids'    => [],
            ];

            $section['id'] = $this->counts['sections'];
            $section['name_shorthand'] = $name_shorthand;
            $section['number'] = $raw[4];
            $section['seats'] = !empty($raw[21]) && $raw[21] != 0 ? (int) $raw[21] : null;

            $times = explode('-', $raw[9]);
            $section['start_timestamp'] = strtotime($times[0]);
            $section['end_timestamp'] = strtotime($times[1]);

//            $section['types'] = static::explodeTrim($raw[15]);
            $section['notes'] = $raw[28] ?: null;

            foreach (static::explodeTrim($raw[20], ',') as $faculty) {
                $faculty = $this->addFaculty($faculty, true);
                $section['faculty_ids'][] = $faculty['id'];
                $section['faculty_names'][] = $faculty['name'];
            }

            $section['term_id'] = $this->addTerm($raw[8], true)['id'];

            $course = $this->addCourseFromSection($raw);
            $section['department_id'] = $course['department_id'];
            $section['department_name'] = $course['department_name'];
            $section['course_id'] = $course['id'];
            $section['course_name'] = $course['name'];

            // schedules
            $section['schedule_ids'] = $this->parseAndAddSchedules($section['id'], $raw[10], $raw[11], $raw[12], $raw[13], $raw[14]);

            $this->data['sections'][] = $section;
            $this->cacheCheck['sections'][$hash] = $section['id'];

            $this->counts['sections']++;
        }

//        if ($return) {
//            return $this->data['sections'][$this->cacheCheck['sections'][$hash]];
//        }
    }

    public function addDepartment($name, $prefix, $return = false)
    {
        if (isset($this->data['departments'][$prefix]) && $this->data['departments'][$prefix]['name'] !== $name) {
            $department = $this->data['departments'][$prefix];

            if (!$this->confirm('Department names do not match for `'.$prefix.'`: Existing: `'.$department['name'].'` vs Given: `'.$name.'`')) {
                throw new \Exception('Different department names');
            }

            unset($department);
        }

        if (!isset($this->data['departments'][$prefix])) {
            $this->data['departments'][$prefix] = [
                'id'     => $this->counts['departments'],
                'name'   => $name,
                'prefix' => $prefix,
            ];

            $this->counts['departments']++;
        }

        if ($return) {
            return $this->data['departments'][$prefix];
        }

        return;
    }

    public function addCourseFromSection($raw)
    {
        $course = static::getCourseTemplate();
        $parsed = static::parseShorthandName($raw[2]);

        // handle courses already defined and possibly update them with missing data
        if (isset($this->data['courses'][$parsed['course_name_shorthand']])) {
            $course = $this->data['courses'][$parsed['course_name_shorthand']];

            $course['section_ids'][] = $this->counts['sections'];

            // update course description if doesn't match section description
            if (false && !empty($course['description']) && !empty($raw[6]) && $course['description'] !== $raw[6]) {

                $year = (int) (new \DateTime())->setTimestamp(strtotime($raw[5]))->format('Y');

                if ($year >= 2020) {
                    $course['description'] = $raw[6];
                } else {
                    $this->newLine();
                    if ($this->confirm($course['name_shorthand'].' has a description, but does not match '.$raw[2].'. Update description?')) {
                        $course['description'] = $raw[6];
                    }
                }
            }

            // update course description if doesn't have one
            if (empty($course['description']) && !empty($raw[6])) {
                $course['description'] = $raw[6];
            }

            // update course credits pulling from section
            if (!empty($course['credits']['min'])) {
                $course['credits'] = $this->determineWhichCreditsToUse($raw);
            }

            // update pre-reqs
            if (!empty($course['prerequisites'])) {
                $course['prerequisites'] = static::explodeTrim($raw[25], ';');
            }

            $this->data['courses'][$parsed['course_name_shorthand']] = $course;
        } else {
            preg_match('/\^? ?(?<course>[[:print:]]*) \(/', $raw[0], $course_name_matches);

            $course['id'] = $this->counts['courses'];
            $course['name'] = $course_name_matches['course'] ?? null;
            $course['name_shorthand'] = $parsed['course_name_shorthand'];
            $course['number'] = $parsed['course_number'];
            $course['description'] = $raw[6] ?: null;
            $course['credits'] = $this->determineWhichCreditsToUse($raw);
            $course['prerequisites'] = static::explodeTrim($raw[25], ';');
            $course['academic_level'] = $raw[18];

            $course['department_id'] = $this->addDepartment($raw[16], $parsed['department_prefix'], true)['id'];;
//            $course['department_id'] = $this->data['departments'][$parsed['department_prefix']] ?? null;

            $course['locations'] = [$raw[7]];
            $course['section_ids'][] = $this->counts['sections'];

            $this->data['courses'][$parsed['course_name_shorthand']] = $course;

            $this->counts['courses']++;
        }

        if (!isset($this->data['courses'][$parsed['course_name_shorthand']])) {

            $course['description'] = null;
            $course['prerequisites'] = json_encode($course['prerequisites']);
            $course['credits'] = json_encode($course['credits']);
            $course['department'] = json_encode($course['department']);
            $course['locations'] = json_encode($course['locations']);

            $this->table(
                array_keys($course),
                [$course]
            );

            return;
        }

        return $this->data['courses'][$parsed['course_name_shorthand']];
    }

    public function addTerm($name_full, $return = false)
    {
        $name_full = trim($name_full);

        if (!isset($this->data['terms'][$name_full])) {
            $term = [
                'id'       => $this->counts['terms'],
                'name'     => null,
                'semester' => null,
                'year'     => null,
            ];

            $regex = '/(?<semester>Spring|Fall|Summer|Winter)|(?<year>\d+)/';

            preg_match_all($regex, $name_full, $matches, PREG_SET_ORDER);

            $name = trim(preg_replace($regex, '', $name_full));

            if (!empty($name)) {
                $term['name'] = $name;
            }
            $term['semester'] = $matches[0]['semester'] ?: $matches[1]['semester'];
            $term['year'] = isset($matches[0]['year']) && !empty($matches[0]['year']) ? (int) $matches[0]['year'] : (isset($matches[1]['year']) && !empty($matches[1]['year']) ? (int) $matches[1]['year'] : null);

            $this->data['terms'][$name_full] = $term;

            $this->counts['terms']++;
        }

        if ($return) {
            return $this->data['terms'][$name_full];
        }
    }

    public function addLocation($location, $return = false)
    {
        if (!isset($this->data['locations'][$location])) {
            $this->data['locations'][$location] = [
                'id'   => $this->counts['locations'],
                'name' => $location,
            ];

            $this->counts['locations']++;
        }

        if ($return) {
            return $this->data['locations'][$location];
        }
    }

    protected function parseAndAddSchedules($section_id, $meeting_times, $days, $buildings, $rooms, $types)
    {
        static::parseScheduleData($meeting_times, $days, $buildings, $rooms, $types);

        $regex = '/\bM|\bTH|\bT|\bW|\bF|\bSU|\bS/';

        $schedulesToReturn = [];

        $lengths = [
            'meeting_times' => count($meeting_times),
            'days'          => count($days),
            'buildings'     => count($buildings),
            'rooms'         => count($rooms),
            'types'         => count($types),
        ];

        // section/schedule is online
        if ($is_online = static::checkIfOnline($meeting_times, $days, $buildings, $rooms, $types)) {
            $schedule = static::getScheduleTemplate();
            $schedule['id'] = $this->counts['schedules'];
            $schedule['section_id'] = $section_id;
            $schedule['type'] = 'Online';
            $schedule['is_online'] = $is_online;

            $this->data['schedules'][] = $schedule;
            $this->counts['schedules']++;

            return [$schedule['id']];
        }

        if (isset($buildings[0]) && $buildings[0] === 'To Be Announced' || isset($rooms[0]) && $rooms[0] === 'TBA' || isset($types[0]) && $types[0] === 'By Arrangement') {
            return [];
        }

        foreach ($meeting_times as $i => $time) {

            if (empty($time)) {
                continue;
            }

            $schedule = static::getScheduleTemplate();

            $time_replaced = preg_replace($regex, '', $time);
            $time_replaced = static::explodeTrim($time_replaced, '-');

            if (!isset($time_replaced[0]) || !isset($time_replaced[1])) {
                continue;
            }

            $start_time = DateTime::createFromFormat('G:i A', $time_replaced[0]);
            $end_time = DateTime::createFromFormat('G:i A', $time_replaced[1]);

            if (is_bool($start_time) || is_bool($end_time)) {
                $this->error('start: '.$time_replaced[0]);
                $this->error('end: '.$time_replaced[1]);
                break;
            }

//            if ($this->counts['schedules'] === 644) {
//                $this->info('rooms: ' . json_encode($rooms));
//                $this->info('buildings: ' . json_encode($buildings));
//                break;
//            }

            $schedule['id'] = $this->counts['schedules'];
            $schedule['section_id'] = $section_id;
            $schedule['type'] = $types[$i] ?? $types[0] ?? 'Lecture';
            $schedule['start_time'] = $start_time->format('H:i:s');
            $schedule['end_time'] = $end_time->format('H:i:s');
            $schedule['days'] = $days[$i] ?? $days[0] ?? null;

            $schedule['building_id'] = isset($buildings[$i]) ? $this->addBuilding($buildings[$i], true)['id'] : (isset($buildings[0]) ? $this->addBuilding($buildings[0], true)['id'] : null);

            if (!empty($schedule['building_id'])) {
                $schedule['building_name'] = $buildings[$i] ?? $buildings[0];
            }


            $schedule['room'] = $rooms[$i] ?? $rooms[0] ?? null;

            $hash = md5($schedule['section_id'].$schedule['start_time'].$schedule['end_time'].$schedule['days'].$schedule['building_id']);

            if (isset($this->cacheCheck['scheduleInternal'][$hash])) {
                continue;
            } else {
                $this->cacheCheck['scheduleInternal'][$hash] = $schedule['id'];
            }

            $schedulesToReturn[] = $this->data['schedules'][] = $schedule;
            $this->counts['schedules']++;
        }

        return array_column($schedulesToReturn, 'id');
    }

    protected static function parseScheduleData(&$meeting_times, &$days, &$buildings, &$rooms, &$types)
    {
        $meeting_times = !empty($meeting_times) ? self::explodeTrim($meeting_times, ';') : [];
        $days = !empty($days) ? self::explodeTrim($days) : [];
        $buildings = !empty($buildings) ? self::explodeTrim($buildings) : [];
        $rooms = !empty($rooms) ? self::explodeTrim($rooms) : [];
        $types = !empty($types) ? self::explodeTrim($types) : [];
    }

    protected static function checkIfOnline($meeting_times, $days, $buildings, $rooms, $types)
    {
        if (empty($meeting_times) && empty($days)) {
            return true;
        }

        if (empty($buildings) && empty($rooms)) {
            return true;
        }

        if (isset($types[0]) && $types[0] === 'Online') {
            return true;
        }

        if (isset($buildings[0]) && $buildings[0] === 'On-Line') {
            return true;
        }

        if (isset($rooms[0]) && $rooms[0] === 'ONLN') {
            return true;
        }

        return false;
    }

    protected static function getScheduleTemplate()
    {
        return [
            'id'            => null,
            'section_id'    => null,
            'type'          => 'Lecture',
            'start_time'    => null,
            'end_time'      => null,
            'days'          => [],
            'is_online'     => false,
            'building_id'   => null,
            'building_name' => null,
            'room'          => null,
        ];
    }

    public function addBuilding($building, $return = false)
    {
        if ($building === 'On-Line' || $building === 'To Be Announced') {
            return ['id' => null];
        }

        if (!isset($this->data['buildings'][$building])) {
            $this->data['buildings'][$building] = [
                'id'   => $this->counts['buildings'],
                'name' => $building,
            ];

            $this->counts['buildings']++;
        }

        if ($return) {
            return $this->data['buildings'][$building];
        }
    }

    protected static function validateRoom($room)
    {
        return !empty($room) && $room !== 'ONLN' && $room !== 'TBA' ? $room : null;
    }

    public function addFaculty($faculty, $return = false)
    {
        if (!isset($this->data['faculty'][$faculty])) {
            $this->data['faculty'][$faculty] = [
                'id'   => $this->counts['faculty'],
                'name' => $faculty,
            ];

            $this->counts['faculty']++;
        }

        if ($return) {
            return $this->data['faculty'][$faculty];
        }
    }

    protected function determineWhichCreditsToUse($raw): array
    {
        $credits = [
            'min' => null,
            'max' => null,
        ];

        $creditsDesc = static::checkDescForCredits($raw[6]);
        $creditsSect = [
            'min' => !empty($raw[24]) && $raw[24] != 0 ? (float) $raw[24] : null,
            'max' => !empty($raw[23]) && $raw[23] != 0 ? (float) $raw[23] : null,
        ];

        if (empty($creditsSect['max'])) {
            $credits = $creditsDesc;
        } elseif (empty($creditsDesc['max'])) {
            $credits = $creditsSect;
        }
//        elseif ($creditsDesc !== $creditsSect) {
//            $tmpMapping = [
//                'desc' => 'creditsDesc',
//                'sect' => 'creditsSect',
//            ];
//
//            $this->newLine();
//            $toUse = $this->choice(
//                'Credits array do not match for `'.$raw[2].'`, `desc`: '.json_encode($creditsDesc).' vs `sect`: '.json_encode($creditsSect).'',
//                $tmpKeys = array_keys($tmpMapping),
//                1
//            );
//
//            $this->info('Using: `'.$toUse.'`');
//            $this->newLine();
//
//            $tmpVar = $tmpMapping[$toUse];
//            $credits = $$tmpVar;
//        }
        else {
            $credits = $creditsSect;
        }

        return $credits;
    }

    protected function saveFile($name, $data)
    {
        file_put_contents($this->exportDir.$name, json_encode($data, JSON_PRETTY_PRINT));
    }

    protected static function parseShorthandName($shorthand): array
    {
        // e.g. MEEG-112-11
//        [
//            'department_prefix'      => 'MEEG',
//            'course_number'          => '112',
//            'section_number'         => '11',
//            'course_name_shorthand'  => 'MEEG-112',
//            'section_name_shorthand' => 'MEEG-112-11',
//        ]
        $exploded = explode('-', $shorthand);

        return [
            'department_prefix'      => $exploded[0],
            'course_number'          => $exploded[1],
            'section_number'         => $exploded[2] ?? null,
            'course_name_shorthand'  => isset($exploded[0], $exploded[1]) ? $exploded[0].'-'.$exploded[1] : null,
            'section_name_shorthand' => isset($exploded[2]) ? $shorthand : null,
        ];
    }

    protected static function getCourseTemplate(): array
    {
        return [
            'id'              => null,
            'name'            => null,
            'name_shorthand'  => null,
            'number'          => null,
            'description'     => null,
            'credits'         => [
                'min' => null,
                'max' => null,
            ],
            'prerequisites'   => null,
            'academic_level'  => null,
            'location_ids'    => [],
            'location_names'  => [],
            'department_id'   => null,
            'department_name' => null,
            'section_ids'     => [],
        ];
    }

    protected static function checkDescForCredits($description): array
    {
        preg_match('/(\d+)?-?(\d+) semester hour/', $description, $credit_matches);

        $credits = [
            'min' => null, // 1
            'max' => null, // 6  3
        ];

        if (empty($credit_matches)) {
            return $credits;
        }

        return [
            'min' => !empty($credit_matches[1]) && $credit_matches[1] != 0 ? (float) $credit_matches[1] : (!empty($credit_matches[2]) && $credit_matches[2] != 0 ? (float) $credit_matches[2] : null),
            'max' => $credit_matches[2] != 0 ? (float) $credit_matches[2] : null,
        ];
    }

    public static function explodeTrim($string, $separator = ','): array
    {
        return array_values(array_map('trim', array_filter(explode($separator, $string))));
//        return array_filter(array_map('trim', explode($separator, $string)));
    }
}

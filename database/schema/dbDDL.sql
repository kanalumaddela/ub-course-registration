create table if not exists buildings
(
    id   bigint unsigned auto_increment
        primary key,
    name varchar(255) not null,
    constraint buildings_name_unique
        unique (name)
)
    collate = utf8mb4_unicode_ci;

create table if not exists catalogs
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255)                                null,
    name_full  varchar(255) as (concat_ws(_utf8mb4' ', `name`,
                                          concat(upper(substr(`semester`, 1, 1)), substr(`semester`, 2)), `year`)),
    semester   enum ('spring', 'summer', 'fall', 'winter') not null,
    year       year                                        null,
    is_active  tinyint(1)                                  not null
)
    collate = utf8mb4_unicode_ci;

create table if not exists departments
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255) not null,
    prefix     varchar(255) not null,
    constraint departments_name_prefix_unique
        unique (name, prefix)
)
    collate = utf8mb4_unicode_ci;

create table if not exists courses
(
    id             bigint unsigned auto_increment
        primary key,
    name           varchar(255)     not null,
    name_shorthand varchar(255)     not null,
    number         varchar(255)     not null,
    description    text             null,
    credits        tinyint unsigned null,
    department_id  bigint unsigned  not null,
    constraint courses_department_id_foreign
        foreign key (department_id) references departments (id)
            on delete cascade
)
    collate = utf8mb4_unicode_ci;

create table if not exists course_sections
(
    id         bigint unsigned auto_increment
        primary key,
    number     varchar(255)      not null,
    seats      smallint unsigned null,
    start_date date              not null,
    end_date   date              not null,
    faculty    varchar(255)      null,
    course_id  bigint unsigned   not null,
    catalog_id bigint unsigned   not null,
    constraint course_sections_catalog_id_foreign
        foreign key (catalog_id) references catalogs (id),
    constraint course_sections_course_id_foreign
        foreign key (course_id) references courses (id)
)
    collate = utf8mb4_unicode_ci;

create table if not exists course_section_schedules
(
    id                bigint unsigned auto_increment
        primary key,
    course_section_id bigint unsigned      not null,
    type              varchar(255)         null,
    start_time        time                 null,
    end_time          time                 null,
    days              varchar(255)         null,
    is_online         tinyint(1) default 1 not null,
    building_id       bigint unsigned      null,
    room              varchar(255)         null,
    constraint course_section_schedules_building_id_foreign
        foreign key (building_id) references buildings (id),
    constraint course_section_schedules_course_section_id_foreign
        foreign key (course_section_id) references course_sections (id)
            on delete cascade
)
    collate = utf8mb4_unicode_ci;

create index courses_name_shorthand_index
    on courses (name_shorthand);

create table if not exists locations
(
    id   bigint unsigned auto_increment
        primary key,
    name varchar(255) not null,
    constraint locations_name_unique
        unique (name)
)
    collate = utf8mb4_unicode_ci;

create table if not exists users
(
    id                        bigint unsigned auto_increment
        primary key,
    name                      varchar(255)    not null,
    email                     varchar(255)    not null,
    email_verified_at         timestamp       null,
    password                  varchar(255)    not null,
    two_factor_secret         text            null,
    two_factor_recovery_codes text            null,
    remember_token            varchar(100)    null,
    current_team_id           bigint unsigned null,
    profile_photo_path        text            null,
    created_at                timestamp       null,
    updated_at                timestamp       null,
    constraint users_email_unique
        unique (email)
)
    collate = utf8mb4_unicode_ci;

create table if not exists department_advisors
(
    department_id bigint unsigned not null,
    user_id       bigint unsigned not null,
    constraint department_advisors_department_id_user_id_unique
        unique (department_id, user_id),
    constraint department_advisors_department_id_foreign
        foreign key (department_id) references departments (id)
            on delete cascade,
    constraint department_advisors_user_id_foreign
        foreign key (user_id) references users (id)
            on delete cascade
)
    collate = utf8mb4_unicode_ci;

create table if not exists messages
(
    id              bigint unsigned auto_increment
        primary key,
    author_id         bigint unsigned not null,
    recipient_id         bigint unsigned not null,
    content         text            not null,
    read_at         timestamp       null,
    created_at      timestamp       null,
    constraint messages_author_id_foreign
        foreign key (author_id) references users (id)
            on delete cascade,
    constraint messages_recipient_id_foreign
        foreign key (recipient_id) references users (id)
            on delete cascade
)
    collate = utf8mb4_unicode_ci;

create table if not exists student_registrations
(
    user_id           bigint unsigned                                                 not null,
    course_section_id bigint unsigned                                                 not null,
    status            enum ('pending', 'approved', 'denied', 'planned', 'registered') not null,
    constraint student_registrations_user_id_course_section_id_unique
        unique (user_id, course_section_id),
    constraint student_registrations_course_section_id_foreign
        foreign key (course_section_id) references course_sections (id)
            on delete cascade,
    constraint student_registrations_user_id_foreign
        foreign key (user_id) references users (id)
            on delete cascade
)
    collate = utf8mb4_unicode_ci;


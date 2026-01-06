create table cache
(
    `key`      varchar(255) not null
        primary key,
    value      mediumtext   not null,
    expiration int          not null
)
    collate = utf8mb4_unicode_ci;

create table cache_locks
(
    `key`      varchar(255) not null
        primary key,
    owner      varchar(255) not null,
    expiration int          not null
)
    collate = utf8mb4_unicode_ci;

create table class
(
    classID varchar(50) not null
        primary key
);

create table codes
(
    code_id int auto_increment
        primary key,
    code    int          null,
    used    tinyint(1)   null,
    name    varchar(100) null,
    role    varchar(20)  not null
);

create table failed_jobs
(
    id         bigint unsigned auto_increment
        primary key,
    uuid       varchar(255)                          not null,
    connection text                                  not null,
    queue      text                                  not null,
    payload    longtext                              not null,
    exception  longtext                              not null,
    failed_at  timestamp default current_timestamp() not null,
    constraint failed_jobs_uuid_unique
        unique (uuid)
)
    collate = utf8mb4_unicode_ci;

create table job_batches
(
    id             varchar(255) not null
        primary key,
    name           varchar(255) not null,
    total_jobs     int          not null,
    pending_jobs   int          not null,
    failed_jobs    int          not null,
    failed_job_ids longtext     not null,
    options        mediumtext   null,
    cancelled_at   int          null,
    created_at     int          not null,
    finished_at    int          null
)
    collate = utf8mb4_unicode_ci;

create table jobs
(
    id           bigint unsigned auto_increment
        primary key,
    queue        varchar(255)     not null,
    payload      longtext         not null,
    attempts     tinyint unsigned not null,
    reserved_at  int unsigned     null,
    available_at int unsigned     not null,
    created_at   int unsigned     not null
)
    collate = utf8mb4_unicode_ci;

create index jobs_queue_index
    on jobs (queue);

create table migrations
(
    id        int unsigned auto_increment
        primary key,
    migration varchar(255) not null,
    batch     int          not null
)
    collate = utf8mb4_unicode_ci;

create table password_reset_tokens
(
    email      varchar(255) not null
        primary key,
    token      varchar(255) not null,
    created_at timestamp    null
)
    collate = utf8mb4_unicode_ci;

create table pdfs
(
    id          bigint unsigned auto_increment
        primary key,
    title       varchar(255)         not null,
    description text                 null,
    filename    varchar(255)         not null,
    path        varchar(255)         not null,
    file_size   bigint unsigned      not null,
    page_count  int        default 1 not null,
    fach        varchar(255)         not null,
    thema       varchar(255)         not null,
    uploaded_by bigint unsigned      not null,
    is_public   tinyint(1) default 1 not null,
    created_at  timestamp            null,
    updated_at  timestamp            null
)
    collate = utf8mb4_unicode_ci;

create index pdfs_uploaded_by_foreign
    on pdfs (uploaded_by);

create table role
(
    id    int auto_increment
        primary key,
    used  tinyint(1)   null,
    code  int          not null,
    rolle varchar(10)  null,
    name  varchar(100) null
);

create table sessions
(
    id            varchar(255)    not null
        primary key,
    user_id       bigint unsigned null,
    ip_address    varchar(45)     null,
    user_agent    text            null,
    payload       longtext        not null,
    last_activity int             not null
)
    collate = utf8mb4_unicode_ci;

create index sessions_last_activity_index
    on sessions (last_activity);

create index sessions_user_id_index
    on sessions (user_id);

create table subject
(
    subjectName varchar(100) not null
        primary key,
    classID     varchar(50)  not null,
    constraint subject_ibfk_1
        foreign key (classID) references class (classID)
);

create table category
(
    category_name varchar(100)   not null
        primary key,
    subject_name  varchar(100)   not null,
    description   varchar(15000) null,
    constraint subject___fk
        foreign key (subject_name) references subject (subjectName)
);

create index classID
    on subject (classID);

create table task
(
    subjectName   varchar(100)   not null,
    taskID        int auto_increment
        primary key,
    week          int            null,
    content       varchar(16000) not null,
    category_name varchar(100)   null,
    constraint category___fk
        foreign key (category_name) references category (category_name),
    constraint task_ibfk_1
        foreign key (subjectName) references subject (subjectName)
);

create index subjectName
    on task (subjectName);

create table test
(
    t1 int null,
    t2 int null
);

create table tests
(
    t1 int null,
    t2 int null,
    t3 int null
);

create table users
(
    id                        bigint unsigned auto_increment
        primary key,
    name                      varchar(255) not null,
    email                     varchar(255) not null,
    email_verified_at         timestamp    null,
    password                  varchar(255) not null,
    two_factor_secret         text         null,
    two_factor_recovery_codes text         null,
    two_factor_confirmed_at   timestamp    null,
    remember_token            varchar(100) null,
    created_at                timestamp    null,
    updated_at                timestamp    null,
    role                      varchar(10)  null,
    constraint users_email_unique
        unique (email)
)
    collate = utf8mb4_unicode_ci;

create table admin
(
    admin_id bigint unsigned auto_increment
        primary key,
    user_id  bigint unsigned null,
    constraint admin_ibfk_1
        foreign key (user_id) references users (id)
);

create index user_id
    on admin (user_id);

create table parent
(
    parent_id bigint unsigned auto_increment
        primary key,
    user_id   bigint unsigned null,
    constraint parent_ibfk_1
        foreign key (user_id) references users (id)
);

create index user_id
    on parent (user_id);

create table settings
(
    SettingID int             not null
        primary key,
    UserID    bigint unsigned null,
    Setting1  tinyint(1)      null,
    constraint settings_ibfk_1
        foreign key (UserID) references users (id)
);

create index UserID
    on settings (UserID);

create table studentTaskCompleted
(
    studentTaskID int auto_increment
        primary key,
    userID        bigint unsigned null,
    taskID        int             null,
    constraint studentTaskCompleted_ibfk_1
        foreign key (taskID) references task (taskID),
    constraint studentTaskCompleted_ibfk_2
        foreign key (userID) references users (id)
);

create index taskID
    on studentTaskCompleted (taskID);

create table student_parent
(
    parentID  bigint unsigned not null,
    studentID bigint unsigned not null,
    primary key (parentID, studentID),
    constraint student_parent_fk1
        foreign key (parentID) references users (id),
    constraint student_parent_fk2
        foreign key (studentID) references users (id)
);

create table students
(
    student_id int             not null
        primary key,
    points     int             null,
    class      varchar(30)     not null,
    user_id    bigint unsigned null,
    parent_id  bigint unsigned null,
    progress   float           null,
    classID    varchar(50)     null,
    constraint class_id_fk
        foreign key (classID) references class (classID),
    constraint parent_id_fk
        foreign key (parent_id) references parent (parent_id),
    constraint user_id_fk
        foreign key (user_id) references users (id)
);

create table teacher
(
    teacher_id bigint unsigned auto_increment
        primary key,
    user_id    bigint unsigned null,
    constraint teacher_ibfk_1
        foreign key (user_id) references users (id)
);

create index user_id
    on teacher (user_id);
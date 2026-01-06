CREATE TABLE IF NOT EXISTS `parent`(

    `parent_id` bigint(20) unsigned auto_increment,
    `user_id` bigint(20) unsigned,
    primary key(parent_id),
    FOREIGN KEY (user_id) REFERENCES users (id)
    );
CREATE TABLE IF NOT EXISTS `teacher`(

    `teacher_id` bigint(20) unsigned auto_increment,
    `user_id` bigint(20) unsigned,
    primary key(teacher_id),
    FOREIGN KEY (user_id) REFERENCES users (id)
    );

CREATE TABLE IF NOT EXISTS `admin`(

    `admin_id` bigint(20) unsigned auto_increment,
    `user_id` bigint(20) unsigned,
    primary key(admin_id),
    FOREIGN KEY (user_id) REFERENCES users (id)
    )


CREATE TABLE IF NOT EXISTS settings
(
    SettingID int  not null primary key,
    UserID    bigint unsigned null,
    Setting1  tinyint(1)      null,
        foreign key (UserID) references users (id)
)
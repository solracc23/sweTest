CREATE TABLE students (student_id INTEGER PRIMARY KEY, points INTEGER, class VARCHAR(30) NOT NULL , user_id BIGINT UNSIGNED, parent_id BIGINT UNSIGNED, progress FLOAT, classID VARCHAR(50) );
ALTER TABLE students ADD CONSTRAINT user_id_fk FOREIGN KEY (user_id) REFERENCES users(id);
ALTER TABLE students ADD CONSTRAINT parent_id_fk FOREIGN KEY (parent_id) REFERENCES parent(parent_id);
ALTER TABLE students ADD CONSTRAINT class_id_fk FOREIGN KEY (classID) REFERENCES class(classID);
CREATE TABLE users(
id INTEGER PRIMARY KEY AUTOINCREMENT,
username VARCHAR,
password VARCHAR
);

INSERT INTO news VALUES (NULL,'dood','1234');

CREATE TABLE event(
id INTEGER PRIMARY KEY AUTOINCREMENT,
creator INTEGER,/*user id*/
/*data de criacao?*/
description VARCHAR,
event_type VARCHAR,
image VARCHAR,/*nome imagem*/
image_small VARCHAR,
/*tipo privado/publico*/
);

CREATE TABLE registers(
id INTEGER PRIMARY KEY AUTOINCREMENT,
user_id INTEGER,
event_id INTEGER
);

CREATE TABLE comments(
id INTEGER PRIMARY KEY AUTOINCREMENT,
user_id INTEGER,
event_id INTEGER,
/*data do comment*/
comment_text VARCHAR
)
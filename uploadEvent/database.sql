CREATE TABLE users(
id INTEGER PRIMARY KEY AUTOINCREMENT,
username VARCHAR,
password VARCHAR
);

CREATE table eventTypes (
id INTEGER PRIMARY KEY AUTOINCREMENT,
name VARCHAR
);

CREATE TABLE events (
id INTEGER PRIMARY KEY AUTOINCREMENT,
owner INTEGER,/*user id*/
eventtype INTEGER,
creation_date DATETIME, /*date event was created*/
event_date DATETIME, /*date of the event*/
title VARCHAR,
description VARCHAR,
event_type VARCHAR,
image VARCHAR,/*nome imagem*/
publico BOOLEAN, /*privado ou publico*/
FOREIGN KEY (owner) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (eventtype) REFERENCES eventTypes(id)
);

CREATE TABLE registers(
user_id INTEGER,
event_id INTEGER,
PRIMARY KEY (user_id,event_id),
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE comments(
id INTEGER PRIMARY KEY AUTOINCREMENT,
user_id INTEGER,
event_id INTEGER,
date_comment DATETIME,/*data do comment*/
comment_text VARCHAR,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE images (
id INTEGER PRIMARY KEY AUTOINCREMENT,
photo_url VARCHAR,
extension VARCHAR,
user_id INTEGER,
event_id INTEGER,
FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO eventTypes(name)
VALUES ('Concerto'),('Festa'),('Casamento'),('Batismo'),('Almoço/Jantar'),('Palestra'),('Workshop'),('Reunião');

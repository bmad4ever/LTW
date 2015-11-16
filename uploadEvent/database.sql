CREATE TABLE users(
id INTEGER PRIMARY KEY AUTO INCREMENT,
username VARCHAR,
password VARCHAR
);

CREATE table eventTypes (
id INTEGER PRIMARY KEY AUTO INCREMENT,
name VARCHAR
);

CREATE TABLE events (
id INTEGER PRIMARY KEY AUTO INCREMENT,
owner INTEGER,/*user id*/
eventtype INTEGER,
creation_date DATE, /*date event was created*/
event_date DATE, /*date of the event*/
title VARCHAR,
description VARCHAR,
event_type VARCHAR,
image VARCHAR,/*nome imagem*/
publico BOOLEAN, /*privado ou publico*/,
FOREIGN KEY owner REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY eventtype REFERENCES eventTypes(id)
);

CREATE TABLE registers(
user_id INTEGER,
event_id INTEGER,
PRIMARY KEY (user_id,event_id),
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE comments(
id INTEGER PRIMARY KEY AUTO INCREMENT,
user_id INTEGER,
event_id INTEGER,
date_comment DATE,/*data do comment*/
comment_text VARCHAR,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE images (
id INTEGER PRIMARY KEY AUTO INCREMENT,
photo_url VARCHAR,
user_id INTEGER,
event_id INTEGER,
FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE tableType
ADD ('Concerto','Festa','Casamento','Batismo','Almoço/Jantar','Palestra','Workshop','Reunião');
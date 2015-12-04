CREATE table users(
id INTEGER PRIMARY KEY AUTOINCREMENT,
username VARCHAR,
password VARCHAR,
email VARCHAR(320),
active BOOLEAN,
code VARCHAR
);

CREATE table eventTypes(
id INTEGER PRIMARY KEY AUTOINCREMENT,
name VARCHAR
);

CREATE table events(
id INTEGER PRIMARY KEY AUTOINCREMENT,
owner INTEGER,/*user id*/
eventtype INTEGER,
creation_date DATETIME, /*date event was created*/
event_date DATE, /*date of the event*/
title VARCHAR,
description VARCHAR,
publico BOOLEAN, /*privado ou publico*/
FOREIGN KEY (owner) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (eventtype) REFERENCES eventTypes(id)
);

CREATE table registers(
user_id INTEGER,
event_id INTEGER,
PRIMARY KEY (user_id,event_id),
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (event_id) REFERENCES events(id_event) ON DELETE CASCADE
);

CREATE table comments(
id INTEGER PRIMARY KEY AUTOINCREMENT,
user_id INTEGER,
event_id INTEGER,
date_comment DATETIME,/*data do comment*/
comment_text VARCHAR,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (event_id) REFERENCES events(id_event) ON DELETE CASCADE
);

CREATE table images(
id INTEGER PRIMARY KEY AUTOINCREMENT,
/*photo_url VARCHAR,*/
extension VARCHAR,
user_id INTEGER,
event_id INTEGER,
FOREIGN KEY (event_id) REFERENCES events(id_event) ON DELETE CASCADE,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE invitations(
user_id INTEGER,
event_id INTEGER,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (user_id) REFERENCES events(id_events) ON DELETE CASCADE
PRIMARY KEY (user_id, event_id)
)

INSERT INTO eventTypes(name)
VALUES ('Concerto'),('Festa'),('Casamento'),('Batismo'),('Almoço/Jantar'),('Palestra'),('Workshop'),('Reunião');

CREATE TABLE Loggedin(
  id SERIAL PRIMARY KEY,
  name varchar(30) NOT NULL,
  password varchar(30) NOT NULL
);

CREATE TABLE Thread(
  id SERIAL PRIMARY KEY,
  time Timestamp,
  topic varchar(70) NOT NULL,
  starter INTEGER REFERENCES Loggedin(id)
);

CREATE TABLE Message(
  id SERIAL PRIMARY KEY,
  content text,
  time Timestamp,
  author INTEGER REFERENCES Loggedin(id),
  thread INTEGER REFERENCES Thread(id)
);

CREATE TABLE Replier(
  authorid INTEGER REFERENCES Loggedin(id),
  threadid INTEGER REFERENCES Thread(id)
);

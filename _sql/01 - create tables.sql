-- tabuľka pre užívateľov
CREATE TABLE users (
  id       SERIAL PRIMARY KEY,
  name     CHARACTER VARYING(100) NOT NULL,
  email    CHARACTER VARYING(100) NOT NULL UNIQUE,
  password CHARACTER(128)         NOT NULL,
  is_admin BOOLEAN DEFAULT FALSE  NOT NULL
);

-- tabuľka pre články
CREATE TABLE posts (
  id         SERIAL PRIMARY KEY,
  user_id    INTEGER                                   NOT NULL REFERENCES users (id),
  title      CHARACTER VARYING(100)                    NOT NULL,
  text       TEXT                                      NOT NULL,
  has_image  BOOLEAN DEFAULT FALSE                     NOT NULL,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL
);

-- tabuľka pre tagy
CREATE TABLE tags (
  id  SERIAL PRIMARY KEY,
  tag CHARACTER VARYING(100) NOT NULL UNIQUE
);

-- tabuľka pre spojenie článku s tagmi
CREATE TABLE posts_tags (
  post_id INTEGER NOT NULL REFERENCES posts (id),
  tag_id  INTEGER NOT NULL REFERENCES tags (id)
);
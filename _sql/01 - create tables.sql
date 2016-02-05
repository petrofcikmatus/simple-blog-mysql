-- tabuľka pre užívateľov
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name character varying(100) NOT NULL,
    email character varying(100) NOT NULL UNIQUE,
    password character(128) NOT NULL,
    is_admin boolean DEFAULT false NOT NULL
);

-- tabuľka pre články
CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    user_id integer NOT NULL REFERENCES users(id),
    title character varying(100) NOT NULL,
    text text NOT NULL,
    has_image BOOLEAN DEFAULT FALSE NOT NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL
);

-- tabuľka pre tagy
CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    tag character varying(100) NOT NULL UNIQUE
);

-- tabuľka pre spojenie článku s tagmi
CREATE TABLE posts_tags (
    post_id integer NOT NULL REFERENCES posts(id),
    tag_id integer NOT NULL REFERENCES tags(id)
);
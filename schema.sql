CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    login VARCHAR NOT NULL,
    descri VARCHAR NOT NULL,
    handle VARCHAR NOT NULL
);

CREATE TABLE tweets (
    timestamp timestamp default current_timestamp,
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,
    user_id INT REFERENCES users(id),
    likes INT DEFAULT 0,
    retweets INT DEFAULT 0,
    filename VARCHAR
);
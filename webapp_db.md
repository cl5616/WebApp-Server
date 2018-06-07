# category

## POST

`picture=&content=`



## GET

post: `?category=&preference= cookie:`

comment: `?msg=&`

return json

## user

email, password, nickname, profile, bio, //todo settings

```sql
CREATE TABLE users(
    id SERIAL PRIMARY KEY,
	email varchar(256) NOT NULL UNIQUE,
    password char(64) NOT NULL,
    salt char(64) NOT NULL,
    nickname char(32) NOT NULL,
    introduction varchar(256)
);
```



## msg

id, poster userid, timestamp, picture(ref), content, category, deleted,  anonymous, view, like

```sql
-- user_id,post_time,picture,content,category,deleted,anonymous,view_num,like_num
CREATE TYPE category_t AS ENUM('clubs','market','job','academy','social');
CREATE TABLE posts(
	id SERIAL PRIMARY KEY,
    user_id int NOT NULL,
    post_time timestamp NOT NULL,
    picture varchar(32),
    content text NOT NULL,
    title varchar(129) NOT NULL,
    category category_t,
    deleted bit NOT NULL,
    anonymous bit NOT NULL,
)
```

## relation

```sql
CREATE TABLE like_relation(
	msg_id int NOT NULL,
    user_id int NOT NULL
);

CREATE TABLE view_relation(
	msg_id int NOT NULL,
    user_id int NOT NULL
);
```



## comment

id, poster, msgid, timestamp, content, reply

```sql
-- (poster_id,msg_id,comment_time,content,reply_id)
CREATE TABLE comments(
	id SERIAL PRIMARY KEY,
    poster_id int NOT NULL,
    msg_id int NOT NULL,
    comment_time timestamp NOT NULL,
    content text NOT NULL,
    reply_id int
);
```



## chat


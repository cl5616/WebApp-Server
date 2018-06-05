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
	email varchar(256) PRIMARY KEY,
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
CREATE TYPE category_t AS ENUM ('todo');
CREATE TABLE posts(
	id SERIAL PRIMARY KEY,
    user_id int NOT NULL,
    post_time timestamp NOT NULL,
    picture char(12),
    content text NOT NULL,
    category category_t,
    deleted bit NOT NULL,
    anonymous bit NOT NULL,
    view_num int NOT NULL,
    like_num int NOT NULL
)
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


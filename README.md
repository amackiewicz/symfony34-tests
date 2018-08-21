api
===

# Start environment

1. Install composer dependencies 
```
./composer.phar install
```
2. Run backend
```
make run
```
Then visit one of url below prefixed by url you get from command output.

# API endpoints

- [Swagger UI](http://127.0.0.1:8000/api/doc)

## Posts collection [/api/posts]
- Create post [POST]
- Fetch all posts [GET]

## Single post [/api/posts/{postId}]
- Fetch post details [GET]
- Modify post [PUT]

### Single post comments [/api/posts/{postId}/comments]
- Create comment [POST]
- Fetch post comments [GET]



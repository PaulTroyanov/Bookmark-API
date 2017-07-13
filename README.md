HOW TO USE API

There are two types of requests in this API: POST and GET.

Here are available requests for this API:
1) GET /get/bookmark - return 10 latest inserted bookmarks
2) POST /insert/bookmark/{bookmark_url} - insert new bookmark with specified url
3) GET /get/bookmark/{bookmark_url} - return bookmark with comments by specified bookmark url
4) POST /insert/bookmark/{bookmark_id}/comment/{text_of_comment} - insert new comment to specified bookmark by id.

In order to test this API I recommend to use postman. You can download it here: https://www.getpostman.com
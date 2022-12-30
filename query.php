<?php



$conn = new mysqli(getenv("DB_HOST"), 
                   getenv("DB_USER"), 
                   getenv("DB_PASSWORD"), 
                   "fduhole");
if ($conn->connect_error) {
    die("连接数据库失败");
}

$user_id = 1707;

function query_one($sql) {
    global $conn, $user_id;
    $statement = $conn->prepare($sql);
    $statement->bind_param('i', $user_id);
    $statement->execute();
    $result = $statement->get_result();
    return $result->fetch_assoc();
}

$total_hole_num = query_one(
    "SELECT COUNT(*) AS total
    FROM hole 
    WHERE user_id = ?
      AND created_at BETWEEN '2022-8-28' AND '2023-01-07';"
);

$total_hole_reply_num = query_one(
"SELECT COUNT(*) AS total
FROM hole 
WHERE user_id = ?
  AND reply > 0
  AND created_at BETWEEN '2022-8-28' AND '2023-01-07';"
);

$highest_reply_hole = query_one(
"SELECT hole.id, reply, content
FROM hole JOIN floor ON hole.id = floor.hole_id
WHERE hole.user_id = ?
  AND hole.created_at BETWEEN '2022-8-28' AND '2023-01-07'
  AND storey = 1
ORDER BY reply DESC
LIMIT 1;"
);

$total_reply_num = query_one(
"SELECT COUNT(*) AS total
FROM floor
WHERE user_id = ?
AND created_at BETWEEN '2022-8-28' AND '2023-01-07';"
);

$highest_fav_num = query_one(
"SELECT COUNT(*) AS total
FROM hole JOIN user_favorites
ON hole.id = user_favorites.hole_id
WHERE hole.user_id = ?
AND hole.created_at BETWEEN '2022-8-28' AND '2023-01-07';"
);

$highest_like_num = query_one(
"SELECT floor_id, SUM(like_data) AS likes
FROM floor_like JOIN floor
ON floor_like.floor_id = floor.id
WHERE floor.user_id = ?
AND floor.created_at BETWEEN '2022-8-28' AND '2023-01-07'
GROUP BY floor_id
ORDER BY likes DESC
LIMIT 1;"
);


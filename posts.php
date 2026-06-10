<?php
require("header.php");

// variables and arrays for role and session validation
$user = $_SESSION['user'] ?? null;
$posterid = $user['id'] ?? null;
$adminPerm = ($user['role'] ?? null) === 'admin';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $stmt = $db->prepare("SELECT posts.*, users.username AS username FROM posts LEFT JOIN users ON posts.post_by = users.id WHERE posts.id = :id");
  $stmt->execute([
    ':id' => $id
  ]);
  $posts = $stmt->fetch();

  // was missing ID for the comment buttons
  $stmt = $db->prepare("SELECT comments.id, comments.content, comments.comment_date, comments.comment_by, users.username AS commenter FROM comments LEFT JOIN users ON comments.comment_by = users.id WHERE for_post = :id AND comments.deleted_at IS NULL");
  $stmt->execute([
    ':id' => $id
  ]);
  $comments = $stmt->fetchAll();
}

// It couldnt delete because it was looking for ID not comments_id and it didnt filter the page to only show non deleted comments
if (isset($_POST['comments_id'])) {

  $deleteComment = "UPDATE comments SET deleted_at=:deleted_at WHERE id=:id";
  $stmt = $db->prepare($deleteComment);
  $stmt->execute([
    ':deleted_at' => date("Y-m-d H:i:s"),
    ':id' => $_POST['comments_id']
  ]);
  header("Location: posts.php?id=" . $_GET['id']);
  exit;
}

if (isset($_POST['posts_id'])) {
  $id = $_POST['posts_id'];

  $deleteQuery = "DELETE FROM posts WHERE id=:id";

  $stmt = $db->prepare($deleteQuery);
  $stmt->execute([":id" => $id]);

  header("Location: index.php");
  exit;
}

// This entire section is for the voting system for Posts
// the INT inside the Id's for both post and comment just ensures no funny ("") is added into the Id when using it
if (isset($_POST['vote_direction']) && $posterid) {
  $directionVoted = (int) $_POST['vote_direction'];
  $postId = isset($_POST['for_post']) ? (int) $_POST['for_post'] : null;
  $commentId = isset($_POST['for_comment']) ? (int) $_POST['for_comment'] : null;

  // if there is a Post Id and the comment Id is null, the php will run
  // checkVote query is checking if there is already a vote from the logged in user
  // the statement execute is replacing values :post and :user with the actual session post and user.
  if ($postId && !$commentId) {
    $checkVotePosts = "SELECT id, direction FROM votes WHERE for_post = :post AND by_user = :user";
    $stmt = $db->prepare($checkVotePosts);
    $stmt->execute([
      ':post' => $postId,
      ':user' => $posterid
    ]);
    // if the user already HAS a vote it will fetch the specific vote from the votes table
    // e.g. logged in as John Doe and upvoted post 1, data is stored inside table and specifically only fetches John Doe's data so it can be changed them if they want to change votes
    $voteExist = $stmt->fetch();

    // if the user already voted and presses it, it will toggle the vote (.e.g. user already has an upvote and presses it again the vote will toggle between upvote or removed)
    if ($voteExist) {
      if ($voteExist['direction'] == $directionVoted) {
        $stmt = $db->prepare("DELETE FROM votes WHERE id = :id");
        $stmt->execute([':id' => $voteExist['id']]);
        // the first ELSE statement allows the user to change their votes between upvoting (like) or downvoting (dislike)
        // e.g. John Doe already has upvote on post 1, it can changed with this query
      } else {
        $stmt = $db->prepare("UPDATE votes SET direction = :direction WHERE id = :id");
        $stmt->execute([
          ':direction' => $directionVoted,
          ':id' => $voteExist['id']
        ]);
      }
      // the 2nd ELSE statement runs when the user has no data from voting on the specified post
      // e.g. Jane Doe has no vote data from the table so it is inserted with this query
    } else {
      $newPostVote = "INSERT INTO votes (direction, for_post, for_comment, by_user) VALUES (:direction, :post, NULL, :user)";
      $stmt = $db->prepare($newPostVote);
      $stmt->execute([
        ':direction' => $directionVoted,
        ':post' => $postId,
        ':user' => $posterid
      ]);
    }
  }

  // Test Comment Query Later
  if ($commentId && !$postId) {
    $checkVoteComments = "SELECT id, direction FROM votes WHERE for_comment = :comment AND by_user = :user";
    $stmt = $db->prepare($checkVoteComments);

    $stmt->execute([
      ':comment' => $commentId,
      ':user' => $posterid
    ]);

    $voteExist = $stmt->fetch();

    if ($voteExist) {
      if ($voteExist['direction'] == $directionVoted) {
        $stmt = $db->prepare("DELETE FROM votes WHERE id = :id");
        $stmt->execute([':id' => $voteExist['id']]);
      } else {
        $stmt = $db->prepare("UPDATE votes SET direction = :direction WHERE id = :id");
        $stmt->execute([
          ':direction' => $directionVoted,
          ':id' => $voteExist['id']
        ]);
      }
    } else {
      $stmt = $db->prepare("
      INSERT INTO votes (direction, for_post, for_comment, by_user)
      VALUES (:direction, NULL, :comment, :user)
    ");

      $stmt->execute([
        ':direction' => $directionVoted,
        ':comment' => $commentId,
        ':user' => $posterid
      ]);
    }
  }
  header("Location: posts.php?id=" . $_GET['id']);
}

// $postVotes is variable used for showing data from the table inside the HTML php
$showVotesPosts = "SELECT SUM(CASE WHEN direction = 1 THEN 1 ELSE 0 END) AS upvotes, SUM(CASE WHEN direction = -1 THEN 1 ELSE 0 END) AS downvotes FROM votes WHERE for_post = :id";
$stmt = $db->prepare($showVotesPosts);
$stmt->execute([':id' => $id]);
$postVotes = $stmt->fetch();

// This is for checking if the user has already upvoted/downvoted a specific post
$userPostVote = null;

if ($posterid) {
  $postVoteView = "SELECT direction FROM votes WHERE for_post = :post AND by_user = :user";
  $stmt = $db->prepare($postVoteView);
  $stmt->execute([
    ':post' => $id,
    ':user' => $posterid
  ]);
  $userPostVote = $stmt->fetchColumn();
}

$userCommentVote = [];
if ($posterid) {
  $commentVoteView = "SELECT for_comment, direction FROM votes WHERE by_user = :user AND for_comment IS NOT NULL";
  $stmt = $db->prepare($commentVoteView);
  $stmt->execute([
    ':user' => $posterid
  ]);
  foreach ($stmt->fetchAll() as $row) {
    $userCommentVote[$row['for_comment']] = $row['direction'];
  }
}

$showVotesComments = "SELECT for_comment, SUM(CASE WHEN direction = 1 THEN 1 ELSE 0 END) AS upvotes, SUM(CASE WHEN direction = -1 THEN 1 ELSE 0 END) AS downvotes FROM votes WHERE for_comment IS NOT NULL GROUP BY for_comment";
$stmt = $db->prepare($showVotesComments);
$stmt->execute();

$CommentsVotesTotal = [];
foreach ($stmt->fetchAll() as $row) {
  $CommentsVotesTotal[$row['for_comment']] = [
    'upvotes' => $row['upvotes'],
    'downvotes' => $row['downvotes']
  ];
}
$usersession = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>

<!DOCTYPE html>
<html>

<head>
  <title>Placeholder Post View</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
    crossorigin="anonymous" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="styles.css">
  <style type="text/css">
    body {
      background: #f1f1f1;
    }

    .cover-box {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 40px;
      border-radius: 8px;
      color: white;
    }
  </style>
</head>

<body>
  <div class="container-fluid bg-dark navbar-dark">
    <nav class="navbar navbar-expand-lg pt-0">
      <div class="container navbar-dark">
        <a href="index.php" class="navbar-brand">Discussion Forum</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!-- This section is for navbar items being centered after logging in -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <?php if ($usersession) : ?>
            <ul class="navbar-nav mx-auto">
            </ul>
          <?php endif; ?>
          <!-- This section is for navbar items that remain at the right not logged in -->
          <?php if (!$usersession) : ?>
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a href="login-form.php" class="nav-link <?= isset($usersession) ? ' d-none' : '' ?>">Login</a>
              </li>
              <li class="nav-item">
                <a href="register-form.php" class="nav-link <?= isset($usersession) ? ' d-none' : '' ?>">Sign Up</a>
              </li>
            </ul>
          <?php endif; ?>
          <?php if ($usersession) : ?>
            <div class="nav-account">
              <button class="account-trigger" aria-haspopup="true">
                <!-- the specialchars just makes the username act like text instead of code that needs to be read -->
                <span><i class="bi bi-person-circle"></i></span> Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?> ▼
              </button>
              <div class="account-dropbox">
                <a href="dashboard.php" class="nav-link <?= isset($usersession) && $_SESSION['user']['role'] === 'admin' ? '' : 'd-none' ?>"><i class="bi bi-menu-button"></i>Dashboard</a>
                <a href="./logout.php?logout=true" class="nav-link <?= isset($_SESSION['user']) ? '' : ' d-none' ?>"><i class="bi bi-box-arrow-left"></i>Logout</a>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </div>
  <div class="container cover-box mx-auto my-5">
    <div class="mt-3">
      <a href="index.php" class="btn btn-primary"><i class="bi bi-arrow-left-circle"></i></a>
    </div>
    <h1 class="h1 mb-4"><?= $posts['title'] ?></h1>
    <p class="fw-light d-inline">
      <?= $posts['post_date'] ?>
    </p>
    <!-- condition for seeing the edit button cant be put above the delete button because the closing div is unconditional so it makes the post escape -->
    <?php if ($posterid && $posterid == $posts['post_by'] || $adminPerm) : ?>
      <div class="buttons d-flex align-items-center justify-content-end">
        <?php if ($posterid && $posterid == $posts['post_by']) : ?>
          <a
            href="manage-posts-edit.php?id=<?= $posts['id'] ?>"
            class="btn btn-success btn-sm me-2">
            <i class="bi bi-pencil"></i>
          </a>
        <?php endif; ?>

        <form method="post">
          <input type="hidden" name="posts_id" value="<?= $posts['id'] ?>">
          <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i>
          </button>
        </form>
      </div>
    <?php endif; ?>
    <p class="fw-bold text-capitalize">
      <?= $posts['username'] ?>
    </p>
    <p>
      <?= $posts['content'] ?>
    </p>
    <div class="d-flex align-items-center gap-2 mb-3">

      <!-- for_post hidden input is to check what which post it is giving the votes to -->
      <!-- vote_direction hidden input is to make the TINYINT(boolean) value detect either true (1) or false (-1) -->
      <div class="votetip-box">
        <form method="post">
          <input type="hidden" name="for_post" value="<?= $posts['id'] ?>">
          <input type="hidden" name="vote_direction" value="1">
          <button class="btn btn-success btn-sm"><i class="bi bi-arrow-up"></i></button>
        </form>

        <div class="votetip-text">
          <?= $userPostVote == 1 ? "You have already upvoted this" : "Upvote" ?>
        </div>
      </div>
      <p class="mb-0 fw-bold">
        <?= $postVotes['upvotes'] ?? 0 ?>
        |
        <?= $postVotes['downvotes'] ?? 0 ?>
      </p>
      <div class="votetip-box">
        <form method="post">
          <input type="hidden" name="for_post" value="<?= $posts['id'] ?>">
          <input type="hidden" name="vote_direction" value="-1">
          <button class="btn btn-danger btn-sm"><i class="bi bi-arrow-down"></i></button>
        </form>
        <div class="votetip-text">
          <?= $userPostVote == -1 ? "You have already downvoted this" : "Downvote" ?>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <?php foreach ($comments as $comment): ?>
      <div class="card p-3 comment-text-color">
        <p class="d-flex justify-content-between">
          <span class="fw-bold text-capitalize"><?= $comment['commenter'] ?></span>
          <span class="fw-light"><?= $comment['comment_date'] ?></span>
        </p>
        <p class="mt-2">
          <?= $comment['content'] ?>
        </p>
        <div class="d-flex align-items-center">
          <div class="d-flex align-items-center gap-2">
            <div class="votetip-box">
              <form method="post">
                <input type="hidden" name="for_comment" value="<?= $comment['id'] ?>">
                <input type="hidden" name="vote_direction" value="1">
                <button class="btn btn-sm btn-success"><i class="bi bi-arrow-up"></i></button>
              </form>
            </div>

            <div class="votetip-text">
              <?= ($userCommentVote[$comment['id']] ?? 0) == 1 ? 'You already upvoted this' : "Upvote" ?>
            </div>
            <p class="mb-0 fw-bold">
              <?= $CommentsVotesTotal[$comment['id']]['upvotes'] ?? 0 ?>
              |
              <?= $CommentsVotesTotal[$comment['id']]['downvotes'] ?? 0 ?>
            </p>
            <div class="votetip-box">
              <form method="post">
                <input type="hidden" name="for_comment" value="<?= $comment['id'] ?>">
                <input type="hidden" name="vote_direction" value="-1">
                <button class="btn btn-danger btn-sm"><i class="bi bi-arrow-down"></i></button>
              </form>
            </div>
            <div class="votetip-text">
              <?= ($userCommentVote[$comment['id']] ?? 0) == -1 ? "You already downvoted this" : "Downvote" ?>
            </div>
          </div>
          <div class="ms-auto d-flex align-items-center">
            <?php if ($posterid && ($posterid == $comment['comment_by'] || $posterid == $posts['post_by'] || $adminPerm)) : ?>
              <div class="buttons d-flex align-items-center">
                <?php if ($posterid && ($posterid == $comment['comment_by'] || $posterid == $posts['post_by'])) : ?>
                  <a
                    href="manage-comments-edit.php?id=<?= $comment['id'] ?>"
                    class="btn btn-success btn-sm me-2"><i class="bi bi-pencil"></i></a>
                <?php endif; ?>
                <form method="post">
                  <input type="hidden" name="comments_id" value="<?= $comment['id'] ?>">
                  <button class="btn btn-danger btn-sm" type="submit" value="<?= $comment['id'] ?>"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    <div class="text-center">
      <a href="manage-comments-add.php?id=<?= $posts['id'] ?>" class="btn btn-primary btn-sm m-3"> Add new Comment</a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
  <footer>
    <div class="container-fluid bg-dark py-4">
      <div class="container text-center">
        <div class="d-flex justify-content-center pb-2">
          <i class="bi bi-facebook text-white px-2"></i>
          <i class="bi bi-twitter text-white px-2"></i>
          <i class="bi bi-instagram text-white px-2"></i>
          <a href="https://github.com/BillnDoss/php-mysql-project" target="_blank"><i class="bi bi bi-github text-white px-2"></i></a>
        </div>
        <p class="text-white text-center">&copy; 2026 JTTY. All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>

</html>
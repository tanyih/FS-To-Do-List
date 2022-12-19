<?php 
  $database = new PDO(
    'mysql:host=devkinsta_db;dbname=todo_list',
      'root',
      'eIQ1TKk22RcYiBA9' //your database password
  );
  $query = $database->prepare ('SELECT * FROM todos');
    $query->execute();
    
    $todos = $query->fetchAll();

    if (
        $_SERVER['REQUEST_METHOD'] ==='POST'
    ) {

        // add
        if ( $_POST['action'] === 'add'){
           
            $statement = $database->prepare(
                "INSERT INTO todos (`item`) 
                VALUES (:item)"
            );
            $statement->execute([
                'item' => $_POST['todo']
            ]);
        
            header('Location: /');
            exit;
         } 

        //  delete
         if ( $_POST['action'] === 'delete') {
            
            $statement = $database->prepare(
                'DELETE FROM todos WHERE id = :id'
            );
            $statement->execute([
                'id' => $_POST['item_id']
            ]);

            header('Location: /');
            exit;
         }

        //  check
         if($_POST['action'] === 'check'){
          if ($_POST['completed'] == 0 ) {
            $statement = $database->prepare(
              'UPDATE todos SET completed = 1 WHERE id = :id'
            );
          } else{
            $statement = $database->prepare(
              'UPDATE todos SET completed = 0 WHERE id = :id'
            );
          }
          $statement->execute([
            'id' => $_POST['item_id']
          ]);
          header('Location: /');
          exit;
        }

    }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>TODO App</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    />
    <style type="text/css">
      body {
        background: #f1f1f1;
      }
    </style>
  </head>
  <body>
    <div
      class="card rounded shadow-sm"
      style="max-width: 500px; margin: 60px auto;"
    >
      <div class="card-body">
        <h3 class="card-title mb-3">My Todo List</h3>
        <ul class="list-group">
        <?php foreach ($todos as $todo) : ?>
          <li
            class="list-group-item d-flex justify-content-between align-items-center"
          >
          <div>
            <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <?php if($todo['completed'] == 1 ) : ?>
              <input type="hidden" name="item_id" value="<?php echo $todo['id']; ?>">
              <input type="hidden" name="action" value="check">
              <input type="hidden" name="completed" value="<?php echo $todo['completed']; ?>">
              <button class="btn btn-sm btn-success">
                <i class="bi bi-check-square"></i>
              </button>
            <?php else : ?>
                <input type="hidden" name="item_id" value="<?php echo $todo['id']; ?>">
                <input type="hidden" name="action" value="check">
                <input type="hidden" name="completed" value="<?php echo $todo['completed']; ?>">
                <button class="btn btn-sm">
                  <i class="bi bi-square"></i>
                </button>
              <?php endif; ?>
              <span name="todo" class="ms-2"><?php echo $todo['item']; ?></span>
            </form>
          </div>
          <div>
            <form method='POST' action="<?php echo $_SERVER['REQUEST_URI']; ?>">
              <input type="hidden" name='item_id' value="<?php echo $todo['id']; ?>" >
              <input type="hidden" name="action" value="delete">
              <button class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </li>
        <?php endforeach; ?>
          
        </ul>
        <div class="mt-4">
        <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="d-flex justify-content-between align-items-center">
            <input
                type="text"
                class="form-control"
                placeholder="Add new item..."
                name="todo"
                required
            />
            <input 
                type="hidden"
                name="action"
                value="add" 
            />
          <button class="btn btn-primary btn-sm rounded ms-2">Add</button>
        </form>
        </div>
      </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
  </body>
</html>

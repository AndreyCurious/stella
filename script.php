<?php
function console_log($data){ 
  if(is_array($data) || is_object($data)){
  echo("<script>console.log(".json_encode($data).");</script>");
} else {
  echo("<script>console.log('$data');</script>");
}
}

function getPersons() {
  if ($_POST['input'] !== '') {
    $peopleList = array();
    // Получаем посты с адреса
    $urlWallget = 'http://api.vk.com/method/wall.get?access_token=81ff331181ff331181ff3311e382e81c56881ff81ff3311e43cbd2ae498806430b5265b&domain=stella_pro&v=5.199&count='.$_POST['input'].'';
    $result = json_decode(file_get_contents($urlWallget));
    
    $items = $result->response->items;
    foreach ($items as $value) {
      // С каждого поста получаем id и делаем запрос на лайкнувших 
      $urlGetLikes = 'http://api.vk.com/method/likes.getList?access_token=81ff331181ff331181ff3311e382e81c56881ff81ff3311e43cbd2ae498806430b5265b&type=post&extended=1&owner_id='.$value->owner_id.'&item_id='.$value->id.'&v=5.199';
      $people = json_decode(file_get_contents($urlGetLikes));
      $persons = $people->response->items;
      foreach ($persons as $value) {
        $name = $value->first_name. ' ' .$value->last_name;
        if (array_key_exists($name, $peopleList)) {
          $peopleList[$name] += 1;
        } else {
          $peopleList[$name] = 1;
        }
      }
    }
    // Если человек ввел число, превышающее количество постов, то выводится максимальное число постов, которое удалось получить
    echo '<h2>Количество постов: ';
    if ($_POST['input'] > $result->response->count) {
      echo $result->response->count;
    } else {
      echo $_POST['input'];
    };     
    echo '</h2>';
    echo 
    '
      <input id="up" type="radio" name="sort"><label for="up">От меньшего к большему, по лайкам</label><br>
      <input id="down" type="radio" name="sort"><label for="down">От большего к меньшему, по лайкам</label><br><br>
    ';
    echo
    // Здесь табличку сделал не семанитческими тегами, чтобы работала сортировка без JS
    '<div id="table">
      <div class=table-title>
        <h4>Человек</h4>
        <h4>Количество лайков</h4>
      </div>
      <div class="persons">';
      for($i = 1; $i <= count($peopleList); $i++) {
        $data = key($peopleList). ' ' . $peopleList[key($peopleList)];
        // Чтобы не использовать JS, добавляем переменную количества лайков в style
        echo '<div class="items" style="--likes: '.$peopleList[key($peopleList)].'"><span>'.key($peopleList).'</span><span class="likes">'.$peopleList[key($peopleList)].'</span></div>';
        next($peopleList);
      };
    echo '</div></div>';
    echo '<br>';

    console_log($peopleList);
  } else {
    // Если пользователь не ввел число, выведем сообщение об этом и кнопку - вернуться
    echo 'Необходимо ввести число';
    echo 
    '<form action="index.php" method="GET">
      <input name="submit" type="submit" value="Назад" />
    </form>';
  };

};

/*   С джаваскриптом, здесь по кнопкам переключается сортировка, и таблица тегами сделана, но это не имееет большой разницы,
показалось, что по кнопкам радио будет удобнее. Здесь не стал исправлять на радио, раз это промежуточное задание.
    echo '<table border="1" id="table">
      <caption>Количество постов:';
      if ($_POST['input'] > $result->response->count) {
        echo $result->response->count;
      } else {
        echo $_POST['input'];
      };     
      echo '</caption>
      <tr>
        <th>Человек</th>
        <th>Количество лайков</th>
      </tr>';
      for($i = 1; $i <= count($peopleList); $i++) {
        $data = key($peopleList). ' ' . $peopleList[key($peopleList)];
        echo '<tr style="--likes: '.$peopleList[key($peopleList)].'"><td>'.key($peopleList).'</td><td>'.$peopleList[key($peopleList)].'</td></tr>';
        next($peopleList);
      };
    echo '</table>';

    echo "<button id='up' class='up'>От меньшего к большему</button>
    <button id='down' class='down'>От большего к меньшему</button>";

    echo "
    <script type='text/javascript'>
      const upBtn = document.querySelector('up');
      const downBtn = document.querySelector('down');
      document.querySelector('.up').addEventListener('click', (e) => {
        let sortedRows = Array.from(table.rows).slice(1).sort((rowA, rowB) =>Number(rowA.cells[1].innerHTML) > Number(rowB.cells[1].innerHTML) ? 1 : -1)
        table.tBodies[0].append(...sortedRows);
      });
      document.querySelector('.down').addEventListener('click', (e) => {
        let sortedRows = Array.from(table.rows).slice(1).sort((rowA, rowB) =>Number(rowA.cells[1].innerHTML) < Number(rowB.cells[1].innerHTML) ? 1 : -1)
        table.tBodies[0].append(...sortedRows);
      });
    </script>";
*/
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <?php
    getPersons();
  ?>
</body>

</html>







<link rel="stylesheet" href="content/Assets/css/style.css">
<link rel="stylesheet" href="content/Assets/css/jquery-ui.min.css">
<script src="content/Assets/js/jquery-min.js"></script>
<script src="content/Assets/js/jquery-ui.min.js"></script>
<script src="content/Assets/js/jquery.popupoverlay.js"></script>
<script>
    var userAuth = <?=empty($user)?'false':'true'?>;
</script>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<div class="login-popup">
    <input class="task-input" placeholder="login" id="login">
    <input class="task-input" placeholder="password" type="password" id="password">
    <div class="auth-btn">Войти</div>
</div>

<div class="login-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" version="1.1">
        <path d="M16.642 20.669c-0.391 0.39-0.391 1.023-0 1.414 0.195 0.195 0.451 0.293 0.707 0.293s0.512-0.098 0.707-0.293l5.907-6.063-5.907-6.063c-0.39-0.39-1.023-0.39-1.414 0s-0.391 1.024 0 1.414l3.617 3.617h-19.264c-0.552 0-1 0.448-1 1s0.448 1 1 1h19.326zM30.005 0h-18c-1.105 0-2.001 0.895-2.001 2v9h2.014v-7.78c0-0.668 0.542-1.21 1.21-1.21h15.522c0.669 0 1.21 0.542 1.21 1.21l0.032 25.572c0 0.668-0.541 1.21-1.21 1.21h-15.553c-0.668 0-1.21-0.542-1.21-1.21v-7.824l-2.014 0.003v9.030c0 1.105 0.896 2 2.001 2h18c1.105 0 2-0.895 2-2v-28c-0.001-1.105-0.896-2-2-2z"/>
    </svg>
</div>

<div class="task-container">
    <div class="task-dashboard">
        <div class="task-sort">Сортировать по
            <select class="task-select" id="task-sort-field">
                <option value="user_name">Имени пользователя</option>
                <option value="email">Email</option>
                <option value="status">Статусу</option>
            </select>
            <select class="task-select" id="task-sort-type">
                <option value="ASC">По возрастанию</option>
                <option value="DESC">По убыванию</option>
            </select>
        </div>
        <div class="task-open-create" >+</div>
    </div>
    <div class="task-create">
        <div><input class="task-input" id="task-create-username" placeholder="Имя пользователя.."></div>
        <div><input class="task-input" id="task-create-email"  placeholder="Email..."></div>
        <div><textarea class="task-input" id="task-create-task-text"  placeholder="Задача..."></textarea></div>
        <div class="task-create-btn">Создать</div>
    </div>
    <div class="task-list">

    </div>
    <div class="task-pagination">

    </div>
</div>
<script src="content/Assets/js/task.js"></script>


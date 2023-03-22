class Tasks{
    constructor(taskContainer,paginationContainer,activePage = 1,tasksPageLimit = 3,orderBy = 'user_name',orderByType = 'ASC') {
        this.taskContainer = taskContainer;
        this.paginationContainer = paginationContainer;
        this.activePage = activePage;
        this.tasksPageLimit = tasksPageLimit;
        this.orderBy = orderBy;
        this.orderByType = orderByType;
    }
    getOffset(){
        return tasksApp.tasksPageLimit * (tasksApp.activePage-1);
    }
    draw(tasks){
        this.taskContainer.html('');
        this.paginationContainer.html('');
        for(let i = 0;i<tasks['items'].length;i++){
            let adminChanged = '';
            if(parseInt(tasks['items'][i]['admin_changed']) === 1){
                adminChanged = 'Отредактировано администратором'
            }
            let task = $(`
            <div class="task-list-item">
                <div class="task-list-item-content">
                <div class="task-list-item-username">`+tasks['items'][i]['user_name']+`</div>
                <div class="task-list-item-email">`+tasks['items'][i]['email']+`</div>
                <div class="task-list-item-text" onclick="changeTaskText(this)" data-task="`+tasks['items'][i]['id']+`">`+tasks['items'][i]['task_text']+`</div>
                <div class="task-list-item-change">`+ adminChanged +`</div>
                </div>
                <div class="task-list-item-status">
                   <input type="checkbox" onclick="changeTaskStatus(this)" data-task="`+tasks['items'][i]['id']+`">
                </div>
            </div>
            `);
            if(parseInt(tasks['items'][i]['status']) === 1){
                task.find('input[type="checkbox"]').prop('checked',true);
            }
            if(!userAuth){
                task.find('input[type="checkbox"]').prop('disabled',true);
            }
            this.taskContainer.append(task);
        }
        for(let i = 1;i<=tasks['pages'];i++){
            let pagination = $(`
               <div class="pagination-btn" onclick="changePage(this)" data-page="`+i+`"></div>
            `);
            if(i === this.activePage){
                pagination.addClass('active');
            }
            this.paginationContainer.append(pagination);
        }
        return true;
    }
    create(username, email, taskText, callback){
        $.ajax({
            url: 'ajax',
            dataType: 'json',
            data:{
                'model': 'Task\\Task',
                'method': 'create',
                'username' : username,
                'email' : email,
                'taskText' : taskText
            },
            context:this,
            success:function(data){
                alert('Задача успешно создана');
                callback();
            }
        });
    }
}

var tasksApp = new Tasks($('.task-list'),$('.task-pagination'));

function loadTasks(){
    $.ajax({
        url: 'ajax',
        dataType: 'json',
        data: {
            'model': 'Task\\Task',
            'method': 'getTaskList',
            'limit' : tasksApp.tasksPageLimit,
            'offset' : tasksApp.getOffset(),
            'orderBy' : tasksApp.orderBy,
            'orderByType': tasksApp.orderByType
        },
        context: this,
        success: function (data){
            if(data['status'] === 'Success'){
                tasksApp.draw(data['content']);
            }else if(data['status'] === 'Error'){
                console.log(data['content']);
            }
        }
    });

}
function changeTaskStatus(input){
    let status = $(input).prop('checked')?1:0;
    let taskId = $(input).data('task');
    $.ajax({
        url: 'ajax',
        dataType: 'json',
        data: {
            'model': 'Task\\Task',
            'method': 'changeStatus',
            'status': status,
            'taskId': taskId
        },
        context: this,
        success: function (data){
            if(data['content'] === true){
                loadTasks();
            }else  if(data['content'] === 'Not allowed'){
                $(input).prop('checked',!$(input).prop('checked'))
                alert('Авторизуйтесь');
            }
        }
    });
}
function changePage(button){
    tasksApp.activePage = parseInt($(button).data('page'));
    loadTasks();
}
function auth(){
    let login = $('#login').val();
    let password = $('#password').val();
    $.ajax({
        url: 'ajax',
        dataType: 'json',
        data: {
            'model': 'User\\User',
            'method': 'auth',
            'login': login,
            'password': password
        },
        success: function (data){
            if(data['content'] === true){
                location.reload();
            }else if(data['content'] === false){
                alert('Неверный логин или пароль');
            }
        }
    });
}
function changeTaskText(textBlock){
    if($(textBlock).hasClass('change')){
        return;
    }
    if(!userAuth){
        alert('Авторизуйтесь');
        return;
    }

    $(textBlock).addClass('change')
    let taskText = $(textBlock).text();
    let inputBlock = $('<textarea></textarea>');
    let taskId = $(textBlock).data('task');
    inputBlock.text(taskText);
    inputBlock.on('focusout',function (){
        var taskTextUpdate = $(this).val().trim();
        $.ajax({
            url: 'ajax',
            dataType: 'json',
            data: {
                'model': 'Task\\Task',
                'method': 'changeTaskText',
                'taskText': taskTextUpdate,
                'taskId': taskId
            },
            success: function (data){
                $(textBlock).html('');
                $(textBlock).removeClass('change')
                if(data['content'] === true){
                    $(textBlock).text(taskTextUpdate);
                    loadTasks();
                }else if(data['content'] === 'Not allowed'){
                    $(textBlock).text(taskText);
                    alert('Авторизуйтесь');
                }

            }
        });
    });
    $(textBlock).html(inputBlock);
    inputBlock.focus();
}
$('.task-open-create').on('click',function(){
    $('.task-create').show("fade", {direction: "down" }, "slow");
    $(this).hide("fade", {direction: "down" }, "slow");
})
$('.task-create-btn').on('click',function(){
    let username = $('#task-create-username').val().trim();
    let email = $('#task-create-email').val().trim();
    let taskText = $('#task-create-task-text').val().trim();

    if(username === ''||email === ''||taskText ===''){
        alert('Заполните все поля.');
        return;
    }
    var validEmailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    if (!email.match(validEmailRegex)) {
        alert('Введите валидный email');
        return;
    }
    tasksApp.create(username,email,taskText,loadTasks);
    $('.task-create .task-input').val('');
    $('.task-create').hide("fade", {direction: "down" }, "slow");
    $('.task-open-create').show("fade", {direction: "down" }, "slow")
})
$('.task-select').on('change',function (){
   tasksApp.orderBy = $('#task-sort-field').val();
   tasksApp.orderByType = $('#task-sort-type').val();
   loadTasks();
});

$('.login-btn').on('click',function(){
    if(userAuth){
        $.ajax({
            url: 'ajax',
            dataType: 'json',
            data: {
                'model': 'User\\User',
                'method': 'logout',
            },
            success: function (data){
                if(data['content'] === true){
                    location.reload();
                }
            }
        });
    }else{
        $('.login-popup').popup('show');
    }
});
$('.login-popup').popup({
    pagecontainer: '.login-popup',
    escape: false
});
$('.auth-btn').on('click',auth);
window.onload = loadTasks;

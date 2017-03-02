# FileMaker-Server-API

API made using PHP to work with FileMaker databases using AJAX calls.

__'Users' Table__

| id       | name      | age    |
| -------- |:---------:| ------:|
| 110      | Jade      | 20     |
| 111      |           | 22     |
| 112      | Jay       | 27     |


__First Row__
```js
$.ajax({
    url: 'api.php',
    method: 'post',
    data: {
        layout: 'Users'
    },
    success: function (user) {
        console.log(user);
    }
});
```

__Results__
```js
{
    id: 110,
    name: 'Jade',
    age: 20
}
```

__Row By ID__
```js
$.ajax({
    url: 'api.php',
    method: 'post',
    data: {
        find: 'id',
        id: 112,
        layout: 'Users'
    },
    success: function (user) {
        console.log(user);
    }
});
```

__Results__
```js
{
    id: 112,
    name: 'Jay',
    age: 27
}
```

__Adding New Row__
```js
$.ajax({
    url: 'api.php',
    method: 'post',
    data: {
        save: true
    },
    success: function (save) {
        console.log(save);
    }
});
```

__Updating Row By ID__
```js
$.ajax({
    url: 'api.php',
    method: 'post',
    data: {
        save: true
    },
    success: function (save) {
        console.log(save);
    }
});
```
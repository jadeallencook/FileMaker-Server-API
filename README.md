# FileMaker-Server-API

API made using PHP to work with FileMaker databases using AJAX calls.

__'Users' Table__

| id       | name      | age    |
| -------- |:---------:| ------:|
| 110      | Jade      | 20     |
| 111      |           | 22     |
| 112      | Jay       | 27     |


__First Row__
_Return the first row of a table by just passing the API the layout name._
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
_You can return a row that is equal to an ID by passing it both the ID and what field to find it in._
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
_Add a new row of information to the end of any layout._
```js
$.ajax({
    url: 'api.php',
    method: 'post',
    data: {
        save: true
        data: {
            id: 113,
            name: 'Josh',
            age: 25
        }
    },
    success: function (save) {
        console.log(save);
    }
});
```

__Updating Row By ID__
_Update a certain entry by passing the API the row and ID to update along with the data._
```js
$.ajax({
    url: 'api.php',
    method: 'post',
    data: {
        save: true,
        find: 'id',
        id: 111,
        data: {
            name: 'Nick'
        }
    },
    success: function (save) {
        console.log(save);
    }
});
```
# FileMaker-Server-API

API made using PHP to work with FileMaker databases using AJAX calls.

__Settings Table__
| color    | position  | size   |
| -------- |:---------:| ------:|
| red      |           | 3      |

```js
$.ajax({
    url: 'api.php',
    method: 'post',
    data: {
        layout: 'settings'
    },
    success: function (settings) {
        console.log(settings);
    }
});
```

__Results__
```js
{
    color: 'red',
    position: null,
    size: 3
}
```
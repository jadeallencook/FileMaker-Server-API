# FileMaker-Server-API

API made using PHP to work with FileMaker databases using AJAX calls.

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
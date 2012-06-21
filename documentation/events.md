# Events

Events

 * `loop.startup`
 * `loop.shutdown`
 * `pre.dispatch`
 * `post.dispatch`

## Examples

```php
<?php
$app->getEventManager()->subscribe("loop.startup", function(){
    echo "OKs.";
});
```

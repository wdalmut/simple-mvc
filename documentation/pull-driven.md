# Pull Driven Requests

Typically MVC frameworks are "push" based. In otherwords use mechanisms to "push" data to 
a view and not vice-versa. A "pull" framework instead request ("pull") data from a view.

Pull strategy is useful for example during a `for` statement (not only for that [obviously]...). Look 
for an example:

```php
<?php foreach ($this->users as $user) : ?>
<?php
    // Pull data from a controller. 
    $userDetail = $this->pull("/detail/user/id/{$user->id}");
?>
<div class="element">
    <div class="name"><?php echo $userDetail->name;?> <?php echo $userDetail->surname; ?></div>
    <!-- other -->
</div>
<?php endforeach; ?>
```
 
## `simple-mvc` implementation

`simple-mvc` has ***push*** and ***pull*** mechanisms. The *push* is quite simple and a typical 
operation. See an example

```php
<?php
class EgController extends Controller
{
    public function actAction()
    {
        // PUSH to view a variable named <code>var</code>
        $this->view->var = "hello";
    }
}
```

The view show the pushed variable

```php
<?php echo $this->var; ?>
```

The *pull* strategy is quite similar but use the return statement of a controller to retrive 
all the information. Consider in advance that `simple-mvc` doesn't require a valid controller
for retrive a view, that view is mapped directly. See an example

```
<!-- this view is test/miss.phtml (/test/miss GET) -->
<div>
    <h1>Missing controller and action</h1>
    
    <?php $data = $this->pull("/ctr/act"); ?>
    
    <!-- example -->
    <?php echo $data->title; ?>
</div>
```

The view require a `pull` operation from a controller named `ctr` and action `act`. See it:

```php
<?php
class CtrController extends Controller
{
    public function actAction()
    {
        $data = new stdClass();
        
        $data->title = "The title";
        
        // The return type doesn't care...
        return $data;
    }
}
``` 

You can use a "pull" controller as a normal controller with the attached view, but remember
that when you request for a "pull" operation the view is never considered and the framework
remove it without consider the output, only the `return` statement will be used.

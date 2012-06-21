# Controllers

The controller section

## Next action

The `next` action goes forward to the next action appending
the next view.

```php
<?php 
class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->hello = "hello";
        
        
        $this->then("/index/next");
    }
    
    public function nextAction()
    {
        $this->view->cose = "ciao";
    }
}
```

The result is the first view (`index.phtml`) concatenated to the
second view (`next.phtml`).

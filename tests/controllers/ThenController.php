<?php
class ThenController extends Controller
{
    public function firstAction()
    {
        echo "first->";
        $this->then("/then/second");
    }

    public function secondAction()
    {
        echo "<-second";
    }
}

<?php

interface DecideAction{
    public function decideAction(Character $user, Character $target, int $action);
}
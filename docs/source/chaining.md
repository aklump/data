---
title: Conditionals
sort: 50
---
# Using the "onlyIf" method for conditionals

Let's assume this setup:

    <?php
    $from = ['id' => '123'];
    $to = [];
    $obj = new Data;
    
The scenario is this: you want to pass a value from one array to another, only if that value is present in the first array.

    $obj->onlyIf($from, 'id')->set($to);

    // $to['id'] === '123'


    $obj->onlyIf($from, 'title')->set($to);

    // $to === []

    
Same as above only we will use a new path in the final array:

    $obj->onlyIf($from, 'id')->set($to, 'account.id');

    // $to['account']['id'] === '123'

## Using a test callback

By passing a third argument--a callback that takes the value as it's parameter and returns true if the value should be used--you can customize how this method works.

    $from = array('name' => 'bob');
    $value = $this->data->onlyIf($from, 'name', function ($value) {
        return substr($value, 0, 1) === 'a';
    })->value();
    
    // $value === null;

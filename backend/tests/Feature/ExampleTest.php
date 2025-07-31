<?php

test('the application returns a 404 on /', function () {
    $this->get('/')
        ->assertStatus(404);
});

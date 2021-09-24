<?php

Route::get('/', 'ConversationController@index');

Route::post('conversation/message/{message}', 'ConversationController@sendMessage');
Route::get('conversation/history/', 'ConversationController@getHistory');

Route::post('conversation/graphql/{petition}', 'ConversationController@getList');
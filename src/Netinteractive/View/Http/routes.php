<?php
Route::group(array('prefix' => 'view'), function(){
    Route::get('loader/{view}',
        array(
            'as' => 'view.loader',
            'uses' => function ($view){
                $params = \Input::all();
                $params['view'] =  $view;

                return \Response::build($params);
            }
        )
    );
});
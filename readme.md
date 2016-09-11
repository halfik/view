Netinteractive\View
===================

Paczka nadpisujemy domyslny mechanizm widoku. Dodaje mechanizm do obslugi skorek widokow z podzialem na frontend i backend.

Domyslne ladowane sa widoki dla skorki default. Skorke mozna zmienic na poziomie nazwy widoku.

Domyslna skorka default:
        Route::get('/',
            array(
                'as' => 'IndexController@index',
                'uses' => function (){
                    $params = \Input::all();
                    $params['view'] = 'frontend.index';
        
                    return \Utils::runAction('IndexController@index', $params);
                }
            )
        );
        
Ze skorka red:
    
    Route::get('/',
        array(
            'as' => 'IndexController@index',
            'uses' => function (){
                $params = \Input::all();
                $params['view'] = 'red.frontend.index';
    
                return \Utils::runAction('IndexController@index', $params);
            }
        )
    );


Jesli widok nie istnieje dla wskazanej skorki, mechanim przeszuka inne skorki w poszukiwaniu odpowiedniego widoku.
Kolejnosc przeszukiwania skorek zalezy od kolejnosci definicji skorek w pliku konfguracyjnym view.php


## Changelog

* 1.0.8
    * fixed: ViewServiceProvider::boot

* 1.0.6 - 1.0.7
    composer.json fix

##Przykład użycia
**config/view.php**
    
    'skin'=>array(
        'frontend' =>array('default','red'),
        'backend' => 'default'
    ),
    
**resources/views/default/frontend/index.blade.php**
**resources/views/red/frontend/index.blade.php**

    view(frontend.index)
    
Najpierw sprawdzi czy istnieje widok **resources/views/default/frontend/index.blade.php** i jak nie to uzyje **resources/views/red/frontend/index.blade.php**
lub widoku index.blade.php z innej skorki (zaleznie od kolejnosci deklaracji skorek w konfigu).
    



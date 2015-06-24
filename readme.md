Paczka pozwala uzywac skórki dla widokow.

##Prykład użycia
**config/view.php**
    
    'skin'=>array(
        'frontend' =>array('default','red'),
        'backend' => 'default'
    ),
    
**resources/views/frontend/default/index.blade.php**
**resources/views/frontend/red/index.blade.php**

    view(frontend.index)
    
najpierw sprawdzi czy istnieje widok **resources/views/frontend/red/index.blade.php** i jak nie to uzyje **resources/views/frontend/red/index.blade.php**
    



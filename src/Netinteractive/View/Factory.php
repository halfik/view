<?php

namespace Netinteractive\View;

class Factory extends \Illuminate\View\Factory
{
    public function make($view, $data = array(), $mergeData = array()){
        debug($view);
        return parent::make($view, $data, $mergeData);
    }

}

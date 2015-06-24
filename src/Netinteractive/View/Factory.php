<?php

namespace Netinteractive\View;

class Factory extends \Illuminate\View\Factory
{
    /**
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\View\View
     */
    public function make($view, $data = array(), $mergeData = array()){

        //Wyciagamy z widoky pierwsza czesc nazwy i ustawiamy jako mode
        $arrView=explode('.',$view);
        $mode=array_shift($arrView);

        //Jezeli to jest frontendowy lub backendowy skin
        if($mode=='frontend' || $mode=='backend'){

            //Tworzymy nazwe widoku bez mode
            $view=implode($arrView);

            //Pobieramy z konfigu aktualny skin
            $skin=\Config::get('view.skin.'.$mode);
            if(!is_array($skin)){
                $skin=array($skin);
            }
            $skin=array_reverse($skin);

            //Shukamy w ktorym skinie jest widok
            foreach($skin as $s){

                if($this->exists($mode.'.'.$s.'.'.$view)){
                    $view=$mode.'.'.$s.'.'.$view;
                    break;
                }

            }
        }
        return parent::make($view, $data, $mergeData);
    }

}

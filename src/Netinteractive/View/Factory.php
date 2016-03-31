<?php namespace Netinteractive\View;

/**
 * Class Factory
 * @package Netinteractive\View
 */
class Factory extends \Illuminate\View\Factory
{
    public static $DEFAULT_SKIN = 'default';

    protected $mode;
    protected $view;
    protected $skin;
    protected $viewPath;

    /**
     * @var array
     */
    public static $avaibleModes = array(
        'frontend',
        'backend'
    );

    /**
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\View\View
     */
    public function make($view, $data = array(), $mergeData = array())
    {
        #parsujemy string sciezki widoku
        $this->parseViewParts($view);

        #budujemy sciezke do widoku
        $this->buildViewPath();

        #jesli widok nie istnieje dla aktualnego skina, to  przeszukujemy pozostale skiny
        if(!$this->hasViewPath()) {
            #Pobieramy z konfigu liste skinow i przeszukujemy
            $skinList = \Config::get('view.skin.'.$this->getMode());
            if(!is_array($skinList)){
                $skinList = array($skinList);
            }

            foreach($skinList as $s){
                $this->buildViewPath($s);
                if($this->hasViewPath()) {
                    $this->setSkin($s);
                    break;
                }
            }
        }

        if ($this->hasViewPath()){
            $view = $this->getViewPath();
        }

        return parent::make($view, $data, $mergeData);
    }

    /**
     * Metoda sprawdza, czy podany string jest modem
     * @param string $mode
     * @return bool
     */
    public function isMode($mode)
    {
        return in_array($mode, self::$avaibleModes);
    }

    /**
     * Zwraca informacje, czy mamy ustawiony mode dla widoku
     * @return bool
     */
    public function hasMode()
    {
        return !empty($this->mode);
    }

    /**
     * Buduje sciezke do widoku
     * @param string $skin
     * @return void
     */
    protected function buildViewPath($skin=null)
    {
        if (!$skin){
            $skin = $this->getSkin();
        }

        if ($this->hasMode()){
            $viewPath = $skin.'.'.$this->getMode().'.'.$this->getView();
        }else{
            $viewPath = $skin.'.'.$this->getView();
        }

        if($this->exists($viewPath)){
            $this->setViewPath($viewPath);
        }
    }

    /**
     * Sprawdza, czy mam sciezke do widoku
     * @return bool
     */
    public function hasViewPath()
    {
        return !empty($this->viewPath);
    }

    /**
     * Rozbija nazwe widoku na czesci i wyciaga z niej informacje o skinie, mode oraz samym view
     * @param string $view
     */
    protected function parseViewParts($view)
    {
        $viewParts = explode('.',$view);
        $skin = self::$DEFAULT_SKIN;;
        $view = null;
        $mode = null;



        #mamy skina, mode oraz view
        if ( count($viewParts) == 3){
            $skin = array_shift($viewParts);
        }

        #mode oraz widok
        if ( count($viewParts) == 2 ){
            if ($this->isMode($viewParts[0])){
                $mode = array_shift($viewParts);
            }
        }

        $view = implode('.',$viewParts);

        $this->setSkin($skin);
        $this->setMode($mode);
        $this->setView($view);
    }


    /**
     * @return mixed
     */
    public function getSkin()
    {
        return $this->skin;
    }

    /**
     * @param mixed $skin
     */
    public function setSkin($skin)
    {
        $this->skin = $skin;
    }


    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param mixed $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return mixed
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * @param mixed $viewPath
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
    }

}

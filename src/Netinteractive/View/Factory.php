<?php namespace Netinteractive\View;

use \Illuminate\View\Engines\EngineResolver;
use \Illuminate\View\ViewFinderInterface;
use \Illuminate\Contracts\Events\Dispatcher;

/**
 * Class Factory
 * @package Netinteractive\View
 */
class Factory extends \Illuminate\View\Factory
{
    protected $mode;
    protected $view;
    protected $skin;
    protected $viewPath;

    /**
     * @var array
     */
    public static $MODES;
    public static $SKINS;
    public static $DEFAULT_SKIN;
    public static $CURRENT_SKIN;


    /**
     * Create a new view factory instance.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $engines
     * @param  \Illuminate\View\ViewFinderInterface  $finder
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function __construct(EngineResolver $engines, ViewFinderInterface $finder, Dispatcher $events)
    {
        parent::__construct($engines, $finder, $events);

        self::$MODES = \Config::get('packages.netinteractive.view.config.modes');
        self::$SKINS = \Config::get('packages.netinteractive.view.config.skins.list');
        self::$DEFAULT_SKIN = \Config::get('packages.netinteractive.view.config.skins.default');

        if (!self::$CURRENT_SKIN){
            self::$CURRENT_SKIN = \Config::get('packages.netinteractive.view.config.skins.default');
        }
    }

    /**
     * Returns skins list
     * @return mixed
     */
    public static function getSkins()
    {
        return static::$SKINS;
    }

    /**
     * Returns modes list
     * @return array
     */
    public static function getModes()
    {
        return static::$MODES;
    }

    /**
     * Returns default skin
     * @return array
     */
    public static function getDefaultSkin()
    {
        return static::$DEFAULT_SKIN;
    }

    /**
     * Returns current skin
     * @return array
     */
    public static function getCurrentSkin()
    {
        return static::$CURRENT_SKIN;
    }

    /**
     * Sets current skin
     * @throws \Exception
     * @return array
     */
    public static function setCurrentSkin($skin)
    {
        if (!in_array($skin, static::$SKINS)){
            throw new \Exception( sprintf(_("Skin %s not avaible!"), $skin) );
        }
        static::$CURRENT_SKIN = $skin;
    }


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
            foreach(static::getSkins() as $s){
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
        return in_array($mode, static::getModes());
    }

    /**
     * Metoda sprawdza, czy podany string jest skinem
     * @param string $skin
     * @return bool
     */
    public function isSkin($skin)
    {
        return in_array($skin, static::getSkins());
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
        $skin = static::getCurrentSkin();
        $view = null;
        $mode = null;

        #mozliwe, ze w nazwie widoku, mamy mode wioku oraz skina
        if (count($viewParts) >= 2){
            for($i=0; $i<count($viewParts); $i++){
                if ( $this->isSkin($viewParts[$i]) ){
                    $skin = $viewParts[$i];
                    unset($viewParts[$i]);
                }
                else if ( $this->isMode($viewParts[$i]) ){
                    $mode = $viewParts[$i];
                    unset($viewParts[$i]);
                }
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

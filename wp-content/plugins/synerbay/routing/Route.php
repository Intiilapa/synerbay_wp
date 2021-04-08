<?php


namespace SynerBay\Routing;


/**
 * A Route describes a route and its parameters.
 */
class Route
{
    public const TITLE_MAP = [
        'edit_offer'     => 'Edit offer',
        'offer_sub_page' => 'Offer',
        'offer_listing'  => 'Offer Search',
        'store_listing'  => 'Store Search',
    ];

    /**
     * The hook called when this route is matched.
     *
     * @var string
     */
    private $hook;

    /**
     * The URL path that the route needs to match.
     *
     * @var string
     */
    private $path;

    /**
     * The template that the route wants to load.
     *
     * @var string
     */
    private $template;

    /**
     * The reverse string for generate url
     *
     * @var string
     */
    private $reverse;

    /**
     * Load template
     *
     * @var bool|string
     */
    private $loadTemplate;

    /**
     * Constructor.
     *
     * @param string $path
     * @param string $hook
     * @param string $template
     * @param string $reverse
     * @param string $loadTemplate
     */
    public function __construct($path, $hook = '', $template = '', $reverse = '', $loadTemplate = true)
    {
        $this->hook = $hook;
        $this->path = $path;
        $this->template = $template;
        $this->reverse = $reverse;
        $this->loadTemplate = $loadTemplate;
    }

    public static function getTitle($routeName)
    {
        return array_key_exists($routeName, self::TITLE_MAP) ? self::TITLE_MAP[$routeName] : 'SynerBay';
    }

    /**
     * Get the hook called when this route is matched.
     *
     * @return string
     */
    public function get_hook()
    {
        return $this->hook;
    }

    /**
     * Get the URL path that the route needs to match.
     *
     * @return string
     */
    public function get_path()
    {
        return $this->path;
    }

    /**
     * Get the template that the route wants to load.
     *
     * @return string
     */
    public function get_template()
    {
        return $this->template;
    }

    /**
     * Get the reverse
     *
     * @return string
     */
    public function get_reverse()
    {
        return $this->reverse;
    }

    /**
     * Checks if this route want to call a hook when matched.
     *
     * @return bool
     */
    public function has_hook()
    {
        return !empty($this->hook);
    }

    /**
     * Checks if this route want to load a template when matched.
     *
     * @return bool
     */
    public function has_template()
    {
        return !empty($this->template);
    }

    /**
     * Checks if this route has reverse.
     *
     * @return bool
     */
    public function has_reverse()
    {
        return !empty($this->reverse);
    }

    public function load_template()
    {
        return $this->loadTemplate;
    }
}
<?php

namespace libs\view;
use libs\view\Tpl\NotFoundException;

/**
 *  RainTPL
 *  --------
 *  Realized by Federico Ulfo & maintained by the Rain Team
 *  Distributed under GNU/LGPL 3 License
 *
 *  @deprecated
 *  @version 3.0 Alpha milestone: https://github.com/rainphp/raintpl3/issues/milestones?with_issues=no
 */
class Tpl {

    // variables
    public $var = array();

    protected $config = array(),
              $objectConf = array();

    /**
     * Plugin container
     *
     * @var libs\view\Tpl\PluginContainer
     */
    protected static $plugins = null;

    // configuration
    protected static $conf = array(
        'checksum' => array(),
        'charset' => 'UTF-8',
        'debug' => false,
        'include_path' => array(),
        'tpl_dir' => 'templates/',
        'cache_dir' => 'cache/',
        'tpl_ext' => 'html',
        //'ignore_single_quote' => true,
        'predetect' => true,
        'base_url' => '',
        'php_enabled' => false,
        'auto_escape' => true,
        'force_compile' => false,
        'allow_compile' => true,
        'allow_compile_once' => true, // allow compile template only once
        'sandbox' => true,
        'remove_comments' => false,
        'registered_tags' => array(),

        'ignore_unknown_tags' => false,
    );

    // tags registered by the developers
    protected static $registered_tags = array();

    // here should be all blocks defined in code
    protected $definedBlocks = array(

    );


    /**
     * Draw the template
     *
     * @param string $templateFilePath name of the template file
     * @param bool $toString if the method should return a string
     * @param bool $isString if input is a string, not a file path
     * or echo the output
     *
     * @return void, string: depending of the $toString
     */
    public function draw($templateFilePath, $toString = FALSE, $isString = FALSE)
    {
        extract($this->var);
        
        // Merge local and static configurations
        $this->config = array_merge(static::$conf, $this->objectConf);
        
        ob_start();
        
        // parsing a string (moved from drawString method)
        if ($isString)
            require $this->checkString($templateFilePath);
        else // parsing a template file
            require $this->checkTemplate($templateFilePath);
        
        $html = ob_get_clean();

        if (isset($this->config['raintpl3_plugins_compatibility']) && $this->config['raintpl3_plugins_compatibility'])
        {
            // Execute plugins, before_parse
            $context = $this->getPlugins()->createContext(array(
                'code' => $html,
                'conf' => $this->config,
            ));

            $this->getPlugins()->run('afterDraw', $context);
            $html = $context->code;
        }

        if ($toString)
            return $html;
        else
            echo $html;
    }

    /**
     * Draw a string
     *
     * @param string $string string in RainTpl format
     * @param bool $toString if the param
     *
     * @return void, string: depending of the $toString
     */
    public function drawString($string, $toString = false)
    {
        return $this->draw($string, $toString, True);
    }
    
    /**
     * Object specific configuration
     *
     * @param string|array $setting name of the setting to configure
     * or associative array type 'setting' => 'value'
     * @param mixed $value: value of the setting to configure
     * @return libs\view\Tpl $this
     */
    public function objectConfigure($setting, $value = null)
    {
        if (is_array($setting))
        {
            // use this function recursive to set multiple configuration values from array
            foreach ($setting as $key => $value)
            {
                $this->objectConfigure($key, $value);
            }
        } else if (isset(static::$conf[$setting]))
            $this->objectConf[$setting] = $value;
            
        return $this;
    }

    /**
     * Configure the template
     *
     * @param string|array $setting: name of the setting to configure
     * or associative array type 'setting' => 'value'
     * @param mixed $value: value of the setting to configure
     */
    public static function configure($setting, $value = null)
    {
        if (is_array($setting))
        {
            // use this function recursive to set multiple configuration values from array
            foreach ($setting as $key => $value)
            {
                static::configure($key, $value);
            }
        } else if (isset(static::$conf[$setting])) {
            static::$conf[$setting] = $value;
            
            // the checksum must match template with any bool value or it wont work as the template file names will be diffirent
            if ($setting == 'allow_compile' or $setting == 'allow_compile_once')
            {
                $value = True;
            }
            
            static::$conf['checksum'][$setting] = $value; // take trace of all config
        }
    }

    /**
     * Assign variable
     * eg.     $t->assign('name','mickey');
     *
     * @param mixed $variable Name of template variable or associative array name/value
     * @param mixed $value value assigned to this variable. Not set if variable_name is an associative array
     *
     * @return libs\view\Tpl $this
     */
    public function assign($variable, $value = null)
    {
        if (is_array($variable))
            $this->var = $variable + $this->var;
        else
            $this->var[$variable] = $value;

        return $this;
    }

    /**
     * Clean the expired files from cache
     * @param type $expireTime Set the expiration time
     */
    public static function clean($expireTime = 2592000)
    {
        $files = glob(static::$conf['cache_dir'] . "*.rtpl.php");
        $time = time() - $expireTime;
        foreach ($files as $file)
        {
            if ($time > filemtime($file))
                unlink($file);
        }
    }

    /**
     * Allows the developer to register a tag.
     *
     * @param string $tag nombre del tag
     * @param regexp $parse regular expression to parse the tag
     * @param anonymous function $function: action to do when the tag is parsed
     */
    public static function registerTag($tag, $parse, $function) {
        static::$registered_tags[$tag] = array("parse" => $parse, "function" => $function);
    }

    /**
     * Registers a plugin globally.
     *
     * @param libs\view\Tpl\IPlugin $plugin
     * @param string $name name can be used to distinguish plugins of same class.
     */
    public static function registerPlugin(Tpl\IPlugin $plugin, $name = '') {
        $name = (string)$name ?: \get_class($plugin);

        static::getPlugins()->addPlugin($name, $plugin);
    }

    /**
     * Removes registered plugin from stack.
     *
     * @param string $name
     */
    public static function removePlugin($name)
    {
        static::getPlugins()->removePlugin($name);
    }

    /**
     * Returns plugin container.
     *
     * @return libs\view\Tpl\PluginContainer
     */
    protected static function getPlugins()
    {
        return static::$plugins
            ?: static::$plugins = new Tpl\PluginContainer();
    }

    /**
     * Resolve template path
     *
     * @param string $template Template name, path, or absolute path
     * @param array $templateDirectories (Optional) List of included directories
     * @param string $parentTemplateFilePath (Optional) Path to template that included this template
     * @param string $defaultExtension (Optional) Default file extension to append when no extension specified
     *
     * @author Damian Kęska <damian.keska@fingo.pl>
     * @return string
     */
    public static function resolveTemplatePath($template, $templateDirectories = null, $parentTemplateFilePath = null, $defaultExtension = null)
    {
        // add default extension in case there is no any
        if (!pathinfo($template, PATHINFO_EXTENSION) && $defaultExtension)
        {
            $extension = "." . $defaultExtension;
            $template = $template . $extension;
        }

        $path = '';
        $tplDir = array();

        if (is_array($templateDirectories))
            $tplDir = $templateDirectories;

        elseif (!is_array($templateDirectories) || is_string($templateDirectories))
            $tplDir = array($templateDirectories);

        // include current directory
        if ($parentTemplateFilePath) $tplDir[] = dirname($parentTemplateFilePath);
        $tplDir[] = '';

        foreach ($tplDir as $dir)
        {
            if (is_file($dir . '/' . $template))
                $path = $dir . '/' . $template;
            elseif (is_file($dir . '/' . $template . '.tpl'))
                $path = $dir . '/' . $template . '.tpl';

            if ($path) break;
        }

        return $path;
    }

    /**
     * Check if the template exist and compile it if necessary
     *
     * @param string $template Name of the file of the template
     * @param string|null $parentTemplateFilePath (Optional) Parent template file path (that template which is including this one)
     * @param int|null|numeric $parentTemplateLine (Optional) Line from parent template that called this method
     * @param int|null|numeric $parentTemplateOffset (Optional) Offset of parent template where is this function called
     *
     * @throws Tpl\Exception
     * @throws libs\view\Tpl\NotFoundException
     *
     * @return string Compiled template absolute path
     */
    protected function checkTemplate($template, $parentTemplateFilePath = null, $parentTemplateLine = null, $parentTemplateOffset = null)
    {
        $originalTemplate = $template;
        $extension = '';

        $path = self::resolveTemplatePath($template, $this->config['tpl_dir'], $parentTemplateFilePath, $this->config['tpl_ext']);

        // normalize path
        $path = str_replace(array('//', '//'), '/', $path);

        $parsedTemplateFilepath = $this->config['cache_dir'] . basename($originalTemplate) . "." . md5(dirname($path) . serialize($this->config['checksum']) . $originalTemplate) . '.rtpl.php';

        // if the template doesn't exsist throw an error
        if (!$path)
        {
            $traceString = '';

            if ($parentTemplateFilePath && $parentTemplateLine && $parentTemplateOffset)
                $traceString = ', included from "' .$parentTemplateFilePath. '" on line ' .$parentTemplateLine. ', offset ' .$parentTemplateOffset;

            $e = new Tpl\NotFoundException('Template ' . $originalTemplate . ' not found' .$traceString);
            throw $e->templateFile($originalTemplate);
        }

        /**
         * Check if there is an already compiled version
         *
         * @config bool allow_compile
         */
        if (!$this->config['allow_compile'])
        {
            // check if there is a compiled version
            if (!is_file($parsedTemplateFilepath))
            {
                // allow first compilation of file
                if (!$this->config['allow_compile_once'])
                    throw new NotFoundException('Template cache file "' .$parsedTemplateFilepath. '" is missing and "allow_compile", "allow_compile_once" are disabled in configuration');
                    
            } else
                return $parsedTemplateFilepath;
        }

        /**
         * Run the parser if file was not updated since last compilation time
         */
        if ($this->config['debug'] or !file_exists($parsedTemplateFilepath) or ( filemtime($parsedTemplateFilepath) < filemtime($path)))
        {
            $parser = new Tpl\Parser($this);
            $parser->compileFile($path, $parsedTemplateFilepath);
        }

        return $parsedTemplateFilepath;
    }

    /**
     * Compile a string if necessary
     *
     * @param string $string RainTpl template string to compile
     * @return string full filepath that php must use to include
     */
    protected function checkString($string)
    {
        // set filename
        $templateName = md5($string . implode($this->config['checksum']));
        $parsedTemplateFilepath = $this->config['cache_dir'] . $templateName . '.s.rtpl.php';

        // Compile the template if the original has been updated
        if ($this->config['debug'] || !file_exists($parsedTemplateFilepath))
        {
            $parser = new Tpl\Parser($this);
            $parser->compileString($templateName, $parsedTemplateFilepath, $string);
        }

        return $parsedTemplateFilepath;
    }

    private static function addTrailingSlash($folder) {

        if (is_array($folder)) {
            foreach($folder as &$f) {
                $f = self::addTrailingSlash($f);
            }
        } elseif ( strlen($folder) > 0 && $folder[0] != '/' ) {
            $folder = $folder . "/";
        }
        return $folder;

    }

}

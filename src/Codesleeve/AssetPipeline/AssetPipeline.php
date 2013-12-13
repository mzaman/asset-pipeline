<?php namespace Codesleeve\AssetPipeline;

class AssetPipeline
{
    /**
     * Create the asset repository based on this setup
     * 
     * @param unsure atm...
     */
    public function __construct($parser, $generator)
    {
        $this->parser = $parser;
        $this->generator = $generator;
    }

    /**
     * Create javascript include tag(s)
     * 
     * @param  string $filename
     * @param  array $attributes
     * @return string
     */
    public function javascriptIncludeTag($filename, $attributes)
    {
        $webPaths = array();

        $attributesText = $this->attributesArrayToText($attributes);
        $absolutePaths = $this->parser->javascriptFiles($filename);

        foreach ($absolutePaths as $absolutePath)
        {
            $webPaths[] = $this->parser->absolutePathToWebPath($absolutePath);
        }

        $closure = $this->getConfig()['javascript_include_tag'];

        return $closure($webPaths, $attributesText, $absolutePaths, $attributes);
    }

    /**
     * Create stylesheet link tag(s)
     * 
     * @param  string $filename
     * @param  array $attributes
     * @return string
     */
    public function stylesheetLinkTag($filename, $attributes)
    {
        $webPaths = array();

        $attributesText = $this->attributesArrayToText($attributes);
        $absolutePaths = $this->parser->stylesheetFiles($filename);

        foreach ($absolutePaths as $absolutePath)
        {
            $webPaths[] = $this->parser->absolutePathToWebPath($absolutePath);
        }

        $closure = $this->getConfig()['stylesheet_link_tag'];

        return $closure($webPaths, $attributesText, $absolutePaths, $attributes);
    }

    /**
     * Is this asset a javascript type?
     * 
     * @param  string $filename
     * @return boolean
     */
    public function isJavascript($filename)
    {
        return $this->parser->absoluteJavascriptPath($filename);
    }

    /**
     * Is this filename a stylesheet type?
     *  
     * @param  string $filename
     * @return boolean
     */
    public function isStylesheet($filename)
    {
        return $this->parser->absoluteStylesheetPath($filename);
    }

    /**
     * Is this filename any type of file?
     * 
     * @param  string  $filename
     * @return boolean          
     */
    public function isFile($filename)
    {
        $absolutePath = $this->parser->absoluteFilePath($filename);

        return file_exists($absolutePath) && is_file($absolutePath) ? $absolutePath : null;
    }

    /**
     * Return the javascript associated with this path
     * 
     * @param  string $path
     * @return string
     */
    public function javascript($absolutePath)
    {
        return $this->generator->javascript($absolutePath);
    }

    /**
     * Return the stylesheet associated with this path
     * 
     * @param  string $absolutePath
     * @return string
     */
    public function stylesheet($absolutePath)
    {
        return $this->generator->stylesheet($absolutePath);
    }

    /**
     * Return the file download associated with this path
     * 
     * @param  string $path
     * @return string | null
     */
    public function file($path)
    {
        return $this->isFile($path);
    }

    /**
     * Get the config array
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->parser->config;
    }

    /**
     * Set the config array
     * 
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->parser->config = $config;
        $this->generator->config = $config;
    }

    /**
     * Helper function to create a string of text for the array
     * of attributes that are passed in
     * 
     * @param  array $attributes
     * @return string
     */
    protected function attributesArrayToText($attributes)
    {
        $text = "";

        foreach ($attributes as $name => $value)
        {
            $text .= "{$name} = \"{$value}\" ";
        }

        return $text;
    }
}
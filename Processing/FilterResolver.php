<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing;

/**
 * Description of FilterResolver
 *
 * @author jerome
 */
class FilterResolver
{
    protected $paths = array();

    public function __construct($paths = array())
    {
        if (empty($paths)) {
            $paths = array(__DIR__ . '/Filter' => __NAMESPACE__ . '\\Filter');
        }

        $this->setPaths($paths);
    }

    public function setPaths($paths)
    {
        foreach($paths as $path => $namespace) {
            $this->addPath($path, $namespace);
        }

        return $this;
    }

    public function addPath($path, $namespace)
    {
        $this->paths[$path] = $namespace;
        return $this;
    }

    public function resolve($shortname)
    {
        foreach($this->paths as $path => $namespace) {
            foreach(new \FilesystemIterator($path) as $file) {
                $basename = $file->getBasename('.php');

                if (strtolower($basename) == strtolower($shortname) . 'filter') {
                    return $namespace . '\\' . $basename;
                }
            }
        }
die;
        throw new \RuntimeException(sprintf('Could not find filter %s', $shortname));
    }
}

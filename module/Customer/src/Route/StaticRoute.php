<?php
namespace Customer\Route;
use Zend\Router\Http\RouteMatch;
use Zend\Router\Http\RouteInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;
use \Traversable;
use Zend\Router\Exception;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 4/27/2018
 * Time: 3:36 PM
 */

class StaticRoute implements RouteInterface {

    // base view directory.
    protected $dirName;

    // path prefix for view template.
    protected $templatePrefix;

    // file name pattern.
    protected $fileNamePattern = '/[a-zA-Z0-9_\-]+/';

    // defaults.
    protected $defaults;

    // list of assembled parameters.
    protected $assembledParams;

    public function __construct($dirName, $templatePrefix, $fileNamePattern, array $defaults) {
        $this->dirName = $dirName;
        $this->templatePrefix = $templatePrefix;
        $this->fileNamePattern = $fileNamePattern;
        $this->defaults = $defaults;
    }

    // Create a new route with given options.
    public static function factory($options = [])
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(__METHOD__ . 'expects an array or Traversable set of options.');
        }

        if (!isset($options['dir_name'])) {
            throw new Exception\InvalidArgumentException('Missing "dir_name" in options array.');
        }

        if (!isset($options['template_prefix'])) {
            throw new Exception\InvalidArgumentException('Missing "template_prefix" in options array.');
        }

        if (!isset($options['filename_pattern'])) {
            throw new Exception\InvalidArgumentException('Missing "filename_pattern" in options array.');
        }

        if (!isset($options)) {
            $options['defaults'] = [];
        }

        return new static(
            $options['dir_name'],
            $options['template_prefix'],
            $options['filename_pattern'],
            $options['defaults']
        );
    }
    // match a given request.
    public function match(Request $request, $pathOffset = null)
    {
        // ensure this route type is used in an HTTP request.
        if (!method_exists($request, 'getUri'))
            return null;

        // get the URL and its path part.
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($pathOffset != null)
            $path = substr($path, $pathOffset);

        // get the array of path segments.
        $segments = explode('/', $path);

        // check each segment against allowed file name template.
        foreach ($segments as $segment) {
            if (strlen($segment) == 0)
                continue;
            if (!preg_match($this->fileNamePattern, $segment))
                return null;
        }

        // check if such a .phtml file exists on disk
        $fileName = $this->dirName . '/' . $this->templatePrefix . $path . '.phtml';
        if (!is_file($fileName) || !is_readable($fileName))
            return null;

        $matchedLength = strlen($path);

        // Prepare the RouteMatch object.
        return new RouteMatch(array_merge(
            $this->defaults,
            ['page' => $this->templatePrefix . $path]
            ), $matchedLength);
    }

    // Assemble URL by route params.
    public function assemble(array $params = [], array $options = [])
    {
        $mergedParams = array_merge($this->defaults, $params);

        $this->assembledParams = [];

        if (!isset($params['page'])) {
            throw new Exception\InvalidArgumentException(__METHOD__ . 'expects the page parameter.');
        }

        $segments = explode('/', $params['page']);
        $url = '';
        foreach ($segments as $segment) {
            if (strlen($segment) == 0)
                continue;
            $url .= '/' . rawurlencode($segment);
        }

        $this->assembledParams[] = 'page';

        return $url;
    }

    // get a list of parameters while assembling.
    public function getAssembledParams() {
        return $this->assembledParams;
    }
}
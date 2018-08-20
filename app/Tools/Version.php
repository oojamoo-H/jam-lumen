<?php
namespace App\Tools;

class Version
{
    CONST VERSION_SEPARATOR = '.';
    CONST REGEX_VALID_VERSION = '/^[><]?[=]?[0-9.]+$/';

    CONST VERSION_SMALL = '<';
    CONST VERSION_BIG = '>';
    CONST VERSION_SMALL_OR_EQUAL = '<=';
    CONST VERSION_BIG_OR_EQUAL = '>=';
    CONST VERSION_EQUAL = '=';

    var $mainVersion;
    var $subVersion;
    var $minorVersion;

    public function __construct($version)
    {
        if (!is_array($version)) {
            $version = explode(self::VERSION_SEPARATOR, $version);
        }

        $this->mainVersion = isset($version[0]) ? (int)$version[0] : 1;
        $this->subVersion = isset($version[1]) ? (int)$version[1] : 0;
        $this->minorVersion = isset($version[2]) ? (int)$version[2] : 0;
    }

    public function test($versionRule)
    {
        if (preg_match(self::REGEX_VALID_VERSION, $versionRule)) {
            $relation = substr($versionRule, 0, 2);
            if ($relation == self::VERSION_BIG_OR_EQUAL) {
                $fun = 'bigOrEqual';
                $offset = 2;
            } else if ($relation == self::VERSION_SMALL_OR_EQUAL) {
                $fun = 'smallOrEqual';
                $offset = 2;
            } else {
                $relation = $relation[0];
                $offset = 1;
                if ($relation == self::VERSION_BIG) {
                    $fun = 'big';
                } else if ($relation == self::VERSION_SMALL) {
                    $fun = 'small';
                } else if ($relation == self::VERSION_EQUAL) {
                    $fun = 'equal';
                } else {
                    $fun = 'equal';
                    $offset = 0;
                }
            }
            $version = substr($versionRule, $offset);
            return call_user_func_array(array($this, $fun), array(new Version($version)));
        } else {
            return FALSE;
        }
    }

    protected function big(Version $version)
    {
        return $this->mainVersion > $version->mainVersion
        || (($this->mainVersion == $version->mainVersion) && ($this->subVersion > $version->subVersion))
        || (($this->mainVersion == $version->mainVersion) && ($this->subVersion == $version->subVersion) && ($this->minorVersion > $version->minorVersion));
    }

    protected function small(Version $version)
    {
        return $this->mainVersion < $version->mainVersion
        || (($this->mainVersion == $version->mainVersion && $this->subVersion < $version->subVersion))
        || (($this->mainVersion == $version->mainVersion) && ($this->subVersion == $version->subVersion) && ($this->minorVersion < $version->minorVersion));
    }

    protected function equal(Version $version)
    {
        return $this->mainVersion == $version->mainVersion
        && $this->subVersion == $version->subVersion
        && $this->minorVersion == $version->minorVersion;
    }

    protected function bigOrEqual(Version $version)
    {
        return $this->big($version) || $this->equal($version);
    }

    protected function smallOrEqual(Version $version)
    {
        return $this->small($version) || $this->equal($version);
    }
}

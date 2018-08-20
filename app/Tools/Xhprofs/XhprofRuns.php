<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/8/7
 * Time: 14:04
 */

namespace App\Tools\Xhprofs;

class XhprofRuns
{

    protected $env;

    protected $autoEnd;

    protected $xhprofData;

    protected $runId;

    protected $runs;

    protected $enable;

    public function __construct()
    {
        $this->enable = env('XHPROF_ENABLE', FALSE);
        $this->autoEnd = env('XHPROF_AUTO_END', FALSE);
    }


    public function start()
    {
        if (!$this->enable){
            return FALSE;
        }

        xhprof_enable(XHPROF_FLAGS_NO_BUILTINS|XHPROF_FLAGS_CPU|XHPROF_FLAGS_MEMORY);
        if ($this->autoEnd){
            register_shutdown_function(function(){
                $this->end();
            });
        }
    }

    public function end()
    {
        if (!$this->enable){
            return FALSE;
        }

        $this->xhprofData = xhprof_disable();
        $this->runs = new XhprofRunDefault;
        $this->runId = $this->runs->save_run($this->xhprofData, 'xhprof_foo');
    }

}
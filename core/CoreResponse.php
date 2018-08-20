<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/6/14
 * Time: 18:44
 */

namespace Core;


use App\Tools\ErrorHandler;
use Illuminate\Http\Response;

class CoreResponse extends Response
{
    protected static $_content = [
        'code' => 0,
        'data' => [],
        'message' => '',
    ];


    /**
     * Set the content on the response.
     *
     * @param  mixed  $content
     * @return $this
     */
    public function setContent($content)
    {
        //$content = $content === FALSE ? static::$_content : $content;
        if($content === false){
            self::$_content['code'] = ErrorHandler::$code;
            self::$_content['message'] = ErrorHandler::$message;
        }else{
            self::$_content['code'] = 200;
            self::$_content['message'] = 'success';
            self::$_content['data'] = $content;
        }
        $content = static::$_content;
        $this->original = $content;

        // If the content is "JSONable" we will set the appropriate header and convert
        // the content to JSON. This is useful when returning something like models
        // from routes that will be automatically transformed to their JSON form.
        if ($this->shouldBeJson($content)) {
            $this->header('Content-Type', 'application/json');

            $content = $this->morphToJson($content);
        }

        // If this content implements the "Renderable" interface then we will call the
        // render method on the object so we will avoid any "__toString" exceptions
        // that might be thrown and have their errors obscured by PHP's handling.
        elseif ($content instanceof Renderable) {
            $content = $content->render();
        }

        parent::setContent($content);

        return $this;
    }

}
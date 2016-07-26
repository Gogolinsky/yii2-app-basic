<?php

namespace app\modules\settings\components;

use app\modules\settings\models\Settings;
use yii\base\Component;

/**
 * Class SeoComponent
 */
class SeoComponent extends Component
{
    /** @var Settings $instance */
    public $instance;

    public function init()
    {
        $this->instance = Settings::findOne(1);
    }

    public function metrika()
    {
        if ($this->instance->metrika_code) {
            echo $this->instance->metrika_code;
        }
    }

    public function analitics()
    {
        if ($this->instance->analitics_code) {
            echo $this->instance->analitics_code;
        }
    }

    public function ywebmaster()
    {
        if ($this->instance->yandex_webmaster) {
            echo $this->instance->yandex_webmaster;
        }
    }

    public function gwebmaster()
    {
        if ($this->instance->google_webmaster) {
            echo $this->instance->google_webmaster;
        }
    }
}
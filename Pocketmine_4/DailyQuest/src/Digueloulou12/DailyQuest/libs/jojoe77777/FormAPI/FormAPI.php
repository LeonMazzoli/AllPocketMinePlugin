<?php

declare(strict_types = 1);

namespace Digueloulou12\DailyQuest\libs\jojoe77777\FormAPI;

use pocketmine\plugin\PluginBase;

class FormAPI extends PluginBase
{
    public function createCustomForm(?callable $function = null): CustomForm
    {
        return new CustomForm($function);
    }

    public function createSimpleForm(?callable $function = null): SimpleForm
    {
        return new SimpleForm($function);
    }
}
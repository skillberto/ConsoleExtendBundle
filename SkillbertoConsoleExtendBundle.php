<?php

namespace Skillberto\ConsoleExtendBundle;

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SkillbertoConsoleExtendBundle extends Bundle
{
    public function registerCommands(Application $application)
    {
        parent::registerCommands($application);

        //include Doctrine ORM commands if exist
        if (class_exists('Doctrine\\ORM\\Version')) {
            ConsoleRunner::addCommands($application);
        }
    }
}

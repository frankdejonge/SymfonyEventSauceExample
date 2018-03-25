<?php

namespace App\Command;

use EventSauce\EventSourcing\CodeGeneration\CodeDumper;
use EventSauce\EventSourcing\CodeGeneration\YamlDefinitionLoader;
use function file_put_contents;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCodeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('eventsauce:generate-code')
            ->setDescription('generate commands and events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dumper = new CodeDumper();
        $loader = new YamlDefinitionLoader();
        $defintionGroup = $loader->load(__DIR__ . '/../../ApplicationProcess/commands-and-events.yml');
        $code = $dumper->dump($defintionGroup);
        file_put_contents(__DIR__ . '/../../ApplicationProcess/commands-and-events.php', $code);
    }

}
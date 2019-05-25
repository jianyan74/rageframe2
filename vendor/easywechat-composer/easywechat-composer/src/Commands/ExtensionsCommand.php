<?php

declare(strict_types=1);

/*
 * This file is part of the EasyWeChatComposer.
 *
 * (c) 张铭阳 <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChatComposer\Commands;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExtensionsCommand extends BaseCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('easywechat:extensions')
            ->setDescription('Lists all installed extensions.');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $extensions = require __DIR__.'/../../extensions.php';

        if (empty($extensions) || !is_array($extensions)) {
            return $output->writeln('<info>No extension installed.</info>');
        }

        $table = new Table($output);
        $table->setHeaders(['Name', 'Observers'])
            ->setRows(
                array_map([$this, 'getRows'], array_keys($extensions), $extensions)
            )->render();
    }

    /**
     * @param string $name
     * @param array  $extension
     *
     * @return array
     */
    protected function getRows($name, $extension)
    {
        return [$name, implode("\n", $extension['observers'] ?? [])];
    }
}

<?php

namespace SimpleBus\Command\Bus\Middleware;

use SimpleBus\Command\Command;

class FinishesCommandBeforeHandlingNext implements CommandBusMiddleware
{
    /**
     * @var array
     */
    private $queue = [];

    /**
     * @var bool
     */
    private $isHandling = false;

    /**
     * Completely finishes handling the current command, before allowing other middlewares to start handling new
     * commands.
     *
     * {@inheritdoc}
     */
    public function handle(Command $command, callable $next)
    {
        $this->queue[] = $command;

        if (!$this->isHandling) {
            $this->isHandling = true;

            while ($command = array_shift($this->queue)) {
                $next($command);
            }

            $this->isHandling = false;
        }
    }
}

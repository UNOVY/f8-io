<?php

/**
 * @license MIT
 *
 * Based on Slim Framework v4 Slim/tests/MiddlewareDispatcherTest.php
 */

declare(strict_types=1);

namespace Tests\PHPUnit;

use F8\IO\Interfaces\ContextInterface;
use F8\IO\Interfaces\GuardInterface;
use F8\IO\Interfaces\InputInterface;
use F8\IO\Interfaces\OutputInterface;
use F8\IO\Interfaces\TaskHandlerInterface;
use Prophecy\Argument;

/**
 * @covers \F8\IO\GuardDispatcher
 *
 * @internal
 */
final class GuardDispatcherTest extends TestCase
{
    public function testAddGuard()
    {
        $guardDispatcher = $this->createGuardDispatcher();
        $guardDispatcher->addGuard($this->createGuard());

        $output = $guardDispatcher->handle(
            $this->prophesize(ContextInterface::class)->reveal(),
            $this->prophesize(InputInterface::class)->reveal(),
        );

        static::assertInstanceOf(OutputInterface::class, $output);
    }

    public function testExecutesKernelWithEmptyMiddlewareStack()
    {
        $outputProphecy = $this->prophesize(OutputInterface::class);

        $kernelProphecy = $this->prophesize(TaskHandlerInterface::class);
        $kernelProphecy->handle(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
        )->willReturn($outputProphecy->reveal());

        $guardDispatcher = $this->createGuardDispatcher($kernelProphecy->reveal());

        $output = $guardDispatcher->handle(
            $this->prophesize(ContextInterface::class)->reveal(),
            $this->prophesize(InputInterface::class)->reveal(),
        );

        $kernelProphecy->handle(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
        )->shouldHaveBeenCalled();

        static::assertSame($outputProphecy->reveal(), $output);
    }

    public function testExecutesGuardsLastInFirstOut()
    {
        $contextProphecy = $this->prophesize(ContextInterface::class);

        $inputProphecy = $this->prophesize(InputInterface::class);

        $outputProphecy = $this->prophesize(OutputInterface::class);

        $kernelProphecy = $this->prophesize(TaskHandlerInterface::class);
        $kernelProphecy->handle(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
        )->will(function ($args) use ($outputProphecy): OutputInterface {
            $output = $outputProphecy->reveal();

            $output->input_sequence = $args[1]->sequence;
            $output->output_sequence = ['kernel'];

            return $output;
        });

        $guard0Prophecy = $this->prophesize(GuardInterface::class);
        $guard0Prophecy->process(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
            Argument::type(TaskHandlerInterface::class),
        )->will(function ($args): OutputInterface {
            array_push($args[1]->sequence, 'guard0');

            $out = $args[2]->handle($args[0], $args[1]);

            array_push($out->output_sequence, 'guard0');

            return $out;
        });

        $guard1Prophecy = $this->prophesize(GuardInterface::class);
        $guard1Prophecy->process(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
            Argument::type(TaskHandlerInterface::class),
        )->will(function ($args): OutputInterface {
            array_push($args[1]->sequence, 'guard1');

            $out = $args[2]->handle($args[0], $args[1]);

            array_push($out->output_sequence, 'guard1');

            return $out;
        });

        $guard2Prophecy = $this->prophesize(GuardInterface::class);
        $guard2Prophecy->process(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
            Argument::type(TaskHandlerInterface::class),
        )->will(function ($args): OutputInterface {
            array_push($args[1]->sequence, 'guard2');

            $out = $args[2]->handle($args[0], $args[1]);

            array_push($out->output_sequence, 'guard2');

            return $out;
        });

        $kernel = $kernelProphecy->reveal();
        $dispatcher = $this->createGuardDispatcher($kernel);
        $dispatcher->addGuard($guard0Prophecy->reveal());
        $dispatcher->addGuard($guard1Prophecy->reveal());
        $dispatcher->addGuard($guard2Prophecy->reveal());

        $input = $inputProphecy->reveal();
        $input->sequence = ['start'];

        $output = $dispatcher->handle($contextProphecy->reveal(), $input);

        static::assertSame(['start', 'guard2', 'guard1', 'guard0'], $output->input_sequence);
        static::assertSame(['kernel', 'guard0', 'guard1', 'guard2'], $output->output_sequence);
    }

    public function testCanBeExecutedMultipleTimes()
    {
        $outputProphecy = $this->prophesize(OutputInterface::class);

        $kernelProphecy = $this->prophesize(TaskHandlerInterface::class);
        $kernelProphecy->handle(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
        )->willReturn($outputProphecy->reveal());

        $guardProphecy = $this->prophesize(GuardInterface::class);
        $guardProphecy->process(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
            Argument::type(TaskHandlerInterface::class),
        )->willReturn($outputProphecy->reveal());

        $guardDispatcher = $this->createGuardDispatcher($kernelProphecy->reveal());
        $guardDispatcher->addGuard($guardProphecy->reveal());

        $output0 = $guardDispatcher->handle(
            $this->prophesize(ContextInterface::class)->reveal(),
            $this->prophesize(InputInterface::class)->reveal(),
        );
        $output1 = $guardDispatcher->handle(
            $this->prophesize(ContextInterface::class)->reveal(),
            $this->prophesize(InputInterface::class)->reveal(),
        );

        static::assertSame($outputProphecy->reveal(), $output0);
        static::assertSame($outputProphecy->reveal(), $output1);
        $kernelProphecy->handle(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
        )->shouldNotHaveBeenCalled();
    }

    public function testCanBeExecutedRecursivelyDuringDispatch()
    {
        $contextProphecy = $this->prophesize(ContextInterface::class);

        $inputProphecy = $this->prophesize(InputInterface::class);

        $outputProphecy = $this->prophesize(OutputInterface::class);

        $kernelProphecy = $this->prophesize(TaskHandlerInterface::class);

        $kernel = $kernelProphecy->reveal();
        $dispatcher = $this->createGuardDispatcher($kernel);

        $guardProphecy = $this->prophesize(GuardInterface::class);
        $guardProphecy->process(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
            Argument::type(TaskHandlerInterface::class),
        )->will(function ($args) use ($dispatcher, $outputProphecy): OutputInterface {
            if ($args[1]->nested) {
                $out = $outputProphecy->reveal();
                $out->trace = [];
                array_push($out->trace, 'nested');

                return $out;
            }

            array_push($args[1]->nested, '1');

            $out = $dispatcher->handle($args[0], $args[1]);

            array_push($out->trace, 'outer');

            return $out;
        });
        $dispatcher->addGuard($guardProphecy->reveal());

        $input = $inputProphecy->reveal();
        $input->trace = [];
        $input->nested = [];

        $output = $dispatcher->handle($contextProphecy->reveal(), $input);

        static::assertSame(['nested', 'outer'], $output->trace);
    }
}

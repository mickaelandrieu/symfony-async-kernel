<?php

/*
 * This file is part of the Symfony Async Kernel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Symfony\Component\HttpKernel;

use React\Promise\PromiseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AsyncHttpKernelNeededException;

/**
 * Class AsyncKernel.
 */
abstract class AsyncKernel extends Kernel
{
    /**
     * Handles a Request to convert it to a Response.
     *
     * When $catch is true, the implementation must catch all exceptions
     * and do its best to convert them to a Response instance.
     *
     * @param Request $request A Request instance
     * @param int     $type    The type of the request
     *                         (one of HttpKernelInterface::MASTER_REQUEST or HttpKernelInterface::SUB_REQUEST)
     * @param bool    $catch   Whether to catch exceptions or not
     *
     * @return PromiseInterface
     *
     * @throws \Exception When an Exception occurs during processing
     */
    public function handleAsync(
        Request $request,
        $type = self::MASTER_REQUEST,
        $catch = true
    ): PromiseInterface {
        $this->boot();

        $httpKernel = $this->getHttpKernel();
        if (!$httpKernel instanceof AsyncHttpKernel) {
            throw new AsyncHttpKernelNeededException();
        }

        return $httpKernel->handleAsync(
            $request,
            $type,
            $catch
        );
    }
}

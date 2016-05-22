<?php
/*
 * This file is part of the proto package.
 *
 * Copyright (c) Jason Coward <jason@opengeek.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenGeek\Proto;

/**
 * Invokable class for applying Middleware to your application.
 *
 * @package OpenGeek\Proto
 */
class Middleware
{
    public function __invoke(App $app)
    {
        /* call $app->add() to apply middleware to your application */
    }
}

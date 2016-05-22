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
 * Invokable class for building Routes to your application.
 *
 * @package OpenGeek\Proto
 */
class Routes
{
    public function __invoke(App $app)
    {
        $app->get('/', [\OpenGeek\Proto\Controller\View::class, 'handle']);
    }
}

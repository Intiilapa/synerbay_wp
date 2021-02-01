<?php

namespace Automattic\WooCommerce\Vendor\League\Container\Exception;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}

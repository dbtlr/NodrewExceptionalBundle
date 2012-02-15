<?php

namespace Nodrew\Bundle\ExceptionalBundle\Handler;

/**
 * @package		ExceptionalBundle
 * @author		Drew Butler <hi@nodrew.com>
 * @copyright	(c) 2012 Drew Butler
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
interface ContextHandlerInterface
{
    /**
     * Get the array of context objects.
     *
     * @return array
     */
    public function getContext();
}
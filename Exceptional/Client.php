<?php
namespace Nodrew\Bundle\ExceptionalBundle\Exceptional;

use Nodrew\Bundle\ExceptionalBundle\Model\ServiceParameters;

/**
 * The ExceptionalBundle Client Loader.
 *
 * This class assists in the loading of the exceptional-php library.
 *
 * @package		ExceptionalBundle
 * @author		Drew Butler <drew@abstracting.me>
 * @copyright	(c) 2011 Drew Butler
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
class Client
{
    protected $model;

    /**
     * @param Nodrew\Bundle\ExceptionalBundle\Model\ServiceParameters $model
     */
    public function __construct(ServiceParameters $model)
    {
        $this->model = $model;
    }
    
    /**
     * @param Exception $exception
     */
    public function notifyOnException(\Exception $exception)
    {
        
    }
}

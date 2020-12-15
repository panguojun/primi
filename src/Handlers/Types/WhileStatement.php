<?php

namespace Smuuf\Primi\Handlers\Types;

use \Smuuf\Primi\Context;
use \Smuuf\Primi\Handlers\HandlerFactory;
use \Smuuf\Primi\Ex\BreakException;
use \Smuuf\Primi\Ex\ContinueException;
use \Smuuf\Primi\Handlers\SimpleHandler;

class WhileStatement extends SimpleHandler {

	protected static function handle(
		array $node,
		Context $context
	) {

		// Execute the left-hand node and get its return value.
		$condHandler = HandlerFactory::getFor($node['left']['name']);
		$blockHandler = HandlerFactory::getFor($node['right']['name']);

		// 1-bit value for ticking task queue once per two iterations.
		$tickBit = 1;

		while (
			$condHandler::run($node['left'], $context)->isTruthy()
		) {

			// Switch the bit from 1/0 or vice versa.
			if ($tickBit ^= 1) {
				$context->getTaskQueue()->tick();
			}

			try {
				$blockHandler::run($node['right'], $context);
			} catch (ContinueException $e) {
				continue;
			} catch (BreakException $e) {
				break;
			}

		}

	}

}

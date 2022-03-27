<?php

namespace Smuuf\Primi\Handlers;

use \Smuuf\Primi\Context;
use \Smuuf\Primi\Helpers\SimpleHandler;
use \Smuuf\Primi\Structures\FuncValue;
use \Smuuf\Primi\Structures\FnContainer;

use function \Smuuf\Primi\Helpers\ensure_indexed as primifn_ensure_indexed;

class AnonymousFunction extends SimpleHandler {

	public static function handle(array $node, Context $context) {

		$fn = FnContainer::build($node['body'], $node['params'], $context);
		return new FuncValue($fn);

	}

	public static function reduce(array &$node): void {

		// Prepare list of parameters.
		$params = [];
		if (isset($node['params'])) {
			// Make sure this is always list, even with one item.
			$node['params'] = primifn_ensure_indexed($node['params']);
			foreach ($node['params'] as $p) {
				$params[] = $p['text'];
			}
		}

		$node['params'] = $params;

	}

}

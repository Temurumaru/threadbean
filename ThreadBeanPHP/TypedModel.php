<?php

namespace ThreadBeanPHP;

use ThreadBeanPHP\SimpleModel as SimpleModel;

/**
 * TypedModel
 * Just like the standard SimpleModel but allows for
 * better type hinting in PHP 8 and higher.
 *
 * Usage:
 *
 * define( 'REDBEAN_MODEL_PREFIX', '\\' );
 * R::setup();
 * class Book extends \ThreadBeanPHP\TypedModel { }
 * $book = R::dispense('book');
 * $book = Book::cast($book);
 * var_dump( $book ); -- and you'll see Book...
 *
 * @file       ThreadBeanPHP/TypeModel.php
 * @author     Gabor de Mooij and the ThreadBeanPHP Team
 * @license    BSD/GPLv2
 *
 * @copyright
 * copyright (c) G.J.G.T. (Gabor) de Mooij and the ThreadBeanPHP Community
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class TypedModel extends SimpleModel
{
	public static function cast($instance): static
	{
		return $instance->box();
	}
}
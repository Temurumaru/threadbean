<?php

namespace ThreadBeanPHP\BeanHelper;

use ThreadBeanPHP\BeanHelper as BeanHelper;
use ThreadBeanPHP\Facade as Facade;
use ThreadBeanPHP\OODBBean as OODBBean;
use ThreadBeanPHP\SimpleModelHelper as SimpleModelHelper;

/**
 * Bean Helper.
 *
 * The Bean helper helps beans to access access the toolbox and
 * FUSE models. This Bean Helper makes use of the facade to obtain a
 * reference to the toolbox.
 *
 * @file    ThreadBeanPHP/BeanHelperFacade.php
 * @author  Gabor de Mooij and the ThreadBeanPHP Community
 * @license BSD/GPLv2
 *
 * @copyright
 * (c) copyright G.J.G.T. (Gabor) de Mooij and the ThreadBeanPHP Community
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class SimpleFacadeBeanHelper implements BeanHelper
{
	/**
	 * Factory function to create instance of Simple Model, if any.
	 *
	 * @var callable|NULL
	 */
	private static $factory = null;

	/**
	 * Factory method using a customizable factory function to create
	 * the instance of the Simple Model.
	 *
	 * @param string $modelClassName name of the class
	 *
	 * @return SimpleModel
	 */
	public static function factory( $modelClassName )
	{
		$factory = self::$factory;
		return ( $factory ) ? $factory( $modelClassName ) : new $modelClassName();
	}

	/**
	 * Sets the factory function to create the model when using FUSE
	 * to connect a bean to a model.
	 *
	 * @param callable|NULL $factory factory function
	 *
	 * @return void
	 */
	public static function setFactoryFunction( $factory )
	{
		self::$factory = $factory;
	}

	/**
	 * @see BeanHelper::getToolbox
	 */
	public function getToolbox()
	{
		return Facade::getToolBox();
	}

	/**
	 * @see BeanHelper::getModelForBean
	 */
	public function getModelForBean( OODBBean $bean )
	{
		$model     = $bean->getMeta( 'type' );
		$prefix    = defined( 'REDBEAN_MODEL_PREFIX' ) ? REDBEAN_MODEL_PREFIX : '\\Model_';

		return $this->resolveModel($prefix, $model, $bean);
	}

	/**
	 * Resolves the model associated with the bean using the model name (type),
	 * the prefix and the bean.
	 *
	 * @note
	 * If REDBEAN_CLASS_AUTOLOAD is defined this will be passed to class_exist as
	 * autoloading flag.
	 *
	 * @param string   $prefix Prefix to use for resolution
	 * @param string   $model  Type name
	 * @param OODBBean $bean   Bean to resolve model for
	 *
	 * @return SimpleModel|CustomModel|NULL
	 */
	protected function resolveModel($prefix, $model, $bean) {

		/* Determine autoloading preference */
		$autoloadFlag = ( defined( 'REDBEAN_CLASS_AUTOLOAD' ) ? REDBEAN_CLASS_AUTOLOAD : TRUE );

		if ( strpos( $model, '_' ) !== FALSE ) {
			$modelParts = explode( '_', $model );
			$modelName = '';
			foreach( $modelParts as $part ) {
				$modelName .= ucfirst( $part );
			}
			$modelName = $prefix . $modelName;
			if ( !class_exists( $modelName ) ) {
				$modelName = $prefix . ucfirst( $model );
				if ( !class_exists( $modelName, $autoloadFlag ) ) {
					return NULL;
				}
			}
		} else {
			$modelName = $prefix . ucfirst( $model );
			if ( !class_exists( $modelName, $autoloadFlag ) ) {
				return NULL;
			}
		}
		$obj = self::factory( $modelName );
		$obj->loadBean( $bean );
		return $obj;
	}

	/**
	 * @see BeanHelper::getExtractedToolbox
	 */
	public function getExtractedToolbox()
	{
		return Facade::getExtractedToolbox();
	}
}

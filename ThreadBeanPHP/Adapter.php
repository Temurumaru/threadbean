<?php

namespace ThreadBeanPHP;

/**
 * Adapter Interface.
 * Describes the API for a ThreadBeanPHP Database Adapter.
 * This interface defines the API contract for
 * a ThreadBeanPHP Database Adapter.
 *
 * @file    ThreadBeanPHP/Adapter.php
 * @author  Gabor de Mooij and the ThreadBeanPHP Community
 * @license BSD/GPLv2
 *
 * @copyright
 * (c) G.J.G.T. (Gabor) de Mooij and the ThreadBeanPHP Community.
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
interface Adapter
{
	/**
	 * Should returns a string containing the most recent SQL query
	 * that has been processed by the adapter.
	 *
	 * @return string
	 */
	public function getSQL();

	/**
	 * Executes an SQL Statement using an array of values to bind
	 * If $noevent is TRUE then this function will not signal its
	 * observers to notify about the SQL execution; this to prevent
	 * infinite recursion when using observers.
	 *
	 * @param string  $sql      string containing SQL code for database
	 * @param array   $bindings array of values to bind to parameters in query string
	 * @param boolean $noevent  no event firing
	 *
	 * @return int
	 */
	public function exec( $sql, $bindings = array(), $noevent = FALSE );

	/**
	 * Executes an SQL Query and returns a resultset.
	 * This method returns a multi dimensional resultset similar to getAll
	 * The values array can be used to bind values to the place holders in the
	 * SQL query.
	 *
	 * @param string $sql      string containing SQL code for database
	 * @param array  $bindings array of values to bind to parameters in query string
	 *
	 * @return array
	 */
	public function get( $sql, $bindings = array() );

	/**
	 * Executes an SQL Query and returns a resultset.
	 * This method returns a single row (one array) resultset.
	 * The values array can be used to bind values to the place holders in the
	 * SQL query.
	 *
	 * @param string $sql      string containing SQL code for database
	 * @param array  $bindings array of values to bind to parameters in query string
	 *
	 * @return array|NULL
	 */
	public function getRow( $sql, $bindings = array() );

	/**
	 * Executes an SQL Query and returns a resultset.
	 * This method returns a single column (one array) resultset.
	 * The values array can be used to bind values to the place holders in the
	 * SQL query.
	 *
	 * @param string $sql      string containing SQL code for database
	 * @param array  $bindings array of values to bind to parameters in query string
	 *
	 * @return array
	 */
	public function getCol( $sql, $bindings = array() );

	/**
	 * Executes an SQL Query and returns a resultset.
	 * This method returns a single cell, a scalar value as the resultset.
	 * The values array can be used to bind values to the place holders in the
	 * SQL query.
	 *
	 * @param string $sql      string containing SQL code for database
	 * @param array  $bindings array of values to bind to parameters in query string
	 *
	 * @return string|NULL
	 */
	public function getCell( $sql, $bindings = array() );

	/**
	 * Executes the SQL query specified in $sql and indexes
	 * the row by the first column.
	 *
	 * @param string $sql      string containing SQL code for database
	 * @param array  $bindings array of values to bind to parameters in query string
	 *
	 * @return array
	 */
	public function getAssoc( $sql, $bindings = array() );

	/**
	 * Executes the SQL query specified in $sql and returns
	 * an associative array where the column names are the keys.
	 *
	 * @param string $sql      String containing SQL code for databaseQL
	 * @param array  $bindings values to bind
	 *
	 * @return array
	 */
	public function getAssocRow( $sql, $bindings = array() );

	/**
	 * Returns the latest insert ID.
	 *
	 * @return integer
	 */
	public function getInsertID();

	/**
	 * Returns the number of rows that have been
	 * affected by the last update statement.
	 *
	 * @return integer
	 */
	public function getAffectedRows();

	/**
	 * Returns a database agnostic Cursor object.
	 *
	 * @param string $sql      string containing SQL code for database
	 * @param array  $bindings array of values to bind to parameters in query string
	 *
	 * @return Cursor
	 */
	public function getCursor( $sql, $bindings = array() );

	/**
	 * Returns the original database resource. This is useful if you want to
	 * perform operations on the driver directly instead of working with the
	 * adapter. ThreadBean will only access the adapter and never to talk
	 * directly to the driver though.
	 *
	 * @return Driver
	 */
	public function getDatabase();

	/**
	 * This method is part of the ThreadBean Transaction Management
	 * mechanisms.
	 * Starts a transaction.
	 *
	 * @return void
	 */
	public function startTransaction();

	/**
	 * This method is part of the ThreadBean Transaction Management
	 * mechanisms.
	 * Commits the transaction.
	 *
	 * @return void
	 */
	public function commit();

	/**
	 * This method is part of the ThreadBean Transaction Management
	 * mechanisms.
	 * Rolls back the transaction.
	 *
	 * @return void
	 */
	public function rollback();

	/**
	 * Closes database connection.
	 *
	 * @return void
	 */
	public function close();

	/**
	 * Sets a driver specific option.
	 * Using this method you can access driver-specific functions.
	 * If the selected option exists the value will be passed and
	 * this method will return boolean TRUE, otherwise it will return
	 * boolean FALSE.
	 *
	 * @param string $optionKey   option key
	 * @param string $optionValue option value
	 *
	 * @return boolean
	 */
	public function setOption( $optionKey, $optionValue );

	/**
	 * Returns the version string from the database server.
	 *
	 * @return string
	 */
	public function getDatabaseServerVersion();
}

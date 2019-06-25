<?php

namespace Codeman\Admin\Interfaces;

interface NewsInterface
{
	/**
	* Select all resources from storage.
	*
	* @return Object
	*/
	public function getAll();

	/**
	* Select the specified resource from storage by id.
	*
	* @param  int  $id
	* @return Object
	*/
	public function getById( $id );

	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store( $inputs );

	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update( $id, $inputs  );

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy( $id );
}
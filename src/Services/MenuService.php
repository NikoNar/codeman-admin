<?php

namespace Codeman\Admin\Services;

use Codeman\Admin\Interfaces\MenuInterface;

use Codeman\Admin\Models\Menu;
use Image;

class MenuService implements MenuInterface
{
	/**
	 * The object of Menu class.
	 *
	 * @var Team
	 */
	protected $menu;

	/**
	 * Client constructor.
	 *
	 * @param  Client 
	 */
	public function __construct( Menu $menu )
	{
		$this->menu = $menu;
	}

	/**
	* Select all resources from storage.
	*
	* @return Object
	*/
	public function getAll()
	{
		return $this->menu->all();
	}

	/**
	* Select the specified resource from storage by id.
	*
	* @param  int  $id
	* @return Object
	*/
	public function getById( $id )
	{
		return $this->menu->find($id);
	}

	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store( $inputs )
	{
		return $this->menu->create($this->createInputs( $inputs ));
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update( $id, $inputs )
	{		
		return $this->getById($id)->update($this->updateInputs($id, $inputs ));
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy( $id )
	{
		return $this->getById($id)->delete();
	}

	/**
	* Select all ids and titles of resource from storage.
	*
	* @return Array
	*/
	public function getAllMenusNamesArray($current_menu_id = null)
	{
		if($current_menu_id) {
			return $this->menu->where('id', '!=', $current_Menu_id )->pluck('name', 'id')->toArray();
		}else{
			return $this->menu->all()->pluck('name', 'id')->toArray();
		}
	}

	/**
	* Filtering and checking the data before store
	*
	* @param  int  $id
	* @return Response
	*/
	private function createInputs( $inputs)
	{

		return $inputs;
	}

	/**
	* Filtering and checking the data before update
	*
	* @param  int  $id
	* @return Response
	*/
	private function updateInputs( $id, $inputs )
	{
		return $inputs;
	}
}
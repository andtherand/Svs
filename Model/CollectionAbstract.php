<?php
    
abstract class Svs_Model_CollectionAbstract implements Iterator, Countable, ArrayAccess 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var array 	the collection of entities
	 */
	protected $_collection = array();
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * adds an entity to the collection
	 * provides a fluid interface
	 *  
	 * @param	Svs_Model_EntityAbstract $entity the actual entity to put in the collection
	 * @param 	[string|null $key the index of the entity in the collection] 
	 * @return 	Svs_Model_CollectionAbstract
	 */
	public function add(Svs_Model_EntityAbstract $entity, $key = null)
	{
		$this->offsetSet($key, $entity);
		return $this;
	}
	
	
	/**
	 * resets the collection
	 * 
	 * @see 	Iterator Interface
	 */
	public function rewind()
	{
	    reset($this->_collection);
	}
	
	/**
	 * moves the pointer to the next entity in the collection
	 * 
	 * @see 	Iterator Interface
	 */
	public function next()
	{
		next($this->_collection);
	}
	
	/**
	 *  returns the current? key of the collection 
	 * 
	 *  @see 	Iterator Interface
	 */
	public function key()
	{
		return key($this->_collection);
	}
	
	/**
	 * returns the current entity in the collection
	 * 
	 * @see 	Iterator Interface
	 * @return 	Svs_Model_EntityAbstract
	 */
	public function current()
	{
		return current($this->_collection);
	}
	
	/**
	 * checks if the current item is occupied by an entity
	 * 
	 * @see 	Iterator Interface
	 * @return 	boolean
	 */
	public function valid()
	{
		return (bool)$this->current();
	}
	
	/**
	 * counts the entities in the collection
	 * 
	 * @see	 	Countable Interface
	 * @return	int
	 */
	public function count()
	{
		return count($this->_collection);
	}
	
	/**
	 *	adds an entity to the collection
	 *  if no key is given adds to a numeric indexed collection array
	 *  else adds to an assoc array
	 *  provides a fluid interface
	 * 
	 *  @see 	ArrayAccess Interface
	 *  @param	[string|null $key 	optional key to 
	 * 								associated with an entity in the collection]
	 *  @param	Svs_Model_EntityAbstract $entity the entity to save 
	 *  @return Svs_Model_CollectionAbstract
	 */
	public function offsetSet($key = null, $entity)
	{
		// if no key is given switch arguments
		if($key === null){
					
			if(!in_array($entity, $this->_collection)){
				$this->_collection[] = $entity;
			}
			return $this;
			
		} elseif(
			!array_key_exists($key, $this->_collection)
			&& !in_array($entity, $this->_collection)
		){
			$this->_collection[$key] = $entity;
		}
		return $this;
	}
	
	/**
	 * checks if a given key is in the boundaries of the collection
	 * 
	 * @see 	ArrayAccess Interface
	 * @return 	Svs_Model_EntityAbstract
	 */
	public function offsetGet($key)
	{
		if($this->offsetExists($key)){
			return $this->_collection[$key];
		}
	}
	
	/**
	 * deletes an entity from a the collection identified by the given key or 
	 * if key is an entity by the object itself
	 * 
	 * @see 	ArrayAccess Interface
	 * @return	void
	 */
	public function offsetUnset($key)
	{
		if($key instanceof Svs_Model_EntityAbstract){
			$entity = $key;
			$tmpEntities = array();
			
			foreach($this->_collection as $savedEntity){
				if($entity !== $savedEntity){
					$tmpEntities[] = $savedEntity;  
				}
			}
			$this->_collection = $tmpEntities;
			return;
		}
		
		if(array_key_exists($key, $this->_collection)){
			unset($this->_collection[$key]);
		}
	}
	
	/**
	 * 	checks whether or not a given key has been set
	 * 
	 *  @see 	ArrayAccess Interface
	 *  @return boolean
	 */
	public function offsetExists($key)
	{
		return array_key_exists($key, $this->_collection);
	}
	
	/**
	 * convinience method to get the whole collection
	 * 
	 * @return array
	 */
	public function toArray()
	{
		return $this->_collection;
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}

<?php

class GridFieldHasOneRelationHandler extends GridFieldRelationHandler {
	protected $onObject;
	protected $relationName;

	protected $targetObject;

	protected $canCancel;

	public function __construct(DataObject $onObject, $relationName, $targetFragment = 'before', $canCancel = false) {
		$this->onObject = $onObject;
		$this->relationName = $relationName;

		$hasOne = $onObject->has_one($relationName);
		if(!$hasOne) {
			user_error('Unable to find a has_one relation named ' . $relationName . ' on ' . $onObject->ClassName, E_USER_WARNING);
		}
		$this->targetObject = $hasOne;

		$this->canCancel = $canCancel;

		parent::__construct(false, $targetFragment);
	}

	protected function setupState($state, $extra = null) {
		parent::setupState($state, $extra);
		if($state->FirstTime) {
			$state->RelationVal = $this->onObject->{$this->relationName}()->ID;
		}
	}

	public function getColumnContent($gridField, $record, $columnName) {
		$class = $gridField->getModelClass();
		if(!($class == $this->targetObject || is_subclass_of($class, $this->targetObject))) {
			user_error($class . ' is not a subclass of ' . $this->targetObject . '. Perhaps you wanted to use ' . $this->targetObject . '::get() as the list for this GridField?', E_USER_WARNING);
		}

		$state = $this->getState($gridField);
		
		$checked = $state->RelationVal == $record->ID;
		$field = new ArrayData(array('Checked' => $checked, 'Value' => $record->ID, 'Name' => $this->relationName . 'ID'));
		return $field->renderWith('GridFieldHasOneRelationHandlerItem');
	}

	protected function saveGridRelation(GridField $gridField, $arguments, $data) {
		$field = $this->relationName . 'ID';
		$state = $this->getState($gridField);
		$id = intval($state->RelationVal);
		$this->onObject->{$field} = $id;
		$this->onObject->write();
		parent::saveGridRelation($gridField, $arguments, $data);
	}

	protected function cancelGridRelation(GridField $gridField, $arguments, $data) {
		parent::cancelGridRelation($gridField, $arguments, $data);	

		if($this->canCancel) {
			// Delete relation
			$field = $this->relationName . 'ID';
			$this->onObject->{$field} = 0;
			$this->onObject->write();
			// Reset Gridfield
			$state = $this->getState($gridField);
			$state->ShowingRelation = false;
			$state->FirstTime = true;
			$state->RelationVal = 0;
		} 		
	}	

	protected function getFields($gridField) {
		$fields = parent::getFields($gridField);
		if ($this->canCancel) {
			$reset = Object::create(
						'GridField_FormAction',
						$gridField,
						'relationhandler-resetrel',
						'Reset',
						'cancelGridRelation',
						null
					);
			$fields->push($reset);
		}
		return $fields;
	}

	
}

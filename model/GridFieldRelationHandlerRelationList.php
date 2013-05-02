<?php

class GridFieldManyRelationHandler_HasManyList extends HasManyList {
	public function __construct() {

	}

	public function getForeignKey(HasManyList $on = null) {
		if(!$on) {
			return;
		}
		return $on->foreignKey;
	}

	public function getForeignIDFilter(RelationList $on = null) {
		if(!$on) {
			return;
		}
		return $on->foreignIDFilter();
	}
}

class GridFieldManyRelationHandler_ManyManyList extends ManyManyList {
	public function __construct() {

	}

	public function getJoinTable(ManyManyList $on = null) {
		if(!$on) {
			return;
		}
		return $on->joinTable;
	}

	public function getLocalKey(ManyManyList $on = null) {
		if(!$on) {
			return;
		}
		return $on->localKey;
	}

	public function getForeignKey(ManyManyList $on = null) {
		if(!$on) {
			return;
		}
		return $on->foreignKey;
	}

	public function getForeignIDFilter(RelationList $on = null) {
		if(!$on) {
			return;
		}
		return $on->foreignIDFilter();
	}
}

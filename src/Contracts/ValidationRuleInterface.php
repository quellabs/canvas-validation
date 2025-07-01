<?php
	
	namespace Quellabs\CanvasValidation\Contracts;
	
	/**
	 * Interface for validation rules in the Canvas Validation system
	 */
	interface ValidationRuleInterface {
		
		/**
		 * ValidationInterface constructor
		 * @param array $conditions Optional array of conditions to configure the rule
		 */
		public function __construct(array $conditions=[]);
		
		/**
		 * Validates the given value against this rule's criteria
		 * @param mixed $value The value to validate (can be any type depending on rule)
		 * @return bool True if validation passes, false if it fails
		 */
		public function validate($value) : bool;
		
		/**
		 * Retrieves the conditions used to configure this validation rule
		 * @return array The conditions array used by this rule
		 */
		public function getConditions() : array;
		
		/**
		 * Gets the error message to display when validation fails
		 * @return string The error message for validation failure
		 */
		public function getError() : string;
	}
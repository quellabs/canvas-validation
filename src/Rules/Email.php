<?php
	
	namespace Quellabs\CanvasValidation\Rules;
	
	use Quellabs\CanvasValidation\Contracts\ValidationRuleInterface;
	
	/**
	 * Class Email
	 * Implementation of a validation rule for email addresses
	 */
	class Email implements ValidationRuleInterface {
		
		/**
		 * Conditions for the validation
		 */
		protected array $conditions;
		
		/**
		 * Constructor of the Email class
		 * @param array $conditions Conditions for the validation
		 */
		public function __construct(array $conditions = []) {
			$this->conditions = $conditions;
		}
		
		/**
		 * Retrieves the conditions that are used in this rule
		 * @return array The conditions for the validation
		 */
		public function getConditions() : array {
			return $this->conditions;
		}
		
		/**
		 * Validates if the value is a valid email address
		 * @param mixed $value The value that needs to be validated
		 * @return bool True if the value is a valid email address, otherwise false
		 */
		public function validate(mixed $value): bool {
			// If the value is an empty string or null, it is considered valid
			if (($value === "") || is_null($value)) {
				return true;
			}
			
			// Check if the value is a valid email address
			return filter_var($value, FILTER_VALIDATE_EMAIL);
		}
		
		/**
		 * Retrieves the error message if the value is not valid
		 * @return string The error message
		 */
		public function getError(): string {
			// If no custom error message is set, use the default message
			if (!isset($this->conditions["message"])) {
				return "This value is not a valid email address.";
			}
			
			// Return the custom error message
			return $this->conditions["message"];
		}
	}
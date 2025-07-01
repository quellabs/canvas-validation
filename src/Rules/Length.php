<?php
	
	namespace Quellabs\CanvasValidation\Rules;
	
	use Quellabs\CanvasValidation\Contracts\ValidationRuleInterface;
	
	/**
	 * Length validation rule class
	 *
	 * Validates that a string value meets minimum and/or maximum length requirements.
	 * Supports configurable min/max length conditions and custom error messages.
	 */
	class Length implements ValidationRuleInterface {
		
		/**
		 * Array containing validation conditions (min, max, message)
		 * @var array
		 */
		protected array $conditions;
		
		/**
		 * Default error message set during validation
		 * @var string
		 */
		protected string $error;
		
		/**
		 * Length constructor
		 * @param array $conditions Optional array of validation conditions:
		 *                         - 'min': minimum required length
		 *                         - 'max': maximum allowed length
		 *                         - 'message': custom error message
		 */
		public function __construct(array $conditions = []) {
			$this->conditions = $conditions;
			$this->error = "";
		}
		
		/**
		 * Returns the conditions used in this Rule
		 * @return array The validation conditions array
		 */
		public function getConditions() : array {
			return $this->conditions;
		}
		
		/**
		 * Validates the provided value against length constraints
		 * @param mixed $value The value to validate (expected to be string)
		 * @return bool True if validation passes, false otherwise
		 */
		public function validate($value): bool {
			// Allow empty values and null to pass validation
			// This follows the principle that length validation should only
			// apply to non-empty values
			if (($value === "") || is_null($value)) {
				return true;
			}
			
			// Check the minimum length requirement if specified
			if (isset($this->conditions['min'])) {
				if (strlen($value) < $this->conditions['min']) {
					// Set default error message for minimum length violation
					$this->error = "This value is too short. It should have {{ min }} characters or more.";
					return false;
				}
			}
			
			// Check the maximum length requirement if specified
			if (isset($this->conditions['max'])) {
				if (strlen($value) > $this->conditions['max']) {
					// Set default error message for maximum length violation
					$this->error = "This value is too long. It should have {{ max }} characters or less.";
					return false;
				}
			}
			
			// Validation passed - value meets all length requirements
			return true;
		}
		
		/**
		 * Returns the appropriate error message
		 * @return string Either the custom message from conditions or the default error message
		 */
		public function getError(): string {
			// Return a custom error message if provided in conditions
			if (!isset($this->conditions["message"])) {
				return $this->error;
			}
			
			// Otherwise, return the default error message set during validation
			return $this->conditions["message"];
		}
	}
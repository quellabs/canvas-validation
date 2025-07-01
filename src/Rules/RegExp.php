<?php
	
	namespace Quellabs\CanvasValidation\Rules;
	
	use Quellabs\CanvasValidation\Contracts\ValidationRuleInterface;
	
	/**
	 * Regular Expression Validation Rule
	 *
	 * This class validates input values against a regular expression pattern.
	 * It implements the ValidationRuleInterface to provide consistent validation behavior.
	 */
	class RegExp implements ValidationRuleInterface {
		
		/**
		 * Array containing validation conditions including the regexp pattern and optional message
		 * @var array
		 */
		protected array $conditions;
		
		/**
		 * RegExp constructor
		 *
		 * Initializes the validation rule with optional conditions.
		 * Expected conditions array format:
		 * - 'regexp': The regular expression pattern to match against
		 * - 'message': Optional custom error message
		 *
		 * @param array $conditions Array of validation conditions
		 */
		public function __construct(array $conditions = []) {
			$this->conditions = $conditions;
		}
		
		/**
		 * Returns the conditions used in this Rule
		 * @return array The conditions array containing regexp pattern and optional message
		 */
		public function getConditions() : array {
			return $this->conditions;
		}
		
		/**
		 * Validates the given value against the regular expression pattern
		 *
		 * Returns true if:
		 * - Value is empty string, null, or the regexp condition is not set (passes validation)
		 * - The regular expression pattern matches the value
		 *
		 * @param mixed $value The value to validate
		 * @return bool True if validation passes, false otherwise
		 */
		public function validate($value): bool {
			// Allow empty values to pass validation (use NotBlank rule for mandatory fields)
			if (($value === "") || is_null($value) || empty($this->conditions["regexp"])) {
				return true;
			}
			
			// Use preg_match to test the value against the regular expression
			// Returns true if the pattern matches, false if it doesn't match or if there's an error
			return preg_match($this->conditions["regexp"], $value) !== false;
		}
		
		/**
		 * Returns the error message for validation failures
		 * @return string The error message (custom message if provided, default message otherwise)
		 */
		public function getError(): string {
			// Return a custom error message if provided
			if (!isset($this->conditions["message"])) {
				return "Regular expression did not match.";
			}
			
			return $this->conditions["message"];
		}
		
	}
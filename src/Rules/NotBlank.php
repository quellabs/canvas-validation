<?php
	
	namespace Quellabs\CanvasValidation\Rules;
	
	use Quellabs\CanvasValidation\Contracts\ValidationRuleInterface;
	
	/**
	 * NotBlank validation rule class
	 *
	 * Validates that a value is not blank (contains non-whitespace characters).
	 * This rule trims whitespace from the value and ensures the resulting string
	 * has a length greater than 0.
	 */
	class NotBlank implements ValidationRuleInterface {
		
		/**
		 * Array of conditions/options for this validation rule
		 * @var array
		 */
		protected array $conditions;
		
		/**
		 * NotBlank constructor
		 * @param array $conditions Optional array of conditions (e.g., custom error message)
		 */
		public function __construct(array $conditions = []) {
			$this->conditions = $conditions;
		}
		
		/**
		 * Returns the conditions used in this Rule
		 * @return array The conditions array
		 */
		public function getConditions() : array {
			return $this->conditions;
		}
		
		/**
		 * Validates that the given value is not blank
		 * @param mixed $value The value to validate
		 * @return bool True if the value is not blank, false otherwise
		 */
		public function validate($value): bool {
			// Trim whitespace and check if the resulting string has content
			return strlen(trim($value)) > 0;
		}
		
		/**
		 * Returns the error message for validation failure
		 * @return string The error message to display when validation fails
		 */
		public function getError(): string {
			// Check if a custom error message was provided in conditions
			if (!isset($this->conditions["message"])) {
				// Return default error message if no custom message is set
				return "This value should not be blank";
			}
			
			// Return the custom error message
			return $this->conditions["message"];
		}
	}
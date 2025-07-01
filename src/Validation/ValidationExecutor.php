<?php
	
	namespace Quellabs\CanvasValidation\Validation;
	
	/**
	 * ValidationExecutor
	 *
	 * Core validation engine that processes input data against validation rules.
	 * This class orchestrates the validation process by iterating through rules,
	 * applying validators, and collecting error messages with variable substitution.
	 *
	 * Used internally by ValidateAspect to perform the actual validation logic.
	 */
	class ValidationExecutor {
		
		/**
		 * Validates the input data against the given rules.
		 * @param array $input The input data to validate.
		 * @param array $rules The validation rules to apply.
		 * @param array $errors An array to store any validation errors (passed by reference).
		 * @throws \InvalidArgumentException If an invalid validator is provided.
		 */
		public function validate(array $input, array $rules, array &$errors): void {
			// Iterate through each rule
			foreach ($rules as $key => $value) {
				// Check if the input field exists
				if (!isset($input[$key])) {
					continue; // Skip to the next rule if the field is missing
				}
				
				// Ensure $validators is always an array, even if a single validator was provided
				$validators = is_array($value) ? $value : [$value];
				
				// Apply each validator to the input field
				foreach ($validators as $validator) {
					if (!$validator->validate($input[$key])) {
						// If validation fails, create an error message
						$errors[$key] = $this->replaceVariablesInErrorString($validator->getError(), [
							'key'   => $key,
							'value' => $input[$key],
						]);
						
						// Stop validating this field after the first error
						break;
					}
				}
			}
		}
		
		/**
		 * Replaces variables in an error string with their corresponding values.
		 * @param string $string The error string containing variables.
		 * @param array $variables An associative array of variable names and their values.
		 * @return string The error string with variables replaced.
		 */
		protected function replaceVariablesInErrorString(string $string, array $variables): string {
			return preg_replace_callback('/{{\\s*([a-zA-Z_][a-zA-Z0-9_]*)\\s*}}/', function ($matches) use ($variables) {
				return $variables[$matches[1]] ?? $matches[0];
			}, $string);
		}
	}
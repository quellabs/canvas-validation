# Canvas Validation

[![Latest Version](https://img.shields.io/packagist/v/quellabs/canvas-validation.svg?style=flat-square)](https://packagist.org/packages/quellabs/canvas-validation)
[![PHP Version](https://img.shields.io/packagist/php-v/quellabs/canvas-validation.svg?style=flat-square)](https://packagist.org/packages/quellabs/canvas-validation)
[![License](https://img.shields.io/packagist/l/quellabs/canvas-validation.svg?style=flat-square)](https://packagist.org/packages/quellabs/canvas-validation)

A modern PHP validation library built on **Aspect-Oriented Programming (AOP)** principles. Canvas Validation provides seamless, declarative form validation for your web applications by automatically intercepting HTTP requests and validating data before it reaches your controller methods.

## ✨ Features

- 🎯 **AOP-Based**: Clean separation of validation logic from business logic
- 🚀 **Zero Configuration**: Works out of the box with sensible defaults
- 🔄 **Smart Detection**: Automatically handles JSON API and web form responses
- 📋 **Rich Rule Set**: 11+ built-in validation rules covering common use cases
- 🛡️ **Security First**: Built-in XSS protection and input sanitization
- 🔧 **Extensible**: Easy to create custom validation rules

## 🚀 Quick Start

### Installation

```bash
composer require quellabs/canvas-validation
```

### Basic Usage

1. **Create a validation class:**

```php
<?php

use Quellabs\CanvasValidation\Contracts\ValidationInterface;
use Quellabs\CanvasValidation\Rules\NotBlank;
use Quellabs\CanvasValidation\Rules\Email;
use Quellabs\CanvasValidation\Rules\Length;
use Quellabs\CanvasValidation\Rules\Type;

class UserValidation implements ValidationInterface {
    public function getRules(): array {
        return [
            'name' => [
                new NotBlank(),
                new Length(['min' => 2, 'max' => 50]),
            ],
            'email' => [
                new NotBlank(),
                new Email(),
            ],
            'age' => [
                new Type(['type' => 'integer']),
                new Length(['min' => 18, 'max' => 120]),
            ],
        ];
    }
}
```

2. **Apply validation to your controller:**

```php
<?php

use Quellabs\CanvasValidation\ValidateAspect;

class UserController {
    /**
     * @Route("/api/users", methods={"POST"})
     * @InterceptWith(ValidateAspect::class, validate=UserValidation::class, autoRespond=true)
     */
    public function createUser(Request $request): Response {
        // For JSON API requests, validation errors are automatically returned as HTTP 422
        // For web forms, check validation status manually:
        if (!$request->attributes->get('validation_passed', true)) {
            $errors = $request->attributes->get('validation_errors', []);
            return $this->render('user/form.tpl', ['errors' => $errors]);
        }
        
        // Process validated data...
        return new JsonResponse(['status' => 'success']);
    }
}
```

That's it! 🎉 Canvas Validation will automatically:
- Intercept incoming requests
- Validate data against your rules
- Return JSON errors for API requests (when `autoRespond=true`)
- Store validation results in request attributes for web forms

## 📋 Available Validation Rules

| Rule           | Description                               | Example Usage                                              |
|----------------|-------------------------------------------|------------------------------------------------------------|
| `AtLeastOneOf` | Value must satisfy at least one condition | `new AtLeastOneOf([new Email(), new RegExp('/^\d+$/')])`   |
| `Date`         | Validates date format and ranges          | `new Date(['format' => 'Y-m-d', 'after' => '2020-01-01'])` |
| `Email`        | Validates email addresses                 | `new Email()`                                              |
| `Length`       | Validates string/array length             | `new Length(['min' => 8, 'max' => 255])`                   |
| `NotBlank`     | Ensures value is not empty                | `new NotBlank()`                                           |
| `NotHTML`      | Prevents HTML tags (XSS protection)       | `new NotHTML()`                                            |
| `NotLongWord`  | Prevents overly long words                | `new NotLongWord(['maxLength' => 50])`                     |
| `RegExp`       | Regular expression validation             | `new RegExp('/^[A-Z]{2,3}$/')`                             |
| `Type`         | Data type validation                      | `new Type(['type' => 'integer'])`                          |
| `ValueIn`      | Value must be in allowed list             | `new ValueIn(['allowed' => ['red', 'green', 'blue']])`     |
| `Zipcode`      | Postal/ZIP code validation                | `new Zipcode(['country' => 'US'])`                         |

## 🔧 Advanced Usage

### Custom Error Messages

```php
public function getRules(): array {
    return [
        'username' => new Length([
            'min' => 3,
            'max' => 20,
            'message' => 'Username must be between {{min}} and {{max}} characters long'
        ]),
        'email' => new Email([
            'message' => 'Please provide a valid email address'
        ])
    ];
}
```

### Multiple Forms on One Page

```php
/**
 * @InterceptWith(ValidateAspect::class, validate=ContactFormValidation::class, formId="contact")
 * @InterceptWith(ValidateAspect::class, validate=NewsletterValidation::class, formId="newsletter") 
 */
public function handleForms(Request $request): Response {
    $contactValid = $request->attributes->get('contact_validation_passed');
    $contactErrors = $request->attributes->get('contact_validation_errors', []);
    
    $newsletterValid = $request->attributes->get('newsletter_validation_passed');
    $newsletterErrors = $request->attributes->get('newsletter_validation_errors', []);
    
    // Handle each form independently...
}
```

### Creating Custom Validation Rules

```php
<?php

use Quellabs\CanvasValidation\Contracts\ValidationRuleInterface;

class StrongPassword implements ValidationRuleInterface {
    private array $conditions;
    
    public function __construct(array $conditions = []) {
        $this->conditions = array_merge([
            'minLength' => 8,
            'requireNumbers' => true,
            'requireSpecialChars' => true,
            'message' => 'Password must be at least {{minLength}} characters with numbers and special characters',
        ], $conditions);
    }
    
    public function validate($value): bool {
        if (strlen($value) < $this->conditions['minLength']) {
            return false;
        }
        
        if ($this->conditions['requireNumbers'] && !preg_match('/\d/', $value)) {
            return false;
        }
        
        if ($this->conditions['requireSpecialChars'] && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value)) {
            return false;
        }
        
        return true;
    }
    
    public function getError(): string {
        return $this->conditions['message'];
    }
    
    public function getConditions(): array {
        return $this->conditions;
    }
}
```

## 🛡️ Security Features

Canvas Validation includes several security-focused features:

- **XSS Prevention**: `NotHTML` rule strips dangerous HTML tags
- **Input Length Limits**: Prevent buffer overflow attempts
- **Type Safety**: Strong typing prevents type confusion attacks
- **Regular Expression Validation**: Flexible pattern matching for secure input formats

## 🤝 Contributing

We welcome contributions!

### Development Setup

```bash
# Clone the repository
git clone https://github.com/quellabs/canvas-validation.git
cd canvas-validation

# Install dependencies
composer install

# Run tests
composer test
```

## 🐛 Issues & Support

- **Bug Reports**: [GitHub Issues](https://github.com/quellabs/canvas-validation/issues)
- **Feature Requests**: [GitHub Discussions](https://github.com/quellabs/canvas-validation/discussions)
- **Security Issues**: security@quellabs.com

## 📄 License

Canvas Validation is open-source software licensed under the [MIT License](LICENSE).
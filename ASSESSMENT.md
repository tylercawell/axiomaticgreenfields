# Axiomatic Greenfields - Assessment Notes

** Approach **

Focus Points:

- Clean Architecture
- Data Integrity
- User Isolation
- Test-driven Validation


# Architecture

The application follows a layered approach.

Conteollers
- Thin and mininalist
-  Responsible for request validation and response handling

Services
- Heart of the application (Core business logic)
- Handles
    -   Commission note creation
    -   Updates
    -   Authorizaton rules
    -   Data validation through relationships

Models
- Company
- Branches
- Employee
- Commission Note

Jobs
Asyncronous processing
- Sms Notifications
- Email Notifications

# Authorisation

Authorisation is enforced using Spatie Laravel Permissions and Service-level Checks

Users can update their own commission notes, should a user have manage commision notes, all notes can be updated

# Data Integrity

Strict validation rules ensure:
    - A branch must belong to a company
    -  An employee must belong to a selected branch

Preventing invalid relational data

# Seeding Strategy

Seeded data is designed to be simple within the context of the assignnment

-   Realistic
-   Relationally Correct
-   Useful for testing UI and Logic

Includes:
    - Companies
    - Branches
    - Employees
    - Commission Notes

Fixed amounts have been allocated

- 10,000.00
- 20,000.00

# Testing

Testing is implemented by PEST

Coverage includes

- Authorisation rules
- CRUD operations
- Service-layer logic

# Notifications

When a commission note is created and SMS and E-Mail job are dispatched

These are queable for scalability

# Assumptions
- This is a simple production style application
- External integrations are mocked, but production ready with valid credentials
- Focus on backend correctness and structure

# Improvements

- Security improvements would need to be made for production by:
 - Implementing Encryption across the DB fro sensitive information
 - Rotation of API keys for service provider
 - Official API documentation
 - Role Based authentication combined with permnissions, allowing roles to have multiple permissions, creating more flexibility and additional control over the application

- Complete Audits would need to be implemented for transparency and accountability

- Basic reusable companents were created, however more detailed versions would be required to be able to strive for DNR (Do Not Repeat)

- Dashboards and Reporting would need to be implented saving users time
- Improved UI (Current UI is Laravel Default based), Implementing a template based UI would be an efficient and effective way to improve user experience and focus on core functionality of the applications

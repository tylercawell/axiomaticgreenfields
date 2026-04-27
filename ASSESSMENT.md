# Axiomatic Greenfields – Assessment Notes

## 🧠 Approach

### Focus Areas

- Clean Architecture  
- Data Integrity  
- User Isolation  
- Test-Driven Validation  

## 🏗 Architecture

The application follows a layered architecture to ensure separation of concerns and maintainability.

### Controllers
- Thin and minimal  
- Responsible for request validation and response handling  

### Services
- Core of the application (business logic)  
- Responsible for:
  - Commission note creation  
  - Updates  
  - Authorization rules  
  - Cross-entity data validation  

### Models
Domain entities include:
- Company  
- Branch  
- Employee  
- Commission Note  

### Jobs
- Handles asynchronous processing  
- Includes:
  - SMS notifications  
  - Email notifications  

## 🔐 Authorization

Authorization is implemented using:

- Spatie Laravel Permission  
- Service-layer validation  

### Rules

- Users may update:
  - Their own commission notes  
  - OR any commission note if they have manage commission notes permission  

## 🔗 Data Integrity

Strict validation rules enforce:

- A branch must belong to the selected company  
- An employee must belong to the selected branch  

This prevents invalid relational data from being stored.

## 📊 Seeding Strategy

Seeded data is designed to be:

- Simple within the context of the assessment  
- Relationally correct  
- Useful for testing UI and logic  

### Includes

- Companies  
- Branches  
- Employees  
- Commission Notes  

### Fixed Commission Amounts

- 10,000.00  
- 20,000.00  
- 30,000.00  
- 40,000.00  

## 🧪 Testing

Testing is implemented using Pest.

### Coverage Includes

- Authorization rules  
- CRUD operations  
- Service-layer logic  

## 📬 Notifications

When a commission note is created:

- An SMS notification job is dispatched  
- An email notification is dispatched  

These are designed to be queueable for scalability.

## ⚠️ Assumptions

- This is a simplified production-style implementation  
- External integrations are mocked but structured for real-world use  
- Primary focus is backend correctness, structure, and scalability  

## 🚀 Potential Improvements

### 🔒 Security
- Enforce two-factor authentication  
- Encrypt sensitive data at rest  
- Implement API key rotation strategies  
- Introduce full audit logging for financial actions  

### 🧩 Architecture
- Introduce role-based grouping on top of permissions  
- Expand reusable component architecture (DRY principles)  
- Introduce DTOs or action classes for stricter data handling  

### 📊 Features
- Dashboards and reporting for better insights  
- Activity logs and audit trails  

### 🎨 UI/UX
- Replace default Laravel UI with a structured design system (e.g. Metronic)  
- Improve usability and workflow efficiency  

### 📱 Communication
- Replace SMS with WhatsApp integration:
  - More cost-effective  
  - Better delivery tracking  
  - Easier auditability  

  Transparenct Note: AI was used to clean up language
# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Planned
- MySQL/PostgreSQL database support
- Email notifications (PHPMailer)
- Two-factor authentication (2FA)
- Account statements (PDF generation)

---

## [1.0.0] - 2025-11-14

### 🎉 Initial Release

#### Added
- **User Management**
  - User registration with email validation
  - Secure login with bcrypt password hashing
  - Auto-generated 10-digit account numbers
  - User profile management

- **Banking Features**
  - Request deposits (admin approval required)
  - Request withdrawals (admin approval required)
  - Instant money transfers between accounts
  - Real-time balance updates
  - Transaction history with timestamps

- **Admin Panel**
  - Secure admin authentication
  - Dashboard with system statistics
  - Approve/reject deposit requests
  - Approve/reject withdrawal requests
  - View all users and balances
  - Monitor all system transactions
  - Change admin password functionality

- **Security Features**
  - Login rate limiting (5 attempts, 15-minute lockout)
  - Account lockout mechanism
  - Session management
  - Password hashing with bcrypt
  - Input validation and sanitization

- **Notification System**
  - Real-time notifications for users
  - Notification badges with unread count
  - Notifications for deposits, withdrawals, transfers
  - Admin approval/rejection notifications

- **Technical Implementation**
  - PHP 8.3+ with strict types
  - Full OOP architecture (7 classes)
  - Enums for type safety (TransactionType, RequestStatus, UserRole)
  - JSON file-based database
  - RESTful API design
  - Responsive design with Tailwind CSS
  - Modern ES6+ JavaScript

#### Technical Details
- **Database Schema**: JSON file storage with users, accounts, transactions, notifications, and pending requests
- **API Endpoints**: 15+ RESTful endpoints for all operations
- **Classes**: Database, User, Account, Transaction, Notification, Admin
- **Security**: Rate limiting, password hashing, session management

---

## [0.2.0] - 2025-11-13 (Development)

### Added
- Rate limiting implementation
- Account lockout after failed attempts
- Admin password change feature

### Fixed
- Undefined array key warnings for new users
- Admin panel redirect loop issue
- Password hash mismatch

### Changed
- Improved error handling in User.php
- Enhanced debugging output in admin.html
- Better localStorage management

---

## [0.1.0] - 2025-11-10 (Initial Development)

### Added
- Basic project structure
- User and Admin classes
- Database abstraction layer
- Frontend HTML pages
- API router

---

## Version History

### Semantic Versioning

- **MAJOR** version (X.0.0): Incompatible API changes
- **MINOR** version (0.X.0): Backward-compatible functionality
- **PATCH** version (0.0.X): Backward-compatible bug fixes

### Release Tags

- `v1.0.0` - First stable release
- `v0.x.x` - Development versions

---

## How to Update

### From 0.x.x to 1.0.0

1. Backup your `data.json` file
2. Download the new version
3. Replace all PHP and HTML files
4. Keep your existing `data.json`
5. Clear browser cache
6. Test all functionality

---

## Contributors

See the list of [contributors](https://github.com/YOUR_USERNAME/mini-bank/contributors) who participated in this project.

---

## Links

- [GitHub Repository](https://github.com/YOUR_USERNAME/mini-bank)
- [Issue Tracker](https://github.com/YOUR_USERNAME/mini-bank/issues)
- [Documentation](https://github.com/YOUR_USERNAME/mini-bank/wiki)
- [Releases](https://github.com/YOUR_USERNAME/mini-bank/releases)

---

**Note**: For older changes, see [HISTORY.md](HISTORY.md) (if applicable)

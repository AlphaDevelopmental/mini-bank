# Modern Mini Bank Application

A complete banking system built with PHP 8.3+ (OOP) and modern frontend technologies.

## 🌟 Features

### User Features
- User registration and secure login
- Auto-generated 10-digit account numbers
- Request deposits/withdrawals (admin approval required)
- Instant money transfers between accounts
- Real-time transaction history
- Notification system with badges
- Rate limiting (5 failed login attempts, 15-minute lockout)

### Admin Features
- Secure admin panel
- Dashboard with system statistics
- Approve/reject deposit and withdrawal requests
- View all users and their balances
- Monitor all system transactions
- Change admin password

### Technical Features
- PHP 8.3+ with strict types
- Full OOP architecture (7 classes)
- Enums for type safety (TransactionType, RequestStatus, UserRole)
- Password hashing with bcrypt
- Login rate limiting and account lockout
- JSON file-based database (no MySQL required)
- RESTful API design
- Responsive design (mobile-friendly)
- Session management

## 📁 Project Structure
```
mini-bank/
├── index.html          # User login/register page
├── dashboard.html      # User dashboard
├── admin.html         # Admin control panel
├── config.php         # Configuration & enums
├── Database.php       # Database operations class
├── User.php           # User management class
├── Account.php        # Account operations class
├── Transaction.php    # Transaction handling class
├── Notification.php   # Notification system class
├── Admin.php          # Admin operations class
├── api.php            # API router
├── data.json          # JSON database (not tracked)
└── README.md          # This file
```

## 🚀 Installation

### Prerequisites
- PHP 8.3 or higher
- Apache web server
- XAMPP/LAMPP (recommended)

### Setup Steps

1. **Clone the repository:**
```bash
   git clone https://github.com/AlphaDevelopmental/mini-bank.git
   cd mini-bank
```

2. **Create data.json file:**
```bash
   cp data.json.example data.json
```

3. **Set permissions (Linux/Mac):**
```bash
   chmod 666 data.json
```

4. **Start Apache:**
   - XAMPP: Open XAMPP Control Panel → Start Apache
   - LAMPP: `sudo /opt/lampp/lampp start`

5. **Access the application:**
   - User Portal: `http://localhost/mini-bank/`
   - Admin Panel: `http://localhost/mini-bank/admin.html`

## 🔐 Default Credentials

**Admin Login:**
- Email: `admin@bank.com`
- Password: `admin123`

**⚠️ Important:** Change the default admin password immediately after first login via Admin Panel → Settings.

## 📖 Usage

### For Users:
1. Register a new account
2. Login with your credentials
3. Note your auto-generated account number
4. Request deposits/withdrawals (requires admin approval)
5. Transfer money instantly to other account numbers
6. View transaction history and notifications

### For Admin:
1. Login to admin panel
2. View pending deposit/withdrawal requests
3. Approve or reject requests
4. Monitor all users and system transactions
5. Change admin password in Settings tab

## 🔒 Security Features

- Bcrypt password hashing
- Rate limiting (5 attempts, 15-min lockout)
- Session management
- Input validation
- CSRF protection ready
- XSS prevention

## 🛠️ Configuration

Edit `config.php` to customize:
- Database file location
- Error reporting
- CORS headers
- Session settings

Edit `User.php` constants to adjust rate limiting:
```php
private const MAX_LOGIN_ATTEMPTS = 5;      // Change attempts limit
private const LOCKOUT_DURATION = 900;      // Change lockout time (seconds)
```

## 📝 API Endpoints

- `POST /api.php?action=register` - User registration
- `POST /api.php?action=login` - User/admin login
- `GET /api.php?action=getAccount&userId={id}` - Get account info
- `POST /api.php?action=requestDeposit` - Request deposit
- `POST /api.php?action=requestWithdrawal` - Request withdrawal
- `POST /api.php?action=transfer` - Transfer money
- `GET /api.php?action=getTransactions&userId={id}` - Get transactions
- `GET /api.php?action=getNotifications&userId={id}` - Get notifications
- `POST /api.php?action=adminApproveRequest` - Approve request (admin)
- `POST /api.php?action=adminRejectRequest` - Reject request (admin)
- `POST /api.php?action=adminChangePassword` - Change admin password

## 🧪 Testing

### Test Rate Limiting:
1. Try to login with wrong password 5 times
2. Account should lock for 15 minutes
3. Check error logs for debug info

### Test Admin Workflow:
1. Register as user
2. Request $1000 deposit
3. Login as admin
4. Approve the deposit request
5. User balance should update instantly

## 🐛 Troubleshooting

**"Undefined array key" errors:**
- Run the application once; User.php auto-migrates old data

**Can't login to admin:**
- Check data.json has admin user with correct password hash
- Verify role is set to "admin"
- Check browser console for errors

**Redirect loop on admin page:**
- Clear localStorage: `localStorage.clear()`
- Clear browser cache
- Check admin.html access check logic

**File permission errors:**
- Linux/Mac: `chmod 666 data.json`
- Windows: Ensure write permissions

## 📚 Technologies Used

- **Backend:** PHP 8.3+ (OOP, Enums, Strict Types)
- **Frontend:** HTML5, JavaScript (ES6+), Tailwind CSS
- **Database:** JSON file storage
- **Architecture:** RESTful API, MVC-inspired

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## ⚠️ Disclaimer

This is a demonstration project for educational purposes. For production use:
- Use a proper database (MySQL, PostgreSQL)
- Implement additional security measures
- Add SSL/TLS encryption
- Use environment variables for sensitive data
- Implement proper logging and monitoring
- Add comprehensive testing

## 🔮 Future Enhancements

- [ ] MySQL/PostgreSQL database support
- [ ] Email notifications (PHPMailer)
- [ ] Two-factor authentication (2FA)
- [ ] Account statements (PDF generation)
- [ ] Loan management system
- [ ] Multi-currency support
- [ ] Transaction search and filters
- [ ] Export to Excel/CSV
- [ ] Dark mode
- [ ] Mobile app (React Native)

## 👤 Author

Ajani Taiwo MIcheal
- GitHub: [@AlphaDevelopmental](https://github.com/AlphaDevelopmental)

## 🙏 Acknowledgments

- Tailwind CSS for styling
- PHP community for best practices
- Claude AI for development assistance

## 
# Web Security Vulnerability Demonstrations

This project is an educational web application that demonstrates common web security vulnerabilities and their secure implementations. It serves as a practical learning tool for understanding various types of web attacks and how to prevent them.

## ⚠️ Important Notice

**This project is for educational purposes only!** The vulnerable examples contain intentional security flaws and should never be deployed in a production environment.

## Prerequisites

- XAMPP (or similar PHP development environment)
- PHP 7.4+
- MySQL
- Web browser

## Installation

1. Clone or download this repository into your XAMPP's htdocs directory:
   ```
   C:\xampp\htdocs\CyberSecurity
   ```

2. Import the database:
   - Start MySQL in XAMPP
   - Create a new database named 'security_demo'
   - Import the SQL file from `config/database.sql`

3. Configure database connection:
   - Check `config/database.php` for database credentials
   - Default configuration uses:
     - Host: localhost
     - User: root
     - Password: (empty)
     - Database: security_demo

4. Access the application:
   ```
   http://localhost/CyberSecurity
   ```

## Security Demonstrations

The project includes demonstrations of the following security vulnerabilities:

### 1. Remote File Inclusion (RFI)
- Demonstrates how attackers can include malicious files from external servers
- Shows proper input validation and whitelisting prevention techniques
- Location: `attacks/rfi/`

### 2. SQL Injection (SQLi)
- Shows database query manipulation vulnerabilities
- Demonstrates proper use of prepared statements
- Includes authentication bypass examples
- Location: `attacks/sqli/`

### 3. Cross-Site Scripting (XSS)
- Demonstrates different types of XSS attacks
- Shows proper input/output sanitization
- Includes Content Security Policy implementation
- Location: `attacks/xss/`

### 4. Cross-Site Request Forgery (CSRF)
- Shows how malicious sites can trick users into unauthorized actions
- Demonstrates token-based CSRF prevention
- Location: `attacks/csrf/`

### 5. File Upload Vulnerabilities
- Demonstrates insecure file upload risks
- Shows proper file validation and handling
- Includes examples of file type verification
- Location: `attacks/fileupload/`

### 6. Command Injection
- Shows OS command injection vulnerabilities
- Demonstrates proper command sanitization
- Location: `attacks/cmdi/`

### 7. Local File Inclusion (LFI)
- Demonstrates accessing sensitive local files
- Shows proper file inclusion practices
- Location: `attacks/lfi/`

### 8. Path Traversal
- Shows directory traversal vulnerabilities
- Demonstrates proper path validation
- Location: `attacks/pathtraversal/`

### 9. Denial of Service (DoS)
- Shows resource exhaustion vulnerabilities
- Demonstrates rate limiting and other protections
- Location: `attacks/dos/`

## Project Structure

```
CyberSecurity/
├── attacks/             # Security vulnerability demonstrations
│   ├── cmdi/           # Command injection examples
│   ├── csrf/           # Cross-Site Request Forgery examples
│   ├── dos/            # Denial of Service examples
│   ├── fileupload/     # File upload vulnerability examples
│   ├── lfi/            # Local File Inclusion examples
│   ├── pathtraversal/  # Path Traversal examples
│   ├── rfi/            # Remote File Inclusion examples
│   ├── sqli/           # SQL Injection examples
│   └── xss/            # Cross-Site Scripting examples
├── config/             # Configuration files
│   ├── database.php    # Database connection settings
│   └── database.sql    # Database schema and sample data
└── index.php          # Main application entry point
```

## Security Features Demonstrated

Each vulnerability example includes:
- A vulnerable implementation showing the security flaw
- A secure implementation showing proper protection
- Detailed explanations of the vulnerability
- Code comments explaining security measures
- Visual demonstration of attack vectors
- Best practices for prevention

## Best Practices Shown

1. Input Validation and Sanitization
2. Output Encoding
3. Prepared Statements for SQL
4. Content Security Policy (CSP)
5. File Upload Validation
6. CSRF Token Implementation
7. Rate Limiting
8. Error Handling
9. Access Control
10. Secure Configuration

## Educational Resources

Each demonstration includes:
- Clear explanations of the vulnerability
- Attack vectors and impact
- Prevention techniques
- Code examples
- Security best practices
- Real-world scenarios

## Development Guidelines

When working with this project:
1. Never use the vulnerable code in production
2. Keep the demonstrations isolated
3. Use the secure implementations as reference
4. Follow proper security practices
5. Keep dependencies updated
6. Review security measures regularly

## Contributing

Feel free to contribute additional security demonstrations or improvements:
1. Fork the repository
2. Create a feature branch
3. Add your security demonstration
4. Include both vulnerable and secure examples
5. Add detailed documentation
6. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Disclaimer

The vulnerable examples in this project contain intentional security flaws for educational purposes. Never use vulnerable code in production environments. The authors are not responsible for any misuse or damage caused by this code.

## Security Notice

When running these demonstrations:
1. Use in a controlled environment only
2. Never expose to the public internet
3. Use isolated development environments
4. Monitor system resources
5. Follow proper security protocols

## Contact

For questions, suggestions, or concerns about this security demonstration project, please open an issue in the repository.

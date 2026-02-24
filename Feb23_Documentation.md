# Password Hashing Research

## Online Resources Used
- https://www.php.net/manual/en/function.password-hash.php
- https://www.php.net/manual/en/faq.passwords.php

## AI Prompt Used
How do I convert a plain text password into a hashed password in PHP and how do I update my login system to use password_verify() instead of comparing plain text passwords?

## AI Output
To securely hash passwords in PHP and update your login system, you should use PHP’s built-in `password_hash()` and `password_verify()` functions. These use strong algorithms like **bcrypt** (default) or **Argon2** and handle salting automatically.

---

# ✅ 1. Hashing a Plain Text Password (When Registering)

When a user registers, **never store the plain text password**. Instead:

```php
<?php
$password = $_POST['password'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Store $hashedPassword in the database
?>
```

### Important:

* `PASSWORD_DEFAULT` currently uses bcrypt (future-proof).
* The salt is automatically generated.
* The hash includes algorithm info, so you don’t need to manage it.

Example stored hash:

```
$2y$10$wHk6sQJvK7uF9E1jzVYF7O6pQkZ8rM2JvGQWmX5L9aB3uH7yKfP9e
```

---

# ✅ 2. Updating Your Login System (Using `password_verify()`)

### ❌ Old insecure way:

```php
if ($inputPassword === $row['password']) {
    // Login success
}
```

### ✅ Secure way:

```php
<?php
$inputPassword = $_POST['password'];

// Get hashed password from DB
$storedHash = $row['password'];

if (password_verify($inputPassword, $storedHash)) {
    // Password is correct
    echo "Login successful!";
} else {
    // Invalid password
    echo "Invalid credentials.";
}
?>
```

`password_verify()`:

* Hashes the input password
* Compares it securely
* Protects against timing attacks

---

# ✅ 3. Full Example (Registration + Login)

### Registration Script

```php
<?php
$password = $_POST['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->execute([$email, $hashedPassword]);
?>
```

---

### Login Script

```php
<?php
$stmt = $pdo->prepare("SELECT password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($_POST['password'], $user['password'])) {
    echo "Login successful";
} else {
    echo "Invalid email or password";
}
?>
```

---

# ✅ 4. Optional: Rehashing Passwords (Future-Proofing)

If PHP updates its default algorithm, you can rehash automatically:

```php
if (password_verify($inputPassword, $storedHash)) {

    if (password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
        $newHash = password_hash($inputPassword, PASSWORD_DEFAULT);

        // Update database with new hash
    }

    echo "Login successful";
}
```

---

# 🚨 Important Security Tips

* Never store plain text passwords.
* Never use MD5 or SHA1 (they are broken).
* Always use prepared statements (PDO or MySQLi).
* Use HTTPS.
* Consider rate limiting login attempts.

---

If you'd like, I can also help you convert your existing plain-text password database to hashed passwords safely.

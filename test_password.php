<?php
$stored_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
$password = 'admin123';

if (password_verify($password, $stored_hash)) {
    echo "✅ Password 'admin123' is CORRECT for this hash";
} else {
    echo "❌ Password 'admin123' does NOT match this hash";
}

echo "<br><br>Generate new hash for 'admin123':<br>";
echo password_hash('admin123', PASSWORD_DEFAULT);
?>
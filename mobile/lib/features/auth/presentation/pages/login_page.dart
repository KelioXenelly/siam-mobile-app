import 'dart:ui';

import 'package:flutter/material.dart';
import 'package:mobile/core/services/storage_service.dart';
import 'package:mobile/core/services/auth_service.dart';
import 'package:mobile/shared/widgets/glass_card.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final AuthService _authService = AuthService();
  final _identifierController = TextEditingController();
  final _passwordController = TextEditingController();

  String _role = 'mahasiswa'; // State untuk Role
  bool _obscureText = true;    // State untuk Show/Hide Password
  bool _isLoading = false;     // State untuk Loading
  String? _errorMessage;

  void _handleLogin() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final response = await _authService.login(
          _identifierController.text,
          _passwordController.text
      );

      final data = response.data;

      final token = data['token'];
      final user = data['user'];
      await StorageService.saveToken(token);

      print("TOKEN DISIMPAN: $token");
      print("USER: $user");


      final targetPath =
      // Navigasi berdasarkan role
      _role == 'mahasiswa' ? '/dashboard' : '/dosen/dashboard';

      Navigator.pushReplacementNamed(context, targetPath);
    } catch (e) {
      setState(() => _errorMessage = "Login gagal. Silakan coba lagi.");
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        width: double.infinity,
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [Color(0xFF2563EB), Color(0xFF3B82F6), Color(0xFF60A5FA)],
          ),
        ),
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: Column(
              children: [
                GlassCard(
                  child: Column(
                    children: [
                      // Logo S
                      Container(
                        width: 80, height: 80,
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(colors: [Color(0xFF2563EB), Color(0xFF4F46E5)]),
                          borderRadius: BorderRadius.circular(20),
                          boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 10)],
                        ),
                        child: const Center(child: Text("S", style: TextStyle(fontSize: 40, fontWeight: FontWeight.bold, color: Colors.white))),
                      ),
                      const SizedBox(height: 16),
                      const Text("SIAM", style: TextStyle(fontSize: 28, fontWeight: FontWeight.bold)),
                      const Text("Sistem Informasi Absensi Mahasiswa", style: TextStyle(color: Colors.grey, fontSize: 12)),
                      const SizedBox(height: 30),

                      // Role Switch (Custom Tab)
                      Container(
                        padding: const EdgeInsets.all(4),
                        decoration: BoxDecoration(color: Colors.grey[200], borderRadius: BorderRadius.circular(12)),
                        child: Row(
                          children: [
                            _buildRoleTab("mahasiswa", "Mahasiswa"),
                            _buildRoleTab("dosen", "Dosen"),
                          ],
                        ),
                      ),
                      const SizedBox(height: 24),

                      // Input Email/NIM
                      _buildLabel(_role == 'mahasiswa' ? 'Email / NIM' : 'Email / NIDN'),
                      TextField(
                        controller: _identifierController,
                        decoration: _inputDecoration(_role == 'mahasiswa' ? 'Masukkan email atau NIM' : 'Masukkan email atau NIDN'),
                      ),
                      const SizedBox(height: 16),

                      // Input Password
                      _buildLabel('Password'),
                      TextField(
                        controller: _passwordController,
                        obscureText: _obscureText,
                        decoration: _inputDecoration('Masukkan password').copyWith(
                          suffixIcon: IconButton(
                            icon: Icon(_obscureText ? Icons.visibility_off : Icons.visibility),
                            onPressed: () => setState(() => _obscureText = !_obscureText),
                          ),
                        ),
                      ),

                      if (_errorMessage != null) ...[
                        const SizedBox(height: 12),
                        Text(_errorMessage!, style: const TextStyle(color: Colors.red, fontSize: 13)),
                      ],

                      const SizedBox(height: 24),

                      // Submit Button
                      SizedBox(
                        width: double.infinity,
                        height: 55,
                        child: ElevatedButton(
                          onPressed: _isLoading ? null : _handleLogin,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: const Color(0xFF2563EB),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                            elevation: 5,
                          ),
                          child: _isLoading
                              ? const CircularProgressIndicator(color: Colors.white)
                              : const Text("Masuk", style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 16)),
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 20),
              ],
            ),
          ),
        ),
      ),
    );
  }

  // Helper Widgets untuk kerapihan kode
  Widget _buildRoleTab(String value, String label) {
    bool isSelected = _role == value;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _role = value),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 10),
          decoration: BoxDecoration(
            color: isSelected ? Colors.white : Colors.transparent,
            borderRadius: BorderRadius.circular(8),
            boxShadow: isSelected ? [BoxShadow(color: Colors.black12, blurRadius: 4)] : [],
          ),
          child: Center(
            child: Text(label, style: TextStyle(color: isSelected ? Colors.blue[700] : Colors.grey, fontWeight: FontWeight.bold)),
          ),
        ),
      ),
    );
  }

  Widget _buildLabel(String text) {
    return Align(alignment: Alignment.centerLeft, child: Padding(padding: const EdgeInsets.only(bottom: 8), child: Text(text, style: const TextStyle(fontWeight: FontWeight.w500))));
  }

  InputDecoration _inputDecoration(String hint) {
    return InputDecoration(
      hintText: hint,
      filled: true,
      fillColor: Colors.white.withOpacity(0.5),
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
    );
  }
}
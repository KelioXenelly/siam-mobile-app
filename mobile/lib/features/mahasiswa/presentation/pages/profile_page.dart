import 'package:flutter/material.dart';
import 'package:mobile/core/services/storage_service.dart';
import 'package:mobile/shared/widgets/bottom_nav.dart';
import 'package:mobile/shared/widgets/glass_card.dart';

class ProfilePage extends StatefulWidget {
  const ProfilePage({super.key});

  @override
  State<ProfilePage> createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {

  int _currentIndex = 3;

  void _onNavTapped(int index) {
    if (index == _currentIndex) return;

    switch (index) {
      case 0:
        Navigator.pushReplacementNamed(context, '/dashboard');
        break;

      case 1:
      // nanti scan
        break;

      case 2:
      // nanti riwayat
        break;

      case 3:
        Navigator.pushReplacementNamed(context, '/profile');
        break;
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = {
      "name": "Kelio Xenelly",
      "email": "kelio.xenelly@itbss.ac.id",
      "nim": "23110001",
      "prodi": "Sistem dan Teknologi Informasi",
      "role": "mahasiswa"
    };

    return Scaffold(
      backgroundColor: const Color(0xFFF1F5F9),
      bottomNavigationBar: BottomNav(
        currentIndex: _currentIndex,
        onTap: _onNavTapped,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [

            /// 🔵 HEADER
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(20, 60, 20, 80),
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFF2563EB), Color(0xFF4F46E5)],
                ),
                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(40),
                  bottomRight: Radius.circular(40),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [

                  /// 🔙 BACK + TITLE
                  Row(
                    children: [
                      GestureDetector(
                        onTap: () => Navigator.pop(context),
                        child: Container(
                          width: 40,
                          height: 40,
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Icon(Icons.arrow_back, color: Colors.white),
                        ),
                      ),
                      const SizedBox(width: 12),
                      const Text(
                        "Profile",
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 22,
                          fontWeight: FontWeight.bold,
                        ),
                      )
                    ],
                  ),

                  const SizedBox(height: 20),

                  /// 👤 PROFILE INFO
                  Row(
                    children: [
                      Container(
                        width: 80,
                        height: 80,
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.2),
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(color: Colors.white.withOpacity(0.3)),
                        ),
                        child: const Icon(Icons.person, size: 40, color: Colors.white),
                      ),
                      const SizedBox(width: 16),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            user['name']!,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 20,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          Text(
                            user['email']!,
                            style: const TextStyle(color: Colors.white70),
                          ),
                          const SizedBox(height: 6),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                            decoration: BoxDecoration(
                              color: Colors.white.withOpacity(0.2),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              "NIM: ${user['nim']}",
                              style: const TextStyle(color: Colors.white),
                            ),
                          )
                        ],
                      )
                    ],
                  )
                ],
              ),
            ),

            const SizedBox(height: 16),

            /// 🔥 CONTENT
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                children: [

                  /// 📋 INFO CARD
                  GlassCard(
                    child: Column(
                      children: [
                        _buildItem(Icons.person, "Nama Lengkap", user['name']!),
                        _divider(),
                        _buildItem(Icons.email, "Email", user['email']!),
                        _divider(),
                        _buildItem(Icons.badge, "NIM", user['nim']!),
                        _divider(),
                        _buildItem(Icons.school, "Program Studi", user['prodi']!),
                        _divider(),
                        _buildItem(Icons.person_outline, "Role", user['role']!),
                      ],
                    ),
                  ),

                  const SizedBox(height: 20),

                  /// ⚙️ SETTINGS
                  const Align(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      "Pengaturan",
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                  ),

                  const SizedBox(height: 10),

                  GlassCard(
                    child: Row(
                      children: const [
                        Icon(Icons.key, color: Colors.blue),
                        SizedBox(width: 10),
                        Expanded(child: Text("Ubah Password")),
                        Icon(Icons.chevron_right),
                      ],
                    ),
                  ),

                  const SizedBox(height: 20),

                  /// 🔴 LOGOUT
                  GestureDetector(
                    onTap: () async {
                      await StorageService.removeToken();

                      Navigator.pushNamedAndRemoveUntil(
                        context,
                        '/login',
                            (route) => false,
                      );
                    },
                    child: Container(
                      width: double.infinity,
                      height: 55,
                      decoration: BoxDecoration(
                        gradient: const LinearGradient(
                          colors: [Colors.red, Colors.redAccent],
                        ),
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.red.withOpacity(0.3),
                            blurRadius: 20,
                            offset: const Offset(0, 10),
                          )
                        ],
                      ),
                      child: const Center(
                        child: Text(
                          "Logout",
                          style: TextStyle(
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                  ),

                  const SizedBox(height: 40),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

  /// 🔹 ITEM BUILDER
  Widget _buildItem(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Row(
        children: [
          Icon(icon, color: Colors.blue),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: const TextStyle(fontSize: 12, color: Colors.grey)),
                Text(value, style: const TextStyle(fontWeight: FontWeight.bold)),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _divider() {
    return const Divider(height: 1);
  }
}